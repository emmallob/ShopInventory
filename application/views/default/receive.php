<?php
if(!$admin_user->logged_InControlled()) { die(require("login.php")); }
if($admin_user->lock_user_screen()) { die(require("lockscreen.php")); }
if(!$admin_user->changed_password_first()) { die(require("change_password.php")); }

$PAGETITLE="Receive Payment"; 
require "TemplateHeader.php";

$admin_logged = $admin_user->return_username();
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
<span>Complete the form to receive payment from client.</span>
</div>
<div class="">
<div class="card-block">


<style>.col-form-label {text-transform:uppercase;font-weight:bolder}</style>

    
<form id="main" method="post" action="javascript:receive_payment();" autocomplete="Off" enctype="multipart/form-data">

<?php /* confirm store wants to operate a sale on credit system */ if(CREDIT_SALES == 1) { ?>
<div class="col-sm-8">
<div class="row">
<div class="col-sm-12">
<label class="col-sm-12 col-form-label">CUSTOMER NAME</label>
<div class="input-group input-group-primary">
<span class="input-group-addon">
<i class="icofont icofont-presentation"></i>
</span>
<input name="customer_name" onkeyup="return search_user();" type="text" class="span8 form-control" id="customer_name">
</div>
<div class="list_registered_customers"></div>
<input type="hidden" id="customer_id" name="customer_id" value="">
</div>
</div>
</div>



<div class="col-sm-8">
<div class="row">
<div class="col-sm-6">
<label class="col-sm-12 col-form-label">LIST OF ORDERS MADE</label>
<div class="input-group input-group-primary">
<span class="input-group-addon">
<i class="icofont icofont-user"></i>
</span>
<div id="orders_listing">
<select required class="form-control" id="order_id" style="width:300px;" name="orders_listing">
<option value="0">Select Order to Assign payment</option>
</select>
</div>
</div>
</div>
</div>
<div class="row">
<div class="col-sm-6">
<label class="col-sm-12 col-form-label">AMOUNT TO BE PAID</label>
<div class="input-group input-group-primary">
<span class="input-group-addon">
<i class="icofont icofont-money"></i>
</span>
<input maxlength="15" name="amount" required type="text" class="form-control autonumber" id="amount">
</div>
</div>
</div>

<div class="row">
<div class="col-sm-6">
<label class="col-sm-12 col-form-label">OUSTANDING DEBTS</label>
<div class="input-group input-group-primary">
<span class="input-group-addon">
<i class="icofont icofont-money"></i>
</span>
<input maxlength="15" readonly name="outstanding" type="text" class="form-control autonumber" id="outstanding">
</div>
</div>
</div>



<div class="form-group">
<div class="col-sm-12">
<button title="Click to list all customers" onclick="window.location.href='<?php print SITE_URL; ?>/customers'" type="button" class="btn btn-danger"><i class="ion-ios-arrow-back"></i> Go Back</button>
<button type="submit" class="btn btn-success m-b-0"><i class="fa fa-save"></i> Add Record</button>
</div>
</div>
<?php } ?>
</form>
<div id="receive_results"></div>
<style>.chose_customer {border:solid #f4f4f4 1px;padding:5px;margin:5px;cursor:pointer}</style>
<?php /* confirm store wants to operate a sale on credit system */ if(CREDIT_SALES == 1) { ?>
<script>
function search_user() {
	$(".list_registered_customers").html("");
	var fi = $("#customer_name").val();
	if(fi.length > 1) {
		$.ajax({
			type: "POST",
			data: "process_form&searchField1&fi="+fi,
			url: "<?php print SITE_URL; ?>/doProcess",
			beforeSend:function() {
				$(".list_registered_customers").html('<div class="please-wait" style="font-family:Verdana, Geneva, sans-serif; font-size:12px; color:black;height:10px;"><br>Please wait <img src="<?php print SITE_URL; ?>/assets/images/loadings.gif" align="absmiddle" /></div><br clear="all">');
			}, success:function(response) {
				$(".list_registered_customers").html(response);
			}
		});
	}				
}
function chose_customer(customer_name, outstanding, customer_id) {
	$("#customer_name").val(customer_name);
	$("#outstanding").val(outstanding);
	$("#customer_id").val(customer_id);
	$("#orders_listing").html('');
	$(".list_registered_customers").html('');
	$.ajax({
		type: "POST",
		data: "process_form&list_orders&cid="+customer_id,
		url: "<?php print SITE_URL; ?>/doProcess",
		success:function(response) {
			$("#orders_listing").html(response);
		}
	});
}
function receive_payment() {
	var cn = $("#customer_name").val();
	var ca = $("#amount").val();
	var cid = $("#customer_id").val();
	var cord = $("#order_id").val();
	$.ajax({
		type: "POST",
		data: "process_form&receive_pay&cn="+cn+"&ca="+ca+"&cid="+cid+"&cord="+cord,
		url: "<?php print SITE_URL; ?>/doReceive",
		success:function(response) {
			$("#receive_results").html(response);
		}
	});
}
</script>
<?php } ?>
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