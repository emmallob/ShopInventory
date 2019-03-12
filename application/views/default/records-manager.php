<?php
#call some important functions 
$user_id = xss_clean($admin_user->return_username());

$customers_list = load_class('customers', 'models');
$products = load_class('products', 'models');
$orders_list = load_class('Orders', 'models');
$suppliers = load_class('suppliers', 'models');
$csales = load_class('sales', 'models');
	
	
	
#set the payment id for the product
if(isset($SITEURL[2]) and $SITEURL[1] == "voucher") {
	#set the voucher id
	$voucher_id = xss_clean($SITEURL[2]);
	#get the voucher details for the receipt
	if($orders_list->order_by_id($voucher_id)->o_success) {
		$voucher_date = $orders_list->order_by_id($voucher_id)->o_date;
		$voucher_payment = $orders_list->order_by_id($voucher_id)->payment_method;
		$sold_by = $orders_list->order_by_id($voucher_id)->sold_by;
		$o_discount = $orders_list->order_by_id($voucher_id)->o_discount;
		$overall_price = $orders_list->order_by_id($voucher_id)->overall_price;
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Records Manager :: <?php print STORE_NAME; ?> >> <?PHP PRINT config_item('site_name'); ?></title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<link href="<?php print SITE_URL; ?>/assets/bower_components/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
	<style>
		.wrapper {
			width:98%;
			box-shadow:0px 1px 2px #000;
			margin:auto auto;
			color:#000!important;
		}
		.wrapper table h1 {color:#000!important;padding:10px;margin:0px;}
		.wrapper table strong{font-size:13px;margin-bottom:5px;padding:10px;}
		.wrapper label {font-weight:bolder;margin-top:10px;}
		.wrapper table {font-family:Times New Roman;font-size:12px}
	</style>
</head>
<body>

<div class="wrapper">
<table class="table table-bordered" width="100%" border="1">
	<tr>
		<td align="center" colspan="3"><h1>LIFE ESSENTIALS</h1></td>
	</tr>
	<tr>
		<td valign="top" colspan="2">
			<?php if(!isset($SITEURL[2])) { ?>
			<h4 align="left" style="text-decoration:underline">PRINT 
			<?php if(isset($SITEURL[1])) { print strtoupper(xss_clean($SITEURL[1])); } ?> RECORDS</h4>
			<div class="col-md-5">
			<select onchange="window.location.href=this.value" required class="form-control" id="gender" name="gender">
			<option value="">SELECT OPTION TO FETCH RECORDS</option>
			<option value="">----------------------------------</option>
			<option <?php if(isset($SITEURL[1]) and $SITEURL[1]=="customer") print "selected"; ?> value="<?php print SITE_URL; ?>/records-manager/customer">CUSTOMERS</option>
			<option <?php if(isset($SITEURL[1]) and $SITEURL[1]=="supplier") print "selected"; ?> value="<?php print SITE_URL; ?>/records-manager/supplier">SUPPLIERS</option>
			<option <?php if(isset($SITEURL[1]) and $SITEURL[1]=="sales") print "selected"; ?> value="<?php print SITE_URL; ?>/records-manager/sales?s=<?php print date("Y-m-d"); ?>">SALES RECORDS <strong>(Tabulate by the orders)</strong></option>
			<option <?php if(isset($SITEURL[1]) and $SITEURL[1]=="products") print "selected"; ?> value="<?php print SITE_URL; ?>/records-manager/products">PRODUCTS (Products sold within a period)</option>
			<?php if($admin_user->confirm_admin_user() == true) { ?>
			<option <?php if(isset($SITEURL[1]) and $SITEURL[1]=="activity") print "selected"; ?> value="<?php print SITE_URL; ?>/records-manager/activity?s=<?php print date("Y-m-d"); ?>">ACTIVITY HISTORY</option>
			<?php } ?>
			<?php if($admin_user->confirm_admin_user() == true) { ?>
			<option <?php if(isset($SITEURL[1]) and $SITEURL[1]=="receipts") print "selected"; ?> value="<?php print SITE_URL; ?>/records-manager/receipts">RECEIPTS RECORDS</option>
			<?php } ?>
			</select>
			</div>
			<br clear="both">
			<hr>
			
			<?php if(isset($SITEURL[1]) and $SITEURL[1]=="sales") { ?>
			<div class="row">
			<div class="col-md-2">
				<label>VOUCHER ID</label>
				<input value="<?php if(isset($_GET["v"])) print xss_clean($_GET["v"]); ?>" placeholder="Filter By Voucher ID" type="text" id="voucher_id" class="form-control">
			</div>
			<div class="col-md-2">
				<label>CUSTOMER ID</label>
				<select required class="form-control" id="customer_id" name="customer_id">
				<option value="NULL">Filter by Customer</option>
				<?php
				#fetch the article categories 
				$customer_records = $DB->query("select * from `_customers` where status='1' and store_id='".STORE_ID."'");
				#count the number of rows 
				if(count($customer_records) > 0):
				#using foreach loop to fetch the data 
				foreach($customer_records as $cres):					
				?>
				<?php if(isset($_GET["c"]) and $_GET["c"]==$cres["customer_id"]): ?>
				<option selected value="<?php print $cres["customer_id"]; ?>"><?php print $cres["firstname"]; ?> <?php print $cres["lastname"]; ?></option>
				<?php else: ?>
				<option value="<?php print $cres["customer_id"]; ?>"><?php print $cres["firstname"]; ?> <?php print $cres["lastname"]; ?></option>
				<?php endif; ?>
				<?php endforeach; endif; ?>
				</select>
			</div>
			<div class="col-md-2">
				<label>ADMIN ID</label>
				<select required class="form-control" id="admin_id" name="admin_id">
				<option value="NULL">Filter by Admin</option>
				<?php
				#fetch the article categories 
				$admin_records = $DB->query("select * from `_admin` where status='1' and role!='1001' and store_id='".STORE_ID."'");
				#count the number of rows 
				if(count($admin_records) > 0):
				#using foreach loop to fetch the data 
				foreach($admin_records as $admin_results):					
				?>
				<?php if(isset($_GET["a"]) and $_GET["a"]==$admin_results["username"]): ?>
				<option selected value="<?php print $admin_results["username"]; ?>"><?php print $admin_results["fullname"]; ?></option>
				<?php else: ?>
				<option value="<?php print $admin_results["username"]; ?>"><?php print $admin_results["fullname"]; ?></option>
				<?php endif; ?>
				<?php endforeach; endif; ?>
				</select>
			</div>
			<div class="col-md-2">
			<label>START DATE</label>
			<input value="<?php if(isset($_GET["s"])) print xss_clean($_GET["s"]); else print date("Y-m-d"); ?>" class="form-control datepicker" id="start" type="text" placeholder="Select start date"/>
			</div>
			<div class="col-md-2">
			<label>THE END DATE</label>
			<input value="<?php if(isset($_GET["e"])) print xss_clean($_GET["e"]); ?>" class="form-control datepicker" id="end" type="text" placeholder="Select end date"/>
			</div>
			
			<div class="col-md-2">
			<label>SUBMIT FORM</label>
			<input style="cursor:pointer" onclick=" return filter_records();" class="form-control btn btn-success" type="submit" value="FILTER"/>
			</div>
			</div>
			
			<script>
			function filter_records() {
				var a = $("#admin_id").val();
				var v = $("#voucher_id").val();
				var c = $("#customer_id").val();
				var s = $("#start").val();
				var e = $("#end").val();
				window.location.href='<?php print SITE_URL; ?>/records-manager/sales?a='+a+'&c='+c+'&v='+v+'&s='+s+'&e='+e;				
			}
			</script>
			<?php } ?>
				
				
			<?php if(isset($SITEURL[1]) and $SITEURL[1]=="products") { ?>
			<div class="row">
			<div class="col-md-3">
				<label>FILTER BY PRODUCT ID</label>
				<input value="<?php if(isset($_GET["p"])) print strtoupper(xss_clean($_GET["p"])); ?>" placeholder="Filter By Product ID" type="text" id="product_id" class="form-control">
			</div>
			<div class="col-md-3">
				<label>FILTER BY ADMIN</label>
				<select required class="form-control" id="admin_id" name="admin_id">
				<option value="NULL">Filter by Admin</option>
				<?php
				#fetch the article categories 
				$admin_records = $DB->query("select * from `_admin` where status='1' and role!='1001' and store_id='".STORE_ID."'");
				#count the number of rows 
				if(count($admin_records) > 0):
				#using foreach loop to fetch the data 
				foreach($admin_records as $admin_results):					
				?>
				<?php if(isset($_GET["a"]) and $_GET["a"]==$admin_results["username"]): ?>
				<option selected value="<?php print $admin_results["username"]; ?>"><?php print $admin_results["fullname"]; ?></option>
				<?php else: ?>
				<option value="<?php print $admin_results["username"]; ?>"><?php print $admin_results["fullname"]; ?></option>
				<?php endif; ?>
				<?php endforeach; endif; ?>
				</select>
			</div>
			<div class="col-md-3">
			<label>SELECT START DATE</label>
			<input value="<?php if(isset($_GET["s"])) print xss_clean($_GET["s"]); ?>" class="form-control datepicker" id="start" type="text" placeholder="Select start date"/>
			</div>
			<div class="col-md-3">
			<label>SELECT THE END DATE</label>
			<input value="<?php if(isset($_GET["e"])) print xss_clean($_GET["e"]); ?>" class="form-control datepicker" id="end" type="text" placeholder="Select end date"/>
			</div>
			</div>
			<hr>
			<div class="row">
			<div class="col-md-4 text-center">
			<input style="cursor:pointer" onclick=" return filter_records();" class="form-control btn btn-success" type="submit" value="FILTER RECORDS"/>
			</div>
			</div>
			<script>
			function filter_records() {
				var a = $("#admin_id").val();
				var v = $("#product_id").val();
				var s = $("#start").val();
				var e = $("#end").val();
				window.location.href='<?php print SITE_URL; ?>/records-manager/products?a='+a+'&p='+v+'&s='+s+'&e='+e;				
			}
			</script>
			<?php } ?>	
				
			<?php if(isset($SITEURL[1]) and in_array($SITEURL[1], array("receipts"))) { ?>
			<div class="row">
			<div class="col-md-3">
				<label>FILTER BY ADMIN</label>
				<select required class="form-control" id="admin_id" name="admin_id">
				<option value="NULL">Filter by Admin</option>
				<?php
				#fetch the article categories 
				$admin_records = $DB->query("select * from `_admin` where status='1' and role!='1001' and store_id='".STORE_ID."'");
				#count the number of rows 
				if(count($admin_records) > 0):
				#using foreach loop to fetch the data 
				foreach($admin_records as $admin_results):					
				?>
				<?php if(isset($_GET["a"]) and $_GET["a"]==$admin_results["username"]): ?>
				<option selected value="<?php print $admin_results["username"]; ?>"><?php print $admin_results["fullname"]; ?></option>
				<?php else: ?>
				<option value="<?php print $admin_results["username"]; ?>"><?php print $admin_results["fullname"]; ?></option>
				<?php endif; ?>
				<?php endforeach; endif; ?>
				</select>
			</div>
			<div class="col-md-3">
			<label>SELECT START DATE</label>
			<input value="<?php if(isset($_GET["s"])) print xss_clean($_GET["s"]); ?>" class="form-control datepicker" id="start" type="text" placeholder="Select start date"/>
			</div>
			<div class="col-md-3">
			<label>SELECT THE END DATE</label>
			<input value="<?php if(isset($_GET["e"])) print xss_clean($_GET["e"]); ?>" class="form-control datepicker" id="end" type="text" placeholder="Select end date"/>
			</div>
			</div>
			<hr>
			<div class="row">
			<div class="col-md-4 text-center">
			<input style="cursor:pointer" onclick=" return filter_records();" class="form-control btn btn-success" type="submit" value="FILTER RECORDS"/>
			</div>
			</div>
			<script>
			function filter_records() {
				var a = $("#admin_id").val();
				var s = $("#start").val();
				var e = $("#end").val();
				window.location.href='<?php print SITE_URL; ?>/records-manager/receipts?a='+a+'&s='+s+'&e='+e;				
			}
			</script>
			<?php } ?>
				
			<?php if(isset($SITEURL[1]) and in_array($SITEURL[1], array("customer","supplier"))) { ?>
			<div class="row">
			<div class="col-md-3">
				<label>FILTER BY <?php print strtoupper($SITEURL[1]); ?> ID</label>
				<input value="<?php if(isset($_GET["v"])) print xss_clean($_GET["v"]); ?>" placeholder="Filter By <?php print ucfirst($SITEURL[1]); ?> ID" type="text" id="<?php print strtolower($SITEURL[1]); ?>_id" class="form-control">
			</div>
			
			<div class="col-md-3">
				<label>FILTER BY ADMIN</label>
				<select required class="form-control" id="admin_id" name="admin_id">
				<option value="NULL">Filter by Admin</option>
				<?php
				#fetch the article categories 
				$admin_records = $DB->query("select * from `_admin` where status='1' and role!='1001' and store_id='".STORE_ID."'");
				#count the number of rows 
				if(count($admin_records) > 0):
				#using foreach loop to fetch the data 
				foreach($admin_records as $admin_results):					
				?>
				<?php if(isset($_GET["a"]) and $_GET["a"]==$admin_results["username"]): ?>
				<option selected value="<?php print $admin_results["username"]; ?>"><?php print $admin_results["fullname"]; ?></option>
				<?php else: ?>
				<option value="<?php print $admin_results["username"]; ?>"><?php print $admin_results["fullname"]; ?></option>
				<?php endif; ?>
				<?php endforeach; endif; ?>
				</select>
			</div>
			<div class="col-md-3">
			<label>SELECT START DATE</label>
			<input value="<?php if(isset($_GET["s"])) print xss_clean($_GET["s"]); ?>" class="form-control datepicker" id="start" type="text" placeholder="Select start date"/>
			</div>
			<div class="col-md-3">
			<label>SELECT THE END DATE</label>
			<input value="<?php if(isset($_GET["e"])) print xss_clean($_GET["e"]); ?>" class="form-control datepicker" id="end" type="text" placeholder="Select end date"/>
			</div>
			</div>
			<hr>
			<div class="row">
			<div class="col-md-4 text-center">
			<input style="cursor:pointer" onclick=" return filter_records();" class="form-control btn btn-success" type="submit" value="FILTER RECORDS"/>
			</div>
			</div>
			<script>
			function filter_records() {
				var a = $("#admin_id").val();
				var c = $("#<?php print strtolower($SITEURL[1]); ?>_id").val();
				var s = $("#start").val();
				var e = $("#end").val();
				window.location.href='<?php print SITE_URL; ?>/records-manager/<?php print strtolower($SITEURL[1]); ?>?a='+a+'&c='+c+'&s='+s+'&e='+e;				
			}
			</script>
			<?php } ?>
			
			<?php if(isset($SITEURL[1]) and in_array($SITEURL[1], array("activity"))) { ?>
			<div class="row">
			<div class="col-md-3">
				<label>FILTER BY ADMIN</label>
				<select required class="form-control" id="admin_id" name="admin_id">
				<option value="<?php print $user_id; ?>"><?php print $user_id; ?></option>
				<option value="<?php print $user_id; ?>">----------------------------------</option>
				<?php
				#fetch the article categories 
				$admin_records = $DB->query("select * from `_admin` where status='1' and role!='1001' and store_id='".STORE_ID."'");
				#count the number of rows 
				if(count($admin_records) > 0):
				#using foreach loop to fetch the data 
				foreach($admin_records as $admin_results):					
				?>
				<?php if(isset($_GET["a"]) and $_GET["a"]==$admin_results["username"]): ?>
				<option selected value="<?php print $admin_results["username"]; ?>"><?php print $admin_results["fullname"]; ?></option>
				<?php else: ?>
				<option value="<?php print $admin_results["username"]; ?>"><?php print $admin_results["fullname"]; ?></option>
				<?php endif; ?>
				<?php endforeach; endif; ?>
				</select>
			</div>
			<div class="col-md-3">
			<label>SELECT START DATE</label>
			<input value="<?php if(isset($_GET["s"])) print xss_clean($_GET["s"]); ?>" class="form-control datepicker" id="start" type="text" placeholder="Select start date"/>
			</div>
			<div class="col-md-3">
			<label>SELECT THE END DATE</label>
			<input value="<?php if(isset($_GET["e"])) print xss_clean($_GET["e"]); ?>" class="form-control datepicker" id="end" type="text" placeholder="Select end date"/>
			</div>
			<div class="col-md-3 text-center">
			<label>&nbsp;</label>
			<input style="cursor:pointer" onclick=" return filter_records();" class="form-control btn btn-success" type="submit" value="FILTER RECORDS"/>
			</div>
			</div>
			
			<script>
			function filter_records() {
				var a = $("#admin_id").val();
				var s = $("#start").val();
				var e = $("#end").val();
				window.location.href='<?php print SITE_URL; ?>/records-manager/activity?a='+a+'&s='+s+'&e='+e;				
			}
			</script>
			<?php } ?>
			
			
			<?php } ?>
		
			<?php if(isset($SITEURL[2]) and $SITEURL[1] == "voucher") { ?>
			<h5 align="left">RECEIPT VOUCHER</h5>
			<strong>VOUCHER NUMBER:</strong> <?php print $voucher_id; ?>
			<br><strong>VOUCHER DATE:</strong> <?php print $voucher_date; ?>
			<br><strong>SOLD BY:</strong> <?php print $sold_by; ?>
			<br><strong>PAYMENT TYPE:</strong> <?php print $voucher_payment; ?>
			<br clear="both"><br clear="both">
			<?php } ?>
		</td>
		<td valign="top" align="left" style="font-size:12px">
			<h5>CONTACT US</h5>
			<strong>PHONE:</strong> <?php print $stores->fetch()->phone; ?> / <?php print $stores->fetch()->phone2; ?>
			<br><strong>EMAIL:</strong> <?php print $stores->fetch()->email; ?>
			<br><strong>ADDRESS:</strong> <?php print $stores->fetch()->address; ?>
			<br clear="both"><br clear="both">
			<button class="btn btn-success" onclick="javascript:window.location.href='<?php print SITE_URL; ?>'"><li class="icon-home">GO BACK</button>
			<button class="btn btn-primary" onclick="javascript:window.print();"><li class="icon-printer">PRINT</button>
		</td>
	</tr>
	<tr>
	<td colspan="3">
		
		<?php if(isset($SITEURL[1]) and in_array($SITEURL[1], array("customer","supplier"))) { ?>
		<table class="table table-bordered">
			<thead>
			<tr class="text-uppercase">
			<th>ID</th>
			<th>FULLNAME</th>
			<?php if(strtolower($SITEURL[1]) == "customer") { ?>
			<th>GENDER</th>
			<?php } ?>
			<th>CONTACT</th>
			<th>ADDRESS</th>
			<th>BALANCE</th>
			<?php if(strtolower($SITEURL[1]) == "customer") { ?><th>DEBTS</th><?php } ?>
			<th>LAST <?php if(strtolower($SITEURL[1]) == "customer") print "ORDER"; else print "SUPPLIED"; ?></th>
			</tr>
			</thead>		  
			<tbody>
			<?php
			$where_clause = "status='1'";
			#check if the form has been submitted
			if(isset($_GET["a"]) and $_GET["a"] != "NULL" and !empty($_GET["a"])) {
				$where_clause .= " and added_by='".xss_clean($_GET["a"])."'";
			}
			if(isset($_GET["c"]) and !empty($_GET["c"]) and $_GET["c"] != "NULL") {
				$where_clause .= " and ".strtolower($SITEURL[1])."_id='".xss_clean($_GET["c"])."'";
			}
			if(isset($_GET["s"]) and !empty($_GET["s"]) and empty($_GET["e"])) {
				$where_clause .= " and date_added='".xss_clean($_GET["s"])."'";
			}
			if(isset($_GET["s"]) and !empty($_GET["s"]) and !empty($_GET["e"])) {
				$where_clause .= " and date_added between '".xss_clean($_GET["s"])."' and '".xss_clean($_GET["e"])."'";
			}

			#initialization
			$list_customers = $DB->query("select * from _".strtolower($SITEURL[1])."s where $where_clause and store_id='".STORE_ID."' order by id desc");
			foreach($list_customers as $results):
			?>
			<tr>
			<td><?php print $results["".strtolower($SITEURL[1])."_id"]; ?></td>
			<?php if(strtolower($SITEURL[1]) == "customer") { ?>
			<td><?php print $results["firstname"] ." " .$results["lastname"]; ?></td>
			<?php } else { ?>
			<td><?php print $results["fullname"]; ?></td>
			<?php } ?>
			<?php if(strtolower($SITEURL[1]) == "customer") { ?>
			<td><?php print $results["gender"]; ?></td>
			<?php } ?>
			<td><?php print $results["contact"]; ?> <?php if(strlen($results["contact2"]) > 5) print " / ".$results["contact2"]; ?></td>	
			<td><?php if(strlen($results["email"]) > 5) print " <strong>EMAIL: </strong> ".$results["email"]."<br>"; ?> <strong>ADDRESS: </strong> <?php print $results["address"]; ?> <br>
			<strong>REGION: </strong> <?php print $customers_list->region_by_id($results["region"])->name; ?> Region </td>
			<td>GH&#8373; <?php print number_format($results["balance"], 2); ?></td>
			<?php if(strtolower($SITEURL[1]) == "customer") { ?>
			<td>GH&#8373; <?php print number_format($results["outstanding"], 2); ?></td>
			<?php } ?>
			<td><?php print date("d-m-Y", strtotime($results["lastdate"])); ?></td> 
			</tr>
			<?php endforeach; ?>
			</tbody>
		</tbody>
		</table>
		<?php } ?>
			
		<?php if(isset($SITEURL[1]) and in_array($SITEURL[1], array("products"))) { ?>
		<table class="table table-bordered">
			<thead>
			<tr class="text-uppercase">
			<th>PID</th>
			<th>PRODUCT NAME</th>
			<th>PREVIOUS QTY</th>
			<th>QTY SOLD</th>
			<th>CURRENT QTY</th>
			<th>UNIT PRICE</th>
			<th>AMOUNT OF SOLD PRODUCT</th>
			<th>QTY LEFT AMOUNT</th>
			</tr>
			</thead>		  
			<tbody>
			<?php
			$where_clause = "returned='0'";
			$where_clause2 = "returned='0'";
			#check if the form has been submitted
			if(isset($_GET["a"]) and $_GET["a"] != "NULL" and !empty($_GET["a"])) {
				//$where_clause .= " and added_by='".xss_clean($_GET["a"])."'";
			}
			if(isset($_GET["p"]) and !empty($_GET["p"]) and $_GET["p"] != "NULL") {
				$actual_id = $products->product_by_id("product_id", $_GET["p"])->p_id;
				$where_clause .= " and product_id='$actual_id'";
			}
			if(isset($_GET["s"]) and !empty($_GET["s"]) and empty($_GET["e"])) {
				$where_clause = $where_clause2 .= " and full_date='".xss_clean($_GET["s"])."'";
			}
			if(isset($_GET["s"]) and !empty($_GET["s"]) and !empty($_GET["e"])) {
				$where_clause = $where_clause2 .= " and full_date between '".xss_clean($_GET["s"])."' and '".xss_clean($_GET["e"])."'";
			}

			#initialization
			$total_quantity_as_at = $sold_price = $total_quantity = $total_current = $sold_quantity = 0;
			$list_products = $DB->query("select * from _customers_orders_details where $where_clause and store_id='".STORE_ID."' group by product_id order by id desc");
			#using the foreach loop to get all the products in the database
			foreach($list_products as $results) {
				
			#assign a variable to some data retrieved
			$pid = $results["product_id"];
			$current_quantity = @$products->product_by_id("id", $results["product_id"])->p_quantity;
			$total_current += $current_quantity;
			$unit_price = $results["product_price"];
			
			#run another query into the customer_orders_details to get the products quantity sold
			$list_products_in_orders = $DB->query("select sum(quantity) as quantity from _customers_orders_details where $where_clause2 and product_id='$pid' and store_id='".STORE_ID."'");
			#count the number of rows found 
			if(count($list_products_in_orders) > 0) {
				#using foreach loop to get the results 
				foreach($list_products_in_orders as $orders_results) {
					#assign variables to the information derived
					$sold_quantity = $orders_results["quantity"];
					$total_quantity += $sold_quantity;
				}
			}
			#new initialization
			$previous_quantity = $current_quantity + $sold_quantity;
			$total_quantity_as_at += $previous_quantity;
			$sold_price += $unit_price*$sold_quantity;
			?>
			<tr>
			<td><?php print @$products->product_by_id("product_id", $results["product_id"])->pr_id; ?></td>
			<td><?php print @$products->product_by_id("product_id", $results["product_id"])->p_name; ?></td>
			<td><?php print $previous_quantity; ?></td>
			<td><?php print $sold_quantity; ?></td>	
			<td><?php print $current_quantity; ?></td>
			<td>GH¢ <?php print $unit_price; ?></td>
			<td>GH¢ <?php print number_format(($unit_price*$sold_quantity), 2); ?></td>
			<td>GH¢ <?php print number_format(($unit_price*$current_quantity), 2); ?></td>
			</tr>
			<?php } ?>
			<tr style="font-weight:bolder">
			<td colspan="2"></td>
			<td><span class="font-medium"><?php print $total_quantity_as_at ?></span></td>
			<td><span class="font-medium"><?php print $total_current ?></span></td>
			<td><span class="font-medium"><?php print $total_quantity ?></span></td>
			<td><span class="font-medium"></span></td>
			<td><span class="font-medium">GH¢ <?php print number_format($sold_price, 2); ?></span></td>
			<td><span class="font-medium"></span></td>
			</tr>
			</tbody>

		</tbody>
		</table>
		<?php } ?>
				
		<?php if(isset($SITEURL[1]) and in_array($SITEURL[1], array("receipts"))) { ?>
		<table class="table table-bordered">
			<thead>
			<tr class="text-uppercase">
			<th>ID</th>
			<th>CUSTOMER</th>
			<th>ORDER ID</th>
			<th>AMOUNT PAID</th>
			<th>RECEIVED BY</th>
			<th>DATE</th>
			</tr>
			</thead>
		  
			<tbody>
			<?php
			$where_clause = "1";
			#check if the form has been submitted
			if(isset($_GET["a"]) and $_GET["a"] != "NULL" and !empty($_GET["a"])) {
				$where_clause .= " and admin_id='".xss_clean($_GET["a"])."'";
			}
			if(isset($_GET["s"]) and !empty($_GET["s"]) and empty($_GET["e"])) {
				$where_clause .= " and date_received='".xss_clean($_GET["s"])."'";
			}
			if(isset($_GET["s"]) and !empty($_GET["s"]) and !empty($_GET["e"])) {
				$where_clause .= " and date_received between '".xss_clean($_GET["s"])."' and '".xss_clean($_GET["e"])."'";
			}

			#initialization
			$list_customers = $DB->query("select * from _receipts where $where_clause and store_id='".STORE_ID."' order by id desc");
			foreach($list_customers as $results):
			?>
			<tr>
			<td><?php print $results["id"]; ?></td>
			<td><?php print $customers_list->_list_customer_by_id($results["customer_id"])->c_fullname; ?></td>
			<td><a href="<?php print SITE_URL; ?>/sales-view/<?php print $results["order_id"]; ?>"><?php print $results["order_id"]; ?></a></td>
			<td><?php print $results["amount"]; ?></td>
			<td><?php print $results["admin_id"]; ?></td>
			<td><?php print $results["date_received"]; ?></td>
			</tr>
			<?php endforeach; ?>
			</tbody>

		</tbody>
		</table>
		<?php } ?>
		
		
		<?php if(isset($SITEURL[1]) and in_array($SITEURL[1], array("activity")) AND $admin_user->confirm_admin_user()) { ?>
		<div class="dt-responsive table-responsive col-sm-12">
		<table id="simpletable" class="table table-striped table-bordered nowrap">
		<thead>
		<tr>
		<th>TIME</th>
		<th>ACTIVITY DETAILS</th>
		<th>DESCRIPTION</th>
		</tr>
		</thead>
		<tbody>
		<?php
		#confirm that an administrator has logged in
		if($admin_user->confirm_admin_user() == true) { 
			$user_access = true;
			$admin_access = true;
		}
		#initializing
		$where_clause = "1";
		
		#get the current user id 
		$current_user = xss_clean($admin_user->return_username());
		
		if($admin_user->confirm_admin_user() == false) { 
			$where_clause = "(admin_id='$current_user')";
		}
		#check if the form has been submitted
		if(($admin_user->confirm_admin_user() == true) and (isset($_GET["a"]) and $_GET["a"] != "NULL" and !empty($_GET["a"]))) {
			$where_clause .= " and admin_id='".xss_clean($_GET["a"])."'";
		}
		if(isset($_GET["c"]) and !empty($_GET["c"]) and $_GET["c"] != "NULL") {
			$where_clause .= " and ".strtolower($SITEURL[1])."_id='".xss_clean($_GET["c"])."'";
		}
		if(isset($_GET["s"]) and !empty($_GET["s"]) and empty($_GET["e"])) {
			$where_clause .= " and full_date='".xss_clean($_GET["s"])."'";
		}
		if(isset($_GET["s"]) and !empty($_GET["s"]) and !empty($_GET["e"])) {
			$where_clause .= " and full_date between '".xss_clean($_GET["s"])."' and '".xss_clean($_GET["e"])."'";
		}


		#list the user activities over the period
		if($admin_user->confirm_admin_user() == true) {
			$activites_list = $DB->query("select * from _activity_logs where $where_clause and store_id='".STORE_ID."' order by id desc");
		} else {
			$activites_list = $DB->query("select * from _activity_logs where $where_clause and store_id='".STORE_ID."' order by id desc");
		}

		foreach($activites_list as $results):
		?>
		<tr id="logid<?php print $results["id"]; ?>">
		<td><?php print date("d F Y H:ia", strtotime($results["date_recorded"])); ?></td>
		<td>
			<?php 
			if($results["activity_page"] == "invoice") {
				?>
				<a href="<?php print SITE_URL; ?>/sales-view/<?php print $results["activity_details"]; ?>">INVOICE: <?php print $results["activity_details"]; ?></a>
				<br>
				Customer: <a href="<?php print SITE_URL; ?>/customers-view/<?php print $results["activity_id"]; ?>"><?php print @$customers_list->_list_customer_by_id($results["activity_id"])->c_fullname; ?></a>
				<?php
			}
			?>
			<?php 
			if($results["activity_page"] == "product") {
				?>
				Stock Item: <br><a href="<?php print SITE_URL; ?>/stocks-view/<?php print $results["activity_id"]; ?>"><?php print $results["activity_id"]; ?></a>
				<?php
			}
			?>
			<?php 
			if($results["activity_page"] == "stocks") {
				?>
				Stock Details: <br><a href="<?php print SITE_URL; ?>/stocks-details/<?php print $results["activity_id"]; ?>"><?php print $results["activity_id"]; ?></a>
				<?php
			}
			?>
			<?php 
			if($results["activity_page"] == "customer") {
				?>
				Customer: <a href="<?php print SITE_URL; ?>/customers-view/<?php print $results["activity_id"]; ?>"><?php print @$customers_list->_list_customer_by_id($results["activity_id"])->c_fullname; ?></a>
				<?php
			}
			?>
			<?php 
			if($results["activity_page"] == "login-notice") {
				?>
				Login Attempts Notification
				<?php
			}
			?>
			<?php 
			if($results["activity_page"] == "password-change-notice") {
				?>
				Password Change Request Notification
				<?php
			}
			?>
			<?php 
			if($results["activity_page"] == "password") {
				?>
				Password changed <br>
				<?php
			}
			?>
			<?php 
			if($results["activity_page"] == "password-changed") {
				?>
				Admin: Password changed
				<Br><a href="<?php print SITE_URL; ?>/profile/<?php print $results["activity_id"]; ?>"><?php print @$results["activity_id"]; ?></a>
				<?php
			}
			?>
			<?php 
			if($results["activity_page"] == "stocks") {
				?>
				Stock Details: <br><a href="<?php print SITE_URL; ?>/stocks-details/<?php print $results["activity_id"]; ?>"><?php print @$results["activity_id"]; ?></a>
				<?php
			}
			?>
		</td>
		<td>
			<?php if(($results["activity_page"] == "password-changed") and ($results["activity_id"] == $current_user)) { ?>
			Your password was recently changed by an Administrator
			<?php } else { ?>
			<?php print @$results["activity_description"]; ?>
			<?php } ?>
			<br>by <small><a href="<?php print SITE_URL; ?>/profile/<?php print $results["admin_id"]; ?>"><?php print @$results["admin_id"]; ?></a></small>
		</td>
		</tr>
		<?php endforeach; ?>
		</tbody>
		</table>
		</div>
		<?php } ?>
		
		
		<?php if(isset($SITEURL[1]) and $SITEURL[1] == "sales") { ?>
		<div class="dt-responsive table-responsive">
		<table id="simpletable" class="table table-striped table-bordered nowrap">
		<thead>
		<tr>
		<th>ORDER ID</th>
		<th>CUSTOMER</th>
		<th>DATE</th>
		<th>SOLD BY</th>
		<th>SUB PRICE</th>
		<th>DISCOUNT</th>
		<th>TOTAL AMOUNT</th>
		<th>TOTAL RECEIPTS</th>
		</tr>
		</thead>
		<tbody>
		<?php
		#initialization
		$where_clause = "store_id='".STORE_ID."' and status='1'";
		#check if the form has been submitted
		if(isset($_GET["a"]) and $_GET["a"] != "NULL" and !empty($_GET["a"])) {
			$where_clause .= " and sold_by='".xss_clean($_GET["a"])."'";
		}
		if(isset($_GET["v"]) and !empty($_GET["v"])) {
			$where_clause .= " and unique_id='".xss_clean($_GET["v"])."'";
		}
		if(isset($_GET["c"]) and !empty($_GET["c"]) and $_GET["c"] != "NULL") {
			$where_clause .= " and customer_id='".xss_clean($_GET["c"])."'";
		}
		if(isset($_GET["s"]) and !empty($_GET["s"]) and empty($_GET["e"])) {
			$where_clause .= " and full_date='".xss_clean($_GET["s"])."'";
		}
		if(isset($_GET["s"]) and !empty($_GET["s"]) and !empty($_GET["e"])) {
			$where_clause .= " and full_date between '".xss_clean($_GET["s"])."' and '".xss_clean($_GET["e"])."'";
		}
		#list the user orders 
		$list_orders = $DB->query("select * from _customers_orders where $where_clause and returned='0' and store_id='".STORE_ID."' order by id asc");
		#list them here 
		foreach($list_orders as $results):
		?>
		<tr>
		<td><a href="<?php print SITE_URL; ?>/sales-view/<?php print $results["unique_id"]; ?>"><?php print $results["unique_id"]; ?></a></td>
		<td><?php print $customers_list->_list_customer_by_id($results["customer_id"])->c_fullname; ?></td>
		<td class="mailbox-date"><?php print date("d M Y", strtotime($results["date_added"])); ?></td>
		<td class="mailbox-date"><?php print $results["sold_by"]; ?></td>
		<td><span class="font-medium">GHc <?php print $results["total_price"]; ?></span></td>
		<td><span class="font-medium">GHc <?php print $results["discount"]; ?></span></td>
		<td><span class="font-medium">GHc <?php print $results["overall_price"]; ?></span></td>
		<td><span class="font-medium">GHc <?php print $results["total_paid"]; ?></span></td>
		</tr>
		<?php endforeach; ?>
		<tr style="font-weight:bolder">
		<td colspan="4"></td>
		<td><span class="font-medium">GHc <?php print $csales->_calculate_based_on_parameters("total_price", "WHERE $where_clause and returned='0'"); ?></span></td>
		<td><span class="font-medium">GHc <?php print $csales->_calculate_based_on_parameters("discount", "WHERE $where_clause and returned='0'"); ?></span></td>
		<td><span class="font-medium">GHc <?php print $csales->_calculate_based_on_parameters("overall_price", "WHERE $where_clause and returned='0'"); ?></span></td>
		<td><span class="font-medium">GHc <?php print $csales->_calculate_based_on_parameters("total_paid", "WHERE $where_clause and returned='0'"); ?></span></td>
		</tr>
		<?php if((count($list_orders) == 1) and isset($_GET["v"])) { ?>
		<tr style="font-weight:bolder">
		<td colspan="7"></td>		
		<td>
			<a class="btn btn-primary" href="<?php print SITE_URL; ?>/print-sales/<?php print $results["unique_id"]; ?>">PRINT</a>
		</td>
		</tr>
		<?php } ?>
		</tbody>
		</table>
		</div>
		<?php } ?>
	
	
	</td>
	</tr>
</table>
</div>

<script type="text/javascript" src="<?php print SITE_URL; ?>/assets/bower_components/jquery/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php print SITE_URL; ?>/assets/bower_components/jquery-ui/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php print SITE_URL; ?>/assets/bower_components/popper.js/js/popper.min.js"></script>
<script type="text/javascript" src="<?php print SITE_URL; ?>/assets/bower_components/bootstrap/js/bootstrap.min.js"></script>

<script type="text/javascript" src="<?php print SITE_URL; ?>/assets/bower_components/jquery-slimscroll/js/jquery.slimscroll.js"></script>

<script type="text/javascript" src="<?php print SITE_URL; ?>/assets/bower_components/modernizr/js/modernizr.js"></script>
<script type="text/javascript" src="<?php print SITE_URL; ?>/assets/bower_components/modernizr/js/css-scrollbars.js"></script>

<script type="text/javascript" src="<?php print SITE_URL; ?>/assets/bower_components/classie/js/classie.js"></script>

<script type="text/javascript" src="<?php print SITE_URL; ?>/assets/pages/advance-elements/moment-with-locales.min.js"></script>
<script type="text/javascript" src="<?php print SITE_URL; ?>/assets/bower_components/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="<?php print SITE_URL; ?>/assets/pages/advance-elements/bootstrap-datetimepicker.min.js"></script>

<script type="text/javascript" src="<?php print SITE_URL; ?>/assets/bower_components/bootstrap-daterangepicker/js/daterangepicker.js"></script>

<script type="text/javascript" src="<?php print SITE_URL; ?>/assets/bower_components/datedropper/js/datedropper.min.js"></script>

<script type="text/javascript" src="<?php print SITE_URL; ?>/assets/bower_components/spectrum/js/spectrum.js"></script>
<script type="text/javascript" src="<?php print SITE_URL; ?>/assets/bower_components/jscolor/js/jscolor.js"></script>

<script type="text/javascript" src="assets/pages/jquery-minicolors/js/jquery.minicolors.min.js"></script>

<script type="text/javascript" src="<?php print SITE_URL; ?>/assets/bower_components/i18next/js/i18next.min.js"></script>
<script type="text/javascript" src="<?php print SITE_URL; ?>/assets/bower_components/i18next-xhr-backend/js/i18nextXHRBackend.min.js"></script>
<script type="text/javascript" src="<?php print SITE_URL; ?>/assets/bower_components/i18next-browser-languagedetector/js/i18nextBrowserLanguageDetector.min.js"></script>
<script type="text/javascript" src="<?php print SITE_URL; ?>/assets/bower_components/jquery-i18next/js/jquery-i18next.min.js"></script>

<script type="text/javascript" src="<?php print SITE_URL; ?>/assets/js/script.js"></script>
<script type="text/javascript" src="<?php print SITE_URL; ?>/assets/pages/advance-elements/custom-picker.js"></script>
<script src="<?php print SITE_URL; ?>/assets/js/pcoded.min.js"></script>
<script src="<?php print SITE_URL; ?>/assets/js/jquery.mCustomScrollbar.concat.min.js"></script>
<script src="<?php print SITE_URL; ?>/assets/js/jquery.mousewheel.min.js"></script>



<link rel="stylesheet" type="text/css" href="<?php print SITE_URL; ?>/assets/pages/menu-search/css/component.css">
<link rel="stylesheet" type="text/css" href="<?php print SITE_URL; ?>/assets/pages/advance-elements/css/bootstrap-datetimepicker.css">
<link rel="stylesheet" type="text/css" href="<?php print SITE_URL; ?>/assets/bower_components/bootstrap-daterangepicker/css/daterangepicker.css" />
<link rel="stylesheet" type="text/css" href="<?php print SITE_URL; ?>/assets/bower_components/datedropper/css/datedropper.min.css" />
<script>
$('.datepicker').datepicker();
</script>
</body>
</html>