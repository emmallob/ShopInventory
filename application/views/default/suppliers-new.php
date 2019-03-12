<?php
	if(!$admin_user->logged_InControlled()) { die(require("login.php")); }
	if($admin_user->lock_user_screen()) { die(require("lockscreen.php")); }
	if(!$admin_user->changed_password_first()) { die(require("change_password.php")); }
	
	$PAGETITLE="Suppliers Add";
	require "TemplateHeader.php";
	
	$sales = load_class('sales', 'models');
	$products = load_class('products', 'models');
	$suppliers = load_class('Suppliers', 'models');
	
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
// initializing
$fullname=$lastname=$gender=$region=$contact2=$contact=$website=$address=$email="";
$balance=$outstanding=0.00;
//include the update file
load_file(
	array(
		'suppliers'=>'updates'
	)
);
?>





<style>.col-form-label {text-transform:uppercase;font-weight:bolder}</style>

    
<form id="main" method="post" SITEURL="" autocomplete="Off" enctype="multipart/form-data">


<div class="col-sm-8">

<div class="row">
<div class="col-sm-6">
<label class="col-sm-12 col-form-label">CID</label>
<div class="input-group input-group-primary">
<span class="input-group-addon">
<i class="ion ion-ios-monitor"></i>
</span>
<input name="supplier_id" readonly required value="LES<?php print $DB->max_all("id","_suppliers")+2; ?>" type="text" class="span8 form-control" id="supplier_id">
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
<input name="fullname" required value="<?php print $fullname; ?>" type="text" class="span8 form-control" id="fullname">
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
	<option value="<?php print $shop_results["id"]; ?>"><?php print strtoupper($shop_results["name"]); ?></option>
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
<input maxlength="15" name="contact" value="<?php print $contact; ?>" type="number" class="form-control" id="contact">
</div>
</div>
<div class="col-sm-6">
<label class="col-sm-12 col-form-label">Mobile Number</label>
<div class="input-group input-group-primary">
<span class="input-group-addon">
<i class="icofont icofont-telephone"></i>
</span>
<input maxlength="15" name="contact2" value="<?php print $contact2; ?>" type="number" class="span8 form-control" id="contact2">
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
<input maxlength="100" name="email" value="<?php print $email; ?>" type="email" class="form-control" id="email">
</div>
</div>
<div class="col-sm-6">
<label class="col-sm-12 col-form-label">Website Address</label>
<div class="input-group input-group-primary">
<span class="input-group-addon">
<i class="ion-android-globe"></i>
</span>
<input maxlength="100" name="website" value="<?php print $website; ?>" type="text" class="span8 form-control" id="website">
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
<input maxlength="15" name="balance" value="<?php print $balance; ?>" type="text" class="form-control autonumber" id="balance">
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
<textarea class="form-control" onclick="return hidepassfield();" id="address" name="address" rows="6"><?php print $address; ?></textarea>
</div>
</div>
</div>


<hr>
<input type="hidden" name="parsedform">
<input type="hidden" name="supplierAdd">
<input type="hidden" name="updatepage">
</div>


<div class="form-group row">
<label class="col-sm-1"></label>
<div class="col-sm-10">
<button title="Click to list all customers" onclick="window.location.href='<?php print SITE_URL; ?>/customers'" type="button" class="btn btn-danger"><i class="ion-ios-arrow-back"></i> Go Back</button>
<button type="submit" class="btn btn-success m-b-0"><i class="fa fa-save"></i> Add Record</button>
</div>
</div>

</form>
	
	
	



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