<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Common Functions
 *
 * Loads the base classes and executes the request.
 *
 * @package		CodeIgniter
 * @subpackage	CodeIgniter
 * @category	Common Functions
 * @author		EllisLab Dev Team
 * @link		https://codeigniter.com/user_guide/
 */

// ------------------------------------------------------------------------

$language =	array();

if ( ! function_exists('is_php'))
{
	/**
	 * Determines if the current version of PHP is equal to or greater than the supplied value
	 *
	 * @param	string
	 * @return	bool	TRUE if the current version is $version or higher
	 */
	function is_php($version)
	{
		static $_is_php;
		$version = (string) $version;

		if ( ! isset($_is_php[$version]))
		{
			$_is_php[$version] = version_compare(PHP_VERSION, $version, '>=');
		}

		return $_is_php[$version];
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('is_really_writable'))
{
	/**
	 * Tests for file writability
	 *
	 * is_writable() returns TRUE on Windows servers when you really can't write to
	 * the file, based on the read-only attribute. is_writable() is also unreliable
	 * on Unix servers if safe_mode is on.
	 *
	 * @link	https://bugs.php.net/bug.php?id=54709
	 * @param	string
	 * @return	bool
	 */
	function is_really_writable($file)
	{
		// If we're on a Unix server with safe_mode off we call is_writable
		if (DIRECTORY_SEPARATOR === '/' && (is_php('5.4') OR ! ini_get('safe_mode')))
		{
			return is_writable($file);
		}

		/* For Windows servers and safe_mode "on" installations we'll actually
		 * write a file then read it. Bah...
		 */
		if (is_dir($file))
		{
			$file = rtrim($file, '/').'/'.md5(mt_rand());
			if (($fp = @fopen($file, 'ab')) === FALSE)
			{
				return FALSE;
			}

			fclose($fp);
			@chmod($file, 0777);
			@unlink($file);
			return TRUE;
		}
		elseif ( ! is_file($file) OR ($fp = @fopen($file, 'ab')) === FALSE)
		{
			return FALSE;
		}

		fclose($fp);
		return TRUE;
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('list_folder_items'))
{
	/**
	 * List folder items
	 *
	 * list_folder_items($directory) gets all items in a folder and displays them
	 *
	 * @param	string
	 * @return	files array 
	 */
	function list_folder_items($directory)
	{
		if($handle = opendir($directory)) {
		
			while(false !== ($entry = readdir($handle))) {
				
				if($entry != '.' && $entry != '..') {
					
					return $entry."<br>";
					
				}
			}
		}
	}
}

// ----------------------------------------------------------------------
/**
	 * String to Array Function
	 *
	 * This function acts as a singleton. If the string is not an array
	 * it returns the string as a array. Also if it is already an array 
	 * then it will return same to it. 
	 *
	 * @param	string	the class name being requested
	 * @param	string	the directory where the class should be found
	 * @param	mixed	an optional argument to pass to the class constructor
	 * @return	object
	 */
if ( ! function_exists('_str_to_array'))
{
	function _str_to_array($email)
	{
		if ( ! is_array($email))
		{
			return (strpos($email, ',') !== FALSE)
				? preg_split('/[\s,]/', $email, -1, PREG_SPLIT_NO_EMPTY)
				: (array) trim($email);
		}

		return $email;
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('load_class'))
{
	/**
	 * Class registry
	 *
	 * This function acts as a singleton. If the requested class does not
	 * exist it is instantiated and set to a static variable. If it has
	 * previously been instantiated the variable is returned.
	 *
	 * @param	string	the class name being requested
	 * @param	string	the directory where the class should be found
	 * @param	mixed	an optional argument to pass to the class constructor
	 * @return	object
	 */
	function load_class($class, $directory = 'core')
	{
		static $_classes = array();
		
		$class = ucfirst($class);
		
		// Does the class exist? If so, we're done...
		if (isset($_classes[$class]))
		{
			return $_classes[$class];
		}

		$name = FALSE;
		
		// Look for the class in the native system/libraries folder
		foreach (array(BASEPATH, APPPATH) as $path) {
			
			if (file_exists($path.$directory.'/'.$class.'.php')) {
				$name = $class;

				if (class_exists($name, FALSE) === FALSE) {
					
					require_once($path.$directory.'/'.$class.'.php');
					
					break;
				} 
			}
		}

		// Did we find the class?
		if ($name === FALSE) {
			// Note: We use exit() rather than show_error() in order to avoid a
			// self-referencing loop with the Exceptions class
			echo 'Unable to locate the specified class: '.$class.'.php';
			exit(5); // EXIT_UNK_CLASS
		}
		
		// Keep track of what we just loaded
		is_loaded($class);
		
		$_classes[$class] = isset($param)
			? new $name($param)
			: new $name();
		return $_classes[$class];
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('is_loaded'))
{
	/**
	 * Keeps track of which libraries have been loaded. This function is
	 * called by the load_class() function above
	 *
	 * @param	string
	 * @return	array
	 */
	function &is_loaded($class = '')
	{
		static $_is_loaded = array();

		if ($class !== '')
		{
			$_is_loaded[strtolower($class)] = $class;
		}

		return $_is_loaded;
	}
}

// ------------------------------------------------------------------------
if ( ! function_exists('load_core')) {
	
	function load_core($file, $directory = 'core') {		
		// Look for the class in the native system/helpers folder
		foreach (array(BASEPATH) as $path) {
			
			if (file_exists($path.$directory.'/'.$file.'.php')) {				
				require_once($path.$directory.'/'.$file.'.php');				
				break;
			} else {
				echo 'The '.$file.' file does not exist.';
				exit(3); // EXIT_CONFIG
			}
		}
	}
}

// ------------------------------------------------------------------------
if ( ! function_exists('load_library')) {
	
	function load_library($file, $directory = 'libraries') {
		
		// Submit the file name to the _str_to_array($file) function 
		$file = _str_to_array($file);
		
		// Use foreach Loop to get the file 
		foreach($file as $filename) {
			
			// confirm that the strlen($filename) should be more
			// than 3 characters 
			if( strlen($filename) > 3 ) {
				if (file_exists(BASEPATH.$directory.'/'.$filename.'.php')) {				
					require_once(BASEPATH.$directory.'/'.$filename.'.php');				
				} else {
					echo 'The '.ucfirst($filename).' Helper file does not exist.';
					exit(3); // EXIT_CONFIG
				}
			}
		}
		
	}
}

// ------------------------------------------------------------------------
if ( ! function_exists('load_helpers')) {
	
	function load_helpers($file, $directory = 'helpers') {		
		
		// Submit the file name to the _str_to_array($file) function 
		$file = _str_to_array($file);
		
		// Use foreach Loop to get the file 
		foreach($file as $filename) {
			
			// confirm that the strlen($filename) should be more
			// than 3 characters 
			if( strlen($filename) > 3 ) {
				if (file_exists(BASEPATH.$directory.'/'.$filename.'.php')) {				
					require_once(BASEPATH.$directory.'/'.$filename.'.php');				
				} else {
					echo 'The '.ucfirst($filename).' Helper file does not exist.';
					exit(3); // EXIT_CONFIG
				}
			}
			
		}
	}
}

// ------------------------------------------------------------------------
if ( ! function_exists('load_file')) {
	
	function load_file($file) {
		
		// Submit the file name to the _str_to_array($file) function 
		$file = _str_to_array($file);
		
		// Look for the class in the native system/helpers folder
		foreach($file as $filename=>$directory) {
			
			if (file_exists(BASEPATH.$directory.'/'.$filename.'.php')) {				
				require_once(BASEPATH.$directory.'/'.$filename.'.php');	
			} else {
				echo 'The '.$file.' file does not exist.';
				exit(3); // EXIT_CONFIG
			}
		}
	}
}



// ------------------------------------------------------------------------

if ( ! function_exists('get_config'))
{
	/**
	 * Loads the main config.php file
	 *
	 * This function lets us grab the config file even if the Config class
	 * hasn't been instantiated yet
	 *
	 * @param	array
	 * @return	array
	 */
	function &get_config(Array $replace = array())
	{
		static $config;

		if (empty($config))
		{
			$file_path = BASEPATH.'config/config.php';
			$found = FALSE;
			if (file_exists($file_path))
			{
				$found = TRUE;
				require($file_path);
			}

			// Does the $config array exist in the file?
			if ( ! isset($config) OR ! is_array($config))
			{
				echo 'Your config file does not appear to be formatted correctly.';
				exit(3); // EXIT_CONFIG
			}
		}

		// Are any values being dynamically added or replaced?
		foreach ($replace as $key => $val)
		{
			$config[$key] = $val;
		}

		return $config;
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('config_item')) {
	/**
	 * Returns the specified config item
	 *
	 * @param	string
	 * @return	mixed
	 */
	function config_item($item) {
		static $_config;

		if (empty($_config)) {
			// references cannot be directly assigned to static variables, so we use an array
			$_config[0] =& get_config();
		}

		return isset($_config[0][$item]) ? $_config[0][$item] : NULL;
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('is_https')) {
	/**
	 * Is HTTPS?
	 *
	 * Determines if the application is accessed via an encrypted
	 * (HTTPS) connection.
	 *
	 * @return	bool
	 */
	function is_https()
	{
		if ( ! empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off'){
			return TRUE;
		}
		elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https')
		{
			return TRUE;
		}
		elseif ( ! empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off')
		{
			return TRUE;
		}

		return FALSE;
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('is_cli'))
{

	/**
	 * Is CLI?
	 *
	 * Test to see if a request was made from the command line.
	 *
	 * @return 	bool
	 */
	function is_cli()
	{
		return (PHP_SAPI === 'cli' OR defined('STDIN'));
	}
}

function show_error($heading, $message, $template = 'error_general', $status_code = 500)
{
	$ob_level = ob_get_level();
	
	$templates_path = config_item('error_views_path');
	if (empty($templates_path))
	{
		$templates_path = VIEWPATH.'errors'.DIRECTORY_SEPARATOR;
	}

	if (is_cli())
	{
		$message = "\t".(is_array($message) ? implode("\n\t", $message) : $message);
		$template = 'cli'.DIRECTORY_SEPARATOR.$template;
	}
	else
	{
		$message = '<p>'.(is_array($message) ? implode('</p><p>', $message) : $message).'</p>';
		$template = 'html'.DIRECTORY_SEPARATOR.$template;
	}
	
	if (ob_get_level() > $ob_level + 1)
	{
		ob_end_flush();
	}
	return include($templates_path.$template.'.php');
}
	
// ------------------------------------------------------------------------

if ( ! function_exists('show_404'))
{
	/**
	 * 404 Page Handler
	 *
	 * This function is similar to the show_error() function above
	 * However, instead of the standard error template it displays
	 * 404 errors.
	 *
	 * @param	string
	 * @param	bool
	 * @return	void
	 */
	function show_404($page = '', $log_error = FALSE)
	{
		if (is_cli())
		{
			$heading = 'Not Found';
			$message = 'The controller/method pair you requested was not found.';
		}
		else
		{
			$heading = '404 Page Not Found';
			$message = 'The page you requested was not found.';
		}

		// By default we log this, but allow a dev to skip it
		if ($log_error)
		{
			log_message('error', $heading.': '.$page);
		}

		echo show_error($heading, $message, 'error_404', 404);
		exit(4); // EXIT_UNKNOWN_FILE
	}
}


// ------------------------------------------------------------------------

if ( ! function_exists('log_message'))
{
	/**
	 * Error Logging Interface
	 *
	 * We use this as a simple mechanism to access the logging
	 * class and send messages to be logged.
	 *
	 * @param	string	the error level: 'error', 'debug' or 'info'
	 * @param	string	the error message
	 * @return	void
	 */
	function log_message($level, $message)
	{
		static $_log;

		if ($_log === NULL)
		{
			// references cannot be directly assigned to static variables, so we use an array
			$_log[0] = load_class('Log', 'core');
		}

		$_log[0]->write_log($level, $message);
	}
}

function lang_load($langfile, $idiom = '', $return = FALSE, $add_suffix = TRUE, $alt_path = '') {
	
	global $language;
	
	$langfile = str_replace('.php', '', $langfile);

	if ($add_suffix === TRUE)
	{
		$langfile = preg_replace('/_lang$/', '', $langfile).'_lang';
	}

	$langfile .= '.php';

	if (empty($idiom) OR ! preg_match('/^[a-z_-]+$/i', $idiom))
	{
		$config =& get_config();
		$idiom = empty($config['language']) ? 'english' : $config['language'];
	}

	// Load the base file, so any others found can override it
	$basepath = BASEPATH.'language/'.$idiom.'/'.$langfile;
	if (($found = file_exists($basepath)) === TRUE)
	{
		include($basepath);
	}

	// Do we have an alternative path to look in?
	if ($alt_path !== '')
	{
		$alt_path .= 'language/'.$idiom.'/'.$langfile;
		if (file_exists($alt_path))
		{
			include($alt_path);
			$found = TRUE;
		}
	}
	

	if ($found !== TRUE)
	{
		show_error('Unable to load the requested language file: language/'.$idiom.'/'.$langfile);
	}

	if ($return === TRUE)
	{
		return $lang;
	}

	$language = array_merge($language, $lang);

	return TRUE;
}
	
function lang_line($line, $log_errors = TRUE, $filename) {
	
	lang_load($filename);
	
	global $language;
	
	$value = isset($language[$line]) ? $language[$line] : FALSE;

	// Because killer robots like unicorns!
	if ($value === FALSE && $log_errors === TRUE)
	{
		exit('Could not find the language line "'.$line.'"');
	}

	return $value;
}


function method($upper = FALSE)
{
	return ($upper)
		? strtoupper($_SERVER['REQUEST_METHOD'])
		: strtolower($_SERVER['REQUEST_METHOD']);
}
// ------------------------------------------------------------------------

function show_msg($type, $message,$client=null) {
		
	if($type=="Error"):
		$class = "alert alert-error";
	elseif($type=="Success"):
		$class = "alert alert-success";
	else:
		$class = "alert alert-info";
	endif;
	
	if($client==null):
		$button="<button type=\"button\" class=\"close\" data-dismiss=\"alert\" style=\"font-size:13px\">x</button>";
	else:
		$button="";
	endif;
	
	print ("<div class='box-content alerts'>
		<div class=\"$class\" style=\"margin-bottom:10px;font-size:15px;text-align:justify\">
			$button
			$message
		</div>
	</div>");
	
}
	
	
# use this function to calculate whether its a percentage upwards or downwards.
function calculate_percentage($initial_figure, $final_figure) {
	
	if($initial_figure > 0) {
		
		if($final_figure == 0.00)
			$final_figure = $initial_figure;
		
		# find the difference between the two values 
		$difference = $initial_figure - $final_figure;
		
		#calculate the percentage difference 
		$percentage = -1 * (($difference / $initial_figure) * 100);
		
		# check if the initial is greater than the later
		# if initial is greater then its a percentage upwards but if not then its a percentage downwards
		if($percentage >  0)
			$sign = '+';
		else
			$sign = '';
		
		
		return $sign.round($percentage)."%";
	} else {
		return "0%";
	}
	
}