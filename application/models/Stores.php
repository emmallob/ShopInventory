<?php 

class Stores {
	
	private $results;
	
	public function __construct() {
		
		global $DB;
		
		$this->db = $DB;
	}
	
	public function query() {
		
		global $session;
		
		try {
			
			$stmt = $this->db->where(STORE_TABLE, '*', ARRAY('id'=>"='".$session->userdata(":storeID")."'", 'siteactive'=>"='1'", 'sitedisabled'=>"='0'"));
			
			if($this->db->num_rows($stmt) > 0) {
				return $stmt;
			}
		
			return (array)$stmt;
		
		} catch(PDOException $e) { return $e->getMessage(); }
		
	}
	
	public function fetch() {
		
		//call the query
		$this->call = $this->query();
		
		//using foreach loop to fetch all the details as parsed
		foreach($this->call as $this->f):
			
			//assign the variables
			$this->name = $this->f["sitename"];
			$this->siteslogan = $this->f["siteslogan"];
			$this->email = $this->f["siteemail"];
			$this->prefix = $this->f["prefix"];
			$this->email2 = $this->f["siteemail2"];
			$this->phone = $this->f["sitephone"];
			$this->phone2 = $this->f["sitephone2"];
			$this->logo = $this->f["sitelogo"];
			$this->url = $this->f["siteurl"];
			$this->keywords = $this->f["sitekeywords"];
			$this->log_in_comment = $this->f["log_to_comment"];
			$this->receipt_message = $this->f["receipt_message"];
			$this->description = $this->f["sitedescription"];
			$this->seo = $this->f["siteseo"];			
			$this->headertext = $this->f["headertext"];
			$this->display_limit = $this->f["display_limit"];
			$this->stocks_limit = $this->f["stocks_limit"];
			$this->login_attempts = $this->f["login_attempts"];
			$this->denied_period = $this->f["denied_period"];
			$this->sell_on_credit = $this->f["sell_on_credit"];
			$this->automatic_backup = $this->f["automatic_backup"];
			$this->link_facebook = $this->f["link_facebook"];
			$this->link_twitter = $this->f["link_twitter"];
			$this->link_instagram = $this->f["link_linkedin"];
			$this->site_date_format = $this->f["date_format"];
			$this->link_google = $this->f["link_googleplus"];
			$this->developer = $this->f["siteauthor"];
			$this->active = $this->f["siteactive"];
			$this->address = $this->f["siteaddress"];
			
		endforeach;
	
		return $this;
		
	}

}
?>