<?php
	if(!$admin_user->logged_InControlled()) { die(require("login.php")); }
	if($admin_user->lock_user_screen()) { die(require("lockscreen.php")); }
	if(!$admin_user->changed_password_first()) { die(require("change_password.php")); }
	
	$PAGETITLE="Stocks History";
	require "TemplateHeader.php";

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
<span>This page displays the list of stocks that has been added over the period of time..</span>
</div>
<div class="card-block">


<div class="dt-responsive table-responsive">
<table id="simpletable" class="table table-striped table-bordered nowrap">
<thead>
<tr>
<th>STOCK ID</th>
<th>STOCK DATE</th>
<th>MANAGE</th>
</tr>
</thead>
<tbody>
<?php
#initialization
#list the user orders 
$list_orders = $DB->query("select * from _stocks_details where store_id='".STORE_ID."' group by stock_id order by id desc");
#list them here 
foreach($list_orders as $results):
?>
<tr>
<td><?php print $results["stock_id"]; ?></td>
<td><?php print date("d F Y", strtotime($results["date_added"])); ?></td>
<td>
<a href='<?php print SITE_URL; ?>/stocks-details/<?php print $results["stock_id"]; ?>' type="button" class="btn btn-success "><i class="fa ion-eye"></i> View Details</a>
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