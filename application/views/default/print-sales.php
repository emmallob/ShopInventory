<?php
global $libs, $stores, $session;
#call some important functions 
$customers_list = load_class('customers', 'models');
$products = load_class('products', 'models');
$orders_list = load_class('Orders', 'models');
$suppliers = load_class('suppliers', 'models');
	
$voucher_id = "Receipt";

#set the payment id for the product
if(isset($SITEURL[1])) {
	#set the voucher id
	$voucher_id = xss_clean($SITEURL[1]);
	#get the voucher details for the receipt
	if($orders_list->order_by_id($voucher_id)->o_success) {
		$voucher_date = $orders_list->order_by_id($voucher_id)->o_date;
		$user_id  = $orders_list->order_by_id($voucher_id)->o_uid;
		$voucher_payment = $orders_list->order_by_id($voucher_id)->payment_method;
		$sale_type = $orders_list->order_by_id($voucher_id)->sale_type;
		$sold_by = $orders_list->order_by_id($voucher_id)->sold_by;
		$o_paid = $orders_list->order_by_id($voucher_id)->o_paid;
		$o_discount = $orders_list->order_by_id($voucher_id)->o_discount;
		$overall_price = $orders_list->order_by_id($voucher_id)->overall_price;
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title><?php print $voucher_id; ?> :: <?php print STORE_NAME; ?> >> <?PHP PRINT config_item('site_name'); ?></title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<link href="<?php print SITE_URL; ?>/assets/bower_components/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
	<style>
		.wrapper {
			width:98%;
			box-shadow:0px 1px 2px #000;
			margin:auto auto;
			color:#000!important;
		}
		.wrapper table h1 {color:#000!important;padding:10px;margin:0px;}
		.wrapper table strong{font-size:13px;margin-bottom:5px;padding:10px;}
		.wrapper table {font-family:Times New Roman;font-size:11px}
	</style>
</head>
<body>

<div class="wrapper">
<table class="table table-bordered" width="100%" border="1">
	<tr>
		<td align="center" colspan="3"><h1><?php print config_item('site_name'); ?></h1></td>
	</tr>
	<tr>
		<td valign="top" colspan="2" style="">
			<h4 align="left">RECEIPT VOUCHER</h4>
			<strong>VOUCHER NUMBER:</strong> <?php print $voucher_id; ?>
			<br><strong>VOUCHER DATE:</strong> <?php print date(SITE_DATE_FORMAT, strtotime($voucher_date)); ?>
			<?php if($sale_type = $orders_list->order_by_id($voucher_id)->sale_type == "CREDIT") { ?>
			<br><strong>PURCHASE TYPE:</strong> CREDIT
			<?php } ?>
			<br><strong>SOLD BY:</strong> <?php print $sold_by; ?>
		</td>
		<td>
			<?php print $customers_list->_list_customer_by_id($user_id)->c_fullname; ?><br>
			<?php print $customers_list->_list_customer_by_id($user_id)->c_contact; ?><br>
			<?php print $customers_list->_list_customer_by_id($user_id)->c_email; ?>
		</td>
	</tr>
	<tr>
	<td colspan="3">
	
		
		<table class="table table-bordered">
		  <thead>
		  <tr style="font-weight:bolder;text-transform:uppercase;color:#000">
			<td class="text-left">Product</td>
			<td class="text-left">Qty Sold</td>
			<td class="text-right">Unit Price</td>
			<td class="text-right">Total</td>
			</tr>
		  </thead>
		  
		  <tbody>
		<?php
		#fetch the orders list
		$user_orders = $DB->query("select * from _customers_orders_details where unique_id='{$SITEURL[1]}' and store_id='".STORE_ID."'");
		#initials
		$orderTotal = 0;
		#count the number of rows
		if(count($user_orders) > 0):

		#list them here 
		foreach($user_orders as $results4):

		$price = $results4["product_price"];

		$pricetotal = $price * $results4['quantity'];
		$shop_id = $results4['shop_id'];
		$orderTotal = $pricetotal + $orderTotal;
		setlocale(LC_MONETARY, "en_US");
		$pricetotal = number_format($pricetotal, 2);
		?>
		<tr>
		<td width="45%"><?php print $products->product_by_id("id", $results4["product_id"])->p_name; ?></td>
		<td><?php print $results4["quantity"]; ?></td>
		<td class="text-right">GH¢ <?php print $results4["product_price"]; ?></td>
		<td class="text-right">GH¢ <?php print $pricetotal; ?></td>
		</tr>
		<?php endforeach; endif; ?>

		<tr>
		  <td style="font-weight:bolder;text-transform:uppercase" colspan="3" class="text-right">Subtotal</td>
		  <td style="font-weight:bolder;text-transform:uppercase" class="text-right">GH¢ <?php print number_format($orderTotal, 2); ?></td>
		</tr>
		<tr>
		  <td style="font-weight:;text-transform:uppercase" colspan="3" class="text-right">Discount</td>
		  <td style="font-weight:;text-transform:uppercase" class="text-right">GH¢ <?php print number_format($o_discount, 2); ?></td>
		</tr>
		<tr>
		  <td style="font-weight:bolder;text-transform:uppercase" colspan="3" class="text-right">Overall Total</td>
		  <td style="font-weight:bolder;text-transform:uppercase" class="text-right">GH¢ <?php print number_format($overall_price, 2); ?></td>
		</tr>
		<?php if($overall_price != $o_paid) { ?>
		<tr>
		  <td style="font-weight:;text-transform:uppercase" colspan="3" class="text-right">Amount Paid</td>
		  <td style="font-weight:;text-transform:uppercase" class="text-right">GH¢ <?php print number_format($o_paid, 2); ?></td>
		</tr>
		<tr>
		  <td style="font-weight:bolder;text-transform:uppercase" colspan="3" class="text-right">OUSTANDING</td>
		  <td style="font-weight:bolder;text-transform:uppercase" class="text-right">GH¢ <?php print number_format(($overall_price-$o_paid), 2); ?></td>
		</tr>
		<?php } ?>
		</tbody>
		</table>

		__________________________<br clear="both">
		SIGNATURE / STAMP<br clear="both">
		<strong><?php print $session->userdata(":lifeUsername"); ?></strong>
		
		<BR>
		<HR>
		<center><em><?php print $stores->fetch()->receipt_message; ?></em></center>
		<HR>
		<center><strong>PHONE:</strong> <?php print $stores->fetch()->phone; ?> / <?php print $stores->fetch()->phone2; ?> <strong>EMAIL:</strong> <?php print $stores->fetch()->email; ?>
		<br clear="both"><br clear="both"></center>
		<script>
		window.print();
		window.close();
		</script>

	
	</td>
	</tr>
</table>
</div>

</body>
</html>