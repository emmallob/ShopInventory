<?php
	if(!$admin_user->logged_InControlled()) { die(require("login.php")); }
	if($admin_user->lock_user_screen()) { die(require("lockscreen.php")); }
	if(!$admin_user->changed_password_first()) { die(require("change_password.php")); }
	
	$PAGETITLE="Activity Logs";
	require "TemplateHeader.php";
	
	$customers = load_class('customers', 'models');
	$suppliers = load_class('suppliers', 'models');

	#initializing
	$user_access = true;
	$admin_access = false;

	if($admin_user->confirm_admin_user() == true) {
		$admin_access = true;
	}

	if(isset($_GET["u"]) and ($admin_user->return_username() != $_GET["u"]) and ($admin_user->confirm_admin_user() == true)) {
		$current_user = ucfirst(xss_clean($_GET["u"]));
	} else {
		$current_user = ucfirst(xss_clean($admin_user->return_username()));
	}
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
<li class="breadcrumb-item"><a href="<?php print SITE_URL; ?>/products"><?php print $PAGETITLE; ?></a></li>
</ul>
</div>
</div>


<?php $notices->notice_board(); ?>



<div class="page-body">
<div class="row">
<div class="col-sm-12">




<div class="card">
<div class="card-header">
<h5><?php print $PAGETITLE; ?></h5>
<span>This page displays all the activity logs of an admin over the period.</span>
</div>
<div class="card-block">

<div class="row">
<div class="col-sm-12">
<?php if($admin_access == true) { ?>
<div class="col-sm-4">
<label class="col-sm- col-form-label"><strong>FILTER BY ADMIN</strong></label>
<div class="input-group input-group-primary">
<span class="input-group-addon">
<i class="icofont icofont-presentation"></i>
</span>
<select onchange="window.location.href=this.value" required class="form-control" id="u" name="u">
	<option value="<?php print SITE_URL; ?>/activity-logs"><?php print $admin_user->return_username(); ?><option value="<?php print SITE_URL; ?>/activity-logs">----------------------------------------</option>
	</option>
	<?php
	#list the administrators
	$admin_list = $DB->query("select * from _admin where admin_deleted='0' and activated='1' and role != '1001'");
	#count the number of rows found 
	if(count($admin_list) > 0) {
	#using foreach loop to get the details/
	foreach($admin_list as $aresults) { ?>
	<option <?php if(isset($_GET["u"]) and $_GET["u"]==$aresults["username"]) print "selected"; ?> value="<?php print SITE_URL; ?>/activity-logs?u=<?php print xss_clean($aresults["username"]); ?>"><?php print xss_clean($aresults["username"]); ?></option>
	<?php } ?>
	<?php } ?>
</select>
</div>
<HR>
</div>
<br clear="both">
<?php } ?>
<br clear="both">
</div>

<div class="dt-responsive table-responsive col-sm-12">
<table id="simpletable" class="table table-striped table-bordered nowrap">
<thead>
<tr>
<th>ID</th>
<th>TIME</th>
<th>ACTIVITY DETAILS</th>
<th>DESCRIPTION</th>
</tr>
</thead>
<tbody>
<?php
#initializing
$where_clause = "status='1'";
#check the page that will be used for the filtering
if(isset($_GET["p"])) {
	$where_clause = " and activity_page='".xss_clean($_GET["p"])."'";
}

#check the item id that will be used for the filtering
if(isset($_GET["id"])) {
	$where_clause = " and activity_id='".xss_clean($_GET["id"])."'";
}

#list the user activities over the period
if($admin_access == true) {
	$activites_list = $DB->query("select * from _activity_logs where (admin_id='$current_user') or (activity_id='$current_user') order by id desc");
} else {
	$activites_list = $DB->query("select * from _activity_logs where (admin_id='$current_user') or (activity_id='$current_user') order by id desc");
}

foreach($activites_list as $results):
?>
<tr id="logid<?php print $results["id"]; ?>">
<td><?php print $results["id"]; ?></td>
<td><?php print date("d F Y H:ia", strtotime($results["date_recorded"])); ?></td>
<td>
	<?php 
	if(in_array($results["activity_page"], array("invoice","returned-sale"))) {
		?>
		<a href="<?php print SITE_URL; ?>/sales-view/<?php print $results["activity_details"]; ?>">INVOICE: <?php print $results["activity_details"]; ?></a>
		<br>
		Customer: <a href="<?php print SITE_URL; ?>/customers-view/<?php print $results["activity_id"]; ?>"><?php print $customers->_list_customer_by_id($results["activity_id"])->c_fullname; ?></a>
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
	if($results["activity_page"] == "supplier") {
		?>
		Supplier: <strong><a href="<?php print SITE_URL; ?>/customers-view/<?php print $results["activity_id"]; ?>"><?php print $suppliers->_list_suppliers_by_id($results["activity_id"])->sfullname; ?></a></strong><br>
		ID: <a href="<?php print SITE_URL; ?>/customers-view/<?php print $results["activity_id"]; ?>"><?php print $results["activity_id"]; ?></a>
		<?php
	}
	?>
	<?php 
	if($results["activity_page"] == "customer") {
		?>
		Customer: <strong><a href="<?php print SITE_URL; ?>/customers-view/<?php print $results["activity_id"]; ?>"><?php print $customers->_list_customer_by_id($results["activity_id"])->c_fullname; ?></a></strong><br>
		ID: <a href="<?php print SITE_URL; ?>/customers-view/<?php print $results["activity_id"]; ?>"><?php print $results["activity_id"]; ?></a>
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
		<Br><a href="<?php print SITE_URL; ?>/profile/<?php print $results["activity_id"]; ?>"><?php print $results["activity_id"]; ?></a>
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
</td>
<td>
	<?php if(($results["activity_page"] == "password-changed") and ($results["activity_id"] == $current_user)) { ?>
	Your password was recently changed by an Administrator
	<?php } else { ?>
	<?php print $results["activity_description"]; ?>
	<?php } ?>
	<br>by <small><a href="<?php print SITE_URL; ?>/profile/<?php print $results["admin_id"]; ?>"><?php print $results["admin_id"]; ?></a></small>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
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