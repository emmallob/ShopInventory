<?php 

class Suppliers {
	
	public function __construct() {
		
		global $DB;
		
		$this->db = $DB;
		$this->user_agent = load_class('user_agent', 'libraries');
		$this->session = load_class('session', 'libraries\Session');
		
	}
	
	public function _generate_suppliers_id() {
		
		return SITE_ACRO.$this->db->maxOfAll("id","_suppliers")+1;
	}
	
	public function _list_suppliers($limit = "limit 0, 1000") {
		
		$store_addons = "and store_id='".STORE_ID."'";
		
		try {
			
			$stmt = $this->db->query("select * from _suppliers where status='1' and admin_deleted='1' $store_addons $limit");
			
			return $stmt;
			
		} catch(PDOException $e) {}
	}
	
	public function _list_suppliers_where($where_clause = null, $limit = "limit 0, 1000") {
		
		$store_addons = "and store_id='".STORE_ID."'";
		
		try {
			
			$stmt = $this->db->query("select * from _suppliers where status='1' and admin_deleted='1' $store_addons $where_clause $limit");
			
			return $stmt;
			
		} catch(PDOException $e) {}
		
	}
	
	
	public function _list_suppliers_by_id2($supplier_id) {
		
		$this->surp_found = false;
		
		$store_addons = "and store_id='".STORE_ID."'";
		
		if(!empty($supplier_id) and preg_match("/^[A-Z0-9]+$/", strtoupper($supplier_id))) {
						
			try {
			
				$stmt2 = $this->db->query("select * from _suppliers where id = '$supplier_id' and status='1' $store_addons");
				
				if ($this->db->num_rows($stmt2) == 1) {				
					foreach($stmt2 as $results1) {
						
						$this->surp_found = true;
						
						$this->sfullname = $results1["fullname"];
						$this->supplier_id = $results1["id"];
						$this->semail = $results1["email"];
						$this->scontact = $results1["contact"];
						$this->scontact2 = $results1["contact2"];
						$this->swebsite = $results1["website"];
						$this->saddress = $results1["address"];
						$this->sbalance = $results1["balance"];
						$this->sregion = $results1["region"];
						$this->soutstanding = $results1["outstanding"];
						$this->srecommended_by = $results1["recommended_by"];
						$this->slastdate = $results1["lastdate"];
						$this->sdescription = $results1["description"];
						$this->sdate_recorded = $results1["date_added"];
						$this->slast_supplied_id = $results1["last_supplied_id"];						
					}					
				}
				
			} catch(PDOException $e) {}
			
		}
		
		return $this;
	}
	
	
	public function _list_suppliers_by_id($supplier_id) {
		
		$this->surp_found = false;
		
		$store_addons = "and store_id='".STORE_ID."'";
		
		if(!empty($supplier_id) and preg_match("/^[A-Z0-9]+$/", strtoupper($supplier_id))) {
						
			try {
			
				$stmt2 = $this->db->query("select * from _suppliers where supplier_id = '$supplier_id' and status='1' $store_addons");
				
				if ($this->db->num_rows($stmt2) == 1) {				
					foreach($stmt2 as $results1) {
						
						$this->surp_found = true;
						
						$this->sfullname = $results1["fullname"];
						$this->supplier_id = $results1["id"];
						$this->semail = $results1["email"];
						$this->scontact = $results1["contact"];
						$this->scontact2 = $results1["contact2"];
						$this->swebsite = $results1["website"];
						$this->saddress = $results1["address"];
						$this->sbalance = $results1["balance"];
						$this->sregion = $results1["region"];
						$this->soutstanding = $results1["outstanding"];
						$this->srecommended_by = $results1["recommended_by"];
						$this->slastdate = $results1["lastdate"];
						$this->sdescription = $results1["description"];
						$this->sdate_recorded = $results1["date_added"];
						$this->slast_supplied_id = $results1["last_supplied_id"];						
					}					
				}
				
			} catch(PDOException $e) {}
			
		}
		
		return $this;
	}
	
	public function _update_last_supplied($stock_id, $supplier_id) {
		
		$store_addons = "and store_id='".STORE_ID."'";
		
		if($this->db->just_exec("update _suppliers set last_supplied_id='$stock_id', lastdate=now() where supplier_id='$supplier_id' $store_addons")) {			
			return true;
		} else {
			return false;
		}
	}
	
	public function _add_supplier($supplier_id, $fullname, $email, $contact, $contact2, $website, $address, $balance, $outstanding, $lastdate, $description, $last_supplied_id) {
		
		#insert a new supplier records 
		if($this->db->just_exec("into into _suppliers set supplier_id='$supplier_id', fullname='$fullname', email='$email', contact='$contact', contact2='$contact', website='$website', address='$address', balance='$balance', outstanding='$outstanding', lastdate='$lastdate', description='$description', last_supplied_id='$last_supplied_id', store_id='".STORE_ID."'")) {			
			return true;
		} else {
			return false;
		}
		
	}
	
	public function _update_supplier($supplier_id, $fullname, $email, $contact, $contact2, $website, $address, $balance, $outstanding, $lastdate, $description, $last_supplied_id) {
		
		#update the supplier records 
		if($this->db->just_exec("update _suppliers set fullname='$fullname', email='$email', contact='$contact', contact2='$contact', website='$website', address='$address', balance='$balance', outstanding='$outstanding', lastdate='$lastdate', description='$description', last_supplied_id='$last_supplied_id' where supplier_id='$supplier_id' and store_id='".STORE_ID."'")) {			
			return true;
		} else {
			return false;
		}
		
	}
	
	public function _delete_supplier($supplier_id) {
		
		$store_addons = "and store_id='".STORE_ID."'";
		
		if($this->db->just_exec("update _suppliers set status='0', admin_deleted='1', deleted_date=now() where supplier_id='$supplier_id' $store_addons")) {
			return true;
		} else {
			return false;
		}
	}
	
	
}
?>