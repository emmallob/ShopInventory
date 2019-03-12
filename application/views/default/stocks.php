<?php
	if(!$admin_user->logged_InControlled()) { die(require("login.php")); }
	if($admin_user->lock_user_screen()) { die(require("lockscreen.php")); }
	if(!$admin_user->changed_password_first()) { die(require("change_password.php")); }
	
	$PAGETITLE = "Products Stock Available";
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
<li class="breadcrumb-item"><a href="<?php print SITE_URL; ?>/products">Products List</a></li>
<li class="breadcrumb-item"><a href="<?php print SITE_URL; ?>/stocks"><?php print $PAGETITLE; ?></a></li>
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
<span>This page displays all products that have currently been uploaded unto the server. From this field, you can add product to cart and checkout.</span>
</div>

<div class="col-sm-12">
<?php
#check if the product does exists
if(isset($_GET["msg"])):
if(xss_clean($_GET["msg"])==1):
show_msg("Success", "Product Stock Information was successfully updated.");
elseif(xss_clean($_GET["msg"])==0):
show_msg("Error", "Sorry there was an error updating page information.");
elseif(xss_clean($_GET["msg"])==5):
show_msg("Success", "New Product Stocks was successfully inserted");
elseif(xss_clean($_GET["msg"])==3):
show_msg("Success", "Product Stock Information was successfully inserted.");
endif;
endif;
?>
</div>


<div class="card-block">
<div class="dt-responsive table-responsive">
<table id="simpletable" class="table table-striped table-bordered nowrap">
<thead>
<tr class="text-uppercase">
<th>STOCK ID</th>
<th>PRODUCT NAME</th>
<th>QUANTITY</th>
<th>PURCHASE PRICE</th>
<th>SELLING PRICE</th>
<th>MANAGE</th>
</tr>
</thead>

<tbody>
<?php 
#
$list_products = $DB->query("select * from _products where store_id='".STORE_ID."' order by id desc limit 1500");
foreach($list_products as $results):
?>
<tr>
<td><?php print $results["product_id"]; ?></td>
<td><span class="font-medium"><?php print $results["product_name"]; ?></span></td>
<td align="center" <?php if($results["product_quantity"] <= STOCKS_LIMIT) { print "style='color:#721c24;background-color:#f8d7da;border-color:#f5c6cb;font-weight:bolder'";} ?>><?php print $results["product_quantity"]; ?></td>
<td>GH&#8373;  <?php print number_format($results["product_price"],2); ?></td>
<td>GH&#8373;  <?php print number_format($results["product_actuals"],2); ?></td>
<td>
<button onclick="window.location.href='<?php print SITE_URL; ?>/stocks-view/<?php print $results["product_id"]; ?>'" type="button" class="btn btn-info"><i class="ion-edit"></i></button>
</td>
</tr>
<?php endforeach; ?>
</tbody>

</table>

<a href='<?php print SITE_URL; ?>/stocks-new' title="Record new stocks that you have gone to buy from the market." type="button" class="btn btn-success"><li class="fa fa-save"></li> TOP PRODUCT STOCK UP</a>

<a href='<?php print SITE_URL; ?>/stocks-add' title="Use this button to insert a new stock that you have purchased" type="button" class="btn btn-warning"><li class="fa fa-plus"></li> NEW PRODUCT?</a>

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