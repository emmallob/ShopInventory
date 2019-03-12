<?php 

class Stocks {
	
	public function __construct() {
		
		global $DB;
		
		$this->db = $DB;
		$this->user_agent = load_class('user_agent', 'libraries');
		$this->session = load_class('session', 'libraries\Session');
		
	}
	
	public function _product_stock($product_id) {
		
		$store_addons = "and store_id='".STORE_ID."'";
		
		try {
			
			$stmt = $this->db->prepare("select * from _stocks, l_update=now() where product_id='{$product_id}' $store_addons");
			$stmt->execute();
			
			while($results = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
				$this->stocks_left = $results["quantity"];
			}
			
		} catch(PDOException $e) {}
		
		return $this;
	}
	
	public function _update_stock($quantity, $product_id) {
		
		$store_addons = "and store_id='".STORE_ID."'";
		
		try {
			
			if($this->db->query("update _stocks set quantity='{$quantity}', l_update=now() where product_id='{$product_id}' $store_addons")) {
				return true;
			}
			
		} catch(PDOException $e) {}
		
	}
	
	public function _deduct_stock($quantity, $product_id) {
		
		$store_addons = "and store_id='".STORE_ID."'";
		
		try {
			
			$stostmt = $this->db->query("select * from _stocks where product_id='{$product_id}' $store_addons");
			
			foreach($stostmt as $storesults) {
				#get the initial stock 
				$stocks_left = $storesults["quantity"];
				#set the new stocks
				$stocks_new = (int)($stocks_left) - (int)($quantity);
				#update the stocks details
				$this->db->query("update _stocks set quantity='{$stocks_new}', l_update=now() where product_id='{$product_id}' and store_id='".STORE_ID."'");
				#update the products details as well 
				$this->db->query("update _products set product_quantity='{$stocks_new}' where id='{$product_id}' and store_id='".STORE_ID."'");
			}
			
		} catch(PDOException $e) {}
		
	}
	
	public function backup_system($file) {
		
		$output = "-- phpMyAdmin SQL Dump\n";
		$output .= "-- version 4.7.0\n";
		$output .= "-- https://www.phpmyadmin.net/\n";
		$output .= "--\n";
		$output .= "-- Host: ".SITE_NAME." - ".DB_HOST."\n";
		$output .= "-- Generation Time: " . date("r", time()) . "\n";
		$output .= "-- Server version: 10.1.22-MariaDB\n";
		$output .= "-- PHP Version: " . phpversion() . "\n\n";
		$output .= "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n";
		$output .= "SET AUTOCOMMIT = 0;\n";
		$output .= "START TRANSACTION;\n";
		$output .= "SET time_zone = \"+00:00\";\n\n";
	
		$output .= "--\n-- Database: `".DB_NAME."`\n--\n";
		$output .= "\nCREATE DATABASE IF NOT EXISTS `".DB_NAME."` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `".DB_NAME."`;\n";
		// get all table names in db and stuff them into an array
		$tables = array();
		$stmt = $this->db->query("SHOW TABLES");
		while($row = $stmt->fetch(PDO::FETCH_NUM)){
			$tables[] = $row[0];
		}

		// process each table in the db
		foreach($tables as $table){
			$fields = "";
			$sep2 = "";
			$output .= "\n-- " . str_repeat("-", 60) . "\n\n";
			$output .= "--\n-- Table structure for table `$table`\n--\n\n";
			// get table create info
			$output .= "DROP TABLE IF EXISTS `$table`;\n";
			$stmt = $this->config->query("SHOW CREATE TABLE $table");
			$row = $stmt->fetch(PDO::FETCH_NUM);
			$output.= $row[1].";\n\n";
			// get table data
			$output .= "--\n-- Dumping data for table `$table`\n--\n\n";
			$stmt = $this->config->query("SELECT * FROM $table");
			while($row = $stmt->fetch(PDO::FETCH_OBJ)){
				// runs once per table - create the INSERT INTO clause
				if($fields == ""){
					$fields = "INSERT INTO `$table` (";
					$sep = "";
					// grab each field name
					foreach($row as $col => $val){
						$fields .= $sep . "`$col`";
						$sep = ", ";
					}
					$fields .= ") VALUES";
					$output .= $fields . "\n";
				}
				// grab table data
				$sep = "";
				$output .= $sep2 . "(";
				foreach($row as $col => $val){
					// add slashes to field content
					$val = addslashes($val);
					// replace stuff that needs replacing
					$search = array("\'", "\n", "\r");
					$replace = array("''", "\\n", "\\r");
					$val = str_replace($search, $replace, $val);
					$output .= $sep . "'$val'";
					$sep = ", ";
				}
				// terminate row data
				$output .= ")";
				$sep2 = ",\n";
			}
			// terminate insert data
			$output .= ";\n";
		}   
	
		//open the file
		$fh = @fopen($file,"w");
		
		//write the contents into the file
		@fwrite($fh,$output);
		@fclose($fh);
		
		return true;
	}
	
}
?>