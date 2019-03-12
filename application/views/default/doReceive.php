<?php
#call the global function 
global $SITEURL, $admin_user, $DB;

#confirm that the user has parsed this value
IF(ISSET($SITEURL[1])) {
	#load file for validation
	load_core('security');
	load_helpers('string_helper');
	load_helpers('url_helper');
	$encrypt = load_class('encrypt', 'libraries');
	$user_agent = load_class('user_agent', 'libraries');
	$orders_list = load_class('Orders', 'models');
	$customers_list = load_class('customers', 'models');
	
	IF(($SITEURL[1] == "doReceiveMoney") AND ISSET($_POST["receive_pay"])) {
		
		#check what the user wants to do
		if(isset($_POST["process_form"]) and $admin_user->logged_InControlled() == true) {
			
			if(isset($_POST["receive_pay"]) and isset($_POST["cn"])) {
				$cn = xss_clean($_POST["cn"]);
				$ca = xss_clean($_POST["ca"]);
				$cord = xss_clean($_POST["cord"]);
				$cid = xss_clean($_POST["cid"]);
				$admin_id = $admin_user->return_username();
				
				if($cord == "0") {
					print "<div class='alert alert-danger'>Sorry! You have not selected any Order ID.</div>";
				} else {
					$old_out = substr($functions->create_slug($customers->_list_customer_by_id($cid)->c_outstanding), 0, -2);
					$o_paid = $orders_list->order_by_id($cord)->o_paid;
					$o_discount = $orders_list->order_by_id($cord)->o_discount;
					$overall_price = $orders_list->order_by_id($cord)->overall_price;
					$activity = ($db->max_all("id", "_receipts"))+2;
					
					if($ca > $old_out) {
						print "<div class='alert alert-danger'>Sorry! The amount entered exceeds the outstanding balance. Please rectify it and continue.</div>";
					} else {
					
						$new_balance = $old_out - $ca;
						$total_paid = $o_paid + $ca;
						
						$DB->just_exec("insert into _receipts set customer_id='$cid', order_id='$cord', amount='$ca', admin_id='$admin_id', store_id='".STORE_ID."'");
						$DB->just_exec("update _customers set outstanding='$new_balance' where customer_id='$cid' and store_id='".STORE_ID."'");
						$DB->just_exec("update _customers_orders set total_paid='$total_paid' where unique_id='$cord' and store_id='".STORE_ID."'");
						
						if($ca == ($overall_price - $o_paid)) {
							$DB->just_exec("update _customers_orders set payment_complete='1' where unique_id='$cord'");
						}
						
						$DB->just_exec("insert into _activity_logs set store_id='".STORE_ID."', full_date=now(), date_recorded=now(), admin_id='$admin_id', activity_page='receipt', activity_id='$activity', activity_details='$cord', activity_description='Payment Received from Customer: Added.'");
						
						print "<div class='alert alert-success'>Payment of GHs$ca Received from the Customer.</div>";
						print "<script>$('#ReviewForm')[0].reset();</script>";
						
					}
				}
			}
			
		}
		
	}
	
	

} ELSE {
	show_error('Page Not Found', 'Sorry the page you are trying to view does not exist on this server', 'error_404');
}