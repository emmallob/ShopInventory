<?php 
#start a new session
global $admin_user;

$backup_system = load_class('stocks', 'models');

#check what the user wants to do
if(isset($_POST["process_form"]) and ($admin_user->confirm_admin_user() == true)) {	
	if($admin_user->confirm_super_user()) {
		$store_id = 0;
	} else {
		$store_id = $db->return_store_id();
	}
	if(isset($_POST["backup_system"])){
		$file = config_item('update_folder')."db_$store_id".random_string('alnum', 45).".sql";
		$backup = $backup_system->backup_system($file);
		if($backup) {
			print "Database System was successfully backedup";
		} else {
			print "There was an error while trying to update the system.";
		}
	}
}
?>