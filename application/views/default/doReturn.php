<?php 
#start a new session
global $admin_user, $functions;

$orders_list = load_class('orders', 'models');
$customers = load_class('customers', 'models');
$products = load_class('products', 'models');
	
#check what the user wants to do
if(isset($_POST["process_form"]) and $admin_user->logged_InControlled() == true) {
	
	#initializing
	$discount = 0.00;
	#confirm that the user wants to return the order
	if(isset($_POST["return_order"]) and isset($_POST["oidd"]) and isset($_POST["customer_id"])) {
		#call the orders controller
		$orders = new Orders;
		#check the user input
		$admin_id = $_SESSION[":lifeUsername"];
		$order_id = xss_clean($_POST["oidd"]);
		$customer_id = xss_clean($_POST["customer_id"]);
		
		$outstanding = substr(create_slug($customers->_list_customer_by_id($customer_id)->c_outstanding), 0, -3);
		$total_paid = $orders_list->order_by_id($order_id)->o_paid;
		$discount = $orders_list->order_by_id($order_id)->o_discount;
		$overall_price = $orders_list->order_by_id($order_id)->overall_price;
		
		$outstanding_payment = $overall_price - $total_paid;
		$outstanding_difference = $outstanding-$outstanding_payment;
		
		#get the list of products that fall under this category
		$order_details = $db->just_query("select * from _customers_orders_details where unique_id='$order_id' and store_id='".STORE_ID."'");
		
		#count the number of rows found 
		if(count($order_details) > 0) {
			#using the foreach loop to get the complete details of the various products 
			foreach($order_details as $details_results) {
				#assign variables to the said products 
				$product_id = $details_results["product_id"];
				$product_quantity = $details_results["quantity"];
				#get the product old quantity 
				$old_quantity = (int)$products->product_by_id("id", $product_id)->p_quantity;
				$new_quantity = $old_quantity+$product_quantity;
				#update the products by adding up this new quantity to it.
				$DB->just_exec("update _products set product_quantity='$new_quantity' where id='$product_id'");
				#update the products stock by adding this quantity to it 
				$DB->just_exec("update _stocks set quantity='$new_quantity' where product_id='$product_id'");
			}
		}
		
		#update the orders return status
		$DB->just_exec("update _customers_orders set returned='1' where unique_id='$order_id' and store_id='".STORE_ID."'");
		$DB->just_exec("update _customers_orders_details set returned='1' where unique_id='$order_id' and store_id='".STORE_ID."'");
		#update the outstanding difference in there
		$DB->just_exec("update _customers set outstanding='{$outstanding_difference}' where customer_id='$customer_id' and store_id='".STORE_ID."'");
		#record the new admin activity in the database 
		$DB->just_exec("insert into _activity_logs set store_id='".STORE_ID."', full_date=now(), date_recorded=now(), admin_id='$admin_id', activity_page='returned-sale', activity_id='$customer_id', activity_details='$order_id', activity_description='Returned product was successfully received and system restored.'");
		print "<div class='alert alert-success'>Your request was successfully executed. The Order with id <strong>$order_id</strong> has been returned and recorded. Thank you.</div>";
		print "<script>$(\"#return_product_button\").removeAttr(\"disabled\", false);</script>";
	}	
	
	if(isset($_POST["return_process_begin"]) and isset($_POST["id"])  and isset($_POST["n_quant"])) {
		
		#assign variables to the items 
		$details_id = (int)xss_clean($_POST["id"]);
		$n_quantity = (int)xss_clean($_POST["n_quant"]);
		$n_discount = xss_clean($_POST["n_dis"]);
		$n_price = xss_clean($_POST["n_price"]);
		$old_quantity = (int)xss_clean($_POST["old_quantity"]);
		
		#check the new quantity value if its a negative or positive
		if(!preg_match("/^[0-9]+$/", $n_quantity)) {
			print "<div class='alert alert-danger'>Please you must enter a valid number (0-9).</div>";
		} elseif($n_quantity > $old_quantity) {
			print "<div class='alert alert-danger'>Sorry please ensure the Quantity is lower than the previous one. If the Customer desires to top it up, then please use the <strong><a href='".SITE_URL."/products'>Sell Product Option</a></strong>. Thank you</div>";
		} else {
			#tabulate new price for that particular product
			$n_sub_total = number_format(($n_quantity * $n_price), 2);
			
			#get the entire row information of this particular item from the _customers_orders_details table 
			$fetch_details = $DB->query("select * from _customers_orders_details where id='$details_id' and store_id='".STORE_ID."' limit 1");
			#count the number of rows found 
			if(count($fetch_details) > 0) {
				#using the foreach loop to get the row details
				foreach($fetch_details as $fetch_results) {
					#assign the variables 
					$order_id = $fetch_results["unique_id"];
					$customer_id = $fetch_results["customer_id"];
					$product_id = $fetch_results["product_id"];
				}			
			}
			
			
			#go into the _customers_orders table and fetch additional information to aid in the processing of the form 
			$orders_details = $DB->query("select * from _customers_orders where unique_id='$order_id' and store_id='".STORE_ID."' limit 1");
			#count the number of rows found 
			if(count($orders_details) > 0) {
				#using the foreach loop to get the row details
				foreach($orders_details as $orders_results) {
					#assign the variables 
					$sale_type = $orders_results["sale_type"];
					$od_discount = $orders_results["discount"];
					$od_overall = $orders_results["overall_price"];
					$od_total = $orders_results["total_price"];
					$od_paid = $orders_results["total_paid"];
					$od_payment_comp = $orders_results["payment_complete"];
					
				}			
			}
			
			#now fetch all the details that relates to this particular order and get a new total of the order 
			$full_order_details = $DB->query("select * from _customers_orders_details where unique_id='$order_id' and store_id='".STORE_ID."'");
			#initials
			$orderTotal = 0;
			#count the number of rows
			if(count($full_order_details) > 0) {
					
				#list them here 
				foreach($full_order_details as $results4) {
					#assign variables for the price and quantity
					$price = $results4["product_price"];
					$quantity = $results4['quantity'];
					$old_old_quantity = $results4['quantity'];
					#replace the price and quantity if the id equals this current one.
					if($results4["id"] == $details_id) {
						$price = $n_price;
						$quantity = $n_quantity;
					}
					#continue assigning variables 
					$pricetotal = $price * $quantity;
					$shop_id = $results4['shop_id'];
					$orderTotal = $pricetotal + $orderTotal;
					setlocale(LC_MONETARY, "en_US");
					$pricetotal = number_format($pricetotal, 2);
					
				}
				
			}
						
			$where_add = "";
			$new_orderTotal = $orderTotal-$n_discount;
						
			if($new_orderTotal < $od_paid) {
				$refund_Amount = number_format(($od_paid-$new_orderTotal), 2);
				$where_add = ", payment_complete='1', total_paid='$new_orderTotal'";
			}
			
			#fetch other relevant information
			$outstanding = substr($customers->_list_customer_by_id($customer_id)->c_outstanding, 0, -2);
			$old_product_quantity = (int)$products->product_by_id("id", $product_id)->p_quantity;
			$new_product_quantity = (int)($old_product_quantity + ($old_old_quantity - $quantity));
			
			if($new_orderTotal > $od_paid) {
				
				$new_difference = ($old_old_quantity - $quantity) * $price;
				$new_outstanding = $outstanding - $new_difference;
								
				$DB->just_exec("update _customers set outstanding='$new_outstanding' where customer_id='$customer_id' and store_id='".STORE_ID."'");
			}
			
			
			#update the full orders information 
			$DB->just_exec("update _customers_orders set discount='$n_discount', total_price='$orderTotal', overall_price='$new_orderTotal' $where_add where unique_id='$order_id' and store_id='".STORE_ID."'");
			
			$DB->just_exec("update _customers_orders_details set quantity='$n_quantity', product_price='$n_price', total_price='$n_sub_total' where id='$details_id' and store_id='".STORE_ID."'");
			
			$DB->just_exec("update _products set product_quantity='$new_product_quantity' where id='$product_id' and store_id='".STORE_ID."'");
			$DB->just_exec("update _stocks set quantity='$new_product_quantity' where product_id='$product_id' and store_id='".STORE_ID."'");
			
			#print out all the changes that has been effected in the database
			print "<script>";
			print "window.location.href='".SITE_URL."/return/item/$order_id'";
			print "</script>";
			
			
			print "<div class='alert alert-success'>The specified PRODUCT ITEM quantity has successfully been returned into the stock.</div>";
			
			#print message that there will a refund of that much
			if($new_orderTotal < $od_paid) {
				print "<div class='alert alert-success'>You have to refund the Customer a total of <strong>GH¢$refund_Amount</strong>. Thank you.</div>";
			}
		
		}
	}
	
}
