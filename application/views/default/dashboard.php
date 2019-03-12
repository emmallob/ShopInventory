<?php
	if(!$admin_user->logged_InControlled()) { die(require("login.php")); }
	if($admin_user->lock_user_screen()) { die(require("lockscreen.php")); }
	if(!$admin_user->changed_password_first()) { die(require("change_password.php")); }
	
	$PAGETITLE="Dashboard";
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
<h3>Dashboard</h3>
</div>
<div class="page-header-breadcrumb">
<ul class="breadcrumb-title">
<li class="breadcrumb-item">
<a href="<?php print $config->base_url(); ?>">
<i class="icofont icofont-home"></i>
</a>
</li>
<li class="breadcrumb-item"><a href="<?php print $config->base_url(); ?>">Dashboard</a></li>
</ul>
</div>
</div>


<div class="row">
	<div class="col-md-12">
		
		<?php $notices->notice_board(); ?>
		
		<?php if($admin_user->confirm_super_user() == true) { ?>
		<div class="alert alert-success">
			<h4>WELCOME SUPER ADMINISTRATOR</h4>
			<span>Hello Super Admin, You are currently running as a <strong><?php if(STORE_ID == 0) { ?> BOSSY <?php } else { print STORE_NAME; } ?></strong> Store Admin, hence you can view all items and products that belongs to all Stores on the Website. <br> At your current level you can have access to all information that relates to <strong><?php if(STORE_ID == 0) { ?> any store <?php } else { print STORE_NAME; } ?></strong> and perform functionalities<?php if(STORE_ID == 0) { ?> as if you owned that particular store<?php } else { ?>.<?php } ?>
			<br clear="both"><br clear="both">
			<label><strong>SELECT THE STORE YOU WANT TO MANAGE</strong></label>
			<select onchange="change_store_id();" class="form-control col-md-4" id="change_store_id" name="change_store_id">
				<option value="000012">PLEASE SELECT AN OPTION</option>
				<option selected value="BOSSY_0"> >>> <?php print config_item('site_name'); ?></option>
				<?php 
				/* fetch the list of all stores */
				$fetch_stores = $DB->query("select * from _stores where id!='0'");
				/* count the number of rows found */
				if(count($fetch_stores) > 0) {
					/* initalizing */
					print "<option value='000012'>  ------------------------------------------------------------- </option>";
					/* using foreach loop to get the information */
					foreach($fetch_stores as $list_stores) {
						/* list the stores */
						if($_SESSION[":storeID"] == $list_stores["id"])
							print "<option selected value='{$list_stores["sitename"]}_{$list_stores["id"]}'> >>> {$list_stores["sitename"]}</option>";
						else
							print "<option value='{$list_stores["sitename"]}_{$list_stores["id"]}'> >>> {$list_stores["sitename"]}</option>";
					}
				}
				?>
			</select>
			</span>
		</div>		
		<?php } ?>
		<?php if($notices->stock_out()->stock_alerts == true) { ?>
		<div class="alert alert-danger" id="stock_alert_div">
			<strong>OUT OF STOCK:</strong>
			<span>Hello Admin, <?php print $notices->stock_out()->stock_number; ?> Products are running out of stock. <strong><a href="<?php print $config->base_url(); ?>/stocks-new/rows/<?php print $notices->stock_out()->stock_number; ?>">ADD STOCK</a></strong> now</span>
			<button type="button" class="close" data-dismiss="alert" style="font-size:13px">x</button>
		</div>
		<?php } ?>
		<?php if(($notices->login_attempt($admin_user->return_username())->login_alerts == true) and ($notices->login_attempt($admin_user->return_username())->login_number > 1)) { ?>
		<div class="alert alert-danger" id="login_alert_div">
			<strong>LOGIN ATTEMPTS:</strong>
			<span>Hello <?php print $admin_user->return_username(); ?>, Your account was recently logged into for <strong><?php print $notices->login_attempt($admin_user->return_username())->login_number; ?> times. </li></strong> <li class="fa fa-clock-o"></li> <?php print date(SITE_DATE_FORMAT, strtotime($notices->login_attempt($admin_user->return_username())->login_time)); ?> Was this you? <strong><a class="btn btn-info" href="<?php print $config->base_url(); ?>/profile">SECURE YOUR ACCOUNT </a></strong></span>
			<button type="button" style="cursor:pointer;" class="close alert alert-danger" onclick="return remove_system_notices('login', '<?php print $admin_user->return_username(); ?>', 'login_alert_div')" style="font-size:13px">x</button>
		</div>
		<?php } ?>
		<?php if($notices->password_change($admin_user->return_username())->change_request == true) { ?>
		<div class="alert alert-danger" id="request_change_alert_div">
			<strong>PASSWORD CHANGE NOTICE:</strong>
			<span>You requested for a change of password on <li class="fa fa-clock-o"></li> <?php print date(SITE_DATE_FORMAT, strtotime($notices->password_change($admin_user->return_username())->change_time)); ?>. Report to the Administrator or close this notice</span>
			<button type="button" style="cursor:pointer;" class="close alert alert-danger" onclick="return remove_system_notices('pass_request', '<?php print $admin_user->return_username(); ?>', 'request_change_alert_div')" style="font-size:13px">x</button>
		</div>
		<?php } ?>
		<?php if($notices->password_change_requests($admin_user->return_username())->change_request1 == true) { ?>
		<div class="alert alert-danger" id="request_change_alert_div">
			<strong>PASSWORD CHANGE REQUEST:</strong>
			<span>There are Password Change requests pending. <a class="btn btn-info" href="<?php print $config->base_url(); ?>/review-requests">Review Reqeusts</a></span>
			
		</div>
		<?php } ?>
		<?php if(($notices->locked_account()->locked_acs == true) and ($admin_user->confirm_admin_user() == true)) { ?>
		<div class="alert alert-danger" id="">
			<strong>LOCKED ACCOUNT NOTICE:</strong>
			<span>Some Admins have their accounts locked <li class="fa fa-lock"></li> due to multiple trials.
			<br>
			<?php 
			$stmt2 = $DB->query("select * from _login_attempt where attempts > ".ATTEMPTS_NUMBER);
			
			if (count($stmt2) > 0) {
				foreach($stmt2 as $results2) {
					?>
					<span id="activate_account_<?php print $results2["id"]; ?>"><?php print $results2["username"]; ?></span>
					<button type="button" style="cursor:pointer;" class="close alert alert-danger" onclick="return remove_system_notices('multiple_attempt', '<?php print $results2["username"]; ?>', 'activate_account_<?php print $results2["id"]; ?>')" style="font-size:13px">x</button>
					<?php
				}
			}
			?>
			
		</div>
		<?php } ?>
	</div>
</div>



<div class="page-body">
<div class="row">

<div class="col-md-12 col-xl-3">
<div class="card widget-statstic-card borderless-card">
<div class="card-header">
<div class="card-header-left">
<h5>Current Receipts</h5>
<p class="p-t-10 m-b-0 text-muted">Today Receipts</p>
</div>
</div>
<div class="card-block">
<i class="icofont icofont-users-social st-icon bg-danger txt-lite-color"></i>
<div class="text-left">
<h3 class="d-inline-block">GH&#8373;<?php print number_format((int)$sales->_tabulate_sales_for_today(), 2); ?></h3>
</div>
</div>
</div>
</div>


<div class="col-md-12 col-xl-3">
<div class="card widget-statstic-card borderless-card">
<div class="card-header">
<div class="card-header-left">
<h5>Daily Receipts</h5>
<p class="p-t-10 m-b-0 text-muted">Compared to yesterday</p>
</div>
</div>
<div class="card-block">
<i class="icofont icofont-users-social st-icon bg-warning txt-lite-color"></i>
<div class="text-left">
<h3 class="d-inline-block">GH&#8373;<?php print number_format((((int)$sales->_tabulate_sales("NOW()", 1)) - ((int)$sales->_tabulate_sales("'".$sales->_date_difference(date("Y-m-d"), 1)."'", 1))),0); ?></h3>
<i class="icofont icofont-long-arrow-<?php if(((int)$sales->_tabulate_sales("NOW()", 1)) < ((int)$sales->_tabulate_sales("'".$sales->_date_difference(date("Y-m-d"), 1)."'", 1))) print "down"; else print "up"; ?> f-30 text-success"></i>
<span class="f-right bg-<?php if(((int)$sales->_tabulate_sales("NOW()", 1)) < ((int)$sales->_tabulate_sales("'".$sales->_date_difference(date("Y-m-d"), 1)."'", 1))) print "danger"; else print "success"; ?>"><?php print calculate_percentage((((int)$sales->_tabulate_sales("'".$sales->_date_difference(date("Y-m-d"), 1)."'", 1))),  ((int)$sales->_tabulate_sales("NOW()", 1))); ?></span>
</div>
</div>
</div>
</div>

<div class="col-md-12 col-xl-3">
<div class="card widget-statstic-card borderless-card">
<div class="card-header">
<div class="card-header-left">
<h5>Statistics</h5>
<p class="p-t-10 m-b-0 text-muted">Compared to last week</p>
</div>
</div>
<div class="card-block">
<i class="icofont icofont-presentation-alt st-icon bg-primary"></i>
<div class="text-left">
<h3 class="d-inline-block">GH&#8373; <?php print number_format((((int)$sales->_tabulate_sales("NOW()")) - ((int)$sales->_tabulate_sales("'".$sales->_date_difference(date("Y-m-d"), 7)."'"))),0); ?></h3>
<i class="icofont icofont-long-arrow-<?php if(((int)$sales->_tabulate_sales("NOW()")) < ((int)$sales->_tabulate_sales("'".$sales->_date_difference(date("Y-m-d"), 7)."'"))) print "down"; else print "up"; ?> f-30 text-success"></i>
<span class="f-right bg-<?php if(((int)$sales->_tabulate_sales("NOW()")) < ((int)$sales->_tabulate_sales("'".$sales->_date_difference(date("Y-m-d"), 7)."'"))) print "danger"; else print "success"; ?>"><?php print calculate_percentage((((int)$sales->_tabulate_sales("'".$sales->_date_difference(date("Y-m-d"), 7)."'"))),  ((int)$sales->_tabulate_sales("NOW()"))); ?></span>
</div>
</div>
</div>
</div>

<div class="col-md-6 col-xl-3">
<div class="card widget-statstic-card borderless-card">
<div class="card-header">
<div class="card-header-left">
<h5>Revenue <?php print date("F"); ?></h5>
<p class="p-t-10 m-b-0 text-muted">This month's Receipts revenue</p>
</div>
</div>
<div class="card-block">
<i class="icofont icofont-chart-line st-icon bg-success"></i>
<div class="text-left">
<h3 class="d-inline-block">GH&#8373; <?php print number_format(((int)$sales->_tabulate_sales_by_period("MONTH")), 2); ?></h3>
</div>
</div>
</div>
</div>




<div class="col-md-9">
<div class="card">
<div class="card-block">
<h5>Recent Customer Purchase</h5>
</div>
<div class="card-block reset-table p-t-0">
<div class="table-responsive">
<table class="table">
 <thead>
<tr class="text-uppercase">
<th>ORDER ID</th>
<th>TYPE</th>
<th>TOTAL PRICE</th>
<th>DATE</th>
<th>MANAGE</th>
</tr>
</thead>
<tbody>

<?php 
#
$list_users = $DB->query("select * from _customers_orders where returned='0' and store_id='".STORE_ID."' order by id desc limit 5");
foreach($list_users as $results):
?>
<tr <?php if($results["overall_price"] >  $results["total_paid"]) { print "class='alert alert-danger'"; } ?>>
<td><?php print $results["unique_id"]; ?></td>
<td><strong><?php print $results["sale_type"]; ?></strong></td>
<td><span class="font-medium">GH&#8373;<?php print $results["overall_price"]; ?></span></td>
<td><?php print strtoupper(date(SITE_DATE_FORMAT, strtotime($results["date_added"]))); ?></td>
<td>
<a href='<?php print $config->base_url(); ?>/sales-view/<?php print $results["unique_id"]; ?>' type="button" class="btn btn-success"><i class="ion-eye"></i></a>
<a href='<?php print $config->base_url(); ?>/print-sales/<?php print $results["unique_id"]; ?>' target="_blank" type="button" class="btn btn-info"><i class="fa ion-printer"></i> </a>
</td>
</tr>
<?php endforeach; ?>






</tbody>
</table>
</div>
</div>
</div>
</div>




<div class="col-xl-3 col-md-6">
<div class="user-card-block card">
<div class="card-block">


<?php 
#
$last_client = $DB->query("select * from _customers where status='1' order by lastdate desc limit 1");
if(count($last_client)) {
foreach($last_client as $result) {
?>
<div class="top-card text-center">
<img src="<?php print $config->base_url(); ?>/<?php print $result["image"]; ?>" class="img-responsive" alt="">
</div>
<div class="card-contain text-center p-t-40">
<h5 class="text-capitalize p-b-10"><?php print $result["firstname"]; ?> <?php print $result["lastname"]; ?></h5>
<p class="text-muted"><?php print $result["contact"]; ?></p>
</div>
<div class="card-data m-t-40">
<div class="row">
<div class="col-12 text-center">
<p class="text-muted">Last Purchase</p>
<span>GH&#8373; <?php print $sales->_customer_last_purchase($result["customer_id"])->total_order; ?></span>
</div>
</div>
</div>

<?php } } ?>

</div>
</div>
</div>


<?php if(STORE_ID != 0) { ?>
<div class="col-md-12">
<div class="card">
<div class="card-block">
<h5>RECENT PRODUCTS STOCK</h5>
</div>
<div class="card-block reset-table p-t-0">
<div class="table-responsive">
<table class="table">
<thead>
<tr class="text-uppercase">
<th>Product Code</th>
<th>Product Name</th>
<th>Category</th>
<th>Price</th>
<th>Quantity</th>
<th>Manage</th>
</tr>
</thead>

<tbody>
<?php 
#
$list_products = $DB->query("select * from _products where store_id='".STORE_ID."' order by id desc limit 5");
foreach($list_products as $results):
?>
<tr>
<td><?php print $results["product_id"]; ?></td>
<td><span class="font-medium"><?php print $results["product_name"]; ?></span></td>
<td><?php if($results["product_category"] != 0) : print $products->category_byid($results["product_category"],"id")->getName; endif; ?></td>
<td>GH&#8373; <?php print number_format($results["product_actuals"],2); ?></td>
<td align="center" <?php if($results["product_quantity"] <= STOCKS_LIMIT) { print "style='color:#721c24; background-color:#f8d7da; border-color:#f5c6cb;font-weight:bolder'";} ?>><?php print $results["product_quantity"]; ?></td>
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
<button data-toggle="modal" data-target="#myModal<?php print $results["id"]; ?>" type="button" class="btn btn-success"><i class="ion-ios-cart"></i> SELL</button>
</td>
</tr>
<?php endforeach; ?>
</tbody>

</table>
</div>
</div>
</div>
</div>
<?php } ?>

</div>
</div>

</div>
</div>
</div>
</div>
<?php require "TemplateFooter.php"; ?>