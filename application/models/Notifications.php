<?php 

class Notifications {
	
	public function __construct() {
		
		global $DB;
		
		$this->db = $DB;
		$this->user_agent = load_class('user_agent', 'libraries');
		$this->session = load_class('session', 'libraries\Session');
		
	}
	
	public function stock_out() {
		
		$this->stock_alerts = false;
		
		$store_addons = "and store_id='".STORE_ID."'";
		
		try {			
			$stmt3 = $this->db->query("select * from _products where product_quantity < ".STOCKS_LIMIT." and status='1' $store_addons");
			
			if ($this->db->num_rows($stmt3)  > 0) {					
				$this->stock_alerts = true;
				$this->stock_number = $this->db->num_rows($stmt3);
			}
		} catch(PDOException $e) {}
		
		return $this;
	}
	
	
	public function login_attempt($username) {
		
		$this->login_alerts = false;
		
		$store_addons = "and store_id='".STORE_ID."'";
		
		try {			
			$stmt1 = $this->db->query("select * from _admin where username ='$username' $store_addons");
			
			if ($this->db->num_rows($stmt1)  > 0) {
				foreach($stmt1 as $results1) {
					$this->login_alerts = true;
					$this->login_number = $results1["last_login_attempts"];
					$this->login_time = $results1["last_login_attempts_time"];
				}
			}
		} catch(PDOException $e) {}
		
		return $this;
	}
	
	public function password_change($username) {
		
		$this->change_request = false;
		
		$store_addons = "and store_id='".STORE_ID."'";
		
		try {			
			$stmt = $this->db->query("select * from _admin_request_change where username ='$username' $store_addons");
			
			if ($this->db->num_rows($stmt)  > 0) {
				foreach($stmt as $results2) {
					$this->change_request = true;
					$this->change_time = $results2["date_recorded"];
				}
			}
		} catch(PDOException $e) {}
		
		return $this;
	}
	
	public function locked_account() {
		
		$this->locked_acs = false;
		
		try {			
			$stmt = $this->db->query("select * from _login_attempt where attempts > ".ATTEMPTS_NUMBER);
			
			if ($this->db->num_rows($stmt)  > 0) {
				foreach($stmt as $results2) {
					$this->locked_acs = true;
				}
			}
		} catch(PDOException $e) {}
		
		return $this;
	}
	
	public function password_change_requests($username) {
		
		$this->change_request1 = false;
		
		try {			
			$stmt = $this->db->query("select * from _admin_request_change where username !='$username'");
			
			if ($this->db->num_rows($stmt)  > 0) {
				foreach($stmt as $results2) {
					$this->change_request1 = true;
				}
			}
		} catch(PDOException $e) {}
		
		return $this;
	}
	
	public function notice_board() {
		
		global $admin_user;
		
		if(!$admin_user->confirm_super_user()) {
		
			print "<div class='alert alert-info'>We bring you warm greetings from the <strong>Technical Desk</strong> of <strong>".SITE_NAME.".</strong> Better and bigger updates are trending in every now and then. The new update is the introduction of a <strong>Website</strong> which is linked to your <strong>Products Store</strong> where customers will be able to make <strong>Orders</strong> and you confirm the <strong>Checkout</strong> process.<br><br> To Sign Up to this new update, you can <strong><a href='".SITE_URL."/offers/website_link'>CLICK HERE</a></strong> to complete the linkage process. <strong>Terms and Conditions</strong> apply.</div>";
		
		}
		
	}
	
}