<?php

/*

WP Shopify

@link              https://wpshop.io
@since             1.1.1
@package           wp-shopify

@wordpress-plugin
Plugin Name:       WP Shopify
Plugin URI:        https://wpshop.io
Description:       Sell and build custom Shopify experiences on WordPress
Version:           1.1.1
Author:            Andrew Robbins
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

		Get Checkouts Class

		*/
		public static function Admin_Menus() {
			return Admin_Menus::instance(new Config());
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

			$Hooks = self::Hooks();
			$Frontend = self::Frontend();
			$Backend = self::Backend();
			$CPT = self::CPT();
			$Activator = self::Activator();
			$Deactivator = self::Deactivator();
			$License = self::License();
			$I18N = self::I18N();
			$Checkouts = self::Checkouts();
			$Admin_Menus = self::Admin_Menus();
			$Templates = self::Templates();

			$Activator->init();
			$Deactivator->init();
			$License->init();
			$I18N->init();
			$Backend->init();
			$Frontend->init();
			$Checkouts->init();
			$Admin_Menus->init();

			// Establishes all of our template hooks
			$Templates->init();


			/*

			Custom Post Types

			*/
			add_action('init', [$CPT, 'wps_post_type_products']);
			add_action('init', [$CPT, 'wps_post_type_collections']);


			/*

			Misc

			*/
			add_action('plugins_loaded', [$Hooks, 'wps_on_update']);
			add_action('pre_get_posts',  [$Hooks, 'wps_content_pre_loop']);
			add_filter('posts_clauses', [$Hooks, 'wps_clauses_mod'], 10, 2);


			/*

			Sidebars

			*/
			add_action('wps_products_sidebar', [$Hooks, 'wps_products_sidebar']);
			add_action('wps_product_single_sidebar', [$Hooks, 'wps_product_single_sidebar']);
			add_action('wps_collections_sidebar', [$Hooks, 'wps_collections_sidebar']);
			add_action('wps_collection_single_sidebar', [$Hooks, 'wps_collection_single_sidebar']);


			/*

			Filters

			*/
			add_action('wps_collections_display', [$Hooks, 'wps_collections_display'], 10, 2);
			add_action('wps_collections_pagination', [$Hooks, 'wps_collections_pagination']);
			add_filter('wps_collections_args', [$Hooks, 'wps_collections_args']);
			add_filter('wps_collections_custom_args', [$Hooks, 'wps_collections_custom_args']);
			add_filter('wps_collections_custom_args_items_per_row', [$Hooks, 'wps_collections_custom_args_items_per_row']);
			add_filter('wps_collection_single_products_heading_class', [$Hooks, 'wps_collection_single_products_heading_class']);

			add_action('wps_products_display', [$Hooks, 'wps_products_display'], 10, 2);
			add_filter('wps_products_pagination_range', [$Hooks, 'wps_products_pagination_range']);
			add_filter('wps_products_pagination_next_link_text', [$Hooks, 'wps_products_pagination_next_link_text']);
			add_filter('wps_products_pagination_prev_link_text', [$Hooks, 'wps_products_pagination_prev_link_text']);

			add_filter('wps_products_pagination_first_page_text', [$Hooks, 'wps_products_pagination_first_page_text']);
			add_filter('wps_products_pagination_show_as_prev_next', [$Hooks, 'wps_products_pagination_show_as_prev_next']);
			add_filter('wps_products_pagination_prev_page_text', [$Hooks, 'wps_products_pagination_prev_page_text']);
			add_filter('wps_products_pagination_next_page_text', [$Hooks, 'wps_products_pagination_next_page_text']);
			add_filter('wps_products_args', [$Hooks, 'wps_products_args']);
			add_filter('wps_products_args_posts_per_page', [$Hooks, 'wps_products_args_posts_per_page']);
			add_filter('wps_products_args_orderby', [$Hooks, 'wps_products_args_orderby']);
			add_filter('wps_products_args_paged', [$Hooks, 'wps_products_args_paged']);
			add_filter('wps_products_custom_args', [$Hooks, 'wps_products_custom_args']);
			add_filter('wps_products_custom_args_items_per_row', [$Hooks, 'wps_products_custom_args_items_per_row']);
			add_filter('wps_products_price_multi', [$Hooks, 'wps_products_price_multi'], 10, 4);
			add_filter('wps_products_price_one', [$Hooks, 'wps_products_price_one'], 10, 2);
			add_action('wps_products_pagination', [$Hooks, 'wps_products_pagination']);
			add_filter('wps_products_related_args', [$Hooks, 'wps_products_related_args']);
			add_filter('wps_products_related_args_posts_per_page', [$Hooks, 'wps_products_related_args_posts_per_page']);
			add_filter('wps_products_related_args_orderby', [$Hooks, 'wps_products_related_args_orderby']);
			add_filter('wps_products_related_custom_args', [$Hooks, 'wps_products_related_custom_args']);
			add_filter('wps_products_related_custom_items_per_row', [$Hooks, 'wps_products_related_custom_items_per_row']);

			add_filter('wps_product_single_thumbs_class', [$Hooks, 'wps_product_single_thumbs_class'], 10, 2);
			add_filter('wps_product_single_price', [$Hooks, 'wps_product_single_price'], 10, 4);
			add_filter('wps_product_single_price_multi', [$Hooks, 'wps_product_single_price_multi'], 10, 4);
			add_filter('wps_product_single_price_one', [$Hooks, 'wps_product_single_price_one'], 10, 3);

		}

	}

}


/*

Let's go!

*/
function WP_Shopify_Init() {
	return WP_Shopify::instance();
}

$GLOBALS['WP_Shopify'] = WP_Shopify_Init();
