<?php 

class Customers {
	
	public function __construct() {
		
		global $DB;
		
		$this->db = $DB;
		$this->user_agent = load_class('user_agent', 'libraries');
		$this->session = load_class('session', 'libraries\Session');
		
	}
	
	public function _generate_customer_id() {
		
		return SITE_ACRO.$this->db->maxOfAll("id","_customers")+1;
	}
	
	public function _list_customers($limit = "limit 0, 1000") {
		
		global $admin_user;
		
		$store_addons = "and store_id='".STORE_ID."'";
		
		try {
			
			$stmt = $this->db->query("select * from _customers where status='1' and admin_deleted='1' $store_addons $limit");
			
			return $stmt;
			
		} catch(PDOException $e) {}
	}
	
	public function _list_customers_where($where_clause = null, $limit = "limit 0, 1000") {
		
		$store_addons = "and store_id='".STORE_ID."'";
		
		try {
			
			$stmt = $this->db->query("select * from _customers where status='1' and admin_deleted='1' $store_addons $where_clause $limit");
			
			return $stmt;
			
		} catch(PDOException $e) {}
		
	}
	
	public function _list_customer_by_id($customer_id) {
		
		$this->cus_found = false;
		
		$store_addons = "and store_id='".STORE_ID."'";
		
		if(!empty($customer_id) and preg_match("/^[A-Z0-9]+$/", strtoupper($customer_id))) {
						
			try {
			
				$stmt = $this->db->query("select * from _customers where customer_id='$customer_id' and status='1' $store_addons");
				
				
				if ($this->db->num_rows($stmt) == 1) {
					
					$this->cus_found = true;
		
					foreach($stmt as $data4) {
						
					$this->c_firstname = $data4["firstname"];
					$this->c_lastname = $data4["lastname"];
					$this->c_fullname = $this->c_firstname ." ". $this->c_lastname;
					$this->c_email = $data4["email"];
					$this->c_gender = $data4["gender"];
					$this->c_contact = $data4["contact"];
					$this->c_contact2 = $data4["contact2"];
					$this->c_region = $data4["region"];
					$this->c_address = $data4["address"];
					$this->c_website = $data4["website"];
					$this->c_description = $data4["description"];
					$this->c_outstanding = number_format((int)($data4["outstanding"]), 2);
					$this->c_recommended_by = $data4["recommended_by"];
					$this->c_balance = number_format((int)($data4["balance"]), 2);
					$this->c_lastdate = $data4["lastdate"];
					$this->c_last_order_id = $data4["last_order_id"];
					$this->c_date_recorded = $data4["date_recorded"];
					
					}
				}
				
			} catch(PDOException $e) {}
			
		}
		
		return $this;
	}
	
	public function _update_last_order($order_id, $customer_id) {
		
		$store_addons = "and store_id='".STORE_ID."'";
		
		if($this->db->just_exec("update _customers set last_order_id='$order_id', lastdate=now() where customer_id='$customer_id' $store_addons")) {			
			return true;
		} else {
			return false;
		}
	}
	
	public function _add_customer($customer_id, $firstname, $lastname, $email, $contact, $contact2, $website, $address, $balance, $outstanding, $lastdate, $description, $last_order_id) {
		
		#insert a new supplier records 
		if($this->db->just_exec("into into _customers set customer_id='$customer_id', fullname='$fullname', email='$email', contact='$contact', contact2='$contact', website='$website', address='$address', balance='$balance', outstanding='$outstanding', lastdate='$lastdate', description='$description', last_order_id='$last_order_id', store_id='".STORE_ID."'")) {			
			return true;
		} else {
			return false;
		}
		
	}
	
	public function _update_customer($customer_id, $firstname, $lastname, $email, $contact, $contact2, $website, $address, $balance, $outstanding, $lastdate, $description, $last_order_id) {
		
		$store_addons = "and store_id='".STORE_ID."'";
		
		#update the supplier records 
		if($this->db->just_exec("update _customers set firstname='$firstname', lastname='$lastname', email='$email', contact='$contact', contact2='$contact', website='$website', address='$address', balance='$balance', outstanding='$outstanding', lastdate='$lastdate', description='$description', last_order_id='$last_order_id' where customer_id='$customer_id' $store_addons")) {			
			return true;
		} else {
			return false;
		}
		
	}
	
	public function _delete_customer($customer_id) {
		
		$store_addons = "and store_id='".STORE_ID."'";
		
		if($this->db->just_exec("update _customers set status='0', admin_deleted='1', deleted_date=now() where customer_id='$customer_id' $store_addons")) {			
			return true;
		} else {
			return false;
		}
	}
	
	
	public function region_by_id($id) {
		//call global function
		global $db;
		
		$this->c_found = false;
		
		try {
			//check if the id parsed is numeric
			if(preg_match("/^[0-9]+$/", $id)) {
				//query the database table
				$stmt = $this->db->query("SELECT * FROM `_countries_gh_regional` WHERE `id`='$id'");
				//count the number of rows
				if($this->db->num_rows($stmt) == 1) {
					//fetch the results
					foreach($stmt as $res) {
						
						$this->r_found = true;
					
						$this->name = $res['name'];
						$this->id = $res['id'];
						$this->capital = $res['capital'];
					}
					
				}
			
			}
			
			return $this;
		} catch(PDOException $e) {}

	}
}
?>