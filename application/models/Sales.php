<?php 

class Sales {
	
	public function __construct() {
		
		global $DB;
		
		$this->db = $DB;
		$this->user_agent = load_class('user_agent', 'libraries');
		$this->session = load_class('session', 'libraries\Session');
		
	}
	
	public function _tabulate_sales($start_date, $interval = 7) {
		
		$store_addons = "and store_id='".STORE_ID."'";
		
		try {
			
			$this->total_price = $this->db->sumOfAll("total_paid", "_customers_orders", "where 
				 (full_date BETWEEN DATE_SUB($start_date, INTERVAL $interval DAY) and $start_date) and returned='0' $store_addons");
			
			return $this->total_price;
			
		} catch(PDOException $e) {}
		
	}
	
	public function _tabulate_sales_by_period($current_period) {
		
		$store_addons = "and store_id='".STORE_ID."'";
		
		try {
			
			$this->total_price = $this->db->sumOfAll("total_paid", "_customers_orders", "where 
				 ($current_period(full_date) = $current_period(CURDATE())) and returned='0' $store_addons");
			
			return $this->total_price;
			
		} catch(PDOException $e) {}
		
	}
	
	public function _tabulate_sales_for_today($current_year = "CURDATE()") {
		
		$store_addons = "and store_id='".STORE_ID."'";
		
		try {
			
			$this->total_price = $this->db->sumOfAll("total_paid", "_customers_orders", "where 
				 full_date = CURDATE() and returned='0' $store_addons");
			
			return $this->total_price;
			
		} catch(PDOException $e) {}
		
	}
	
	public function _date_difference($start_date, $interval) {
		
		$datetime1 = (int)($interval*24*60*60);
		$datetime2 = strtotime($start_date);

		$secs = $datetime2 - $datetime1;// == <seconds between the two times>

		return date("Y-m-d", ($secs));
	}
	
	public function _customer_last_purchase($customer_id) {
		
		$store_addons = "and store_id='".STORE_ID."'";
		
		try {
			$this->total_order = $this->db->lastRowColumn("overall_price", "_customers_orders where customer_id='$customer_id' and returned='0' $store_addons");
			
			$this->order_date = $this->db->sumOfAll("date_added", "_customers_orders", "where customer_id='$customer_id' and returned='0' $store_addons order by id desc limit 1", NULL);
			
		} catch(PDOException $e) {}
		
		return $this;
	}
	
	public function _calculate_based_on_parameters($column, $query_string) {
		
		$this->total_price = 0.00;
		
		try {
			
			$this->total_price = $this->db->sumOfAll("$column", "_customers_orders", "$query_string");
			
			return $this->total_price;
			
		} catch(PDOException $e) {}
		
	}
	
}