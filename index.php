<?php
# set the names of the directories
$system_folder = "system";
$application_folder = "application";

# Path to the system directory
DEFINE('BASEPATH', $system_folder.DIRECTORY_SEPARATOR);
DEFINE('APPPATH', $application_folder.DIRECTORY_SEPARATOR);
DEFINE('VIEWPATH', $application_folder.DIRECTORY_SEPARATOR);

/*
	replace array indexes:
	1) fix windows slashes
	2) strip up-tree ../ as possible hack attempts
*/
$URL = STR_REPLACE( ARRAY( '\\', '../'), ARRAY( '/',  '' ), $_SERVER['REQUEST_URI'] );

#strip all forms of get data
IF ($offset = STRPOS($URL, '?')) { $URL = SUBSTR($URL, 0, $offset); } ELSE IF ($offset = STRPOS($URL, '#')) {
	$URL = SUBSTR($URL, 0, $offset);
}

/*
	the path routes below aren't just handy for stripping out
	the REQUEST_URI and looking to see if this is an attempt at
	direct file access, they're also useful for moving uploads,
	creating absolute URI's if needed, etc, etc
*/
$chop = -STRLEN(BASENAME($_SERVER['SCRIPT_NAME']));
DEFINE('DOC_ROOT', SUBSTR($_SERVER['SCRIPT_FILENAME'], 0, $chop));
DEFINE('URL_ROOT', SUBSTR($_SERVER['SCRIPT_NAME'], 0, $chop));

# strip off the URL root from REQUEST_URI
IF (URL_ROOT != '/') $URL = SUBSTR($URL, STRLEN(URL_ROOT));

# strip off excess slashes
$URL = TRIM($URL, '/');

# 404 if trying to call a real file
IF ( FILE_EXISTS(DOC_ROOT.'/'.$URL) && ($_SERVER['SCRIPT_FILENAME'] != DOC_ROOT.$URL) && ($URL != '') && ($URL != 'index.php') )
	DIE(show_error('Page Not Found', 'Sorry the page you are trying to view does not exist on this server'));

/*
	If $url is empty of default value, set action to 'default'
	otherwise, explode $URL into an array
*/
$SITEURL = (($URL == '') || ($URL == 'index.php') || ($URL == 'index.html')) ? ARRAY('index') : EXPLODE('/', html_entity_decode($URL));

/*
	I strip out non word characters from $SITEURL[0] as the include
	which makes sure no other oddball attempts at directory
	manipulation can be done. This means your include's basename
	can only contain a..z, A..Z, 0..9 and underscore!
	
	for example, as root this would make:
	pages/default.php
*/

# call the main core function and start processing your document
REQUIRE "system/core/Inventory.php";

$includeFile = config_item('default_view_path').PREG_REPLACE('/[^\w_]-/','',$SITEURL[0]).'.php';

$stores = load_class('stores', 'models');
$admin_user = load_class('users', 'models');

/*CONFIRM THAT THE STORE ID SESSION IS SET */
/*USING THE ID FOR THE STORE OF THE CURRENT ADMIN GET THE INFORMATION THERE OFF*/ 
if($session->has_userdata(":storeID")) {
	define('STORE_ID', $session->userdata(":storeID"));
	define('STORE_NAME', $stores->fetch()->name);
	define('STOCKS_LIMIT', $stores->fetch()->stocks_limit);
	define('DISPLAY_LIMIT', $stores->fetch()->display_limit);
	define('CREDIT_SALES', $stores->fetch()->sell_on_credit);
	define('AUTOMATIC_BACKUP', $stores->fetch()->automatic_backup);
	define('SITE_DATE_FORMAT', $stores->fetch()->site_date_format);
	define('SITE_ACRO', $stores->fetch()->prefix);
}

#Check the site status
GLOBAL $SITEURL;

#check if the file exists
(IS_FILE($includeFile) and FILE_EXISTS($includeFile)) ? INCLUDE($includeFile) : show_error('Page Not Found', 'Sorry the page you are trying to view does not exist on this server');
?>