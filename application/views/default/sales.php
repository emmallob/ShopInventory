<?php
	if(!$admin_user->logged_InControlled()) { die(require("login.php")); }
	if($admin_user->lock_user_screen()) { die(require("lockscreen.php")); }
	if(!$admin_user->changed_password_first()) { die(require("change_password.php")); }
	
	$PAGETITLE="Sales History";
	require "TemplateHeader.php";
	
	$customers_list = load_class('customers', 'models');
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
<span>This page displays the list of products that has been purchased by clients over the period.</span>
</div>
<div class="card-block">


<div class="dt-responsive table-responsive">
<table id="simpletable" class="table table-striped table-bordered nowrap">
<thead>
<tr>
<th>ID</th>
<th>ORDER ID</th>
<th>CUSTOMER</th>
<th>TOTAL PRICE</th>
<th>DATE SOLD</th>
<th>SOLD BY</th>
<th>MANAGE</th>
</tr>
</thead>
<tbody>
<?php
#initialization
$where_clause = "1 and status='1'";
#check if the form has been submitted
if(isset($_GET["filter"])):
if(isset($_GET["t"]) and !empty($_GET["t"]) and preg_match("/^[a-z0-9]+$/", strtolower($_GET["t"]))):
$where_clause = "unique_id like '%".xss_clean($_GET["t"])."%' ";
endif;
if(isset($_GET["p"]) and preg_match("/^[0-9]+$/", $_GET["p"])):
$where_clause .= " and status='".xss_clean($_GET["p"])."' ";
endif;
endif;
#list the user orders 
$list_orders = $DB->query("select * from _customers_orders where $where_clause and payment_complete='1' and store_id='".STORE_ID."'");
#list them here 
foreach($list_orders as $results):
?>
<tr id="orders_list_<?php print $results["unique_id"]; ?>" <?php if($results["returned"] == 1) { ?>class="alert alert-warning"<?php } ?>>
<td><?php print $results["id"]; ?></td>
<td><?php print $results["unique_id"]; ?></td>
<td><?php print strtoupper($customers_list->_list_customer_by_id($results["customer_id"])->c_fullname); ?></td>
<td><span class="font-medium">GHc <?php print number_format($results["overall_price"], 2); ?></span></td>
<td class="mailbox-date"><?php print date(SITE_DATE_FORMAT, strtotime($results["date_added"])); ?></td>
<td class="mailbox-date"><?php print strtoupper($results["sold_by"]); ?></td>
<td>
<a href='<?php print SITE_URL; ?>/sales-view/<?php print $results["unique_id"]; ?>' type="button" class="btn btn-success "><i class="fa ion-eye"></i> View</a>
<a href='<?php print SITE_URL; ?>/print-sales/<?php print $results["unique_id"]; ?>' target="_blank" type="button" class="btn btn-primary"><i class="fa ion-printer"></i> Print</a>
<?php if($results["returned"] == 1) { ?><span class="alert alert-warning">RETURNED</span><?php } ?>
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