<?php
	if(!$admin_user->logged_InControlled()) { die(require("login.php")); }
	if($admin_user->lock_user_screen()) { die(require("lockscreen.php")); }
	if(!$admin_user->changed_password_first()) { die(require("change_password.php")); }
	
	$PAGETITLE="Sell Product";
	require "TemplateHeader.php";
	
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
<li class="breadcrumb-item"><a href="<?php print SITE_URL; ?>/products"><?php print $PAGETITLE; ?></a></li>
</ul>
</div>
</div>

<?php $notices->notice_board(); ?>

<div class="show_pop_up_content"></div>

<div class="page-body">
<div class="row">
<div class="col-sm-12">

<div class="card">
<div class="card-header">
<h5><?php print $PAGETITLE; ?></h5>
<span>This page displays all products that have currently been uploaded unto the server. From this field, you can add product to cart and checkout.</span>
</div>
<div class="card-block">
<div class="dt-responsive table-responsive">
<table id="simpletable" class="table table-striped table-bordered nowrap">
<thead>
<tr class="text-uppercase">
<th>Product Code</th>
<th>Product Name</th>
<th>Category</th>
<th>Purchase</th>
<th>Selling</th>
<th>Quantity</th>
<th>Purchase</th>
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
<td><?php if($results["product_category"] != 0) : print $products->category_byid($results["product_category"],"id")->getName; endif; ?></td>
<td>GHc <?php print number_format($results["product_price"],2); ?></td>
<td>GHc <?php print number_format($results["product_actuals"],2); ?></td>
<td align="center" <?php if($results["product_quantity"] <= STOCKS_LIMIT) { print "style='color:#721c24; background-color:#f8d7da; border-color:#f5c6cb;font-weight:bolder'";} ?>> <?php print $results["product_quantity"]; ?> </td>
<td>
<div id="myModal<?php print $results["id"]; ?>" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><?php print $results["product_name"]; ?></h4>
		<p><strong>QTY IN STOCK: </strong> <?php print $results["product_quantity"]; ?></p>
      </div>
      <div class="modal-body">
        <label><strong>Enter the quantity to add to cart</strong></label>
		<div class="input-group input-group-primary">
		<span class="input-group-addon">
		<i class="icofont icofont-stock-mobile"></i>
		</span>
		<input maxlength="5" placeholder="Enter the quantity" name="quantity<?php print $results["id"]; ?>" value="<?php print $products->session_quantity($results["id"])->quantity; ?>" type="number" class="col-sm-4 form-control" id="quantity<?php print $results["id"]; ?>"><br clear="both">
		<input type="hidden" name="Product_id<?php print $results["id"]; ?>" id="Product_id<?php print $results["id"]; ?>" value="<?php print $results["id"]; ?>">
		</div>
      </div>
      <div class="modal-footer">
        <?php if($products->session_quantity($results["id"])->quantity == 0) { ?> 
		<button type="button" class="btn btn-success" onclick="add_to_cart('<?php print $results["id"]; ?>');">Add To Cart</button>
		<?php } else { ?>
		<button type="button" class="btn btn-success" onclick="adjust_item_quantity('<?php print $results["id"]; ?>');">Adjust Quantity</button>
		<?php } ?>
		<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- Trigger the modal with a button -->
<button data-toggle="modal" data-target="#myModal<?php print $results["id"]; ?>" type="button" class="btn btn-success"><i class="ion-ios-cart"></i> SELL</button>
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