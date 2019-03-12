<?php
	if(!$admin_user->logged_InControlled()) { die(require("login.php")); }
	if($admin_user->lock_user_screen()) { die(require("lockscreen.php")); }
	if(!$admin_user->changed_password_first()) { die(require("change_password.php")); }
	
$PAGETITLE="Add Admin"; 
require "TemplateHeader.php";

#initializing
$user_access = false;
$admin_access = false;
$super_admin_access = false;
#confirm that a user id is parsed 
if(isset($ACTION[1])) {
	$user_id = xss_clean($ACTION[1]);
} else {
	$user_id = xss_clean($admin_user->return_username());
}

$admin_logged = $admin_user->return_username();

#confirm that an administrator has logged in
if($_SESSION[":lifeAdminRole"] == 1) {
	$user_access = true;
	$admin_access = true;
}

#confirm that a supper administrator has logged in
if($_SESSION[":lifeAdminRole"] == 1001) {
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

<?php $notices->notice_board(); ?>

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

#confirm that an admin wants to use this form 
if($admin_access ==  true) {

$firstname=$lastname=$email=$username=$admin_role="";

load_helpers(ARRAY('string_helper','email_helper'));

#confirm that the form has been submitted
if(isset($_POST["firstname"]) and !empty($_POST["firstname"])) {
	#initializing
	$available = true;
	#assign variables 
	$lastname = strtoupper(xss_clean($_POST["lastname"]));
	$email = strtoupper(xss_clean($_POST["email"]));
	$firstname = strtoupper(xss_clean($_POST["firstname"]));
	$password = xss_clean("1234567");
	$admin_role = xss_clean($_POST["admin_role"]);
	$username = strtoupper(xss_clean($_POST["username"]));
	$store_id = (int)STORE_ID;
	
	#mechanism for the user level
	if($admin_role == 1001) {
		$level = "Super Administrator";
	} elseif($admin_role == 1) {
		$level = "Administrator";
	} elseif($admin_role == 2) {
		$level = "Vendor";
	}
	
	#confirm that the username is available
	if(count($DB->query("select * from _admin where username='$username' and admin_deleted='0'")) > 0) {
		print "<div class='alert alert-danger'>Sorry! The username <strong>($username)</strong> is not available.</div>";
		$available = false;
	} else {
		$available = true;
	}
	
	#confirm that the username is available
	if((strlen($email) > 2) and(count($DB->query("select * from _admin where email='$email' and admin_deleted='0'"))) > 0) {
		print "<div class='alert alert-danger'>Sorry! The email <strong>($email)</strong> is not available.</div>";
		$available = false;
	}
	
	#confirm if the username is available
	if($available == true) {
		#update the information 
		$DB->just_exec("insert into _admin set store_id='$store_id', firstname='$firstname', lastname='$lastname', fullname='$firstname $lastname', username='$username', email='$email', level='$level', role='$admin_role',date_added=now(),added_by='".$admin_user->return_username()."'");
		
		#update the current session of the user 
	
		print "<div class='alert alert-success'>Admin information successfully inserted.</div>.";
		
		#confirm that the user has entered a new password 
		if(strlen($password) > 2) {
			#encrypt the user password 
			$npassword = sha1(md5($password));
			#update the information 
			$DB->just_exec("update _admin set password='$npassword' where username='$username'");
			#log the user out
		}
		
		#insert the user activity logs 
		$DB->just_exec("insert into _activity_logs set date_recorded=now(), admin_id='$admin_logged', activity_page='admin', activity_id='$username', activity_details='$username', activity_description='Admin details of $firstname $lastname has been inserted into the database.'");
		
		#send an email to the new user 
		$message = "Hello, $firstname $lastname,<br><br>";
		$message .= "An account has been created at <strong>".SITE_NAME."</strong> on your behalf by <strong>$admin_logged</strong>.<br><br>";
		$message .= "The details of your user account are as follows:<br><br>";
		$message .= "<strong>USERNAME:</strong> $username<br>";
		$message .= "<strong>PASSWORD:</strong> 1234567<br><br>";
		$message .= "Please not that you will be prompted to change this password after logging in for the first time.<br><br>";
		$message .= "<a href='".SITE_URL."/login?url=$email'>Click Here</a> to login to your account.<br><br>Thank you.";
		
		#insert the message into the database 
		$DB->just_exec("insert into _emails set from_id='".$admin_user->return_username()."', to_id='$username', sender_email='".SITE_EMAIL."', receiver_email='$email', subject='[".SITE_EMAIL."] Account Details Created', message='$message'");
		
		#send the email
		$mailing->send_mail2(SITE_NAME, SITE_EMAIL, $firstname." ".$lastname, $email, "Account Details Created", $message);
		
		send_email(
			$email, "[".config_item('site_name')."] Admin Account", 
			$message, config_item('site_name'), config_item('site_email'), 
			NULL, 'default', $username
		);
			
		#reload the page
		if(strlen($password) > 2 and ($admin_user->return_username() == $user_id)) {
			print "<script>setTimeout(function() {window.location.href='".SITE_URL."/administrators'},1000);</script>";
		}
	
	}
	
	
}
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
<input style="text-transform:uppercase" type="text" required value="<?php print $firstname; ?>" id="firstname" name="firstname">
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
<input style="text-transform:uppercase" type="text" required value="<?php print $lastname; ?>" id="lastname" name="lastname">
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
<input style="text-transform:uppercase" type="email" value="<?php print $email; ?>" id="email" name="email">
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
<input style="text-transform:uppercase" type="text" required id="username" value="<?php print $username; ?>" name="username">
<div style='margin:10px;padding:10px;font-size:12px;text-align:center; color:#000;background-color:#fff; border:2px solid #856404'>The username should be very unique. This will identify the admin and help to record all activities on the system. (Activity History / Products Sold / Stocks Added & Updated / Login History)</div>
</div>
</div>
</div>

<div>
<div>
<label class="j-label ">Password</label>
</div>
<div class="j-unit">
<div class="j-input">
<label class="j-icon-right" for="password">
<i class="icofont icofont-lock"></i>
</label>
<input type="text" value="1234567" disabled placeholder="Enter Password" id="password" name="password">
<small>The default password for this admin has already been set.</small>
</div>
</div>
</div>


<div>
<div>
<label class="j-label ">Admin Role</label>
</div>
<div class="j-unit">
<div class="j-input">
<label class="j-icon-right" for="admin_role">
<i class="fa fa-shekel"></i>
</label>
<select name="admin_role" id="admin_role">
	<option value="2">Select Admin Role</option>
	<option <?php if($admin_role == 1) print "selected"; ?> value="1">Super Administrator</option>
	<option <?php if($admin_role == 2) print "selected"; ?> value="2">Vendor</option>
</select>
</div>
</div>
</div>


<div class="j-response"></div>

</div>

<div class="j-footer">
<button type="submit" class="btn btn-success"><li class="fa fa-save"></li> Add Record</button>
</div>
</form>
</div>

<?php } ?>
	



</div>
</div>
</div>
</div>
</div>
</div>
</div>
<?php require "TemplateFooter.php"; ?>