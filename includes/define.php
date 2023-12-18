<?php

if ( ! defined('ABSPATH') ) {
	exit;
}

define('STL_WOO_PLUGIN_DIR', WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'store-locations' . DIRECTORY_SEPARATOR);
define('STL_WOO_PLUGIN_INC_DIR', STL_WOO_PLUGIN_DIR . 'includes'. DIRECTORY_SEPARATOR);
define('STL_WOO_PLUGIN_ADMIN', STL_WOO_PLUGIN_DIR . 'src' . DIRECTORY_SEPARATOR . 'Admin' .DIRECTORY_SEPARATOR);
define('STL_WOO_PLUGIN_PUBLIC', STL_WOO_PLUGIN_DIR . 'src' . DIRECTORY_SEPARATOR . 'Public' .DIRECTORY_SEPARATOR);
$plugin_url = plugins_url('',__FILE__);
$plugin_url = str_replace('/includes', '/assets', $plugin_url);
define('STL_WOO_PLUGIN_DIR_CSS', $plugin_url.'/css/');
define('STL_WOO_PLUGIN_DIR_JS', $plugin_url.'/js/');
define('STL_WOO_PLUGIN_IMAGES', $plugin_url . '/images/');
/* Includes file */
// if( is_file(STL_WOO_PLUGIN_INC_DIR . 'data.php')) {
// 	require_once STL_WOO_PLUGIN_INC_DIR . 'data.php';
// }

if (is_file(STL_WOO_PLUGIN_INC_DIR . 'functions.php')) {
	require_once STL_WOO_PLUGIN_INC_DIR . 'functions.php';
}

cm5_include_folder(STL_WOO_PLUGIN_ADMIN, 'STL_WOO_PLUGIN_');
cm5_include_folder(STL_WOO_PLUGIN_PUBLIC, 'STL_WOO_PLUGIN_Public_');
