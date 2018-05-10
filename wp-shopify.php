<?php

/*

@link              https://wpshop.io
@since             1.1.2
@package           wp-shopify

@wordpress-plugin
Plugin Name:       WP Shopify
Plugin URI:        https://wpshop.io
Description:       Sell and build custom Shopify experiences on WordPress
Version:           1.1.2
Author:            WP Shopify
Author URI:        https://wpshop.io
License:           GPL-2.0+
License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
Text Domain:       wp-shopify
Domain Path:       /languages

*/

if ( !function_exists('version_compare') || version_compare(PHP_VERSION, '5.6.0', '<' )) {
	exit;
}

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
	exit;
}

include_once('lib/autoloader.php');

use WPS\Config;
use WPS\Frontend;
use WPS\Backend;
use WPS\Hooks;
use WPS\Products_General;
use WPS\Collections;
use WPS\AJAX;
use WPS\WS;
use WPS\CPT;
use WPS\I18N;
use WPS\License;
use WPS\Checkouts;
use WPS\Admin_Menus;
use WPS\Admin_Notices;
use WPS\Deactivator;
use WPS\Activator;
use WPS\Templates;

/*

Begins execution of the plugin.

Since everything within the plugin is registered via hooks,
kicking off the plugin from this point in the file does
not affect the page life cycle.

*/
if ( !class_exists('WP_Shopify') ) {

	final class WP_Shopify {

		protected static $instantiated = null;


		/*

		Initialize the class

		*/
		public function __construct() {

			do_action('wps_before_bootstrap');

			$this->init_hooks();

			do_action('wps_after_bootstrap');

		}


		/*

		Creates a new class if one hasn't already been created.
		Ensures only one instance is used.

		*/
		public static function instance() {

			if (is_null(self::$instantiated)) {
				self::$instantiated = new self();
			}

			return self::$instantiated;

		}


		/*

		Stop Cloning

		*/
		public function __clone() {
			_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'wp-shopify' ), '2.1' );
		}


		/*

		Prevent Unserializing class instances

		*/
		public function __wakeup() {
			_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'wp-shopify' ), '2.1' );
		}


		/*

		Get Hooks Class

		*/
		public static function Hooks() {
			return Hooks::instance(new Config());
		}


		/*

		Get Frontend Class

		*/
		public static function Frontend() {
			return Frontend::instance(new Config());
		}


		/*

		Get Backend Class

		*/
		public static function Backend() {
			return Backend::instance(new Config());
		}


		/*

		Get Custom Post Types Class

		*/
		public static function CPT() {
			return CPT::instance(new Config());
		}


		/*

		Get Activator Class

		*/
		public static function Activator() {
			return Activator::instance(new Config());
		}


		/*

		Get Deactivator Class

		*/
		public static function Deactivator() {
			return Deactivator::instance(new Config());
		}


		/*

		Get License Class

		*/
		public static function License() {
			return License::instance(new Config());
		}


		/*

		Get I18N Class

		*/
		public static function I18N() {
			return I18N::instance(new Config());
		}


		/*

		Get Checkouts Class

		*/
		public static function Checkouts() {
			return Checkouts::instance(new Config());
		}


		/*

		Get Admin Menus Class

		*/
		public static function Admin_Menus() {
			return Admin_Menus::instance(new Config());
		}


		/*

		Get Admin Notices Class

		*/
		public static function Admin_Notices() {
			return Admin_Notices::instance();
		}


		/*

		Get Templates Class

		*/
		public static function Templates() {
			return Templates::instance(new Config());
		}


		/*

		Init Hooks

		*/
		public function init_hooks() {

			register_activation_hook(__FILE__, [self::Activator(), 'on_activation']);

			$Hooks = self::Hooks();
			$Frontend = self::Frontend();
			$Backend = self::Backend();
			$CPT = self::CPT();

			$Deactivator = self::Deactivator();
			$License = self::License();
			$I18N = self::I18N();
			$Checkouts = self::Checkouts();
			$Admin_Menus = self::Admin_Menus();
			$Admin_Notices = self::Admin_Notices();
			$Templates = self::Templates();


			$Deactivator->init();
			$License->init();
			$I18N->init();
			$Backend->init();
			$Frontend->init();
			$Checkouts->init();
			$Admin_Menus->init();
			$Admin_Notices->init();

			// Establishes all of our template hooks
			$Templates->init();

			$CPT->init();
			$Hooks->init();

		}

	}

}


/*

Let's go!
Wrapping this in a conditional will prevent fatal error on plugin activation

*/
if (!function_exists("WP_Shopify_Bootstrap")) {

	function WP_Shopify_Bootstrap() {
		return WP_Shopify::instance();
	}

}

$GLOBALS['WP_Shopify'] = WP_Shopify_Bootstrap();
