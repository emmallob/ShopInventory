<?php 
#start a new session
global $admin_user, $session;

#check what the user wants to do
if(isset($_POST["process_form"]) and ($admin_user->confirm_admin_user() == true)) {	
	if(isset($_POST["store_id"]) and isset($_POST["change_id"])){
		#clean the store_id that has been parsed
		$store_id = xss_clean($_POST["store_id"]);
		#explode the content and get only the id
		$store_id = explode("_", $store_id);
		#get the part that is needed 
		$store_id = $store_id[1];
		#confirm that it is a numeric figure
		if(preg_match("/^[0-9]+$/", $store_id)) {
			#set the new session for the store id
			$session->set_userdata(":storeID", $store_id);
			#print a success message
			print "The Store ID has successfully being changed. You will now manage the selected store. Thank you";
		}
	}
}
?>