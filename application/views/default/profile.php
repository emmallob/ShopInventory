<?php
	if(!$admin_user->logged_InControlled()) { die(require("login.php")); }
	if($admin_user->lock_user_screen()) { die(require("lockscreen.php")); }
	if(!$admin_user->changed_password_first()) { die(require("change_password.php")); }
	
$PAGETITLE="Profile"; 
require "TemplateHeader.php";

#initializing
$user_access = true;
$admin_access = false;
$super_admin_access = false;
$super_super_admin_access = false;
#confirm that a user id is parsed 
if(isset($SITEURL[1]) and ($admin_user->return_username() != $SITEURL[1]) and ($admin_user->confirm_admin_user() == true)) {
	$user_id = ucfirst(xss_clean($SITEURL[1]));
} else {
	$user_id = ucfirst(xss_clean($admin_user->return_username()));
}

$admin_logged = ucfirst($admin_user->return_username());

#confirm that an administrator has logged in
if($_SESSION[":lifeAdminRole"] == 1) {
	$user_access = true;
	$admin_access = true;
}

#confirm that a supper administrator has logged in
if(($_SESSION[":lifeAdminRole"] == 1001)) {
	$user_access = true;
	$admin_access = true;
	$super_admin_access = true;
}

if(($_SESSION[":lifeAdminRole"] == 1043)) {
	$user_access = true;
	$admin_access = true;
	$super_admin_access = true;
	$super_super_admin_access = true;
}
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
<li class="breadcrumb-item"><a href="<?php print SITE_URL; ?>/administrators">Administrators Lists</a></li>
<li class="breadcrumb-item"><a href="<?php print SITE_URL; ?>/cart"><?php print $PAGETITLE; ?></a></li>
</ul>
</div>
</div>
<div class="page-body">
<div class="">
<div class="col-sm-12">
<div class="card">
<div class="card-header">
<span>Update the information of an administrator.</span>
</div>
<div class="">
<div class="card-block">

<?php 
#confirm that the form has been submitted
if(isset($_POST["firstname"]) and !empty($_POST["firstname"])) {
	#initializing
	$user_available = $available = true;
	#assign variables 
	$lastname = strtoupper(xss_clean($_POST["lastname"]));
	$email = strtoupper(xss_clean($_POST["email"]));
	$oldemail = strtoupper(xss_clean($_POST["oldemail"]));
	$firstname = strtoupper(xss_clean($_POST["firstname"]));
	$password = xss_clean($_POST["password"]);
	$password1 = xss_clean($_POST["password2"]);
	$store_id = (int)xss_clean($_POST["store_id"]);
	
	if(isset($_POST["admin_role"]))
		$admin_role = xss_clean($_POST["admin_role"]);
	else
		$admin_role = (int)$_SESSION[":lifeAdminRole"];
	
	$new_user_id = ucfirst(xss_clean($_POST["user_id"]));
	$old_user_id = ucfirst(xss_clean($user_id));
	
	#mechanism for the user level
	if($admin_role == 1001) {
		$level = "Super Administrator";
	} elseif($admin_role == 1) {
		$level = "Administrator";
	} elseif($admin_role == 2) {
		$level = "Vendor";
	}
	
	
	#confirm that the username does not already exists in the database 
	if($new_user_id != $old_user_id) {
		#confirm that the username is available
		if(count($DB->query("select * from _admin where username='$new_user_id' and admin_deleted='0'")) > 0) {
			print "<div class='alert alert-danger'>Sorry! The username <strong>($new_user_id)</strong> is not available.</div>";
			$user_available = false;
		} else {
			$user_available = true;
		}

	} elseif(strlen($password) > 0 and strlen($password) < 6) {
		print "<div class='alert alert-danger'>Sorry! The password should be at least 6 characters long.</div>";
		$available = false;
	} elseif(strlen($password) > 0 and ($password != $password1)) {
		print "<div class='alert alert-danger'>Sorry! The passwords do not match.</div>";
		$available = false;
	}
	
	#confirm that the username is available
	if($oldemail != $email) {
		if(count($DB->query("select * from _admin where email='$email' and admin_deleted='0'")) > 0) {
			print "<div class='alert alert-danger'>Sorry! The email <strong>($email)</strong> is not available.</div>";
			$available = false;
		} else {
			$available = true;
		}
	}
	
	if((strlen($password) > 1) and (strlen($password) < 6)) {
		print "<div class='alert alert-danger'>Sorry! The password should be at least 6 characters long.</div>";
		$available = false;
	}
	
	#confirm if the username is available
	if($available == true) {
		#still maintain the username and cause it to be unchanged
		if($admin_access == false) {
			$new_user_id = $old_user_id;
		}
		
		#update the information 
		$DB->just_exec("update _admin set firstname='$firstname', lastname='$lastname', fullname='$firstname $lastname', email='$email', level='$level', role='$admin_role' where username='$old_user_id'");
		
		#update the username information 
		if($user_available == true) {
			$DB->just_exec("update _admin set username='$new_user_id' where username='$old_user_id'");
		}
		
		#update all other information that relates to this administrator 
		#if the username will indeed change as issued by the administrator
		if(($admin_access == true) and ($new_user_id != $old_user_id) and ($user_available == true)) {
			#change the administrator activity logs
			$DB->just_exec("update _activity_logs set admin_id='$new_user_id' where admin_id='$old_user_id'");
			$DB->just_exec("update _activity_logs set activity_details='$new_user_id' where activity_details='$old_user_id'");
			$DB->just_exec("update _admin_log_history set username='$new_user_id' where username='$old_user_id'");
			$DB->just_exec("update _admin_request_change set username='$new_user_id' where username='$old_user_id'");
			$DB->just_exec("update _customers set added_by='$new_user_id' where added_by='$old_user_id'");
			$DB->just_exec("update _orders set sold_by='$new_user_id' where sold_by='$old_user_id'");
			$DB->just_exec("update _products set product_owner='$new_user_id' where product_owner='$old_user_id'");
			$DB->just_exec("update _products set modified_by='$new_user_id' where modified_by='$old_user_id'");
			$DB->just_exec("update _receipts set admin_id='$new_user_id' where admin_id='$old_user_id'");
			$DB->just_exec("update _stocks_details set added_by='$new_user_id' where added_by='$old_user_id'");
			$DB->just_exec("update _suppliers set added_by='$new_user_id' where added_by='$old_user_id'");
		}
				
		#update the current session of the user
		if(($admin_user->return_username() == $old_user_id)) {
			$_SESSION[":lifeAdminRole"] = $admin_role;
			$_SESSION[":lifeUsername"] = $new_user_id;
			$_SESSION[":lifeFullname"] = $firstname." ".$lastname;
		}
		
		print "<div class='alert alert-success'>Admin information successfully updated.</div>.";
		
		#confirm that the user has entered a new password 
		if(strlen($password) > 2) {
			#encrypt the user password 
			$npassword = sha1(md5($password1));
			$mailing = new Mails;
			#update the information 
			$DB->just_exec("update _admin set password='$npassword', lastresetdate='".time()."' where username='$new_user_id'");
			#log the user out
			
			#send an email to the new user if the password was changed
			$message = "The password for your ".SITE_NAME." Account - $email (ID: $username) has been successfully changed.<br><br>";
			$message .= "This request was received from ".$functions->get_ip().". If you have not authorized this request, please contact us immediately using the information below.<br><br>";
			$message .= "<strong>Support</strong><br>For any support with respect to your relationship with us you can always contact us directly using the following Information.<br><br><strong>Technical Support:</strong><br><strong>Email Address: </strong>".SITE_EMAIL."<br> <strong>Tel No.: </strong>".$libs->fetch->phone."<br><br>";
			
			#insert the message into the database 
			$DB->just_exec("insert into _emails set from_id='".$admin_user->return_username()."', to_id='$username', sender_email='".SITE_EMAIL."', receiver_email='$email', subject='[".SITE_EMAIL."] Password Changed', message='$message'");
			
			#send the mail 
			$mailing->send_mail2(SITE_NAME, SITE_EMAIL, $firstname." ".$lastname, $email, "Password Changed", $message);
		}
		
		#insert the new information into the activity logs table
		$DB->just_exec("insert into _activity_logs set  store_id='$store_id', date_recorded=now(), admin_id='$admin_logged', activity_page='admin', activity_id='$new_user_id', activity_details='$new_user_id', activity_description='Admin details of $firstname $lastname has been updated.'");
		
		if((strlen($password) > 2) and ($admin_user->return_username() == $new_user_id)) {
			print "<script>setTimeout(function() {window.location.href='".SITE_URL."/login/logout?expired'},1000);</script>";
		} else {
			//print "<script>setTimeout(function() {window.location.href='".SITE_URL."/profile/$new_user_id'},1000);</script>";
		}
		
		
	}
	
}
#fetch the admin information 
$admin_info = $DB->query("select * from _admin where username='$user_id' and activated='1' and role != '1001'");
#another if statement to ensure that the user does not view the details of another person
if(($admin_user->confirm_admin_user() == true) or ($admin_user->return_username() == $user_id)){
#count number found 
if(count($admin_info) > 0) {
	foreach($admin_info as $admin_results) {
?>
<div class="j-wrapper j-wrapper-640">
<form action="" method="post" autocomplete="off" class="j-pro" id="j-pro">
<div class="j-content">
<div>
<label class="j-label">FirstName</label>
<div class="j-unit">
<div class="j-input">
<label class="j-icon-right" for="firstname">
<i class="icofont icofont-ui-user"></i>
</label>
<input style="text-transform:uppercase" type="text" required value="<?php print $admin_results["firstname"]; ?>" id="firstname" name="firstname">
</div>
</div>
</div>
<div>
<label class="j-label">LastName</label>
<div class="j-unit">
<div class="j-input">
<label class="j-icon-right" for="lastname">
<i class="icofont icofont-ui-user"></i>
</label>
<input style="text-transform:uppercase" type="text" required value="<?php print $admin_results["lastname"]; ?>" id="lastname" name="lastname">
</div>
</div>
</div>
<div>
<div>
<label class="j-label">Email</label>
</div>
<div class="j-unit">
<div class="j-input">
<label class="j-icon-right" for="email">
<i class="icofont icofont-envelope"></i>
</label>
<input style="text-transform:uppercase" type="email" value="<?php print $admin_results["email"]; ?>" id="email" name="email">
<input style="text-transform:uppercase" type="hidden" readonly value="<?php print $admin_results["email"]; ?>" id="oldemail" name="oldemail">
</div>
</div>
</div>


<div>
<div>
<label class="j-label ">Username</label>
</div>
<div class="j-unit">
<div class="j-input">
<label class="j-icon-right" for="username">
<i class="icofont icofont-ui-check"></i>
</label>
<input type="text" <?php if($admin_access == false) { print "readonly"; } ?> required value="<?php print $admin_results["username"]; ?>" id="user_id" name="user_id">
<?php if($admin_access == true) { ?>
<div style='margin:10px;padding:10px;font-size:12px;text-align:center; color:#000;background-color:#fff; border:2px solid #856404'>Changing the username will prolong the process in updating system; this is because all information that relates to this username will be updated as well. (Activity History / Products Sold / Stocks Added & Updated / Login History)</div>
<?php } ?>
<?php if($admin_access == false) { ?>
<div style='margin:10px;padding:10px;font-size:12px;text-align:center; color:#000;background-color:#fff; border:2px solid #856404'>Please contact the Administrator in order to change your username</div>
<?php } ?>
</div>
</div>
</div>

<?php if($user_access == true) { ?>
<div>
<div>
<label class="j-label ">Password</label>
</div>
<div class="j-unit">
<div class="j-input">
<label class="j-icon-right" for="password">
<i class="icofont icofont-lock"></i>
</label>
<input type="password" placeholder="Change Admin Password?" id="password" name="password">
</div>
</div>
</div>

<div>
<div>
<label class="j-label ">Confirm Password</label>
</div>
<div class="j-unit">
<div class="j-input">
<label class="j-icon-right" for="password">
<i class="icofont icofont-lock"></i>
</label>
<input type="password" placeholder="Confirm Password" id="password2" name="password2">

</div>
</div>
</div>

<?php if($admin_results["username"] == $admin_user->return_username()) { ?>
<div style='margin:10px;padding:10px;font-size:12px;text-align:center; color:#000;background-color:#fff; border:2px solid #856404'>Your will be automatically logged out if you should change your password</div>
<?php } ?>
<?php } ?>

<input type="hidden" name="store_id" id="store_id" readonly value="<?php print $admin_results["store_id"]; ?>">

<div>
<div>
<label class="j-label ">Admin Role</label>
</div>
<div class="j-unit">
<div class="j-input">
<label class="j-icon-right" for="admin_role">
<i class="fa fa-shekel"></i>
</label>
<select <?php if($admin_access == false) print "disabled"; ?> name="admin_role" id="admin_role">
	<option value="<?php print $admin_results["role"]; ?>">Select Admin Role</option>
	<option <?php if($admin_results["role"] == 1) print "selected"; ?> value="1">Super Administrator</option>
	<option <?php if($admin_results["role"] == 2) print "selected"; ?> value="2">Vendor</option>
</select>
</div>
</div>
</div>


<div class="j-response"></div>

</div>

<div class="j-footer">
<?php if($user_access == true) { ?>
<button type="submit" class="btn btn-success"><li class="fa fa-save"></li> Update Details</button>
<?php } ?>
</div>
</form>
</div>
<?php } }  else { ?>

<?php show_error('Page Not Found', 'Sorry the page you are trying to view does not exist on this server', 'error_404'); ?>

<?php  } } ?>

	



</div>
</div>
</div>
</div>
</div>
</div>
</div>
<?php require "TemplateFooter.php"; ?>