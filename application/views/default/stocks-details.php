<?php
	if(!$admin_user->logged_InControlled()) { die(require("login.php")); }
	if($admin_user->lock_user_screen()) { die(require("lockscreen.php")); }
	if(!$admin_user->changed_password_first()) { die(require("change_password.php")); }
	
	$PAGETITLE="Stocks Entry Details";
	require "TemplateHeader.php";
	
	$sales = load_class('sales', 'models');
	$products = load_class('products', 'models');
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
<li class="breadcrumb-item"><a href="<?php print SITE_URL; ?>/sales">Sales List</a></li>
<li class="breadcrumb-item"><a href="<?php print SITE_URL; ?>/products"><?php print $PAGETITLE; ?></a></li>
</ul>
</div>
</div>


<?php $notices->notice_board(); ?>


<div class="page-body">
<div class="row col-md-12" style="background:#fff;padding-top:30px">

<?php  if(isset($SITEURL[1]) and preg_match("/^[A-Z0-9]+$/", strtoupper($SITEURL[1]))) { ?>

<?php 
#set the stock detial id
$stock_detail_id = xss_clean($SITEURL[1]);

#query the database to check if this stock detail exists in the database 
$stock_detail_query = $DB->query("select * from _stocks_details where stock_id='$stock_detail_id' and store_id='".STORE_ID."'");

#count the number of rows found
if(count($stock_detail_query) > 0) {

#get just one of the stock
$stock_detail_query2 = $DB->query("select * from _stocks_details where stock_id='$stock_detail_id' and store_id='".STORE_ID."' order by id desc limit 1");

?>


<?php if($admin_user->confirm_admin_user() == true) { ?>



<div class="col-md-4">
<div class="card">
<div class="card-block">
<h5>STOCK ENTRY DETAILS</h5>
</div>
<div class="card-block reset-table p-t-0">
<div class="table-responsive">


<?php foreach($stock_detail_query2 as $stock_detail_results) { ?>
<table class="table">
<tbody>
<tr>
<td style="width: 1%;"><button data-toggle="tooltip" title="Store" class="btn btn-info btn-xs"><i class="ion-ios-cart"></i></button></td>
<td><a href="<?php print ADMIN_URL; ?>/dashboard">Your Store</a></td>
</tr>
<tr>
<td><button data-toggle="tooltip" title="Date Added" class="btn btn-info btn-xs"><i class="ion-calendar"></i></button></td>
<td><?PHP PRINT date(SITE_DATE_FORMAT, strtotime($stock_detail_results["date_added"])); ?></td>
</tr>
<tr>
<td><button data-toggle="tooltip" title="Payment Method" class="btn btn-info btn-xs"><i class="fa ion-card fa-fw"></i></button></td>
<td><strong>ADDED BY#</strong> <?PHP PRINT $stock_detail_results["added_by"]; ?></td>
</tr>
<tr>
<td><button data-toggle="tooltip" title="Payment Method" class="btn btn-success btn-xs"><i class="fa ion-card fa-fw"></i></button></td>
<td><a class="btn btn-info" href="<?php print SITE_URL; ?>/stocks-history"><i class="fa fa-backward"></i> GO BACK</a></td>
</tr>
</tbody>
</table>
<?php } ?>

</div>
</div>
</div>
</div>




<style>.panel-heading h3{font-size:18px;text-transform:uppercase}.panel-heading {background:#f4f4f4}</style>
<div class="col-md-12">
<div class="card">
<div class="card-block">
<h5>STOCK ENTRY DETAILS</h5>
</div>
<div class="card-block reset-table p-t-0">
<div class="table-responsive">


<div class="panel-heading">
<h3 class="panel-title"><i class="ion-ios-information"></i> STOCK ENTRY (#<?PHP PRINT $SITEURL[1]; ?>)</h3>
</div>



<table class="table table-bordered">
<thead>
<tr style="font-weight:bolder;text-align:center">
<td style="font-weight:bolder;text-align:left">PID</td>
<td>DATE</td>
<td>OLD QUANTITY</td>
<td>QUANTITY</td>
<td>NEW QUANTIY</td>
<td>PURCHASE</td>
<td>SELLING</td>
</tr>
</thead>
  
<tbody>
<?php foreach($stock_detail_query as $stock_detail_results1) { ?>
<tr style="text-align:center">
<td style="text-align:left"><a href="<?php print SITE_URL; ?>/stocks-view/<?php print $stock_detail_results1["pid"]; ?>"><?php print $products->product_by_id("product_id",$stock_detail_results1["pid"])->p_name; ?></a></td>
<td><?php print $stock_detail_results1["date_added"]; ?></td>
<td><?php print $stock_detail_results1["old_quantity"]; ?></td>
<td><?php print $stock_detail_results1["quantity"]; ?></td>
<td><?php print $stock_detail_results1["new_quantity"]; ?></td>
<td><?php print $stock_detail_results1["purchase"]; ?></td>
<td><?php print $stock_detail_results1["selling"]; ?></td>
</tr>
<?php } ?>

</tbody>
</table>



</div>
</div>
</div>
</div>


<?php } ?>


<?php } else {
	show_error('Page Not Found', 'Sorry the page you are trying to view does not exist on this server', 'error_404');
} } else { ?>

<?php show_error('Page Not Found', 'Sorry the page you are trying to view does not exist on this server', 'error_404'); ?>

<?php } ?>


</div>
</div>







</div>
</div>
</div>
</div>
<?php require "TemplateFooter.php"; ?>