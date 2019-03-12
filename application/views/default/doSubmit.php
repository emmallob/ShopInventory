<?php 
#start a new session

global $admin_user;

#check what the user wants to do
if(isset($_POST["process_form"]) and $admin_user->logged_InControlled() == true) {
	#initializing
	$discount = 0.00;
	#confirm that the discount and the type has been set appropriately
	if(isset($_POST["submit_a_cart"]) and isset($_POST["dis"]) and isset($_POST["type"])) {
		#call the orders controller
		$orders_list = load_class('Orders', 'models');
		#check the user input
		$customer_id = $session->userdata("Main_guest_Id2");
		$admin_id = $session->userdata(":lifeUsername");
		$payment_type = xss_clean($_POST["type"]);
		$discount = (int)xss_clean($_POST["dis"]);
		$amount = (int)xss_clean($_POST["amount"]);
		$orders_list->_add_user_orders($discount, $customer_id, $admin_id, $payment_type, $amount, STORE_ID);
	}
}