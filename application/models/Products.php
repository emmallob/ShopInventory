<?php

class Products {
	
	public function __construct() {
		
		global $DB;
		
		$this->db = $DB;
		$this->user_agent = load_class('user_agent', 'libraries');
		$this->session = load_class('session', 'libraries\Session');
		
	}
	
	public function check_ifproduct_hasimage($product_id) {
		$this->image_result = '';
		
		$store_addons = "and store_id='".STORE_ID."'";
		
		try {
			
			if($product_id != NULL and !empty($product_id)) {
				$image_stmt = $this->db->query("SELECT * FROM `_products_images` WHERE `product_id`='{$product_id}' and status='1' $store_addons");
				
				if ($this->db->num_rows($image_stmt) < 1) {
					$this->image_result = "<br><small><span class='alert alert-danger' style='padding:2px;'>(Product Has No Image)</span></small>";
				}
			}
		} catch (PDOException $e) {}
		
		return $this;
	}
	
	public function product_by_id($type, $product_id) {
		
		$this->p_success = false;
		
		$store_addons = "and store_id='".STORE_ID."'";
		
		try {
			if($product_id != NULL and !empty($product_id)) {
				
				$stmt = $this->db->query("SELECT * FROM `_products` WHERE `$type`='{$product_id}' and status='1' $store_addons");
				
				/***** Count the number of rows if its equal to 1 *****/
				if ($this->db->num_rows($stmt) == 1) {	
					
					$this->p_success = true;
					
					foreach($stmt as $data) {
						
						$this->p_id = $data['id'];
						$this->pr_id = $data["product_id"];
						$this->p_category = $data["product_category"];
						$this->p_name = $data['product_name'];
						$this->p_slug = $data['product_slug'];
						$this->p_aprice = $data['product_actuals'];
						$this->p_cur = $data['product_currency'];
						$this->p_price = $data['product_price'];
						$this->p_details = $data['product_details'];
						$this->p_spec = $data['product_specifications'];
						$this->p_supplier = $data['product_supplier'];
						$this->p_quantity = $data['product_quantity'];
						$this->p_reviews = $data['product_reviews'];
						$this->p_avail = $data['product_availability'];
						$this->p_date = $data['product_date'];
						$this->p_owner = $data['product_owner'];
						$this->p_views = $data['product_views'];
						$this->p_status = $data['status'];
						$this->p_mod_by = $data['modified_by'];
						$this->p_mod_date = $data['modified_date'];
						
					}
				}
		
			}
			
		} catch (PDOException $e) {}
		
		return $this;
		
	}
	
	
	public function product_category_by_id($id) {
		
		$store_addons = "and store_id='".STORE_ID."'";
		
		try {
			if($id != NULL and !empty($id)) {
				
				$stmt = $this->db->query("SELECT * FROM `_category` WHERE `id`='{$id}' and status='1' $store_addons");
				
				/***** Count the number of rows if its equal to 1 *****/
				if ($this->db->num_rows($stmt) == 1):	
					
					$data = $stmt->fetch(PDO::FETCH_ASSOC);
								
					$this->getcId = $data['id'];
					$this->getcAlias = $data['slug'];
					$this->getcParentID = $data["parent_id"];
					$this->getcName = stripslashes($data['title']);
					$this->getcOption = "<option selected='selected' value='{$this->getcId}'>{$this->getcName}</option>";
					$this->getcOption .= "<option value='{$this->getcId}'>-------------------------------------------------------</option>";
					$this->getcLi = "<li><a href='".SITE_URL."/category/{$this->getcAlias}'>{$this->getcName}</a></li>";
					$this->getcA = "<a href='".SITE_URL."/category/{$this->getcAlias}'>{$this->getcName}</a>";
					$this->getcDescription = $data['description'];
				endif;
		
			}
			
		} catch (PDOException $e) {}
		
		return $this;
		
	}
	
	public function category_byid($id, $type, $parent_find=false) {
		
		$this->getName = "";
		$this->getId = "0000";
		$this->success = false;
		
		$this->getOption = "";
		
		$store_addons = "and store_id='".STORE_ID."'";
		
		try {
			if($id != NULL and !empty($id)) {
				
				$stmt = $this->db->query("SELECT * FROM `_category` WHERE `$type`='{$id}' and status='1' $store_addons");
				
				/***** Count the number of rows if its equal to 1 *****/
				if ($this->db->num_rows($stmt) == 1) {
					
					$this->success = true;
					
					foreach($stmt as $data ) {
								
						$this->getId = $data['id'];
						$this->getAlias = $data['slug'];
						$this->getImage = $data['image'];
						$this->supplierId = $data['supplier_id'];
						$this->parent_uid = $this->getParentID = $data["parent_id"];
						$this->getName = stripslashes($data['title']);
						$this->getOption = "<option selected='selected' value='{$this->getId}'>{$this->getName}</option>";
						
						if($parent_find == true):
							$this->getLi = "<li><a href='".SITE_URL."/products/{$this->product_category_by_id($this->parent_uid)->getcAlias}/{$this->getAlias}'>{$this->getName}</a></li>";
							$this->getA = "<a class='li-breadcrum' href='".SITE_URL."/products/{$this->getAlias}'>{$this->getName}</a>";
						else:
							$this->getLi = "<li><a href='".SITE_URL."/products/{$this->getAlias}'>{$this->getName}</a></li>";
							$this->getA = "<a class='li-breadcrum' href='".SITE_URL."/products/{$this->getAlias}'>{$this->getName}</a>";
						endif;
						
						$this->getDescription = $data['description'];
						
						if(strlen($this->getDescription) < 5):
							$this->getDescription = SITE_DESCRIPTION;
						endif;
						
					}
				}
		
			}
			
		} catch (PDOException $e) {}
		
		return $this;
		
	}
	
	
	public function categories_limit($where_clause=1) {
		
		$store_addons = "and store_id='".STORE_ID."'";
		
		$stmt = $this->db->query("SELECT * FROM `_category` where $where_clause $store_addons order by position");
		
		if(count($stmt)):
			foreach($stmt as $result) {
				print "<option value='{$result["id"]}'>{$result["title"]}</option>";
			}
		endif;
	}
	
	
	public function session_quantity($product_id) {
		
		$this->quantity = 0;
		$this->subtotal = "GH¢ 0.00";
		
		if(isset($_SESSION["CaaAPcart_array"]) and count($_SESSION["CaaAPcart_array"]) > 0) {
			// Start the For Each loop
			$i = 0; 
			foreach ($_SESSION["CaaAPcart_array"] as $each_item) { 
				$i++;
				// RUN IF THE CART HAS AT LEAST ONE ITEM IN IT
				while (list($key, $value) = each($each_item)) {
					if ($key == "item_id" && $value == $product_id) {
						// That item is in cart already so let's adjust its quantity using array_splice()
						$this->quantity = $each_item['quantity'];
						$this->product_price = $this->product_by_id("id", $product_id)->p_aprice;
						$this->subtotal = "GH¢ ".number_format(($this->quantity*$this->product_price), 2);
					}
				}
			}
		}
		
		return $this;
		
	}
	
}