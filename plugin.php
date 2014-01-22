<?php
/*
Plugin Name: Sears/Kmart Ratings & Reviews
Description: Consumes Data from the Sears/Kmart Ratings & Reviews API
Version: 1.0
Author: Dan Crimmins
*/

//Defined paths
define('SHC_RR_PATH', dirname(__FILE__) . '/');
define('SHC_RR_CLASS', SHC_RR_PATH . 'class/');
define('SHC_RR_WIDGETS', SHC_RR_PATH . 'widgets/');
define('SHC_RR_VIEWS', SHC_RR_PATH . 'views/');

//Prefix for settings and stuff
define('SHC_RR_PREFIX', 'shc_rr_');

//Include utilities class
require_once SHC_RR_CLASS . 'rr_utilities.php';

//Register autoload function
spl_autoload_register(array('RR_Utilities', 'autoload'));
 
//Install / Uninstall
register_activation_hook(__FILE__, array('RR_Utilities', 'install'));
register_deactivation_hook(__FILE__, array('RR_Utilities', 'uninstall'));

//Instantiate objects with hooks
RR_Utilities::init();

//Load widgets
RR_Utilities::load_widgets();
