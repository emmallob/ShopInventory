<?php
	if(!$admin_user->logged_InControlled()) { die(require("login.php")); }
	if($admin_user->lock_user_screen()) { die(require("lockscreen.php")); }
	if(!$admin_user->changed_password_first()) { die(require("change_password.php")); }
	
	$PAGETITLE="Add New Stock"; 
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
<li class="breadcrumb-item"><a href="<?php print SITE_URL; ?>/products">Products Lists</a></li>
<li class="breadcrumb-item"><a href="<?php print SITE_URL; ?>/cart"><?php print $PAGETITLE; ?></a></li>
</ul>
</div>
</div>


<?php $notices->notice_board(); ?>



<div class="page-body">
<div class="">
<div class="col-sm-12">




<div class="card">
<div class="card-header">
<span>These are the current products that the customer has decided to purchase. Please review the products before you proceed.</span>
</div>
<div class="">
<div class="card-block">


<?php
#check if the product does exists
//include the update file
if($admin_user->confirm_admin_user() == true) {
	//include the update file
	load_file(
		array(
			'stocks'=>'updates'
		)
	);
}

// initializing
$pagename = $pageslug = $pagecontent = $pageprice = $quantity = $details = $pagealias = $statusnew = "";
$quantity = 1;
$pageprice = $dpageprice = 0.00;

if(isset($_GET["msg"])):
if(xss_clean($_GET["msg"])==1):
show_msg("Success", "Product Stock Information was successfully updated.");
elseif(xss_clean($_GET["msg"])==0):
show_msg("Error", "Sorry there was an error updating page information.");
elseif(xss_clean($_GET["msg"])==2):
show_msg("Error", "Sorry all fields are required");
elseif(xss_clean($_GET["msg"])==3):
show_msg("Success", "Product Stock Information was successfully inserted.");
endif;
endif;
?>


<style>.col-form-label {text-transform:uppercase;font-weight:bolder}</style>
 

<?php if($admin_user->confirm_admin_user() == true) { ?>
 
<form id="main" method="post" SITEURL="" enctype="multipart/form-data">

<div class="col-sm-8">
<div class="row">
<div class="col-sm-12">
<label class="col-sm-12 col-form-label">Product Name</label>
<div class="input-group input-group-primary">
<span class="input-group-addon">
<i class="icofont icofont-presentation"></i>
</span>
<input required name="pagename" onclick="return hidepassfield();" style="font-size:20px;height:35px;text-transform:uppercase" onkeyup="return createLinkAlias();" value="<?php print $pagename; ?>" type="text" class="span8 form-control" id="pagename"  data-provide="typeahead" data-items="4" data-source=''>
</div>
</div>
</div>



<div class="row">
<div class="col-sm-6">
<label class="col-sm-6 col-form-label">Product Category</label>
<div class="input-group input-group-primary">
<span class="input-group-addon">
<i class="icofont icofont-queen"></i>
</span>
<select required onchange="return fetch_category_subs();" class="form-control" id="parent_category2" name="parent_category2">
<?php print $products->category_byid($products->product_by_id("product_slug",$pid)->p_category,"id")->getOption; ?>
<?php $products->categories_limit("parent_id='0' and id!='{$products->product_by_id("product_slug",$pid)->p_category}' and status='1'"); ?>
</select>
</div>
</div>

<div class="col-sm-6">
<label class="col-sm-12 col-form-label">Product Supplier</label>
<div class="input-group input-group-primary">
<span class="input-group-addon">
<i class="icofont icofont-social-shopify"></i>
</span>
<select class="form-control" id="selectError" name="shop_id" data-rel="chosen">
	<option value="0">Select Supplier</option>
	<?php
	#fetch the article categories 
	$cats = $DB->query("select * from `_suppliers` where status='1' and store_id='".STORE_ID."'");
	#count the number of rows 
	if(count($cats) > 0):
	#using foreach loop to fetch the data 
	foreach($cats as $shop_results):					
	?>
	<option value="<?php print $shop_results["supplier_id"]; ?>"><?php print $shop_results["fullname"]; ?></option>
	<?php endforeach; endif; ?>
</select>
</div>
</div>
</div>
</div>


<div class="col-sm-8">
<div class="row">
<div class="col-sm-6">
<label class="col-sm-12 col-form-label">Purchase Price</label>
<div class="input-group input-group-primary">
<span class="input-group-addon">
<i class="icofont icofont-money"></i>
</span>
<input required maxlength="15" name="pageprice" onclick="return hidepassfield();" value="<?php print $pageprice; ?>" type="text" class="form-control autonumber" id="pageprice">
</div>
</div>

<div class="col-sm-6">
<label class="col-sm-12 col-form-label">Selling Price</label>
<div class="input-group input-group-primary">
<span class="input-group-addon">
<i class="icofont icofont-social-prestashop"></i>
</span>
<input required maxlength="15" name="dpageprice" onclick="return hidepassfield();" value="<?php print $dpageprice; ?>" type="text" class="span8 form-control autonumber" id="dpagesource">
</div>
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="col-sm-12 col-form-label">Product Stock Available</label>
<div class="input-group input-group-primary">
<span class="input-group-addon">
<i class="icofont icofont-stock-mobile"></i>
</span>
<input maxlength="5" title="Edit the product quantity under the product stock section" name="quantity" value="<?php print $quantity; ?>" type="number" class="span8 form-control" id="quantity"><br clear="both">
<input readonly name="pageslug" value="<?php print $pagealias; ?>" type="hidden" class="span8 form-control" id="pageslug">
<input type="hidden" name="Product_id" id="Product_id" value="">
</div><hr>
</div>
</div>

<div class="row">
<div class="col-sm-10">
<label class="col-sm-12 col-form-label">Product Description</label>
<div class="input-group input-group-primary">
<span class="input-group-addon">
<i class="icofont icofont-info"></i>
</span>
<textarea class="form-control" style="text-transform:uppercase" onclick="return hidepassfield();" id="pagecontent" name="pagecontent" rows="8"><?php print strip_tags($pagecontent); ?></textarea>
</div>
</div>
</div>


<div class="row">
<div class="col-sm-10">
<label class="col-sm-12 col-form-label">Product Image</label>
<div class="input-group input-group-primary">
<span class="input-group-addon">
<i class="icofont icofont-image"></i>
</span>
<input type="file" class="form-control" name="pageimage[]" onchange="">
<br clear="both">
<input type="file" class="form-control" name="pageimage[]" onchange="">
<br clear="both">
</div>
<small>Only: jpg / png / jpeg, less 1Mb</small><hr>
</div>
</div>






<input type="hidden" name="parsedform">
<input type="hidden" name="productAdd">
<input type="hidden" name="updatepage">
</div>




<div class="form-group row">
<label class="col-sm-1"></label>
<div class="col-sm-12">
<button title="Click to list all products" onclick="window.location.href='<?php print SITE_URL; ?>/products'" type="button" class="btn btn-danger"><i class="ion-ios-arrow-back"></i> Go Back</button>
<button id="stocks_update_form" type="submit" class="btn btn-success m-b-0"><i class="fa fa-save"></i> Add Record</button>
</div>
</div>

</form>
	
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