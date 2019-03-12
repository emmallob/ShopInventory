<?php
	if(!$admin_user->logged_InControlled()) { die(require("login.php")); }
	if($admin_user->lock_user_screen()) { die(require("lockscreen.php")); }
	if(!$admin_user->changed_password_first()) { die(require("change_password.php")); }
	
	$PAGETITLE="Suppliers List";
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
<span>This page displays all suppliers that have currently been added unto the server.</span>
</div><br clear="both">
<div class="col-sm-12">
<?php 
//include the update file
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
</div>
<div class="card-block">
<div class="dt-responsive table-responsive">
<table id="simpletable" class="table table-striped table-bordered nowrap">
<thead>
<tr class="text-uppercase">
<th>ID</th>
<th>FULLNAME</th>
<th>CONTACT</th>
<th>ADDRESS</th>
<th>EMAIL</th>
<th>Manage</th>
</tr>
</thead>

<tbody>
<?php 
#
$list_customers = $DB->query("select * from _suppliers where store_id='".STORE_ID."' order by id desc limit 5");
foreach($list_customers as $results):
?>
<tr>
<td><?php print $results["id"]; ?></td>
<td><?php print strtoupper($results["fullname"]); ?></td>
<td><?php print $results["contact"]; ?> / <?php print $results["contact2"]; ?></td>	
<td><?php print strtoupper($results["address"]); ?></td>
<td><?php print strtoupper($results["email"]); ?></td>
<td>
<button title="View / Edit Supplier details" onclick="window.location.href='<?php print SITE_URL; ?>/suppliers-view/<?php print $results["supplier_id"]; ?>'" type="button" class="btn btn-info"><i class="ion-eye"></i></button>
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