<?php
	if(!$admin_user->logged_InControlled()) { die(require("login.php")); }
	if($admin_user->lock_user_screen()) { die(require("lockscreen.php")); }
	if(!$admin_user->changed_password_first()) { die(require("change_password.php")); }
	
	$PAGETITLE="View Order Details";
	require "TemplateHeader.php";
	
	$customers_list = load_class('customers', 'models');
	$products = load_class('products', 'models');
	$orders_list = load_class('Orders', 'models');
	$suppliers = load_class('suppliers', 'models');
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
<div class="row">







<?php if(isset($SITEURL[1]) and ($orders_list->order_by_id($SITEURL[1])->o_success)) { ?>





<div class="col-md-4">
<div class="card">
<div class="card-block">
<h5>Recent Customer Purchase</h5>
</div>
<div class="card-block reset-table p-t-0">
<div class="table-responsive">


<table class="table">
<tbody>
  <tr>
<td style="width: 1%;"><button data-toggle="tooltip" title="Store" class="btn btn-info btn-xs"><i class="ion-ios-cart"></i></button></td>
<td><a href="<?php print ADMIN_URL; ?>/dashboard">Your Store</a></td>
  </tr>
  <tr>
<td><button data-toggle="tooltip" title="Date Added" class="btn btn-info btn-xs"><i class="ion-calendar"></i></button></td>
<td><?PHP PRINT date(SITE_DATE_FORMAT, strtotime($orders_list->order_by_id($SITEURL[1])->o_date)); ?></td>
  </tr>
  <tr>
<td><button data-toggle="tooltip" title="Payment Method" class="btn btn-info btn-xs"><i class="fa ion-card fa-fw"></i></button></td>
<td><?PHP PRINT $orders_list->order_by_id($SITEURL[1])->payment_method; ?></td>
  </tr>
  <tr>
<td><button data-toggle="tooltip" title="Payment Reference ID" class="btn btn-info btn-xs"><i class="fa ion-card fa-fw"></i></button></td>
<td><strong>REFERENCE ID#</strong> <?PHP PRINT $orders_list->order_by_id($SITEURL[1])->payment_reference_id; ?></td>
  </tr>
</tbody>
</table>


</div>
</div>
</div>
</div>




<div class="col-md-4">
<div class="card">
<div class="card-block">
<h5>Customer Details</h5>
</div>
<div class="card-block reset-table p-t-0">
<div class="table-responsive">
<table class="table">
<tr>
  <td style="width: 1%;"><button data-toggle="tooltip" title="Customer" class="btn btn-info btn-xs"><i class="icon-user"></i></button></td>
  <td><a href="<?php print SITE_URL; ?>/customers-view/<?php print $orders_list->order_by_id($SITEURL[1])->o_uid; ?>"><?php print $customers_list->_list_customer_by_id($orders_list->order_by_id($SITEURL[1])->o_uid)->c_fullname; ?></a></td>
</tr>
<tr>
  <td><button data-toggle="tooltip" title="Telephone" class="btn btn-info btn-xs"><i class="ion-ios-telephone"></i></button></td>
  <td><?php print $customers_list->_list_customer_by_id($orders_list->order_by_id($SITEURL[1])->o_uid)->c_contact; ?></td>
</tr>
<tr>
  <td><button data-toggle="tooltip" title="Customer Group" class="btn btn-info btn-xs"><i class="ion-map"></i></button></td>
  <td><?php print $customers_list->_list_customer_by_id($orders_list->order_by_id($SITEURL[1])->o_uid)->c_address; ?></td>
</tr>
<tr>
  <td><button data-toggle="tooltip" title="E-Mail" class="btn btn-info btn-xs"><i class="ion-email"></i></button></td>
  <td><?php print $customers_list->_list_customer_by_id($orders_list->order_by_id($SITEURL[1])->o_uid)->c_email; ?></td>
</tr>
  </table>
</div>
</div>
</div>
</div>


<div class="col-md-4">
<div class="card">
<div class="card-block">
<h5>ORDER STATUS</h5>
</div>
<div class="card-block reset-table p-t-0">
<div class="table-responsive">

<table class="table">
<tr>
  <td><?php if($orders_list->order_by_id($SITEURL[1])->o_returned == 1) { ?><span class="alert alert-warning">RETURNED</span><?php } ?>
</td>
</tr>
<tr>
  <td><a class="btn btn-success" target="_blank" href='<?php print SITE_URL; ?>/print-sales/<?php print $SITEURL[1]; ?>'><i class="fa ion-printer"></i> Print</a></td>
</tr>
<tr>
  <td><button class="btn btn-primary"><i class="fa icon-user"></i> SOLD BY: <strong><?PHP PRINT $orders_list->order_by_id($SITEURL[1])->sold_by; ?><strong></button></td>
</tr>

</table>



</div>
</div>
</div>
</div>

<style>.panel-heading h3{font-size:18px;text-transform:uppercase}.panel-heading {background:#f4f4f4}</style>
<div class="col-md-12">
<div class="card">
<div class="card-block">
<h5>RECENT PRODUCTS STOCK</h5>
</div>
<div class="card-block reset-table p-t-0">
<div class="table-responsive">


<div class="panel-heading">
<h3 class="panel-title"><i class="ion-ios-information"></i> Order (#<?PHP PRINT $SITEURL[1]; ?>)</h3>
</div>



<table class="table table-bordered">
  <thead>
<tr>
  <td style="width: 50%;font-weight:bolder" class="text-left">Customer Address</td>
  </tr>
  </thead>
  <tbody>
<tr>
  <td class="text-left">
  <br /><?php print $customers_list->_list_customer_by_id($orders_list->order_by_id($SITEURL[1])->o_uid)->c_fullname; ?>
  <br /><?php print $customers_list->_list_customer_by_id($orders_list->order_by_id($SITEURL[1])->o_uid)->c_email; ?>
  <br /><?php print $customers_list->_list_customer_by_id($orders_list->order_by_id($SITEURL[1])->o_uid)->c_contact; ?>
  <br /><?php print $customers_list->_list_customer_by_id($orders_list->order_by_id($SITEURL[1])->o_uid)->c_address; ?>
  <br /><?php print $customers_list->_list_customer_by_id($orders_list->order_by_id($SITEURL[1])->o_uid)->c_website; ?>
  </td>

</tr>
  </tbody>
</table>



<table class="table table-bordered">
  <thead>
  <tr>
<td class="text-left">Product</td>
<td class="text-left">Supplier</td>
<td class="text-left">Quantity</td>
<td class="text-right">Unit Price</td>
<td class="text-right">Total</td>
</tr>
  </thead>
  
  <tbody>
<?php
#fetch the orders list
$user_orders = $DB->query("select * from _customers_orders_details where unique_id='{$SITEURL[1]}'");
#initials
$orderTotal = 0;
#count the number of rows
if(count($user_orders) > 0):
	
#list them here 
foreach($user_orders as $results4):


$o_discount = $orders_list->order_by_id($results4['unique_id'])->o_discount;
$overall_price = $orders_list->order_by_id($results4['unique_id'])->overall_price;
$o_paid = $orders_list->order_by_id($results4['unique_id'])->o_paid;

$price = $results4["product_price"];

$pricetotal = $price * $results4['quantity'];
$shop_id = $results4['shop_id'];
$orderTotal = $pricetotal + $orderTotal;
setlocale(LC_MONETARY, "en_US");
$pricetotal = number_format($pricetotal, 2);
?>
<tr>
<td><a href="<?php print SITE_URL; ?>/stocks-view/<?php print $products->product_by_id("id", $results4["product_id"])->p_slug; ?>"><?php print $products->product_by_id("id", $results4["product_id"])->p_name; ?></a></td>
<td><?php print $suppliers->_list_suppliers_by_id2($shop_id)->sfullname; ?></td>
<td><?php print $results4["quantity"]; ?></td>
<td class="text-right">GHc <?php print $results4["product_price"]; ?></td>
<td class="text-right">GHc <?php print $pricetotal; ?></td>
</tr>
<?php endforeach; endif; ?>
<tr>
<td style="font-weight:bolder;text-transform:uppercase" colspan="4" class="text-right">Subtotal</td>
<td style="font-weight:bolder;text-transform:uppercase" class="text-right">GHc <?php print number_format($orderTotal, 2); ?></td>
</tr>
<tr>
<td style="font-weight:;text-transform:uppercase" colspan="4" class="text-right">Discount</td>
<td style="font-weight:;text-transform:uppercase" class="text-right">GHc <?php print number_format($o_discount, 2); ?></td>
</tr>
<tr>
<td style="font-weight:bolder;text-transform:uppercase" colspan="4" class="text-right">Overall Total</td>
<td style="font-weight:bolder;text-transform:uppercase" class="text-right">GHc <?php print number_format($overall_price, 2); ?></td>
</tr>
<?php if($overall_price != $o_paid) { ?>
<tr>
  <td style="font-weight:;text-transform:uppercase" colspan="4" class="text-right">Amount Paid</td>
  <td style="font-weight:;text-transform:uppercase" class="text-right">GHc <?php print number_format($o_paid, 2); ?></td>
</tr>
<tr>
	 <td style="font-weight:bolder;text-transform:uppercase" colspan="4" class="text-right">OUSTANDING</td>
	 <td style="font-weight:bolder;text-transform:uppercase" class="text-right">GHc <?php print number_format(($overall_price-$o_paid), 2); ?></td>
</tr>
<?php } ?>
</tbody>
</table>



</div>
</div>
</div>
</div>



<?php } else { ?>


<?php show_error('Page Not Found', 'Sorry the page you are trying to view does not exist on this server', 'error_404') ?>


<?php } ?>

</div>
</div>







</div>
</div>
</div>
</div>
<?php require "TemplateFooter.php"; ?>