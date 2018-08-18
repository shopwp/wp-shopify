<?php

/*

@link              https://wpshop.io
@since             1.2.1
@package           wp-shopify

@wordpress-plugin
Plugin Name:       WP Shopify
Plugin URI:        https://wpshop.io
Description:       Sell and build custom Shopify experiences on WordPress.
Version:           1.2.1
Author:            WP Shopify
Author URI:        https://wpshop.io
License:           GPL-2.0+
License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
Text Domain:       wp-shopify
Domain Path:       /languages

*/


if ( !function_exists('version_compare') || version_compare(PHP_VERSION, '5.6.0', '<' )) {
	die;
}

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
	die;
}


// Autoloads the plugin classes
include_once('lib/autoloader.php');


use WPS\Bootstrap;


if ( !function_exists("WP_Shopify_Bootstrap") ) {

	function WP_Shopify_Bootstrap() {
		return Bootstrap::run();
	}

}

$GLOBALS['WP_Shopify'] = WP_Shopify_Bootstrap();
