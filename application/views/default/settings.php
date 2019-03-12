<?php
	if(!$admin_user->logged_InControlled()) { die(require("login.php")); }
	if($admin_user->lock_user_screen()) { die(require("lockscreen.php")); }
	if(!$admin_user->changed_password_first()) { die(require("change_password.php")); }
	
	$PAGETITLE="System Settings"; 
	require "TemplateHeader.php";
	
	$admin_logged = $admin_user->return_username();
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


<div class="page-body">
<div class="">
<div class="col-sm-12">




<div class="card">
<div class="card-header">
<span>Update the main settings of the website.</span>
</div>
<div class="">
<div class="card-block">


<?php
#confirm that the form has been submitted
if(isset($_POST["sitename"]) and !empty($_POST["sitename"]) and isset($_POST["parsedform"])) {
	
	#assign variables 
	$email = xss_clean($_POST["email"]);
	$contact = xss_clean($_POST["contact"]);
	$website = xss_clean($_POST["website"]);
	$sitename = xss_clean($_POST["sitename"]);
	$contact2 = xss_clean($_POST["contact2"]);
	$siteslogan = xss_clean($_POST["siteslogan"]);
	$date_format = xss_clean($_POST["date_format"]);
	$alert_limit = xss_clean($_POST["alert_limit"]);
	$welcomenote = xss_clean($_POST["welcomenote"]);
	$display_limit = xss_clean($_POST["display_limit"]);
	$sell_on_credit = xss_clean($_POST["sell_on_credit"]);
	$receipt_message = xss_clean($_POST["receipt_message"]);
	$automatic_backup = xss_clean($_POST["automatic_backup"]);
		
	#update the database with the following settings
	if($DB->just_exec("update _stores set sitename='$sitename',siteslogan='$siteslogan', siteemail='$email', sitephone='$contact', sitephone2='$contact2', receipt_message='$receipt_message', headertext='$welcomenote', automatic_backup='$automatic_backup', sell_on_credit='$sell_on_credit', date_format='$date_format', display_limit='$display_limit', stocks_limit='$alert_limit' where id='".STORE_ID."'")) {
		
		$DB->just_exec("insert into _activity_logs set date_recorded=now(), admin_id='$admin_logged', activity_page='admin', activity_id='settings', activity_details='settings', activity_description='Store Settings has was updated has been updated.'");
		
		print "<div class='alert alert-success'>Store settings successfully updated.</div><script>setTimeout(function() {window.location.href=''},1000);</script>.";
	} else {
		#print error message
		print "<div class='alert alert-danger'>Sorry! There was an error while updating the server settings.</div>";
	}
	
}
?>
<style>.col-form-label {text-transform:uppercase;font-weight:bolder}</style>
<form id="main" method="post" action="" enctype="multipart/form-data">
<div class="col-sm-12">

<div class="col-sm-12">
<div class="row">
<div class="col-sm-10">


<div class="col-sm-12">

<div class="row">
<div class="col-sm-6">
<label class="col-sm-12 col-form-label">STORE NAME</label>
<div class="input-group input-group-primary">
<span class="input-group-addon">
<i class="icofont icofont-presentation"></i>
</span>
<input name="sitename" required value="<?php print $stores->fetch()->name; ?>" type="text" class="span8 form-control" id="sitename">
</div>
</div>
<div class="col-sm-6">
<label class="col-sm-12 col-form-label">STORE SLOGAN</label>
<div class="input-group input-group-primary">
<span class="input-group-addon">
<i class="icofont icofont-presentation"></i>
</span>
<input name="siteslogan" value="<?php print $stores->fetch()->siteslogan; ?>" type="text" class="span8 form-control" id="siteslogan">
</div>
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="col-sm-12 col-form-label">RECEIPT MESSAGE</label>
<div class="input-group input-group-primary">
<span class="input-group-addon">
<i class="icofont icofont-presentation"></i>
</span>
<input name="receipt_message" value="<?php print $stores->fetch()->receipt_message; ?>" type="text" class="span8 form-control" id="receipt_message">
</div>
<small>This message will appear below the receipts for the customers</small>
</div>
</div>


<div class="row">
<div class="col-sm-4">
<label class="col-sm-12 col-form-label">PRODUCTS DISPLAY LIMIT</label>
<div class="input-group input-group-primary">
<span class="input-group-addon">
<i class="icofont icofont-user"></i>
</span>
<select class="form-control" id="display_limit" name="display_limit">
<option value="<?php print DISPLAY_LIMIT; ?>">Select Display Limit</option>
<option <?php if(DISPLAY_LIMIT == "10") print "selected"; ?> value="10">10</option>
<option <?php if(DISPLAY_LIMIT == "25") print "selected"; ?> value="25">25</option>
<option <?php if(DISPLAY_LIMIT == "50") print "selected"; ?> value="50">50</option>
<option <?php if(DISPLAY_LIMIT == "100") print "selected"; ?> value="100">100</option>
</select>
</div>
<small>Setting this affects the page listings and the limits</small>
</div>
<div class="col-sm-4">
<label class="col-sm-12 col-form-label">STOCKS NOTIFICATION ALERT</label>
<div class="input-group input-group-primary">
<span class="input-group-addon">
<i class="ion-android-globe"></i>
</span>
<select class="form-control" id="alert_limit" name="alert_limit" data-rel="chosen">
	<option value="<?php print STOCKS_LIMIT; ?>">Select Alert Point</option>
	<?php
	#fetch the article categories 
	for($i=10; $i < 105; $i++) {
	if(STOCKS_LIMIT == $i) {
	?>
	<option  selected value="<?php print $i; ?>"><?php print $i; ?></option>
	<?php } else { ?>
	<option value="<?php print $i; ?>"><?php print $i; ?></option>
	<?php } $i += 4; } ?>
</select>
</div>
<small>At what stock balance should you receive an alert</small>
</div>
<div class="col-sm-4">
<label class="col-sm-12 col-form-label">ALLOW CREDIT SALES</label>
<div class="input-group input-group-primary">
<span class="input-group-addon">
<i class="icofont icofont-calendar"></i>
</span>
<select class="form-control" id="sell_on_credit" name="sell_on_credit">
<option <?php if(CREDIT_SALES == 0) print "selected"; ?> value="0">NOT ALLOWED</option>
<option <?php if(CREDIT_SALES == 1) print "selected"; ?> value="1">ALLOWED</option>
</select>
</div>
<small>This will enable your to sell products on credit or not.</small>
</div>
<div class="col-sm-4">
<label class="col-sm-12 col-form-label">ALLOW AUTOMATIC BACKUP</label>
<div class="input-group input-group-primary">
<span class="input-group-addon">
<i class="icofont icofont-calendar"></i>
</span>
<select class="form-control" id="automatic_backup" name="automatic_backup">
<option <?php if(AUTOMATIC_BACKUP == 0) print "selected"; ?> value="0">DISABLED</option>
<option <?php if(AUTOMATIC_BACKUP == 1) print "selected"; ?> value="1">ENABLED</option>
</select>
</div>
<small>This will enable your to sell products on credit or not.</small>
</div>
<div class="col-sm-4">
<label class="col-sm-12 col-form-label">SYSTEM DATE FORMAT</label>
<div class="input-group input-group-primary">
<span class="input-group-addon">
<i class="icofont icofont-calendar"></i>
</span>
<select class="form-control" id="date_format" name="date_format">
<option value="<?php print SITE_DATE_FORMAT; ?>">Select Date Format</option>
<option <?php if(SITE_DATE_FORMAT == "d/m/Y") print "selected"; ?> value="d/m/Y"><?php print date("d/m/Y"); ?></option>
<option <?php if(SITE_DATE_FORMAT == "d-m-Y") print "selected"; ?> value="d-m-Y"><?php print date("d-m-Y"); ?></option>
<option <?php if(SITE_DATE_FORMAT == "d M Y") print "selected"; ?> value="d M Y"><?php print date("d M Y"); ?></option>
<option <?php if(SITE_DATE_FORMAT == "d F Y") print "selected"; ?> value="d F Y"><?php print date("d F Y"); ?></option>
<option <?php if(SITE_DATE_FORMAT == "d M Y H:iA") print "selected"; ?> value="d M Y H:iA"><?php print date("d M Y H:iA"); ?></option>
<option <?php if(SITE_DATE_FORMAT == "d F Y H:iA") print "selected"; ?> value="d F Y H:iA"><?php print date("d F Y H:iA"); ?></option>
</select>
</div>
<small>This date format will appear on receipts.</small>
</div>
</div>
</div>
<div class="col-sm-12">
<div class="row">
<div class="col-sm-6">
<label class="col-sm-12 col-form-label">Contact Number</label>
<div class="input-group input-group-primary">
<span class="input-group-addon">
<i class="icofont icofont-phone"></i>
</span>
<input maxlength="20" name="contact" value="<?php print $stores->fetch()->phone; ?>" type="text" class="form-control" id="contact">
</div>
</div>
<div class="col-sm-6">
<label class="col-sm-12 col-form-label">Mobile Number</label>
<div class="input-group input-group-primary">
<span class="input-group-addon">
<i class="icofont icofont-telephone"></i>
</span>
<input maxlength="16" name="contact2" value="<?php print $stores->fetch()->phone2; ?>" type="text" class="span8 form-control" id="contact2">
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
<input maxlength="100" name="email" value="<?php print $stores->fetch()->email; ?>" type="email" class="form-control" id="email">
</div>
</div>
<div class="col-sm-6">
<label class="col-sm-12 col-form-label">Website Address</label>
<div class="input-group input-group-primary">
<span class="input-group-addon">
<i class="ion-android-globe"></i>
</span>
<input maxlength="100" readonly name="website" value="<?php print $stores->fetch()->url; ?>" type="text" class="span8 form-control" id="website">
</div>
</div>
</div>

<div class="row">
<div class="col-sm-6">
<label class="col-sm-12 col-form-label">ADDRESS</label>
<div class="input-group input-group-primary">
<span class="input-group-addon">
<i class="icofont icofont-info"></i>
</span>
<textarea class="form-control" onclick="return hidepassfield();" id="address" name="address" rows="6"><?php print $stores->fetch()->address; ?></textarea>
</div>
</div>

<div class="col-sm-6">
<label class="col-sm-12 col-form-label">WELCOME NOTE</label>
<div class="input-group input-group-primary">
<span class="input-group-addon">
<i class="icofont icofont-info"></i>
</span>
<textarea class="form-control" onclick="return hidepassfield();" id="welcomenote" name="welcomenote" rows="6"><?php print $stores->fetch()->headertext; ?></textarea>
</div>
</div>
</div>


<hr>
<?php if($admin_user->confirm_admin_user() == true) { ?>
<input type="hidden" name="parsedform">
<input type="hidden" name="customerAdd">
<input type="hidden" name="updatepage">
<?php } ?>
</div>


<div class="form-group row">
<label class="col-sm-12"></label>
<div class="col-sm-10">
<button title="Click to go to the Home Page" onclick="window.location.href='<?php print SITE_URL; ?>'" type="button" class="btn btn-danger"><i class="ion-ios-arrow-back"></i> Go Back</button>
<?php if($admin_user->confirm_admin_user() == true) { ?>
<button type="submit" class="btn btn-success m-b-0"><i class="fa fa-save"></i>Update Settings</button>
<?php } ?>
</div>
</div>

</div>
</div>
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