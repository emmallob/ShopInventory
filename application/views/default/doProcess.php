<?php
#start a new session
if (!isset($_SESSION)) {
    session_start();
}
global $admin_user, $session;

#start a new session
$orders_list = load_class('orders', 'models');
$customers = load_class('customers', 'models');
$suppliers = load_class('suppliers', 'models');
$products = load_class('products', 'models');

#check what the user wants to do
if(isset($_POST["process_form"]) and $admin_user->logged_InControlled() == true) {
	$discount = 0.00;
	#confirm that an letter was parsed
	if(isset($_POST["opt"]) and preg_match("/^[a-z]+$/", $_POST["opt"])){
		#check the user input
		$user_input = xss_clean($_POST["opt"]);
		#verify what the user selected 
		if($user_input == "register") {
			print "<script>window.location.href='".SITE_URL."/customers-new?checkout&ref=checkout';</script>";
		}
	}
	
	#confirm that an letter was parsed
	if(isset($_POST["searchField"]) and isset($_POST["fi"]) and preg_match("/^[a-zA-Z0-9]+$/", $_POST["fi"]) and strlen($_POST["fi"]) > 1) {
		#check the user input
		$s = xss_clean($_POST["fi"]);
		#search for a customer 
		$search = $DB->query("select * from _customers where (firstname like '%$s%' or lastname like '%$s%' or contact like '%$s%' or email like '%$s%') and store_id='".STORE_ID."'");
		if(count($search) > 0) {
			foreach($search as $results) {
				print "<div class='chose_customer' onclick='chose_customer(\"".$results["customer_id"]."\");'><strong>".$results["id"]."</strong>: ".$results["firstname"] . " ". $results["lastname"] . " (".$results["contact"].")</div>";
			}
		}else{
			print "<br><div class='alert alert-danger'>No customer found with the specified name.</div>";
		}
	}
	
	#confirm that an letter was parsed
	if(isset($_POST["searchField1"]) and isset($_POST["fi"]) and preg_match("/^[a-zA-Z0-9]+$/", $_POST["fi"]) and strlen($_POST["fi"]) > 1) {
		#check the user input
		$s = xss_clean($_POST["fi"]);
		#search for a customer 
		$search = $DB->query("select * from _customers where (firstname like '%$s%' or lastname like '%$s%' or contact like '%$s%' or email like '%$s%') and store_id='".STORE_ID."'");
		if(count($search) > 0) {
			foreach($search as $results) {
				print "<div class='chose_customer' onclick='chose_customer(\"".$results["firstname"]." ".$results["lastname"]." (".$results["contact"].")\",\"".$results["outstanding"]."\",\"".$results["customer_id"]."\");'><strong>".$results["id"]."</strong>: ".$results["firstname"] . " ". $results["lastname"] . " (".$results["contact"].")</div>";
			}
		} else{
			print "<br><div class='alert alert-danger'>No customer found with the specified name.</div>";
		}
	}
	
	
	#confirm that an letter was parsed
	if(isset($_POST["searchField2"]) and isset($_POST["fi"]) and preg_match("/^[a-zA-Z0-9]+$/", $_POST["fi"]) and strlen($_POST["fi"]) > 3) {
		#check the user input
		$returned = 0;
		$order_id = strtoupper(xss_clean($_POST["fi"]));
		$type = strtolower(xss_clean($_POST["type"]));
		#search for a customer 
		$search = $DB->query("select * from _customers_orders where unique_id='$order_id' and store_id='".STORE_ID."'");
		if(count($search) > 0) {
			
			print '<div class="col-md-12"><div class="dt-responsive table-responsive">
					<table id="simpletable" class="table table-striped table-bordered nowrap">
					<thead><tr style="font-weight:bold"><th>ID</th><th>ORDER ID</th><th>CUSTOMER</th><th>TOTAL OUSTANDINGS</th>
					<th>DATE SOLD</th><th>SOLD BY</th></tr></thead><tbody>';
					
					foreach($search as $results2) {
						$returned = $results2["returned"];
						$customer_id = $results2["customer_id"];
						?>
						<tr id="orders_list_<?php print $results2["unique_id"]; ?>" <?php if($results2["returned"] == 1) { ?>class="alert alert-danger"<?php } ?>>
						<td><?php print $results2["id"]; ?></td>
						<td><a href="<?php print SITE_URL; ?>/sales-view/<?php print $results2["unique_id"]; ?>"><?php print $results2["unique_id"]; ?></a></td>
						<td><?php print strtoupper($customers->_list_customer_by_id($results2["customer_id"])->c_fullname); ?> (<?php print strtoupper($customers->_list_customer_by_id($results2["customer_id"])->c_contact); ?>)</td>
						<td><span id="customer_outstanding_amount">GH¢ <?php print strtoupper($customers->_list_customer_by_id($results2["customer_id"])->c_outstanding); ?></span></td>
						<td class="mailbox-date"><?php print date(SITE_DATE_FORMAT, strtotime($results2["date_added"])); ?></td>
						<td class="mailbox-date"><?php print strtoupper($results2["sold_by"]); ?></td>
						</tr>
						
						<?php
					}
				print '</tbody></table></div></div>';			
			?>
			
			
			<div class="col-md-12">
			<div class="card">
			<div class="card-block reset-table p-t-0">
			<div class="table-responsive">
			<div class="panel-heading">
			<h4 class="panel-title"><i class="ion-ios-information"></i> Order Details</h4>
			</div>
			<table class="table table-bordered">
			  <thead>
			  <tr style="font-weight:bold;text-transform:uppercase">
			<td class="text-left">Product</td>
			<td class="text-center" width="15%">Unit Price</td>
			<td class="text-center" width="15%"><?php if($type == "item") { ?>New Quantity<?php } else { ?>Quantity<?php } ?></td>			
			<?php if($type == "item") { ?><td class="text-center">REFRESH</td><?php } ?>
			<td class="text-right" width="15%">Total</td>
			</tr>
			  </thead>			  
			  <tbody>
			<?php
			#fetch the orders list
			$user_orders = $DB->query("select * from _customers_orders_details where unique_id='$order_id' and store_id='".STORE_ID."'");
			#initials
			$orderTotal = 0;
			#count the number of rows
			if(count($user_orders) > 0):
				
			#list them here 
			foreach($user_orders as $results4):
			$o_discount = $orders_list->order_by_id($results4['unique_id'])->o_discount;
			$overall_price = $orders_list->order_by_id($results4['unique_id'])->overall_price;
			$o_paid = $orders_list->order_by_id($results4['unique_id'])->o_paid;
			
			$price = $results4["product_price"];
			$pricetotal = $price * $results4['quantity'];
			$shop_id = $results4['shop_id'];
			$orderTotal = $pricetotal + $orderTotal;
			setlocale(LC_MONETARY, "en_US");
			$pricetotal = number_format($pricetotal, 2);
			?>
			<tr class="return_item_id_<?php print $results4["id"]; ?>">
			<td><a href="<?php print ADMIN_URL; ?>/products-edit/<?php print $products->product_by_id("id", $results4["product_id"])->p_slug; ?>"><?php print $products->product_by_id("id", $results4["product_id"])->p_name; ?></a></td>
			<?php if($type == "item") { ?>
			<td class="text-center">
			<input type="number" readonly value="<?php print $price; ?>" id="return_item_price_id_<?php print $results4["id"]; ?>" class="form-control text-center">
			<input type="hidden" value="<?php print $price; ?>" id="return_item_old_price_id_<?php print $results4["id"]; ?>" class="form-control text-center"></td>
			<?php } else { ?>
			<td class="text-center">GH¢ <?php print $results4["product_price"]; ?></td>
			<?php } ?>
			<?php if($type == "item") { ?>
			<td class="text-center">
			<input style="width:150px;" maxlength='3' type="number" id="return_item_qty_id_<?php print $results4["id"]; ?>" value="<?php print $results4["quantity"]; ?>" class="form-control text-center" />
			<input style="width:150px;" maxlength='3' type="hidden" id="return_item_old_qty_id_<?php print $results4["id"]; ?>" value="<?php print $results4["quantity"]; ?>" class="form-control text-center" /></td>
			<td class="text-center">
			<div id="refresh_button">
			<span onclick="start_tabulating_items('<?php print $results4["id"]; ?>');" class="btn btn-success"><i class="fa fa-refresh"></i> </span>
			</div>
			<input type="hidden" name="product_name" id="product_name" value="<?php print strtoupper($products->product_by_id("id", $results4["product_id"])->p_name); ?>"></td>
			<?php } else { ?>
			<td class="text-center"><?php print $results4["quantity"]; ?></td>
			<?php } ?>			
			<td class="text-right"><span class="product_total_price_<?php print $results4["id"]; ?>">GH¢ <?php print $pricetotal; ?></span>
			<input style="width:150px;" maxlength='3' type="hidden" id="product_total_old_price_id_<?php print $results4["id"]; ?>" value="GH¢ <?php print $pricetotal; ?>" class="form-control text-center" /></td>
			</tr>
			<?php endforeach; endif; ?>
			<?php if($type == "order") { ?>
			<tr class="return_item_id_<?php print $results4["id"]; ?>">
			<td style="font-weight:bolder;text-transform:uppercase" colspan="<?php if($type == "item") print 4; else print 3; ?>" class="text-right">Subtotal</td>
			<td style="font-weight:bolder;text-transform:uppercase" class="text-right"><span class="sub_total_price">GH¢ <?php print number_format($orderTotal, 2); ?></span></td>
			</tr>
			<?php } ?>
			<tr class="return_item_id_<?php print $results4["id"]; ?>">
			<td style="font-weight:;text-transform:uppercase" colspan="<?php if($type == "item") print 4; else print 3; ?>" class="text-right">Discount</td>
			<?php if($type == "item") { ?>
			<td class="text-right">
			<input maxlength='3' type="number" id="return_item_dis" value="<?php print number_format($o_discount, 2); ?>" class="form-control text-right" />
			<input type="hidden" id="return_item_old_discount" value="<?php print number_format($o_discount, 2); ?>"/>
			</td>
			<?php } else { ?>
			<td style="font-weight:;text-transform:uppercase" class="text-right">GH¢ <?php print number_format($o_discount, 2); ?></td>
			<?php } ?>
			</tr>
			<?php if($type == "order") { ?>
			<tr class="return_item_id_<?php print $results4["id"]; ?>">
			<td style="font-weight:bolder;text-transform:uppercase" colspan="<?php if($type == "item") print 4; else print 3; ?>" class="text-right">Overall Total</td>
			<td style="font-weight:bolder;text-transform:uppercase" class="text-right"><span class="overall_total_price">GH¢ <?php print number_format($overall_price, 2); ?></span></td>
			</tr>
			<?php } ?>
			<?php if($type == "item") { ?>
			<tr class="return_item_id_<?php print $results4["id"]; ?>">
			<td style="font-weight:bolder;text-transform:uppercase" colspan="<?php if($type == "item") print 4; else print 3; ?>" class="text-right">Amount Paid</td>
			<td style="font-weight:bolder;text-transform:uppercase" class="text-right"><span class="overall_total_price">GH¢ <?php print number_format($o_paid, 2); ?></span></td>
			</tr>
			<?php } ?>
			<?php if($overall_price != $o_paid) { ?>
			<tr class="return_item_id_<?php print $results4["id"]; ?>">
			  <td style="font-weight:;text-transform:uppercase" colspan="<?php if($type == "item") print 4; else print 3; ?>" class="text-right">Amount Paid</td>
			  <td style="font-weight:;text-transform:uppercase" class="text-right">GH¢ <?php print number_format($o_paid, 2); ?></td>
			</tr>
			<tr class="return_item_id_<?php print $results4["id"]; ?>">
				 <td style="font-weight:bolder;text-transform:uppercase" colspan="<?php if($type == "item") print 4; else print 3; ?>" class="text-right">OUSTANDING</td>
				 <td style="font-weight:bolder;text-transform:uppercase" class="text-right"><span id="customer_outstanding_amount1">GH¢ <?php print number_format(($overall_price-$o_paid), 2); ?></span></td>
			</tr>
			<?php } ?>
			</tbody>
			</table>
			</div>
			</div>
			</div>
			</div>

			<div class="form-group">
			<div class="col-sm-">
			<?php if($returned == 0) { ?>
				<?php if($type == "order") { ?>
					<button type="submit" id="return_product_button" class="btn btn-success m-b-0" onclick="return_entire_order('<?php print $order_id; ?>','<?php print $customer_id; ?>');"><i class="fa fa-save"></i> RETURN ENTIRE ORDER</button>
				<?php } ?>
			<?php } ?>
			<?php if($returned == 1) { ?>
			<button type="submit" class="btn btn-warning m-b-0"><i class="fa fa-save"></i> ORDER ALREADY RETURNED</button>
			<?php } ?>
			<button title="Cancel the return process." onclick="cancel_process();" type="button" class="btn btn-danger"><i class="ion-ios-arrow-back"></i> CANCEL</button>
			<?php if($type == "item") { ?><br clear="both"><br clear="both">
				<div class="return_item_results"></div>
			<?php } ?>
			</div>
			</div>
			
			<?php if($type == "item") { ?>
			<script>
			
			function start_tabulating_items(item_id) {
				
				var old_total = $("#product_total_old_price_id_"+item_id).val();
				var old_quantity = $("#return_item_old_qty_id_"+item_id).val();
				var old_price = $("#return_item_old_price_id_"+item_id).val();
				var old_discount = $("#return_item_old_discount").val();
				var product_name = $("#product_name").val();
				
				var n_quantity = $("#return_item_qty_id_"+item_id).val();
				
				if(confirm("Are you sure you want to change the Quantity of " + product_name + " bought from "+ old_quantity + " to " + n_quantity + "? You cannot reverse it once it is done.")) {
					
					var n_discount = $("#return_item_dis").val();
					var price = $("#return_item_price_id_"+item_id).val();
					
					$.ajax({
						type: "post",
						url: "<?php print SITE_URL; ?>/doReturn",
						data: "process_form&return_process_begin&id="+item_id+"&n_quant="+n_quantity+"&n_dis="+n_discount+"&n_price="+price+"&old_quantity="+old_quantity,
						success: function(response) {
							$(".return_item_results").html(response);
						}
					});
				} else {
					$("#return_item_dis").val(old_discount);
					$(".product_total_price_"+item_id).text(old_total);
					$("#return_item_price_id_"+item_id).val(old_price);
					$("#return_item_qty_id_"+item_id).val(old_quantity);
				}
			}		
			</script>
			<?php } ?>
			<?PHP 			
		} else{
			print "<br><div class='alert alert-danger'>No customer found with the specified name.</div>";
		}
	}
	
	#confirm that a specific customer id has been selected
	if(isset($_POST["cust_sel"]) and isset($_POST["cid"]) and preg_match("/^[a-zA-Z0-9]+$/", $_POST["cid"])) {
		#check the user input
		$cust_sel = xss_clean($_POST["cid"]);
		$session->set_userdata("Main_guest_Id2", $cust_sel);
	}
	
	#confirm that a specific customer id has been selected
	if(isset($_POST["continue_unregistered"])) {
		#check the user input
		$session->set_userdata(array("Main_guest_Id2"=>100));
	}
	
	if(isset($_POST["list_orders"]) and isset($_POST["cid"])) {
		$cid = xss_clean($_POST["cid"]);
		print '<select required id="order_id" style="width:300px;" class="form-control" name="orders_listing">';
		print '<option value="0">Select Order Details</option>';
		#call the orders controller
		$search = $DB->query("select * from _customers_orders where customer_id='$cid' and payment_complete='0' and store_id='".STORE_ID."' order by id desc");
		if(count($search) > 0) {
		foreach($search as $results) {
			print "<option value='{$results["unique_id"]}'>{$results["unique_id"]} - OUTSTANDING: GHc ".($results["overall_price"] - $results["total_paid"])."</option>";
		}
		}else{
			print "<option>No outstanding orders found for this customer.</option>";
		}
		print "</select>";	
	}
	
}