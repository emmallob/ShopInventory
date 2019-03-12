<?php 

class Orders {
	
	public function __construct() {
		
		global $DB;
		
		$this->db = $DB;
		$this->config = $this->db->call_connection();
		
		$this->user_agent = load_class('user_agent', 'libraries');
		$this->session = load_class('session', 'libraries\Session');
		$this->customers = load_class('customers', 'models');
		$this->user_agent = load_class('user_agent', 'libraries');
		load_helpers('string_helper');
		load_helpers('url_helper');
	}
	
	public function payment_methods($id) {
		
		$this->pp_success = false;
		
		try {
			if($id != NULL && !empty($id)) {
				
				$stmt = $this->db->query("SELECT * FROM `_payment_methods` WHERE `id`='{$id}'");
				
				/***** Count the number of rows if its equal to 1 *****/
				if ($this->db->num_rows($stmt) == 1) {
				
					$this->pp_success = true;
					
					foreach($stmt as $data){
						$this->pp_name = $data['name'];
						$this->pp_uid = $data['id'];
					}
				}
					
			}
			
		} catch (PDOException $e) {}
		
		return $this;
		
	}
	
	public function order_by_id($id) {
		
		$this->o_success = false;
		
		$store_addons = "and store_id='".STORE_ID."'";
		
		try {
			if($id != NULL && !empty($id)) {
				
				$stmt = $this->db->query("SELECT * FROM `_customers_orders` WHERE `unique_id`='{$id}' $store_addons");
				
				/***** Count the number of rows if its equal to 1 *****/
				if ($this->db->num_rows($stmt) == 1) {
				
					$this->o_success = true;
					
					foreach($stmt as $data){
						
						$this->o_id = $data['id'];
						$this->o_uid = $data['customer_id'];
						$this->o_status = $data["status"];
						$this->o_comment = $data['c_comment'];
						$this->a_comment = $data['a_comment'];
						$this->sale_type = $data['sale_type'];
						$this->user_eviron = $data['user_eviron'];
						$this->o_date = $data['date_added'];
						$this->o_paid = $data['total_paid'];
						$this->o_discount = $data['discount'];
						$this->o_returned = $data['returned'];
						$this->overall_price = $data['overall_price'];
						$this->billing_address = $data['billing_address'];
						$this->sold_by = $data['sold_by'];
						$this->shipping_address = $data['shipping_address'];
						$this->payment_reference_id = $data['payment_reference_id'];
						$this->payment_method_id = $data["payment_method"];
						$this->payment_method = $this->payment_methods($this->payment_method_id)->pp_name;
						$this->o_fullname = $this->customers->_list_customer_by_id($this->o_uid)->c_fullname;
					
					}
				}
					
			}
			
		} catch (PDOException $e) {}
		
		return $this;
		
	}
	
	public function order_details_by_id($id, $limit=null) {
		
		$this->d_success = false;
		
		$store_addons = "and store_id='".STORE_ID."'";
		
		try {
			if($id != NULL && !empty($id)) {
				
				$stmt = $this->config->query("SELECT * FROM `_customers_orders_details` WHERE `unique_id`='{$id}' $store_addons $limit");
				
				/***** Count the number of rows if its equal to 1 *****/
				if ($this->db->num_rows($stmt) > 0) {
				
					$this->d_success = true;
					
					foreach($stmt as $data1){
						
						$this->d_id = $data1['id'];
						$this->d_uid = $data1['unique_id'];
						$this->ccust_id = $data1['customer_id'];
						$this->d_pid = $data1['product_id'];
						$this->d_p = $data1['product_price'];
						$this->d_q = $data1['quantity'];
						$this->d_t = $data1['total_price'];
						$this->d_status = $data1["status"];
						$this->ccust_name = $this->customers->_list_customer_by_id($this->ccust_id)->c_fullname;
						$this->ccust_phone = $this->customers->_list_customer_by_id($this->ccust_id)->c_contact;
						$this->ccust_email = $this->customers->_list_customer_by_id($this->ccust_id)->c_email;
											
					}
				}
					
			}
			
		} catch (PDOException $e) {}
		
		return $this;
	}
		
	public function _add_user_orders($discount, $customer_id, $admin_id, $payment_type, $total_paid, $store_id) {
		
		# orderid is always the last order id plus 1
		$orderid = ($this->db->max_all("id", "_customers_orders"))+2;
		$reference_id = ($this->db->max_all("id", "_customers_orders"))+2;
		
		$stocks = load_class('stocks', 'models');
		
		#start a new session
		if(!isset($_SESSION))
			session_start();

		if(isset($total_paid)){
			
			if (!isset($_SESSION["CaaAPcart_array"]) || count($_SESSION["CaaAPcart_array"]) < 1) {
				print "<div class='alert alert-danger' style='width:100%'>Sorry! You cannot process an empty cart</div><br clear='both'><br clear='both'>";
			} else {
					
				#begin multiple tansactions
				$this->config->beginTransaction();
						
				# using the try functionality to process the form
				try {
					#fetch the browser details
					$browser = $this->user_agent->browser()." ".$this->user_agent->platform();
					$ip = $this->user_agent->ip_address();
					$cartOutput = "";
					$cartTotal = 0;
					#user id 
					$user_id = $customer_id;
					
					#fetch all the user cart details
					if (!isset($_SESSION["CaaAPcart_array"]) || count($_SESSION["CaaAPcart_array"]) < 1) {
						$cartOutput = "<h2 align='center'>Your shopping cart is empty</h2>";
					} else {
						// Start the For Each loop
						$i = 0;
							
						foreach ($_SESSION["CaaAPcart_array"] as $each_item) { 
							#split the cart session 
							$item_id = $each_item["item_id"];
							#query the database
							$sql = $this->db->query("SELECT * FROM _products WHERE id='$item_id' and store_id='$store_id' LIMIT 1");
							#fetch the results
							foreach ($sql as $row) {
								$cproduct_name = $row["product_name"];
								$cprice = $row["product_actuals"];
								$cdetails = $row["product_details"];
								$cpid = $row["product_id"];
								$cslug = $row["product_slug"];
							}
								
							#mechanism for price totalling
							$pricetotal = (int)$cprice * (int)$each_item["quantity"];
							$cartTotal = (int)$pricetotal + (int)$cartTotal;
							$pricetotal = $pricetotal;
							
							#record the entire user cart details
							$this->config->exec("insert into _customers_orders_details (store_id,unique_id,customer_id,product_id,product_price,quantity,total_price,status,full_date) values('$store_id','LEO$orderid','$user_id','$item_id','$cprice','{$each_item["quantity"]}','$pricetotal','1',now())");
							
							
							#update stock and product quantity
							$stocks->_deduct_stock($each_item["quantity"], $item_id);
							
						}
							
						$overallTotal = (int)($cartTotal) - (int)($discount);
						
						if($overallTotal > $total_paid) {
							$payment_complete = 0;
						} else {
							$payment_complete = 1;
						}
						
						#insert the order
						$this->config->exec("insert into _customers_orders (store_id, unique_id, customer_id,user_cookie_key, payment_method, payment_reference_id, discount, total_price, overall_price, status, date_added, full_date, user_eviron, ipaddress, sale_type, total_paid, sold_by,payment_complete) values('$store_id','LEO$orderid','$user_id','".random_string('numeric', 45)."','2','$reference_id','$discount','$cartTotal','$overallTotal','1',now(),now(),'$browser','$ip','$payment_type','$total_paid','$admin_id','$payment_complete')");
						
						#update the user information 
						$this->config->exec("update _customers set last_order_id='{$this->db->max_all("id", "_customers_orders")}', lastdate=now() where customer_id='$customer_id' and store_id='$store_id'");
						
						if($payment_type == "CREDIT") {
							#get the old outstanding of this customer 
							$old_outstanding = substr(create_slug($this->customers->_list_customer_by_id($customer_id)->c_outstanding), 0, -2);
							$newout = ($old_outstanding + ($overallTotal - $total_paid));
							#update the user information
							$this->config->exec("update _customers set outstanding='{$newout}' where customer_id='$customer_id' and store_id='$store_id'");
						}
						
						#record new admin history
						$this->config->exec("insert into _activity_logs set store_id='$store_id', date_recorded=now(), admin_id='$admin_id', activity_page='invoice', activity_id='$user_id', activity_details='LEO$orderid', activity_description='Invoice: <strong>\"LEO$orderid\"</strong> Added.'");
			
						# THIS SECTION WILL RUN WHEN THE USER CHOOSES TO USE 
						# THE PAYMENT ON DELIVERY OPTION
						#unset the session
						$this->session->unset_userdata("CaaAPcart_array");
						$this->session->unset_userdata("Main_guest_Id2:guest_ID_Created");
						
						#end the query 
						$this->config->commit();
						
						print "<script>window.location.href='".SITE_URL."/print-sales/LEO$orderid'</script>";
						
						}
				
					} catch (PDOException $e) {
						#cancel all activities that was previously done.
						$this->config->rollback();
						
						#print error message
						print "<br clear='both'><div class='alert alert-danger text-center' role='alert'>
									<h4>Sorry! There was an error while processing the form.</h4></div>
									<br clear='both'><br clear='both'>";
						print '<script>$("#confirm_payment").removeAttr("disabled", false);</script>';
						print '<script>$("#confirm_credit").removeAttr("disabled", false);</script>';
						
					}
					
				}
				
		}
	}
	
	
	
}