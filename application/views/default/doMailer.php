<?php
#call the global function 
global $SITEURL, $config, $DB;

#confirm that the user has parsed this value
IF(ISSET($SITEURL[1])) {
	#load file for validation
	load_core('security');
	load_helpers('url_helper');
	$encrypt = load_class('encrypt', 'libraries');
	
	# THIS SECTION CALLS THE LIST OF DOCTORS OR HEALTH PRACTITIONERS IN 
	# THE DATABASE THIS SECTION WILL ALSO EMPLOY THE USE OF SEARCH PATTERNS
	IF(($SITEURL[1] == "doCallMails") AND ISSET($_POST["doCallMail"]) AND ISSET($_POST["Key"])) {
		
		$inKey = xss_clean($_POST["Key"]);
		$inId = $session->userdata('fd_unique_id');
		
		$queryDB = $DB->query("SELECT * FROM ".EMAIL_TABLE." WHERE slug='$inKey'");
		
		IF($DB->num_rows($queryDB) > 0) {
			
			FOREACH($queryDB AS $queryResult) {
				?>
				<script>
					$("#m_sender").val('<?php PRINT $queryResult["sent_from"]; ?>');
					$("#m_receiver").val('<?php PRINT $queryResult["send_to"]; ?>');
					$("#m_subject").text('<?php PRINT $encrypt->decode($queryResult["subject"], $queryResult["slug"]); ?>');
					$("#m_content").html('<?php PRINT $encrypt->decode($queryResult["body"], $queryResult["slug"]); ?>');
				</script>
				<?PHP 
			}
			
		}
	}
}