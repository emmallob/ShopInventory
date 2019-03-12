<?php
	if(!$admin_user->logged_InControlled()) { die(require("login.php")); }
	if($admin_user->lock_user_screen()) { die(require("lockscreen.php")); }
	if(!$admin_user->changed_password_first()) { die(require("change_password.php")); }
	
	$PAGETITLE="Suppliers Edit";
	require "TemplateHeader.php";
	
	$sales = load_class('sales', 'models');
	$products = load_class('products', 'models');
	$suppliers = load_class('Suppliers', 'models');
	
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
<li class="breadcrumb-item"><a href="<?php print SITE_URL; ?>/customers">Customers Lists</a></li>
<li class="breadcrumb-item"><a href="<?php print SITE_URL; ?>/cart"><?php print $PAGETITLE; ?></a></li>
</ul>
</div>
</div>

<?php $notices->notice_board(); ?>

<style>input {text-transform:uppercase!important}</style>


<div class="page-body">
<div class="">
<div class="col-sm-12">




<div class="card">
<div class="card-header">
<span>Edit the information of a customer that is saved in the database.</span>
</div>
<div class="\">
<div class="card-block">


<?php
#check if the Customer does exists
if(isset($SITEURL[1]) and preg_match("/^[a-z0-9-]+$/",strtolower($SITEURL[1])) and $suppliers->_list_suppliers_by_id(strtoupper($SITEURL[1]))->surp_found):
//include the update file
load_file(
	array(
		'suppliers'=>'updates'
	)
);

if(isset($_GET["msg"])):
if(xss_clean($_GET["msg"])==1):
show_msg("Success", "Customer Information was successfully updated.");
elseif(xss_clean($_GET["msg"])==0):
show_msg("Error", "Sorry there was an error updating page information.");
elseif(xss_clean($_GET["msg"])==2):
show_msg("Error", "Sorry all fields are required");
elseif(xss_clean($_GET["msg"])==3):
show_msg("Success", "Customer Information was successfully inserted.");
endif;
endif;
?>





<style>.col-form-label {text-transform:uppercase;font-weight:bolder}</style>

    
<form id="main" method="post" SITEURL="" enctype="multipart/form-data">


<div class="col-sm-8">

<div class="row">
<div class="col-sm-6">
<label class="col-sm-12 col-form-label">CID</label>
<div class="input-group input-group-primary">
<span class="input-group-addon">
<i class="ion ion-ios-monitor"></i>
</span>
<input name="supplier_id" readonly required value="<?php print $SITEURL[1]; ?>" type="text" class="span8 form-control" id="supplier_id">
</div>
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="col-sm-12 col-form-label">Fullname</label>
<div class="input-group input-group-primary">
<span class="input-group-addon">
<i class="icofont icofont-presentation"></i>
</span>
<input name="fullname" required value="<?php print $suppliers->_list_suppliers_by_id($SITEURL[1])->sfullname; ?>" type="text" class="span8 form-control" id="fullname">
</div>
</div>
</div>

<div class="row">

<div class="col-sm-6">
<label class="col-sm-6 col-form-label">Region</label>
<div class="input-group input-group-primary">
<span class="input-group-addon">
<i class="ion-android-globe"></i>
</span>
<select class="form-control" id="selectError" name="region" data-rel="chosen">
	<option value="0">Select Region</option>
	<?php
	#fetch the article categories 
	$cats = $DB->query("select * from `_countries_gh_regional`");
	#count the number of rows 
	if(count($cats) > 0):
	#using foreach loop to fetch the data 
	foreach($cats as $shop_results):					
	?>
	<?php if($suppliers->_list_suppliers_by_id($SITEURL[1])->sregion==$shop_results["id"]): ?>
	<option selected value="<?php print $shop_results["id"]; ?>"><?php print strtoupper($shop_results["name"]); ?></option>
	<?php else: ?>
	<option value="<?php print $shop_results["id"]; ?>"><?php print strtoupper($shop_results["name"]); ?></option>
	<?php endif; ?>
	<?php endforeach; endif; ?>
</select>
</div>
</div>
</div>



</div>


<div class="col-sm-8">
<div class="row">
<div class="col-sm-6">
<label class="col-sm-12 col-form-label">Contact Number</label>
<div class="input-group input-group-primary">
<span class="input-group-addon">
<i class="icofont icofont-phone"></i>
</span>
<input maxlength="15" name="contact" value="<?php print $suppliers->_list_suppliers_by_id($SITEURL[1])->scontact; ?>" type="number" class="form-control" id="contact">
</div>
</div>
<div class="col-sm-6">
<label class="col-sm-12 col-form-label">Mobile Number</label>
<div class="input-group input-group-primary">
<span class="input-group-addon">
<i class="icofont icofont-telephone"></i>
</span>
<input maxlength="15" name="contact2" value="<?php print $suppliers->_list_suppliers_by_id($SITEURL[1])->scontact2; ?>" type="number" class="span8 form-control" id="contact2">
</div>
</div>
</div>

<div class="row">
<div class="col-sm-6">
<label class="col-sm-12 col-form-label">Email Address</label>
<div class="input-group input-group-primary">
<span class="input-group-addon">
<i class="icofont icofont-email"></i>
</span>
<input maxlength="100" name="email" value="<?php print $suppliers->_list_suppliers_by_id($SITEURL[1])->semail; ?>" type="email" class="form-control" id="email">
</div>
</div>
<div class="col-sm-6">
<label class="col-sm-12 col-form-label">Website Address</label>
<div class="input-group input-group-primary">
<span class="input-group-addon">
<i class="ion-android-globe"></i>
</span>
<input maxlength="100" name="website" value="<?php print $suppliers->_list_suppliers_by_id($SITEURL[1])->swebsite; ?>" type="text" class="span8 form-control" id="website">
</div>
</div>
</div>


<div class="row">
<div class="col-sm-6">
<label class="col-sm-12 col-form-label">Balance</label>
<div class="input-group input-group-primary">
<span class="input-group-addon">
<i class="icofont icofont-money"></i>
</span>
<input maxlength="15" name="balance" value="<?php print $suppliers->_list_suppliers_by_id($SITEURL[1])->sbalance; ?>" type="text" class="form-control autonumber" id="balance">
</div>
</div>

</div>

<div class="row">
<div class="col-sm-12">
<label class="col-sm-12 col-form-label">Address</label>
<div class="input-group input-group-primary">
<span class="input-group-addon">
<i class="icofont icofont-info"></i>
</span>
<textarea class="form-control" onclick="return hidepassfield();" id="address" name="address" rows="6"><?php print $suppliers->_list_suppliers_by_id($SITEURL[1])->saddress; ?></textarea>
</div>
</div>
</div>


<hr>
<input type="hidden" name="parsedform">
<input type="hidden" name="supplierEdit">
<input type="hidden" name="updatepage">
</div>


<div class="form-group row">
<label class="col-sm-1"></label>
<div class="col-sm-10">
<button title="Click to list all customers" onclick="window.location.href='<?php print SITE_URL; ?>/customers'" type="button" class="btn btn-danger"><i class="ion-ios-arrow-back"></i> Go Back</button>
<button type="submit" class="btn btn-success m-b-0"><i class="fa fa-save"></i> Update Record</button>
</div>
</div>

</form>
	
	
	
	
	
	


<?php else: ?>
<?php show_error('Page Not Found', 'Sorry the page you are trying to view does not exist on this server', 'error_404') ?>
<?php endif; ?>




</div>
</div>
</div>

<link rel="stylesheet" type="text/css" href="<?php print SITE_URL; ?>/assets/bower_components/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="<?php print SITE_URL; ?>/assets/pages/data-table/css/buttons.dataTables.min.css">
</div>
</div>
</div>
</div>
<?php require "TemplateFooter.php"; ?>