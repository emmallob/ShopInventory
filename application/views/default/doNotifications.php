<?php 
#initial 
global $DB, $functions, $libs;

if($admin_user->logged_InControlled() == true) { 

#check if the user is logged in
if(isset($_POST["remove_notice"]) and isset($_POST["type"]) and isset($_POST["item_id"])) {
	#get the items and their values
	$type = xss_clean($_POST["type"]);
	$item_id = xss_clean($_POST["item_id"]);
	
	#check if the type is the login notification
	if($type == "login") {
		#update the user login notification
		$DB->query("update _admin set last_login_attempts='1' where username='$item_id' and store_id='".$DB->return_store_id()."'");
		#add up the the users activity history
		$DB->query("insert into _activity_logs set full_date=now(), date_recorded=now(), admin_id='$item_id', activity_page='login-notice', activity_id='$item_id', activity_details='$item_id', activity_description='A login attempt notification that was sent to you was removed.'");
	}
	if($type == "pass_request") {
		#update the user login notification
		$DB->query("delete from _admin_request_change where username='$item_id' and store_id='".$DB->return_store_id()."'");
		#add up the the users activity history
		$DB->query("insert into _activity_logs set full_date=now(), date_recorded=now(), admin_id='$item_id', activity_page='password-change-notice', activity_id='$item_id', activity_details='$item_id', activity_description='A password change notification that was sent to you was removed.'");
	}
	#CHECK IF THE USER WANT TO LOGIN TO A NEW ACCOUNT 
	if($type == "multiple_attempt") {
		#update the user login notification
		//$DB->query("update _login_attempt set lastlogin=now(), attempts='0' where username='$item_id' and store_id='".$DB->return_store_id()."'");
		$DB->query("update _login_attempt set lastlogin=now(), attempts='0' where username='$item_id'");
	}
}

}
?>