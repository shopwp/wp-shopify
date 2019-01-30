<?php

/*

@link              https://wpshop.io
@since             1.3.4
@package           wp-shopify

@wordpress-plugin
Plugin Name:       WP Shopify
Plugin URI:        https://wpshop.io
Description:       Sell and build custom Shopify experiences on WordPress.
Version:           1.3.4
Author:            WP Shopify
Author URI:        https://wpshop.io
License:           GPL-2.0+
License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
Text Domain:       wp-shopify
Domain Path:       /languages

*/

global $wp_version;

if ( !function_exists('version_compare') || version_compare(PHP_VERSION, '5.6.0', '<' )) {
	wp_die( __("Sorry, WP Shopify requires PHP version 5.6 or higher. Please look through <a href=\"https://wpshop.io/docs/requirements\" target=\"_blank\">our requirements</a> page to learn more. Often times you can simply ask your webhost to upgrade for you. <br><br><a href=" . admin_url('plugins.php') . " class=\"button button-primary\">Back to plugins</a>.", 'wp-shopify') );
}

if ( version_compare($wp_version, '4.7', '<' )) {
	wp_die( __("Sorry, WP Shopify requires WordPress version 4.7 or higher. Please look through <a href=\"https://wpshop.io/docs/requirements\" target=\"_blank\">our requirements</a> page to learn more. Often times you can simply ask your webhost to upgrade for you. <br><br><a href=" . admin_url('plugins.php') . " class=\"button button-primary\">Back to plugins</a>.", 'wp-shopify') );
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
require plugin_dir_path(__FILE__) . 'lib/autoloader.php'; // Our autoloader
require plugin_dir_path(__FILE__) . 'vendor/autoload.php'; // Composer autoloader


/*

Bootstrap everything

*/
use WPS\Bootstrap;


if ( !function_exists("WP_Shopify_Bootstrap") ) {

	function WP_Shopify_Bootstrap() {
		return Bootstrap::run();
	}

}


/*

Performs the plugin bootstrap

*/
$GLOBALS['WP_Shopify'] = WP_Shopify_Bootstrap();


/*

Adds hooks which run on both plugin activation and deactivation.
The actions here are added during Activator->init() and Deactivator-init().

*/
register_activation_hook(__FILE__, function($network_wide) {
	do_action('wps_on_plugin_activate', $network_wide);
});

register_deactivation_hook(__FILE__, function() {
	do_action('wps_on_plugin_deactivate');
});
