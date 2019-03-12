<?php
	if(!$admin_user->logged_InControlled()) { die(require("login.php")); }
	if($admin_user->lock_user_screen()) { die(require("lockscreen.php")); }
	if(!$admin_user->changed_password_first()) { die(require("change_password.php")); }
	
	$PAGETITLE="Update Existing Product Stock";
	require "TemplateHeader.php";
	
	$stocks = load_class('Stocks', 'models');
	$products = load_class('products', 'models');
	load_helpers(array('url_helper','string_helper'));
	
	$pid = 2;
?>
<div class="pcoded-content">
<div class="pcoded-inner-content">
<div class="main-body">
<div class="page-wrapper">
<div class="page-header">
<div class="page-header-title">
<h3><?php print strtoupper($PAGETITLE); ?></h3>
</div>
<div class="page-header-breadcrumb">
<ul class="breadcrumb-title">
<li class="breadcrumb-item">
<a href="<?php print SITE_URL; ?>">
<i class="icofont icofont-home"></i>
</a>
</li>
<li class="breadcrumb-item"><a href="<?php print SITE_URL; ?>/dashboard">Dashboard</a></li>
<li class="breadcrumb-item"><a href="<?php print SITE_URL; ?>/products">Products List</a></li>
<li class="breadcrumb-item"><a href="<?php print SITE_URL; ?>/stocks"><?php print $PAGETITLE; ?></a></li>
</ul>
</div>
</div>


<?php $notices->notice_board(); ?>



<div class="page-body">
<div class="row">
<div class="col-sm-12">


<br clear="both">

<div class="card">
<div class="card-header">
<h5><?php print $PAGETITLE; ?></h5>
<span>You are most welcomed to the stocks manager. This panel will guide through the adding of all stocks that you have purchased. Please take your time as you key in the variables.</span>
</div>
<div class="card-block">

<?php if($admin_user->confirm_admin_user() == true) { ?>

<div class="row">

<div class="col-sm-12">
<label class="col-sm-12 col-form-label">NUMBER OF EXISTING PRODUCTS TO UPDATE <small><strong>(Currently there is a total of <?php print count($DB->query("select * from _products where status='1'")); ?> Products on this server.)</strong></small></label>
<div class="input-group input-group-primary">
<span class="input-group-addon">
<i class="icofont icofont-presentation"></i>
</span>
<select onchange="window.location.href=this.value" required class="form-control" id="gender" name="gender">
	<option value="0">SELECT NUMBER OF PRODUCTS TO ADD</option>
	<option value="">----------------------------------</option>
	<?php for($i=1; $i < 10; $i++) { ?>
	<option <?php if(isset($SITEURL[2]) and $SITEURL[2]==$i) print "selected"; ?> value="<?php print SITE_URL; ?>/stocks-new/rows/<?php print $i; ?>"><?php print $i; ?> Products</option>
	<?php } ?>
</select>
</div>
<small>Select the number of products that you want to insert</small>
<br>
</div>
<style>.table label {font-weight:bolder}</style>
<div class="col-sm-12">
<hr>
<?php
#set the header for the page 
if(isset($SITEURL[2]) and preg_match("/^[0-9]+$/", $SITEURL[2]) and $SITEURL[2] > 0) {
	print "<h2>PLEASE COMPLETE THE FORM</h2>";
}

#confirm that the form has been parsed 
if(isset($_POST["record_stock_details"])) {
	#set the stock id
	$stock_id = ($DB->max_all("id", "_stocks_details"))+1;
	$admin_logged = $admin_user->return_username();
	#assign variable 
	for($i=1; $i <= $SITEURL[2]; $i++) {
		$product_id = xss_clean($_POST["pname$i"]);
		$supplier = xss_clean($_POST["supplier$i"]);
		$price = substr(create_slug($_POST["product_price$i"]), 0, -3);
		$selling = substr(create_slug($_POST["product_selling$i"]), 0, -3);
		$quantity = xss_clean($_POST["product_quantity$i"]);
		$old_quantity = (int)$products->product_by_id("id",$product_id)->p_quantity;
		$new_quantity = $quantity + $old_quantity;
		$product_name = $products->product_by_id("id",$product_id)->p_name;
		$product_slug = $products->product_by_id("id",$product_id)->p_slug;
		
		if($price > $selling) {
			print "<div class='alert alert-danger'>Sorry! Please verify that you have entered a correct selling price for <strong>$product_name</strong></div>";
		} else {
			#update product stock
			$stocks->_update_stock($new_quantity, $product_id);
			
			#update product price information
			$DB->just_exec("update _products set product_price='$price', product_actuals='$selling', product_quantity='$new_quantity' where id='$product_id'");
			
			#update supplier information
			$DB->just_exec("update _suppliers set last_update=now where supplier_id='$supplier'");
			
			#insert a new stocks history with an id
			$DB->just_exec("insert into _stocks_details set stock_id='LEST$stock_id', pid='$product_slug', quantity='$quantity', old_quantity='$old_quantity', new_quantity='$new_quantity', purchase='$price', selling='$selling', supplier='$supplier', date_added=now(), added_by='$admin_logged'");
			
			
		}
	}
	#record new admin history
	$DB->just_exec("insert into _activity_logs set date_recorded=now(), admin_id='$admin_logged', activity_page='stocks', activity_id='LEST$stock_id', activity_details='LEST$stock_id', activity_description='Product Stock has been added.'");
		
	print "<script>window.location.href='".SITE_URL."/stocks?msg=5'</script>";
		
}
#confirm that number of rows has been set by the user
if(isset($SITEURL[2]) and preg_match("/^[0-9]+$/", $SITEURL[2]) and $SITEURL[2] > 0) {
	#using the for loop to create rows and set the form parameters
	print "<form SITEURL='' autocomplete='off' method='post'>";
	print "<table class='table table-bordered nowrap'>";
	for($i=1; $i <= $SITEURL[2]; $i++) {
	print "<tr id='stock_row_id$i'>";
	print "<td><br>$i</td>";
	print "<td><label>PRODUCT NAME</label><br>
	<select name='pname$i' class='form-control'>";
	
	#list all products in the database
	$p_lists = $DB->query("select * from _products where status='1'");
	if(count($p_lists) > 0) {
		print "<option selected value='0'>------------Select Product------------</option>";
		foreach($p_lists as $p_results) {
			if(isset($_POST["pname$i"]) and $_POST["pname$i"] == $p_results["id"]) {
				print "<option selected value='{$p_results["id"]}'>{$p_results["product_name"]}</option>";
			} else {
				print "<option value='{$p_results["id"]}'>{$p_results["product_name"]}</option>";
			}
		}
	}
	
	print "</select></td>";
	print "<td><label>SUPPLIER</label><br>
	<select name='supplier$i' class='form-control'>";
	
	#list all products in the database
	$s_lists = $DB->query("select * from _suppliers where status='1'");
	if(count($s_lists) > 0) {
		print "<option selected value='0'>------------Select Supplier------------</option>";
		foreach($s_lists as $s_results) {
			if(isset($_POST["supplier$i"]) and $_POST["supplier$i"] == $s_results["supplier_id"]) {
				print "<option selected value='{$s_results["supplier_id"]}'>{$s_results["fullname"]}</option>";
			} else {
				print "<option value='{$s_results["supplier_id"]}'>{$s_results["fullname"]}</option>";
			}
		}
	}
	
	print "</select></td>";
	print "<td><label>PURCHASE PRICE</label><br><input style='width:120px' placeholder='Purchase price' type='text' class='form-control autonumber' value='".@xss_clean($_POST["product_price$i"])."' required name='product_price$i'></td>";
	print "<td><label>SELLING PRICE</label><br><input required style='width:120px' placeholder='Selling price' type='text' value='".@xss_clean($_POST["product_selling$i"])."' class='form-control autonumber' name='product_selling$i'></td>";
	print "<td><label>STOCK QUANTITY</label><br><input required style='width:150px' placeholder='Quantity' type='number' value='".@xss_clean($_POST["product_quantity$i"])."' class='form-control' name='product_quantity$i'></td>";
	print "<tr>";
	}
	print "</table>";
	?><br clear="both">
	<p>Please note once it is inserted; you cannot delete. However you can edit its content. Thank you.</p><hr>
	<div class="form-group">
	<button type="submit" class="btn btn-success m-b-0"><i class="fa fa-save"></i> Add Stock Record</button>
	<input type="hidden" name="record_stock_details" value="<?php print $SITEURL[2]; ?>">
	</div>
	<hr>
	<?php 
	print "</form>";
}
?>

</div>

<?php if(!isset($SITEURL[2])) { ?>
<div class='col-sm-12'>
<a href='<?php print SITE_URL; ?>/stocks-add' type="button" class="btn btn-warning"><li class="fa fa-plus"></li> NEW PRODUCT?</a>
</div>
<?php } else { ?>
<div class='col-sm-12'>
<a href='<?php print SITE_URL; ?>/stocks-add' type="button" class="btn btn-danger"><li class="fa fa-plus"></li> PRODUCT DOES NOT EXIST IN RECORD: NEW PRODUCT?</a>
</div>
<?php } ?>









</div>



<?php } ?>


</div>
</div>



<link rel="stylesheet" type="text/css" href="<?php print SITE_URL; ?>/assets/bower_components/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="<?php print SITE_URL; ?>/assets/pages/data-table/css/buttons.dataTables.min.css">
</div>
</div>
</div>
</div>
<?php require "TemplateFooter.php"; ?>