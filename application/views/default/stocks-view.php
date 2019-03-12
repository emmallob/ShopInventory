<?php
	if(!$admin_user->logged_InControlled()) { die(require("login.php")); }
	if($admin_user->lock_user_screen()) { die(require("lockscreen.php")); }
	if(!$admin_user->changed_password_first()) { die(require("change_password.php")); }
	
	$PAGETITLE="Stock Details";
	require "TemplateHeader.php";
	
	$sales = load_class('sales', 'models');
	$products = load_class('products', 'models');
	$suppliers = load_class('Suppliers', 'models');
	
	if(isset($SITEURL[1]))
		$pid = xss_clean($SITEURL[1]);
	else
		$pid = "NULLIFY";
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
<div class="\">
<div class="card-block">


<?php

//include the update file
load_file(
	array(
		'stocks_up'=>'updates'
	)
);

#check if the product does exists
if(isset($SITEURL[1]) and preg_match("/^[a-z0-9-]+$/",strtolower($SITEURL[1])) and $products->product_by_id("product_id",strtolower($SITEURL[1]))->p_success):


$pageslug=$products->product_by_id("product_id",strtolower($SITEURL[1]))->p_slug;
$pagealias=$products->product_by_id("product_id",strtolower($SITEURL[1]))->p_slug;
$dpageprice=$products->product_by_id("product_id",strtolower($SITEURL[1]))->p_aprice;
$quantity=(int)$products->product_by_id("product_id",strtolower($SITEURL[1]))->p_quantity;
$pagename=$products->product_by_id("product_id",strtolower($SITEURL[1]))->p_name;
$pageprice=$products->product_by_id("product_id",strtolower($SITEURL[1]))->p_price;
$pagecontent=$products->product_by_id("product_id",strtolower($SITEURL[1]))->p_spec;
$details=$products->product_by_id("product_id",strtolower($SITEURL[1]))->p_details;
$product_idd=$products->product_by_id("product_id",strtolower($SITEURL[1]))->pr_id;
	
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
    
<form id="main" method="post" id="stocks_update_form" SITEURL="" enctype="multipart/form-data">


<div class="col-sm-10">
<div class="row">
<div class="col-sm-12">
<label class="col-sm-12 col-form-label">Product Name</label>
<div class="input-group input-group-primary">
<span class="input-group-addon">
<i class="icofont icofont-presentation"></i>
</span>
<input name="pagename" required onclick="return hidepassfield();" style="font-size:20px;height:35px;" onkeyup="return createLinkAlias();" value="<?php print $pagename; ?>" type="text" class="span8 form-control" id="pagename">
<input name="product_idd" required type="hidden" value="<?php print $product_idd; ?>" id="product_idd">
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
<?php if(isset($_POST['updatepage'])) print $pcategorynew; ?>
<?php print $products->category_byid($products->product_by_id("product_id",$pid)->p_category,"id")->getOption; ?>
<?php $products->categories_limit("parent_id='0' and id!='{$products->product_by_id("product_id",$pid)->p_category}' and status='1'"); ?>
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
	$cats = $DB->query("select * from `_suppliers` where status='1'");
	#count the number of rows 
	if(count($cats) > 0):
	#using foreach loop to fetch the data 
	foreach($cats as $shop_results):					
	?>
	<?php if($products->product_by_id("product_id",$SITEURL[1])->p_supplier==$shop_results["id"]): ?>
	<option selected value="<?php print $shop_results["id"]; ?>"><?php print $shop_results["fullname"]; ?></option>
	<?php else: ?>
	<option value="<?php print $shop_results["id"]; ?>"><?php print $shop_results["fullname"]; ?></option>
	<?php endif; ?>
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
<input required maxlength="15" <?php if(!$admin_user->confirm_admin_user() == true) { ?>readonly<?php } ?> name="pageprice" onclick="return hidepassfield();" value="<?php print $pageprice; ?>" type="text" class="form-control autonumber" id="pageprice">
</div>
</div>

<div class="col-sm-6">
<label class="col-sm-12 col-form-label">Selling Price</label>
<div class="input-group input-group-primary">
<span class="input-group-addon">
<i class="icofont icofont-social-prestashop"></i>
</span>
<input required maxlength="15" <?php if(!$admin_user->confirm_admin_user() == true) { ?>readonly<?php } ?> name="dpageprice" onclick="return hidepassfield();" value="<?php print $dpageprice; ?>" type="text" class="span8 form-control autonumber" id="dpagesource">
</div>
</div>
</div>

<div class="row">
<div class="col-sm-8">
<label class="col-sm-12 col-form-label">Product Stock Available</label>
<div class="input-group input-group-primary">
<span class="input-group-addon">
<i class="icofont icofont-stock-mobile"></i>
</span>
<input required readonly maxlength="5" data-v-min="0" data-v-max="1000.00" title="Edit the product quantity under the product stock section" name="quantity" value="<?php print $quantity; ?>" type="number" class="span8 form-control" id="quantity"><br clear="both">
<input readonly name="pageslug" value="<?php print $pagealias; ?>" type="hidden" class="span8 form-control" id="pageslug">
<input type="hidden" name="Product_id" id="Product_id" value="<?php print $products->product_by_id("product_id",$pid)->p_id; ?>">
</div><small>If you want to add new stocks then <a href="<?php print SITE_URL; ?>/stocks-new/rows/1">ADD STOCK HERE</a> to increase the quantity of this product stock.</small><hr>
</div>
</div>

<div class="row">
<div class="col-sm-10">
<label class="col-sm-12 col-form-label">Product Description</label>
<div class="input-group input-group-primary">
<span class="input-group-addon">
<i class="icofont icofont-info"></i>
</span>
<textarea required style="text-transform:uppercase" class="form-control" onclick="return hidepassfield();" id="pagecontent" name="pagecontent" rows="8"><?php print strtoupper(strip_tags($details)); ?></textarea>
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


<style>.row .col-sm-10 .my-new-product, .form-group li {list-style:none;}
.my-new-product {float:left;border-radius:4px;box-shadow:0px 1px 2px #000;
padding:5px;margin-right:5px;}
</style>

<div class="row">
<div class="col-sm-10">
<?php 
$product_image = $DB->query("select * from _products_images where product_id='{$products->product_by_id("product_id",$SITEURL[1])->p_id}' and status='1' order by id desc limit 7");
foreach($product_image as $image):
?>
<div class='my-new-product' id="image_div_<?php print $image["id"]; ?>">
<img width="120px" height="110px" class="" src="<?php print SITE_URL.'/'.$image["thumbnail"]; ?>" alt="">
<br><span title="View full image" style="cursor:pointer" onclick="open_image('<?php print SITE_URL.'/'.$image["image"]; ?>');" class="alert alert-info icon-eye"></span>
<?php if(count($product_image) > 0): ?> | <span title="Delete this product image" style="cursor:pointer" onclick="remove_image('<?php print $image["id"]; ?>');" class="alert alert-danger ion-trash-a"></span>
<?php endif; ?></div><?php endforeach; ?>
</div>
</div>

<input type="hidden" name="parsedform">
<input type="hidden" name="productEdit">
<input type="hidden" name="updatepage">
</div>



<script>
function open_image(image) {
window.open(image, "Product Image", "height=450,width=650");
}
function remove_image(imgid) {
if(confirm("Are you sure you want to delete this Product Image completely?")) {
$.ajax({
type: "POST",
data: "remove_image&pr_attachment&id="+imgid,
url: "<?php print SITE_URL; ?>/doDelete/userImage",
cache: false,
beforeSend:function() {
$(".process_state").html('<br clear="all"><div class="please-wait" style="font-family:Verdana, Geneva, sans-serif; font-size:12px; color:black;height:10px;">Please wait <img src="<?php print $config->base_url(); ?>assets/images/loadings.gif" align="absmiddle" /></div><br clear="all">');
}, success:function(response) {
$("#image_div_"+imgid).hide();
}
});
}}

</script>

<div class="form-group row">
<label class="col-sm-2"></label>
<div class="col-sm-12">
<button title="Click to list all stocks available" onclick="window.location.href='<?php print SITE_URL; ?>/stocks'" type="button" class="btn btn-danger"><i class="ion-ios-arrow-back"></i> Go Back</button>
<?php if($admin_user->confirm_admin_user() == true) { ?>
<button id="stocks_update_form" type="submit" class="btn btn-success m-b-0"><i class="fa fa-save"></i> Update Record</button>
<?php } ?>
</div>
</div>

</form>

	
	
	


<?php else: ?>
<?php show_error('Page Not Found', 'Sorry the page you are trying to view does not exist on this server', 'error_404') ?>
<?php endif; ?>




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