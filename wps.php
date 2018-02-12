<?php

/*

WP Shopify

@link              https://wpshop.io
@since             1.0.46
@package           WPS

@wordpress-plugin
Plugin Name:       WP Shopify
Plugin URI:        https://wpshop.io
Description:       Sell and build custom Shopify experiences on WordPress
Version:           1.0.46
Author:            WP Shopify
Author URI:        https://wpshop.io
License:           GPL-2.0+
License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
Text Domain:       wp-shopify
Domain Path:       /languages

*/

if ( !function_exists('version_compare') || version_compare(PHP_VERSION, '5.3.0', '<' )) {
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


/*

Begins execution of the plugin.

Since everything within the plugin is registered via hooks,
kicking off the plugin from this point in the file does
not affect the page life cycle.

*/
if ( ! class_exists('WP_Shopify') ) {

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

			$Activator->init();
			$Deactivator->init();
			$License->init();
			$I18N->init();
			$Backend->init();
			$Frontend->init();
			$Checkouts->init();
			$Admin_Menus->init();



			/*

			Custom Post Types

			*/
			add_action('init', array($CPT, 'wps_post_type_products'));
			add_action('init', array($CPT, 'wps_post_type_collections'));


			/*

			Frontend

			*/
			add_action('wp_footer', array($Frontend, 'wps_insert_cart_before_closing_body'));
			add_action('wp_footer', array($Frontend, 'wps_notice'));


			/*

			Hooks

			*/
			add_action('plugins_loaded', array($Hooks, 'wps_on_update'));
			add_action('pre_get_posts',  array($Hooks, 'wps_content_pre_loop'));
			add_filter('posts_clauses', array($Hooks, 'wps_clauses_mod'), 10, 2);

			add_filter('wps_collections_args', array($Hooks, 'wps_collections_args'));
			add_filter('wps_collections_custom_args', array($Hooks, 'wps_collections_custom_args'));
			add_filter('wps_collections_custom_args_items_per_row', array($Hooks, 'wps_collections_custom_args_items_per_row'));
			add_action('wps_collections_header', array($Hooks, 'wps_collections_header'));
			add_action('wps_collections_loop_start', array($Hooks, 'wps_collections_loop_start'));
			add_action('wps_collections_loop_end', array($Hooks, 'wps_collections_loop_end'));
			add_action('wps_collections_item_start', array($Hooks, 'wps_collections_item_start'), 10, 3 );
			add_action('wps_collections_item_end', array($Hooks, 'wps_collections_item_end'));
			add_action('wps_collections_item', array($Hooks, 'wps_collections_item'));
			add_action('wps_collections_item_before', array($Hooks, 'wps_collections_item_before'));
			add_action('wps_collections_item_after', array($Hooks, 'wps_collections_item_after'));
			add_action('wps_collections_img', array($Hooks, 'wps_collections_img'));
			add_action('wps_collections_title', array($Hooks, 'wps_collections_title'));
			add_action('wps_collections_no_results', array($Hooks, 'wps_collections_no_results'));
			add_action('wps_collections_sidebar', array($Hooks, 'wps_collections_sidebar'));
			add_action('wps_collections_display', array($Hooks, 'wps_collections_display'), 10, 2);



			add_action('wps_collection_single_start', array($Hooks, 'wps_collection_single_start'));
			add_action('wps_collection_single_header', array($Hooks, 'wps_collection_single_header'));
			add_action('wps_collection_single_img', array($Hooks, 'wps_collection_single_img'));
			add_action('wps_collection_single_content', array($Hooks, 'wps_collection_single_content'));
			add_action('wps_collection_single_products', array($Hooks, 'wps_collection_single_products'),  10, 3 );
			add_action('wps_collection_single_end', array($Hooks, 'wps_collection_single_end'));
			add_action('wps_collection_single_sidebar', array($Hooks, 'wps_collection_single_sidebar'));
			add_action('wps_collection_single_product', array($Hooks, 'wps_collection_single_product'));

			add_action('wps_collection_single_products_list', array($Hooks, 'wps_collection_single_products_list'),  10, 3);

			add_action('wps_collection_single_heading', array($Hooks, 'wps_collection_single_heading'), 10, 3);

			add_filter('wps_collection_single_products_heading_class', array($Hooks, 'wps_collection_single_products_heading_class'));
			add_filter('wps_collection_single_products_heading', array($Hooks, 'wps_collection_single_products_heading'));

			add_filter('wps_products_pagination_start', array($Hooks, 'wps_products_pagination_start'));
			add_filter('wps_products_pagination_end', array($Hooks, 'wps_products_pagination_end'));
			add_filter('wps_products_pagination_first_page_text', array($Hooks, 'wps_products_pagination_first_page_text'));
			add_filter('wps_products_pagination_next_link_text', array($Hooks, 'wps_products_pagination_next_link_text'));
			add_filter('wps_products_pagination_prev_link_text', array($Hooks, 'wps_products_pagination_prev_link_text'));
			add_filter('wps_products_pagination_range', array($Hooks, 'wps_products_pagination_range'));
			add_filter('wps_products_pagination_show_as_prev_next', array($Hooks, 'wps_products_pagination_show_as_prev_next'));
			add_filter('wps_products_pagination_prev_page_text', array($Hooks, 'wps_products_pagination_prev_page_text'));
			add_filter('wps_products_pagination_next_page_text', array($Hooks, 'wps_products_pagination_next_page_text'));

			add_filter('wps_products_args', array($Hooks, 'wps_products_args'));
			add_filter('wps_products_args_posts_per_page', array($Hooks, 'wps_products_args_posts_per_page'));
			add_filter('wps_products_args_orderby', array($Hooks, 'wps_products_args_orderby'));
			add_filter('wps_products_args_paged', array($Hooks, 'wps_products_args_paged'));
			add_filter('wps_products_custom_args', array($Hooks, 'wps_products_custom_args'));
			add_filter('wps_products_custom_args_items_per_row', array($Hooks, 'wps_products_custom_args_items_per_row'));

			add_action('wps_products_header', array($Hooks, 'wps_products_header'));
			add_action('wps_products_loop_start', array($Hooks, 'wps_products_loop_start'));
			add_action('wps_products_loop_end', array($Hooks, 'wps_products_loop_end'));
			add_action('wps_products_item_start', array($Hooks, 'wps_products_item_start'), 10, 3);
			add_action('wps_products_item_end', array($Hooks, 'wps_products_item_end'));
			add_action('wps_products_item', array($Hooks, 'wps_products_item'), 10, 3);
			add_action('wps_products_item_before', array($Hooks, 'wps_products_item_before'), 10, 2);
			add_action('wps_products_item_link_start', array($Hooks, 'wps_products_item_link_start'), 10, 2);
			add_action('wps_products_item_link_end', array($Hooks, 'wps_products_item_link_end'));
			add_action('wps_products_item_after', array($Hooks, 'wps_products_item_after'));
			add_action('wps_products_img', array($Hooks, 'wps_products_img'));
			add_action('wps_products_title', array($Hooks, 'wps_products_title'));
			add_action('wps_products_price', array($Hooks, 'wps_products_price'));
			add_filter('wps_products_price_multi', array($Hooks, 'wps_products_price_multi'), 10, 4);
			add_filter('wps_products_price_one', array($Hooks, 'wps_products_price_one'), 10, 2);




			/*

			Pagination actions

			*/
			add_action('wps_collections_pagination', array($Hooks, 'wps_collections_pagination'));
			add_action('wps_products_pagination', array($Hooks, 'wps_products_pagination'));




			add_action('wps_products_no_results', array($Hooks, 'wps_products_no_results'));
			add_action('wps_products_add_to_cart', array($Hooks, 'wps_products_add_to_cart'));
			add_action('wps_products_meta_start', array($Hooks, 'wps_products_meta_start'));
			add_action('wps_products_quantity', array($Hooks, 'wps_products_quantity'));
			add_action('wps_products_actions_group_start', array($Hooks, 'wps_products_actions_group_start'));
			add_action('wps_products_options', array($Hooks, 'wps_products_options'));
			add_action('wps_products_button_add_to_cart', array($Hooks, 'wps_products_button_add_to_cart'));
			add_action('wps_products_actions_group_end', array($Hooks, 'wps_products_actions_group_end'));
			add_action('wps_products_notice_inline', array($Hooks, 'wps_products_notice_inline'));
			add_action('wps_products_meta_end', array($Hooks, 'wps_products_meta_end'));
			add_action('wps_products_sidebar', array($Hooks, 'wps_products_sidebar'));
			add_action('wps_products_display', array($Hooks, 'wps_products_display'), 10, 2);
			add_filter('wps_products_related_args', array($Hooks, 'wps_products_related_args'), 10, 2);
			add_filter('wps_products_related_args_posts_per_page', array($Hooks, 'wps_products_related_args_posts_per_page') );
			add_filter('wps_products_related_args_orderby', array($Hooks, 'wps_products_related_args_orderby') );
			add_filter('wps_products_related_custom_args', array($Hooks, 'wps_products_related_custom_args'));
			add_filter('wps_products_related_custom_items_per_row', array($Hooks, 'wps_products_related_custom_items_per_row'));
			add_action('wps_products_related_start', array($Hooks, 'wps_products_related_start'));
			add_action('wps_products_related_end', array($Hooks, 'wps_products_related_end'));
			add_action('wps_products_related_heading_start', array($Hooks, 'wps_products_related_heading_start'));
			add_action('wps_products_related_heading_end', array($Hooks, 'wps_products_related_heading_end'));
			add_action('wps_products_notice_out_of_stock', array($Hooks, 'wps_products_notice_out_of_stock'));

			add_action('wps_product_single_after', array($Hooks, 'wps_related_products'));
			add_action('wps_product_single_notice_inline', array($Hooks, 'wps_product_single_notice_inline'));
			add_action('wps_product_single_button_add_to_cart', array($Hooks, 'wps_product_single_button_add_to_cart'));
			add_action('wps_product_single_actions_group_start', array($Hooks, 'wps_product_single_actions_group_start'));
			add_action('wps_product_single_content', array($Hooks, 'wps_product_single_content'));
			add_action('wps_product_single_header_before', array($Hooks, 'wps_product_single_header_before'));
			add_action('wps_product_single_header', array($Hooks, 'wps_product_single_header'));
			add_action('wps_product_single_header_after', array($Hooks, 'wps_product_single_header_after'));
			add_action('wps_product_single_header_price_before', array($Hooks, 'wps_product_single_header_price_before'));
			add_action('wps_product_single_header_price', array($Hooks, 'wps_product_single_header_price'));
			add_action('wps_product_single_header_price_after', array($Hooks, 'wps_product_single_header_price_after'));
			add_action('wps_product_single_quantity', array($Hooks, 'wps_product_single_quantity'));
			add_action('wps_product_single_imgs', array($Hooks, 'wps_product_single_imgs'));
			add_action('wps_product_single_options', array($Hooks, 'wps_product_single_options'));
			add_action('wps_product_single_meta_start', array($Hooks, 'wps_product_single_meta_start'));
			add_action('wps_product_single_meta_end', array($Hooks, 'wps_product_single_meta_end'));
			add_action('wps_product_single_info_start', array($Hooks, 'wps_product_single_info_start'));
			add_action('wps_product_single_info_end', array($Hooks, 'wps_product_single_info_end'));
			add_action('wps_product_single_gallery_start', array($Hooks, 'wps_product_single_gallery_start'));
			add_action('wps_product_single_gallery_end', array($Hooks, 'wps_product_single_gallery_end'));
			add_action('wps_product_single_start', array($Hooks, 'wps_product_single_start'));
			add_action('wps_product_single_end', array($Hooks, 'wps_product_single_end'));
			add_action('wps_product_single_thumbs_start', array($Hooks, 'wps_product_single_thumbs_start'));
			add_action('wps_product_single_thumbs_end', array($Hooks, 'wps_product_single_thumbs_end'));
			add_filter('wps_product_single_thumbs_class', array($Hooks, 'wps_product_single_thumbs_class'), 10, 2 );
			add_action('wps_product_single_sidebar', array($Hooks, 'wps_product_single_sidebar'));
			add_filter('wps_product_single_price', array($Hooks, 'wps_product_single_price'), 10, 4 );
			add_filter('wps_product_single_price_multi', array($Hooks, 'wps_product_single_price_multi'), 10, 4 );
			add_filter('wps_product_single_price_one', array($Hooks, 'wps_product_single_price_one'), 10, 3 );

			add_action('wps_cart_icon', array($Hooks, 'wps_cart_icon'));
			add_action('wps_cart_counter', array($Hooks, 'wps_cart_counter'));

			add_action('wps_breadcrumbs', array($Hooks, 'wps_breadcrumbs'));

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
