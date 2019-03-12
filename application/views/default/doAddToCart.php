<?php 
#start a new session
if (!isset($_SESSION)) {
    session_start();
}

global $admin_user, $session;

#initializing
$cartOutput = $cartTotal = "";

if($admin_user->logged_InControlled() == true) {

#check if the user wants to add to cart
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//       Section 1 (if user attempts to add something to the cart from the product page)
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if(isset($_POST["add_item"]) and !isset($_POST["adjust_item"]) and !isset($_POST["remove_item"]) and !isset($_POST["item_to_adjust"])){
	#confirm that an integer was parsed
	if(preg_match("/^[0-9]+$/", $_POST["item"])){
		#update the item with a specified quantity in mind 
		if(isset($_POST["quantity"]) and preg_match("/^[0-9]+$/", $_POST["quantity"])) {
			$qty = xss_clean($_POST["quantity"]);
		} else {
			$qty = 1;
		}
		#confirm that the quantity is more than 0
		if($qty == 0) {
			print "<span class='alert alert-danger' style='width:100%'>Sorry! The item quantity cannot be zero.</span>";
		} else {
		
			#processing of the form parsed by the user 
			$pid = (int)xss_clean($_POST["item"]);
			$wasFound = false;
			$i = 0;
			
			// If the cart session variable is not set or cart array is empty
			if (!isset($_SESSION["CaaAPcart_array"]) || count($_SESSION["CaaAPcart_array"]) < 1) { 
				// RUN IF THE CART IS EMPTY OR NOT SET
				$_SESSION["CaaAPcart_array"] = array(
					0 => array("item_id" => $pid, "quantity" => $qty)
				);
				
			} else {
				// RUN IF THE CART HAS AT LEAST ONE ITEM IN IT
				foreach ($_SESSION["CaaAPcart_array"] as $each_item) { 
					$i++;
					while (list($key, $value) = each($each_item)) {
						if ($key == "item_id" && $value == $pid) {
							// That item is in cart already so let's adjust its quantity using array_splice()
							array_splice($_SESSION["CaaAPcart_array"], $i-1, 1, array(array("item_id" => $pid, "quantity" => $each_item['quantity'] + $qty)));
							$wasFound = true;
						} // close if condition
					} // close while loop
				} // close foreach loop
				if ($wasFound == false) {
				   array_push($_SESSION["CaaAPcart_array"], array("item_id" => $pid, "quantity" => $qty));
				}
			}
			
			#print a notice to the user
			print "<span class='alert alert-success' style='width:100%'>Product was added to cart.</span>";
			
		}
	}
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//       Section 2 (if user chooses to empty their shopping cart)
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if (isset($_POST["empty_cart"])) {
    $session->unset_userdata('CaaAPcart_array');
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//       Section 3 (if user chooses to adjust item quantity)
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if (isset($_POST["adjust_item"]) and isset($_POST["item_to_adjust"]) and preg_match("/^[0-9]+$/", $_POST["item_to_adjust"])
	 and isset($_POST["quantity"]) and preg_match("/^[0-9]+$/", $_POST["quantity"]) and !isset($_POST["add_item"]) and !isset($_POST["remove_item"]) 
) {
    // execute some code
	$item_to_adjust = (int)xss_clean($_POST["item_to_adjust"]);
	$quantity = (int)xss_clean($_POST["quantity"]);
	$quantity = preg_replace('#[^0-9]#i', '', $quantity); // filter everything but numbers
	if ($quantity >= 100) { $quantity = 99; }
	if ($quantity < 1) { $quantity = 1; }
	if ($quantity == "") { $quantity = 1; }
	$i = 0;
	#confirm that the quantity is more than 0
	if($quantity == 0) {
		print "<span class='alert alert-danger' style='width:100%'>Sorry! The item quantity cannot be zero.</span>";
	} else {
			
		foreach ($_SESSION["CaaAPcart_array"] as $each_item) { 
			$i++;
			while (list($key, $value) = each($each_item)) {
				if ($key == "item_id" && $value == $item_to_adjust) {
					// That item is in cart already so let's adjust its quantity using array_splice()
					array_splice($_SESSION["CaaAPcart_array"], $i-1, 1, array(array("item_id" => $item_to_adjust, "quantity" => $quantity)));
					
				} // close if condition
			} // close while loop
		} // close foreach loop
		
		print "<span class='alert alert-success' style='width:100%'>Product quantity has been adjusted.</span>";
	}
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//       Section 4 (if user wants to remove an item from cart)
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if (!isset($_POST["add_item"]) and !isset($_POST["adjust_item"]) and isset($_POST["remove_item"]) and isset($_POST["index_to_remove"]) && $_POST["index_to_remove"] != "" and preg_match("/^[0-9]+$/", $_POST["index_to_remove"]) and isset($_POST["_item_id"])) {
    // Access the array and run code to remove that array index
 	$key_to_remove = (int)$functions->clean_words($_POST["index_to_remove"]);
	$item_id = (int)$functions->clean_words($_POST["_item_id"]);
	if (count($_SESSION["CaaAPcart_array"]) <= 1) {
		unset($_SESSION["CaaAPcart_array"]);
		$session->unset_userdata('CaaAPcart_array');
	} else {
		unset($_SESSION["CaaAPcart_array"]["$key_to_remove"]);
		sort($_SESSION["CaaAPcart_array"]);
	}
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//       Section 5 (if user chooses to empty their shopping cart)
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if (isset($_POST["run_counter"])) {
	if(isset($_SESSION["CaaAPcart_array"]) and isset($_SESSION["OverAllCartTotal"])):
		print count($_SESSION["CaaAPcart_array"]) . " item(s) - GH&#8373;". $_SESSION["OverAllCartTotal"];;
	else:
		print "0 item(s) - GH&#8373;0.00";
	endif;
}


$cartOutput = "";
$cartTotal = 0;
$pp_checkout_btn = '';
$product_id_array = '';
# check if the user wants to list the cart information 
if(isset($_POST["list_cart_info"]) and !isset($_POST["remove_item"]) and !isset($_POST["add_item"])):
	 # check if the session is set and its not empty
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
			$product_image = $DB->query("select * from _products_images where product_id='{$item_id}' and status='1' limit 1");
			if(count($product_image) > 0) {
				foreach($product_image as $image):
					$image = SITE_URL."/{$image["image"]}";
				endforeach;
			} else {
				$image = SITE_URL."/assets/images/product1.png";
			}
			// Dynamic table row assembly
			$cartOutput .= "<li><div class=\"media\">
				<img class=\"d-flex align-self-center\" src=\"$image\" alt=\"\">
				<div class=\"media-body\">
				<h5 class=\"notification-user\"><a href=\"".SITE_URL."/product-details/$slug\" class=\"media-heading\">$product_name</a></h5>
				<p class=\"notification-msg\"></p>
				<span class=\"notification-time\">QTY: {$each_item['quantity']} @ GH&#8373; $price</span>
				<a style='color:#ff4000;font-weight:bolder' href=\"javascript:return false;\" onclick=\"return remove_item_('$i', '$item_id', false);\" data-toggle=\"tooltip\" title=\"Remove\"><i class=\"fa fa-remove\"></i></a></div>
				</div>
				</div></li>";
				
			$i++; 
		} 
		setlocale(LC_MONETARY, "en_US");
		$_SESSION["OverAllCartTotal"] = $cartTotal = number_format($cartTotal, 2);
		$cartOutput .= "</div><div style='padding-left:50px;' class=\"subtotal-cart\">Subtotal: <span>GH&#8373; $cartTotal</span></div>";
		$cartOutput .= "<div class=\"text-center\">
						  <div aria-label=\"View Cart and Checkout Button\">
							<button onclick=\"window.location.href='".SITE_URL."/cart'\" class=\"btn btn-primary btn-sm\" type=\"button\"><i class=\"ion-ios-cart\"></i> View Cart</button>
							<button onclick=\"window.location.href='".SITE_URL."/checkout'\" class=\"btn btn-success btn-sm\" type=\"button\"><i class=\"ion-checkmark\"></i> Checkout</button>
						  </div>
					  </div>";
		
		print $cartOutput;
		
	else:
		print "</div><div class=\"alert alert-success\" class=\"subtotal-cart\">The Shopping Cart is Currently Empty.</div>";
	endif;
endif;


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//       Section 5 (calculate the subtotal)
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if (isset($_POST["tabulate_sub_total"]) and isset($_POST["item_id"])) {
	#assign a variable for the item id
	if(isset($_POST["item_id"]) and preg_match("/^[0-9]+$/", $_POST["item_id"])) {
		#assign variables 
		$item_id =xss_clean($_POST["item_id"]);
		$products = load_class('products', 'models');
		
		#print the new subtotal 
		print $products->session_quantity($item_id)->subtotal;
	}
}


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//       Section 5 (calculate the subtotal)
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if (isset($_POST["tabultate_total_price"]) and isset($_POST["total_price"])) {
	#print the new subtotal 
	print "GH¢ ".$session->userdata('OverAllCartTotal');
}

}
?>