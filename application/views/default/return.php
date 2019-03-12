<?php
	if(!$admin_user->logged_InControlled()) { die(require("login.php")); }
	if($admin_user->lock_user_screen()) { die(require("lockscreen.php")); }
	if(!$admin_user->changed_password_first()) { die(require("change_password.php")); }
	
	$PAGETITLE="Return Sold Item";
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
<li class="breadcrumb-item"><a href="<?php print SITE_URL; ?>/orders">Customers Orders Lists</a></li>
<li class="breadcrumb-item"><a href="#"><?php print $PAGETITLE; ?></a></li>
</ul>
</div>
</div>

<?php $notices->notice_board(); ?>

<style>input {text-transform:uppercase!important}</style>

<div class="page-body">
<div class="">
<div class="col-sm-12">




<div class="card">
<div class="card-header">
<span>Complete the form to receive a returned product by a customer and refund any amount paid by the customer.</span>
</div>
<div class="">
<div class="card-block">

<div class="col-sm-7">
<label class="col-sm-12 col-form-label">SELECT ONE OF THE OPTIONS PROVIDED <small><br clear="both"><strong>(You can choose whether to return an entire order or a number of items in an order.)</strong></small></label>
<div class="input-group input-group-primary">
<span class="input-group-addon">
<i class="icofont icofont-presentation"></i>
</span>
<select onchange="window.location.href=this.value" required class="form-control" id="gender" name="gender">
	<option value="<?php print SITE_URL; ?>/return">PLEASE SELECT</option>
	<option value="<?php print SITE_URL; ?>/return">----------------------------------</option>
	<option <?php if(isset($SITEURL[1]) and $SITEURL[1]== "item") print "selected"; ?> value="<?php print SITE_URL; ?>/return/item">RETURN AN ITEM IN AN ORDER</option>
	<option <?php if(isset($SITEURL[1]) and $SITEURL[1]== "order") print "selected"; ?> value="<?php print SITE_URL; ?>/return/order">RETURN ENTIRE ORDER</option>
	
</select>
</div>
</div>
<hr>
<?php if(isset($SITEURL[1]) and $SITEURL[1]== "order") { ?>
<style>.col-form-label {text-transform:uppercase;font-weight:bolder}</style>
<h4 style="font-weight:normal;font-size:16px;padding-left:20px;color:#ff4000">You have selected to return an <strong>ENTIRE ORDER</strong> Please enter the <strong>VOUCHER ID / ORDER ID</strong> in the field provided below.</h4>
<form SITEURL="javascript:search_order()" method="post">
<div class="col-sm-12">
<div class="row">
<div class="col-sm-12">
<label class="col-sm-12 col-form-label">ORDER ID</label>
<div class="input-group input-group-primary">
<span class="input-group-addon">
<i class="icofont icofont-presentation"></i>
</span>
<input name="order_id" value="<?php if(isset($SITEURL[2])) print $functions->clean_words($SITEURL[2]); ?>" placeholder="Please Enter the Order ID" required type="text" class="col-sm-6 form-control" id="order_id">
</div>
</div>
</div>
</div>
</form>
<div class="list_registered_orders"></div>
<div class="list_registered_orders2"></div>
<?php } ?>
<?php if(isset($SITEURL[1]) and $SITEURL[1]== "item") { ?>
<style>.col-form-label {text-transform:uppercase;font-weight:bolder}</style>
<h4 style="font-weight:normal;font-size:16px;padding-left:20px;color:#ff4000">You have selected to return an <strong>ITEM</strong> in a particular ORDER Please enter the <strong>VOUCHER ID / ORDER ID</strong> in the field provided below.</h4>
<form action="javascript:search_order()" method="post">
<div class="col-sm-12">
<div class="row">
<div class="col-sm-12">
<label class="col-sm-12 col-form-label">ORDER ID</label>
<div class="input-group input-group-primary">
<span class="input-group-addon">
<i class="icofont icofont-presentation"></i>
</span>
<input name="order_id" value="<?php if(isset($SITEURL[2])) print xss_clean($SITEURL[2]); ?>" placeholder="Please Enter the Order ID" required type="text" class="col-sm-6 form-control" id="order_id">
</div>
</div>
</div>
</div>
</form>
<div class="list_registered_orders"></div>
<div class="list_registered_orders2"></div>
<?php } ?>
<div class="col-sm-8">

<div id="receive_results"></div>
<style>.chose_customer {border:solid #f4f4f4 1px;padding:5px;margin:5px;cursor:pointer}</style>
	
<script>
<?php if(isset($SITEURL[2])) { ?> search_order(); <?PHP } ?>
function search_order() {
	$(".list_registered_orders").html("");
	$(".list_registered_orders2").html("");
	var fi = $("#order_id").val();
	
	if(fi.length > 3) {
		$.ajax({
			type: "POST",
			data: "process_form&searchField2&fi="+fi+"&type=<?php if(isset($SITEURL[1])) print xss_clean($SITEURL[1]); ?>",
			url: "<?php print SITE_URL; ?>/doProcess",
			beforeSend:function() {
				$(".list_registered_orders").html('<div style="font-size:12px; color:black;height:10px;">Please wait <img src="<?php print SITE_URL; ?>/assets/images/loadings.gif" align="absmiddle" /></div><br clear="all"><br clear="all">');
			}, success:function(response) {
				$(".list_registered_orders").html(response);
			}
		});
	}				
}
function cancel_process() {
	$(".list_registered_orders").html("");
	$("#order_id").val("");
	$("#order_id").focus();
}

function return_entire_order(order_id, customer_id) {
	$("#return_product_button").attr("disabled", true);
	if(confirm("Are you sure you want to return this ORDER? You cannot reverse the process after confirming.")) {
		$.ajax({
			type: "POST",
			data: "process_form&return_order&oidd="+order_id+"&customer_id="+customer_id,
			url: "<?php print SITE_URL; ?>/doReturn",
			beforeSend:function() {
				$(".list_registered_orders2").html('<div style="font-size:12px; color:black;height:10px;">Please wait will the process is completed. <img src="<?php print SITE_URL; ?>/assets/images/loadings.gif" align="absmiddle" /></div><br clear="all"><br clear="all">');
			}, success:function(response) {				
				$(".list_registered_orders2").html(response);
				$(".list_registered_orders").html("");
			}
		});
	} else {
		$("#return_product_button").removeAttr("disabled", false);
	}
}
</script>

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