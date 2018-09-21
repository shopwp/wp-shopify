<?php

/*

@link              https://wpshop.io
@since             1.2.4
@package           wp-shopify

@wordpress-plugin
Plugin Name:       WP Shopify
Plugin URI:        https://wpshop.io
Description:       Sell and build custom Shopify experiences on WordPress.
Version:           1.2.4
Author:            WP Shopify
Author URI:        https://wpshop.io
License:           GPL-2.0+
License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
Text Domain:       wp-shopify
Domain Path:       /languages

*/

if ( !function_exists('version_compare') || version_compare(PHP_VERSION, '5.6.0', '<' )) {
	wp_die( __("Sorry, WP Shopify requires PHP version 5.6 or higher. Please look through <a href=\"https://wpshop.io/docs/requirements\" target=\"_blank\">our requirements</a> page to learn more. Often times you can simply ask your webhost to upgrade for you. <br><br><a href=" . admin_url('plugins.php') . " class=\"button button-primary\">Back to plugins</a>.", 'wp-shopify') );
}

// If this file is called directly, abort.
if ( !defined('WPINC') ) {
	die;
}

// If this file is called directly, abort.
if ( !defined('ABSPATH') ) {
	die;
}


/*

Autoload everything

*/
require_once('lib/autoloader.php'); // Our autoloader
require_once('vendor/autoload.php'); // Composer autoloader


/*

Bootstrap everything

*/
use WPS\Bootstrap;

if ( !function_exists("WP_Shopify_Bootstrap") ) {

	function WP_Shopify_Bootstrap() {
		return Bootstrap::run();
	}

}


$GLOBALS['WP_Shopify'] = WP_Shopify_Bootstrap();


register_activation_hook(__FILE__, function() {
	do_action('wps_on_plugin_activate');
});

register_deactivation_hook(__FILE__, function() {
	do_action('wps_on_plugin_deactivate');
});
