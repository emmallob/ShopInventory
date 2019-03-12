<?php
#start a new session
if (!isset($_SESSION)) {
    session_start();
}
if(!$admin_user->logged_InControlled()) { die(require("login.php")); }
if($admin_user->lock_user_screen()) { die(require("lockscreen.php")); }
if(!$admin_user->changed_password_first()) { die(require("change_password.php")); }

$PAGETITLE="Checkout"; 
require "TemplateHeader.php";

$sales = load_class('sales', 'models');
$products = load_class('products', 'models');
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
<li class="breadcrumb-item"><a href="<?php print SITE_URL; ?>/products">Products Lists</a></li>
<li class="breadcrumb-item"><a href="<?php print SITE_URL; ?>/cart">Cart</a></li>
<li class="breadcrumb-item"><a href="<?php print SITE_URL; ?>/checkout"><?php print $PAGETITLE; ?></a></li>
</ul>
</div>
</div>


<?php $notices->notice_board(); ?>


<div class="page-body">
<div class="row">
<div class="col-sm-12">




<div class="card">
<div class="card-header">
<span></span>
</div>
<div class="card-block">
<div class="dt-responsive table-responsive">


		

		<!-- Shopping Cart List -->
        <div class="col-md-12">
		
		
		
		
		
		<style>.chose_customer {border:solid #f4f4f4 1px;padding:5px;margin:5px;cursor:pointer}</style>
		 
		
		<?php if(isset($_SESSION["CaaAPcart_array"]) and count($_SESSION["CaaAPcart_array"]) > 0):  ?>
		 <?php if(!isset($_SESSION["Main_guest_Id2"])):  ?>
			<div class="fuelux">
			<!-- Register Form -->
			<div class="col-sm-12 login-register-form">
			  <div class="row">
				<div class="table-responsive">
				<table border="1" width="1000px" class=" table-bordered table-cart">
				<thead>
					<tr>
						<td width="50%"><h4>New Customer</h4></td>
						<td><div class="title"><strong>Already Registered Customer?</strong></div></td>
					</tr>
				</thead>
				<tbody>
					<tr>
					<td>
						<div class="form-group col-sm-12">
							<label for="regionInput">Select Option(*)</label>
							<select id="checkout_options" class="form-control selectpicker" style="width:300px" id="regionInput" data-live-search="false">
								<option value="register"> Register New Account </option>
							</select>
							<p>Creating an account for the customer will help in future.</p>
						</div>
						<div class="form-group col-sm-12">
						<div id="form_processing1"></div>
						<button onclick="return checkout_options();" class="btn btn-info"><i class="fa fa-long-arrow-right"></i> Continue</button>
						</div>
					</td>
					<td>
						<div class="col-sm-12">
						  <div class="form-group">
							  <label for="searchField">Search by Name / Contact</label>
							  <input required onkeyup="return search_user1();" type="text" name="searchField" class="form-control" id="searchField" placeholder="Search by name or contact number">
							</div>
							
							<input type="hidden" name="redir" value="<?php print SITE_URL; ?>/checkout">
							<button type="submit" onclick="return search_user1();" name="loginButton1" class="btn btn-success"><i class="fa fa-long-arrow-right"></i> Search User</button>
							<div class="list_registered_customers"></div>
						</div>
					</td>
					</tr>
					<tr>
						<td colspan="2" align="center"><div onclick="return continue_unregistered();" class="btn btn-danger"><i class="fa fa-long-arrow-left"></i> CONTINUE UNREGISTERED <i class="fa fa-long-arrow-right"></i></div></td>
					</tr>
				</tbody>
				</table>
					
				</div>
				</div>
			
			<script>
			function checkout_options() {
				var opt = $("#checkout_options").val();
				$.ajax({
					type: "POST",
					data: "process_form&opt="+opt,
					url: "<?php print SITE_URL; ?>/doProcess",
					beforeSend:function() {
						$("#form_processing").html('<div class="please-wait" style="font-family:Verdana, Geneva, sans-serif; font-size:12px; color:black;height:10px;">Please wait <img src="<?php print SITE_URL; ?>/assets/images/loadings.gif" align="absmiddle" /></div><br clear="all">');
					}, success:function(response) {
						$("#form_processing1").html(response);
					}
				});
			}
			function continue_unregistered() {
				$.ajax({
					type: "POST",
					data: "process_form&continue_unregistered",
					url: "<?php print SITE_URL; ?>/doProcess",
					success:function(response) {
						window.location.href="<?php print SITE_URL; ?>/checkout";
					}
				});
			}
			</script>
			</div>
		  <?php else: #start a new session
			if (!isset($_SESSION)){ session_start(); }
			$customer_id=$_SESSION["Main_guest_Id2"];
			?>
			<table>
			<tr>
			<td width="30%">
			<div class="col-md-12">
			<div class="card">
			<?php if($_SESSION["Main_guest_Id2"] != 100) { ?>
			<div class="card-block">
			<h5>CUSTOMER SELECTED DETAILS</h5>
			</div>
			<?php } ?>
			<div class="card-block reset-table p-t-0">
			<div class="table-responsive">
			
			
			<table class="table">
			<tbody>
			<?php if($session->userdata("Main_guest_Id2") != 100) { ?>
			<tr>
			<td style="width: 1%;"><button data-toggle="tooltip" title="Store" class="btn btn-info btn-xs"><i class="ion-ios-cart"></i></button></td>
			<td><a href="<?php print SITE_URL; ?>/customers-view/<?php print @$customer_id; ?>">
			<?php print @$customers_list->_list_customer_by_id($customer_id)->c_fullname; ?>
			</a></td>
			</tr>
			<tr>
			<td><button data-toggle="tooltip" title="Date Added" class="btn btn-info btn-xs"><i class="ion-calendar"></i></button></td>
			<td><?php print @$customers_list->_list_customer_by_id($customer_id)->c_contact; ?> / <?php print @$customers_list->_list_customer_by_id($customer_id)->c_contact2; ?></td>
			</tr>
			<tr>
			<td><button data-toggle="tooltip" title="Payment Method" class="btn btn-info btn-xs"><i class="fa ion-card fa-fw"></i></button></td>
			<td><?php print @$customers_list->_list_customer_by_id($customer_id)->c_address; ?></td>
			</tr>
			<tr>
			<td><button data-toggle="tooltip" title="Payment Reference ID" class="btn btn-info btn-xs"><i class="fa ion-card fa-fw"></i></button></td>
			<td><strong>BALANCE#</strong> GH &#8373;<?php print @$customers_list->_list_customer_by_id($customer_id)->c_balance; ?></td>
			</tr>
			<tr>
			<td><button title="Payment Reference ID" class="btn btn-info btn-xs"><i class="ion-network"></i></button></td>
			<td><strong>OUTSTANDING#</strong> GH &#8373;<?php print @$customers_list->_list_customer_by_id($customer_id)->c_outstanding; ?></td>
			</tr>
			<?php } ELSE { ?>
			<tr>
			<td style="width: 1%;"><button data-toggle="tooltip" title="Store" class="btn btn-info btn-xs"><i class="fa fa-user"></i></button></td>
			<td>Cash Customer</td>
			</tr>
			<?php } ?>
			</tbody>
			</table>
			
			
			</div>
			</div>
			</div>
			</div>
			</td>
			<td valign="top">			
			<div class="col-sm-12">
				<div class="form-group">
				<h3 for="searchField">SEARCH CUSTOMER</h3>
				<input style="width:300px" required onkeyup="return search_user1();" type="text" name="searchField" class="form-control" id="searchField" placeholder="Search by name or contact number">
				</div>
				<input type="hidden" name="redir" value="<?php print SITE_URL; ?>/checkout">
				<button type="submit" onclick="return search_user1();" name="loginButton1" class="btn btn-success"><i class="fa fa-long-arrow-right"></i> Search Customer</button>
				<div class="list_registered_customers"></div>
				<HR>
				<button class="btn btn-primary" onclick="window.location.href='<?php print SITE_URL; ?>/customers-new?checkout&ref=checkout'">NEW CUSTOMER?</button>
			</div>			
			</td>
			<td valign="top">	
				<div class="form-group">
				<h3 for="searchField">HISTORY</h3>
				<div class="dt-responsive table-responsive">
				<table id="simpletable" class="table table-striped table-bordered nowrap">
				<thead>
				<tr>
				<th>ORDER ID</th>
				<th>TOTAL PRICE</th>
				<th>DATE SOLD</th>
				<th>SOLD BY</th>
				</tr>
				</thead>
				<tbody>
				<?php
				#list the user orders 
				IF($customer_id != 0) {
					$list_orders = $DB->query("select * from _customers_orders where customer_id='$customer_id' order by id desc limit 5");
					#list them here 
					foreach($list_orders as $results) {
				?>
					<tr>
					<td><a href="<?php print SITE_URL; ?>/sales-view/<?php print $results["unique_id"]; ?>"><?php print $results["unique_id"]; ?></a></td>
					<td><span class="font-medium">GH¢ <?php print $results["total_price"]; ?></span></td>
					<td class="mailbox-date"><?php print date("d M Y", strtotime($results["date_added"])); ?></td>
					<td class="mailbox-date"><?php print $results["sold_by"]; ?></td>
					</tr>
					<?php } ?>
					<?php } ?>
				</tbody>
				</table>
				</div>
				</div>
			</td>
			</tr>
			</table>

						
			
			<?php 
			$cartOutput = "";
			$cartTotal = 0;
			$pp_checkout_btn = '';
			$product_id_array = '';
			if(isset($_SESSION["CaaAPcart_array"]) and count($_SESSION["CaaAPcart_array"]) > 0):
				// Start the For Each loop
				$i = 0; 
				foreach ($_SESSION["CaaAPcart_array"] as $each_item) { 
					#split the cart session 
					$item_id = $each_item["item_id"];
					#query the database
					$sql = $DB->query("SELECT * FROM _products WHERE id='$item_id' LIMIT 1");
					#fetch the results
					foreach ($sql as $row) {
						$product_name = $row["product_name"];
						$price = $row["product_actuals"];
						$details = $row["product_details"];
						$pid = $row["product_id"];
						$slug = $row["product_slug"];
						$qty_avail = $row["product_quantity"];
					}
					#mechanism for price totalling
					$pricetotal = $price * $each_item['quantity'];
					$cartTotal = $pricetotal + $cartTotal;
					setlocale(LC_MONETARY, "en_US");
					$pricetotal = number_format($pricetotal, 2);
					// Dynamic Checkout Btn Assembly
					$x = $i + 1;
					// Create the product array variable
					$product_id_array .= "$item_id-".$each_item['quantity'].","; 
					#
					$product_image = $DB->query("select * from _products_images where product_id='{$item_id}' and status='1' order by rand() limit 1");
					if(count($product_image) > 0) {
						foreach($product_image as $image):
							$image = SITE_URL."/{$image["image"]}";
						endforeach;
					} else {
						$image = SITE_URL."/assets/images/product1.png";
					}
					
					if($qty_avail < $each_item['quantity']) {
						$class_set = "alert alert-danger";
					} else {
						$class_set = "";
					}
					
					// Dynamic table row assembly
					$cartOutput .= "
						<tr>
					  <td class=\"img-cart\">
						<a href=\"".SITE_URL."/stocks-view/$slug\">
						  <img alt=\"Product\" width=\"50px\" src=\"$image\" class=\"img-thumbnail\">
						</a>
					  </td>
					  <td width='45%'>
						<p><a href=\"".SITE_URL."/stocks-view/$slug\" class=\"d-block\">$product_name</a></p>
					  </td>
					  <td class=\"input-qty\"><input maxlength='3' type=\"number\" id=\"quantity$item_id\" value=\"{$each_item['quantity']}\" class=\"form-control text-center $class_set\" /></td>
					  <td class=\"unit\">GH&#8373; $price</td>
					  <td class=\"sub\">GH&#8373; $pricetotal</td>
					  <td class=\"action\" width='8%'>
						<a href=\"javascript:return false\" title=\"Change this product quantity\" class=\"btn btn-primary\" onclick=\"adjust_item_quantity('$item_id','reload')\"><i class=\"ion-android-refresh\"></i></a>&nbsp;
						<a href=\"javascript:return false\" title=\"Delete this product from cart.\" class=\"btn btn-danger\" onclick=\"return remove_item_('$i', '$item_id', true);\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"Remove\"><i class=\"ion-ios-trash\"></i></a>
					  </td>
					</tr>";
						
					$i++; 
				} 
				setlocale(LC_MONETARY, "en_US");
				$cartTotalSum = number_format($cartTotal, 2);
				$cartTotalSum2 = $cartTotal;
				$cartTotalField = "<tr><td colspan=\"4\" class=\"text-right\">Sub Total</td><td colspan=\"2\"><b>GH&#8373; $cartTotalSum</b></td></tr>";
				$cartTotalField .="<tr><td colspan=\"4\" class=\"text-right\">Discount</td><td colspan=\"2\"><input maxlength='10' onkeyup='return tabulate_overall();' type='number' class='form-control' id='discount_price' value='0.00'><input maxlength='10' type='hidden' class='form-control' id='sub_total' value='$cartTotalSum2'></td></tr>";
				/* confirm store wants to operate a sale on credit system */ if(CREDIT_SALES == 1) {
				$cartTotalField .="<tr><td colspan=\"4\" class=\"text-right\">Amount</td><td colspan=\"2\"><input maxlength='10' type='number' class='form-control' id='amount' value='0'></td></tr>";
				} else {
				$cartTotalField .="<input maxlength='10' type='hidden' class='form-control' id='amount' value='$cartTotal'>";
				}
				$cartTotalField .="<tr><td colspan=\"4\" class=\"text-right\">Overall Total</td><td colspan=\"2\"><strong><span id='overall_total_span'>GH&#8373; $cartTotalSum</span><input maxlength='10' type='hidden' class='form-control' id='over_over_total' value='$cartTotal'></strong></td></tr>";
			else:
				$cartTotalField ="<tr><td colspan=\"6\" class=\"text-left\"><b>Your Shopping Cart is Empty</b></td></tr>";
			endif;
			?>
			
			<!-- Shopping Cart List -->
			<div class="col-md-12" style="margin-bottom:150px">
			  <div class="table-responsive">
				<table class="table table-bordered table-cart">
				  <thead>
					<tr>
					  <th>Product</th>
					  <th>Description</th>
					  <th>Quantity</th>
					  <th>Unit price</th>
					  <th>SubTotal</th>
					  <th>Action</th>
					</tr>
				  </thead>
				  <tbody>                
					<?php print $cartOutput; ?>				
					<?php print $cartTotalField; ?>				
				  </tbody>
				</table>			
			  </div>
				<?php 
				#run a loop through the items that has been added to cartOutput
				#confirm that the quantity that is about to be sold is below that which is 
				#in stock. if not then do not show the submit button 
				$submit_button_show = true;
			  
				if(isset($_SESSION["CaaAPcart_array"]) and count($_SESSION["CaaAPcart_array"]) > 0) {
				  
					foreach ($_SESSION["CaaAPcart_array"] as $final_each_item) {
						#split the cart session 
						$item_id = $final_each_item["item_id"];
						#query the database
						$sql = $DB->query("SELECT * FROM _products WHERE id='$item_id' LIMIT 1");
						#fetch the results
						foreach ($sql as $row) {
							$quantity_avail = $row["product_quantity"];
						}
						
						if($final_each_item['quantity'] > $quantity_avail) {
							$submit_button_show = false;
						}
					}
					
				}
				?>
				<?php if($submit_button_show == true) { ?>
				<?php /* confirm store wants to operate a sale on credit system */ if(CREDIT_SALES == 1) { ?>
				<button style="float:left" id="confirm_credit" onclick="return confirm_credit()" class="btn btn-success">BOUGHT ON CREDIT</button>
				<?php } ?>
				<button style="float:right" id="confirm_payment" onclick="return confirm_payment()" class="btn btn-success">CONFIRM PAYMENT & PRINT RECEIPT</button>
				<?php } else {
					print "<div class='alert alert-danger'>Sorry! The quantity set for one of the products exceeds the quantity in stock. Please verify and retry again.</div>";
				} ?>
			  
			  <br clear="both"><br clear="both"><div class="confirm_payment_result"></div>
			</div>
			<!-- End Shopping Cart List -->
		
		
		
		
		
		  <?php endif; ?>
		  
		  <?php else: ?>
		  
		  <div class="table-responsive">
            <table class="table table-bordered table-cart">
              <thead>
                <tr>
                  <th>Product</th>
                  <th>Description</th>
                  <th>Quantity</th>
                  <th>Unit price</th>
                  <th>SubTotal</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
				<tr><td colspan="6" class="text-left"><b>Your Shopping Cart is Empty</b></td></tr>
              </tbody>
            </table>
          </div>
		  <nav aria-label="Shopping Cart Next Navigation">
            <ul class="pager">
              <li class="previous"><a class="btn btn-primary" href="<?php print SITE_URL; ?>/products"><span aria-hidden="true">&larr;</span> Continue Shopping</a></li>
            </ul>
          </nav>
		  <?php endif; ?>
		  
		  
		  <script>
			<?php if(isset($_SESSION["Main_guest_Id2"])) {  ?>
			function tabulate_overall() {
				var sub = parseInt($("#sub_total").val()) || 0;
				var dis = parseInt($("#discount_price").val()) || 0;
				total = sub-dis;
				$("#overall_total_span").text("GH¢ "+total+".00");
				$("#over_over_total").val(total);
				<?php /* confirm store wants to operate a sale on credit system */ if(CREDIT_SALES == 0) { ?>
				$("#amount").val(total);
				<?php } ?>
				if(dis > sub) {
					$("#confirm_payment").attr("disabled", true);
				} else {
					$("#confirm_payment").removeAttr("disabled", false);
				}
				if(dis == 0) {
					$("#over_over_total").val("<?php print $cartTotalSum; ?>");
					$("#amount").val("<?php print $cartTotalSum; ?>");
				}
			}
			<?php } ?>
			function search_user1() {
				$(".list_registered_customers").html("");
				var fi = $("#searchField").val();
				if(fi.length > 1) {
					$.ajax({
						type: "POST",
						data: "process_form&searchField&fi="+fi,
						url: "<?php print SITE_URL; ?>/doProcess",
						beforeSend:function() {
							$(".list_registered_customers").html('<div class="please-wait" style="font-family:Verdana, Geneva, sans-serif; font-size:12px; color:black;height:10px;"><br>Please wait <img src="<?php print SITE_URL; ?>/assets/images/loadings.gif" align="absmiddle" /></div><br clear="all">');
						}, success:function(response) {
							$(".list_registered_customers").html(response);
						}
					});
				}				
			}
			function chose_customer(cid) {
				$.ajax({
					type: "POST",
					data: "process_form&cust_sel&cid="+cid,
					url: "<?php print SITE_URL; ?>/doProcess",
					success:function(response) {
						window.location.href='<?php print SITE_URL; ?>/checkout';
					}
				});
			}
			<?php if(ISSET($_SESSION["Main_guest_Id2"])) {  ?>
			function confirm_payment() {
				$("#confirm_payment").attr("disabled", true);
				$("#confirm_credit").attr("disabled", true);
				var dis = parseInt($("#discount_price").val());
				var total = $("#over_over_total").val();
				var amount = $("#amount").val();
				if(confirm("Are you sure you are want to proceed?")) {				
					
					if(amount != total) {
						alert("Sorry! The amount being paid is less than the overall total of GHs"+total);
						$("#confirm_payment").removeAttr("disabled", false);
						$("#confirm_credit").removeAttr("disabled", false);
					} else {					
						$.ajax({
							type: "POST",
							data: "process_form&submit_a_cart&type=SALE&dis="+dis+"&amount="+amount,
							url: "<?php print SITE_URL; ?>/doSubmit",
							beforeSend:function() {
								$(".confirm_payment_result").html('<div class="please-wait alert alert-warning" style="font-family:Verdana, Geneva, sans-serif; font-size:12px; color:black;height:50px;">Please wait while the process is completed. <img src="<?php print SITE_URL; ?>/assets/images/loadings.gif" align="absmiddle" /></div>');
							},success:function(response) {
								$(".confirm_payment_result").html(response);
							}
						});
					}
				} else {
					$("#confirm_payment").removeAttr("disabled", false);
					$("#confirm_credit").removeAttr("disabled", false);
				}
			}
			<?php /* confirm store wants to operate a sale on credit system */ if(CREDIT_SALES == 1) { ?>
			function confirm_credit() {
				$("#confirm_credit").attr("disabled", true);
				$("#confirm_payment").attr("disabled", true);
				var dis = parseInt($("#discount_price").val());
				var amount = $("#amount").val();
				if(confirm("Are you sure you are want to proceed?")) {				
					$.ajax({
						type: "POST",
						data: "process_form&submit_a_cart&type=CREDIT&dis="+dis+"&amount="+amount,
						url: "<?php print SITE_URL; ?>/doSubmit",
						beforeSend:function() {
							$(".confirm_payment_result").html('<div class="please-wait alert alert-warning" style="font-family:Verdana, Geneva, sans-serif; font-size:12px; color:black;height:50px;">Please wait while the process is completed. <img src="<?php print SITE_URL; ?>/assets/images/loadings.gif" align="absmiddle" /></div>');
						},success:function(response) {
							$(".confirm_payment_result").html(response);
						}
					});
				} else {
					$("#confirm_payment").removeAttr("disabled", false);
					$("#confirm_credit").removeAttr("disabled", false);
				}
			}
			<?php } ?>
			<?php } ?>
			</script>
		  
		  
		  
		 
		  
        </div>
        <!-- End Shopping Cart List -->


</div>
</div>
</div>


<style>

.pager > li > a,
.pager > li > span {
  color: #fff;
  border-bottom: 2px solid #d6d8d9;
  border-radius: 0;
}
.pager {
  padding-left: 0;
  margin: 20px 0;
  text-align: center;
  list-style: none;
}
.pager li {
  display: inline;
  border-radius: 0;
}
.pager li > a,
.pager li > span {
  display: inline-block;
  padding: 5px 14px;
  border: 1px solid #ddd;
  border-radius: 15px;
}
.pager li > a:hover,
.pager li > a:focus {
  text-decoration: none;
}
.pager .next > a,
.pager .next > span {
  float: right;
}
.pager .previous > a,
.pager .previous > span {
  float: left;
}
.pager .disabled > a,
.pager .disabled > a:hover,
.pager .disabled > a:focus,
.pager .disabled > span {
  color: #777;
  cursor: not-allowed;
}
.pager:before,
.pager:after,
.panel-body:before,
.panel-body:after,
.modal-header:before,
.modal-header:after,
.modal-footer:before,
.modal-footer:after {
  display: table;
  content: " ";
}
.pager:after,
.panel-body:after,
.modal-header:after,
.modal-footer:after {
  clear: both;
}
</style>



<link rel="stylesheet" type="text/css" href="<?php print SITE_URL; ?>/assets/bower_components/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="<?php print SITE_URL; ?>/assets/pages/data-table/css/buttons.dataTables.min.css">
</div>
</div>
</div>
</div>
<?php require "TemplateFooter.php"; ?>