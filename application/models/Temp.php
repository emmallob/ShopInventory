<?php 
	
class Temp {
	
	public function __construct() {
		
		global $DB;
		
		$this->db = $DB;
		$this->user_agent = load_class('user_agent', 'libraries');
		$this->session = load_class('session', 'libraries\Session');
		
	}
	
	public function verify_temp_cart_id($product_id, $quantity, $what_to_do) {
		
		#start a new session
		if (!isset($_SESSION)) {
			session_start();
		}
		#set the expiry time 
		$expiry = time()+ (7*24*60*60);
		#fetch the user cookie id
		if($this->session->userdata("guest_ID_Cookie")):
			#set the variable
			$user_temp_id = $this->session->userdata("guest_ID_Cookie");			
			#record the information into the temporary table for the user...		
			$sql = $this->db->query("select * from _temp_cart where `product_id` = '$product_id' and cookie='$user_temp_id'");			
			#record the information into the temporary table for the user...		
			if($what_to_do == "remove"):
				$this->db->query("delete from _temp_cart where `product_id` = '$product_id' and cookie='$user_temp_id'");
			elseif($what_to_do == "adjust"):
				#count the number of rows found
				if ($sql->rowCount() < 0):
					$this->db->query("insert into _temp_cart (product_id, quantity, expiry, cookie) values('$product_id', '$quantity', '$expiry', '$user_temp_id')");
				else:
					$this->db->query("update _temp_cart set quantity='$quantity' where `product_id` = '$product_id' and cookie='$user_temp_id'");
				endif;
			elseif($what_to_do == "insert"):
				# insert the infomation
				$this->db->query("insert into _temp_cart (product_id, quantity, expiry, cookie) values('$product_id', '$quantity', '$expiry', '$user_temp_id')");
			endif;			
		endif;		
	}
	
	public function empty_cart() {
		#fetch the user cookie id
		if($this->session->userdata("guest_ID_Cookie")):
			#set the variable
			$user_temp_id = $this->session->userdata("guest_ID_Cookie");		
			#record the information into the temporary table for the user...		
			$this->db->query("delete from _temp_cart where cookie='$user_temp_id'");			
		endif;
	}
	
	public function list_temp_cart_in_cookie() {
		#start a new session
		if (!isset($_SESSION)) {
			session_start();
		}
		#check if the cookie has been parsed
		if($this->session->userdata("guest_ID_Cookie")):
			#set the variable
			$user_temp_id = $this->session->userdata("guest_ID_Cookie");
			#fetch the information from the database 
			$stmt = $this->db->query("select * from _temp_cart where cookie='$user_temp_id'");
			#count the number of rows that has been fetched
			if($stmt->rowCount() > 0) {
				#start the session id
				$i = 0;
				#using the while loop to fetch the data
				while($results = $stmt->fetch(PDO::FETCH_ASSOC)){
					$wasFound = false;
					if (!$this->session->userdata("CaaAPcart_array") || count($this->session->userdata("CaaAPcart_array")) < 1) { 
						#set the session 
						$this->session->set_userdata("CaaAPcart_array", array(
								$i => array("item_id" => $results["product_id"], 
								"quantity" => $results["quantity"]
							)
						));
					} else {
						// RUN IF THE CART HAS AT LEAST ONE ITEM IN IT
						foreach ($this->session->userdata("CaaAPcart_array") as $each_item) { 
							$i++;
							while (list($key, $value) = each($each_item)) {
								if ($key == "item_id" && $value == $results["product_id"]) {
									// That item is in cart already so let's adjust its quantity using array_splice()
									array_splice($this->session->userdata("CaaAPcart_array"), $i-1, 1, array(array("item_id" => $results["product_id"], "quantity" => $each_item['quantity'] + $results["quantity"])));
									$wasFound = true;
								} // close if condition
							} // close while loop
						}
						if ($wasFound == false) {
						   array_push($this->session->userdata("CaaAPcart_array"), array("item_id" => $results["product_id"], "quantity" => $results['quantity']));
						}
					}
				}
			}
			
		endif;		
	}
	
}

?>