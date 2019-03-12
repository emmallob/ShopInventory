<?php
if(!$admin_user->logged_InControlled()) { die(require("login.php")); }
if($admin_user->lock_user_screen()) { die(require("lockscreen.php")); }
if(!$admin_user->changed_password_first()) { die(require("change_password.php")); }

$PAGETITLE="Shopping Cart"; 
require "TemplateHeader.php";
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
<div class="row">
<div class="col-sm-12">




<div class="card">
<div class="card-header">
<span>These are the current products that the customer has decided to purchase. Please review the products before you proceed.</span>
</div>
<div class="card-block">
<div class="dt-responsive table-responsive">




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
                    <a href=\"".SITE_URL."/stocks-view/$pid\">
                      <img alt=\"Product\" width=\"50px\" src=\"$image\" class=\"img-thumbnail\">
                    </a>
                  </td>
                  <td width='45%'>
                    <p><a href=\"".SITE_URL."/stocks-view/$pid\" class=\"d-block\">$product_name</a></p>
                  </td>
                  <td class=\"input-qty\"><input maxlength='3' type=\"number\" id=\"quantity$item_id\" value=\"{$each_item['quantity']}\" class=\"form-control text-center $class_set\" /></td>
                  <td class=\"unit\">GH&#8373; $price</td>
                  <td><span class=\"sub_$item_id\">GH&#8373; $pricetotal</span></td>
                  <td class=\"action\" width='8%'>
                    <a href=\"javascript:return false\" title=\"Change this product quantity\" class=\"btn btn-primary\" onclick=\"adjust_item_quantity('$item_id')\"><i class=\"ion-android-refresh\"></i></a>&nbsp;
                    <a href=\"javascript:return false\" title=\"Delete this product from cart.\" class=\"btn btn-danger\" onclick=\"return remove_item_('$i', '$item_id', true);\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"Remove\"><i class=\"ion-ios-trash\"></i></a>
                  </td>
                </tr>";
					
				$i++; 
			} 
			setlocale(LC_MONETARY, "en_US");
			$cartTotal = number_format($cartTotal, 2);
			$cartTotal ="<tr><td colspan=\"4\" class=\"text-right\">Total</td><td colspan=\"2\"><b><span class=\"total_all_here\">GH&#8373; $cartTotal</span></b></td></tr>";
		
		else:
			$cartTotal ="<tr><td colspan=\"6\" class=\"text-left\"><b>Your Shopping Cart is Empty</b></td></tr>";
		endif;
		?>
		
		<!-- Shopping Cart List -->
        <div class="col-md-12">
          <div class="table-responsive">
			<nav aria-label="Shopping Cart Next Navigation">
            <ul class="pager">
			  <?php if(isset($_SESSION["CaaAPcart_array"]) and count($_SESSION["CaaAPcart_array"]) > 0): ?>
              <li class="next"><a href="#" onclick="return empty_cart();" class="btn btn-danger"><i class="fa fa-trash-o"></i> Empty Cart</a></li>
			  <?php endif; ?>
            </ul>
			</nav>
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
				<?php print $cartTotal; ?>				
              </tbody>
            </table>			
          </div>		  
          <nav aria-label="Shopping Cart Next Navigation">
            <ul class="pager">
              <li class="previous"><a class="btn btn-info" href="<?php print SITE_URL; ?>/products"><span aria-hidden="true">&larr;</span> Continue Shopping</a></li>
			  <?php if(isset($_SESSION["CaaAPcart_array"]) and count($_SESSION["CaaAPcart_array"]) > 0): ?>
              <li class="next"><a class="btn btn-success" href="<?php print SITE_URL; ?>/checkout">Checkout <span aria-hidden="true">&rarr;</span></a></li>
			  <?php endif; ?>
            </ul>
          </nav>
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