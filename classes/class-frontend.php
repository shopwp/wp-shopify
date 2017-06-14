<?php

namespace WPS;

use WPS\DB\Products as DB_Products;
use WPS\DB\Variants as DB_Variants;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
	exit;
}


/*

Public Class

*/

if (!class_exists('Frontend')) {

	class Frontend {

		protected static $instantiated = null;

		private $Config;

		/*

		Initialize the class and set its properties.

		*/
		public function __construct($Config) {
			$this->config = $Config;
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

		Public styles

		*/
		public function wps_public_styles() {

			if(!is_admin()) {
				wp_enqueue_style( $this->config->plugin_name . '-styles', $this->config->plugin_url . 'css/public.min.css', array(), $this->config->plugin_version, 'all' );
			}

		}


		/*

		Public scripts

		*/
		public function wps_public_scripts() {

			// old
			// http://sdks.shopifycdn.com/js-buy-sdk/latest/shopify-buy.polyfilled.globals.min.js

			// new
			// http://sdks.shopifycdn.com/js-buy-sdk/latest/shopify-buy.polyfilled.globals.min.js

			if(!is_admin()) {
				// wp_enqueue_script('wps-shopify', 'https://sdks.shopifycdn.com/js-buy-sdk/v0/latest/shopify-buy.umd.polyfilled.min.js', array('jquery'), null, true);

				// WP Shopify JS Vendor
				// wp_enqueue_script($this->config->plugin_name . '-admin-vendor', $this->config->plugin_url . 'dist/vendor.min.js', array(), $this->config->plugin_version, false );

				// WP Shopify JS Public
				wp_enqueue_script($this->config->plugin_name . '-public', $this->config->plugin_url . 'dist/public.min.js', array('jquery'), $this->config->plugin_version, true);

				wp_localize_script($this->config->plugin_name . '-public', $this->config->plugin_name, array(
						'ajax' => admin_url( 'admin-ajax.php' ),
						'pluginsPath' => plugins_url()
					)
				);

			}

		}


		/*

		Get plugin settings

		*/
		// public function config->wps_get_settings_connection()  {
		// 	return get_option($this->config->plugin_name);
		// }


		/*

		[wps_products] Shortcode

		There's a few things going on here.

		1. 'wps_format_products_shortcode_args' formats the provided shortcode args
				by taking the comma seperated list of values in each attribute and constructing
				an array. It also uses the attribute name as the array key. For example"

			 	array(
					'title' => array(
						'Sale', 'Featured'
					)
				)''

		2. Next, it passes the array of args to 'wps_map_products_args_to_query'
			 which is the main function that constructs our custom SQL query. This is where
			 the "custom" property is set that we eventually check for within 'wps_clauses_mod'.

		3. At this point in the execution we load our template by pulling in our
			 products-all.php. This template then calls our custom action 'wps_products_display'

		4. 'wps_products_display' then calls 'wps_clauses_mod' when it invokes WP_Query. The
			 execution order looks like this:

		5. Because 'wps_clauses_mod' will get fired for both products and collections, we then
			 need to fork where the execution goes by calling one of three functions depending
			 on what we're dealing with. They are:

			 construct_clauses_from_products_shortcode
			 construct_clauses_from_collections_custom_shortcode
			 construct_clauses_from_collections_smart_shortcode

			 ================================================================
			 wps_products_shortcode ->
			 wps_format_products_shortcode_args ->
			 wps_map_products_args_to_query ->
			 wps_products_display -> (via WP_Query) -> wps_clauses_mod
					either a. construct_clauses_from_products_shortcode
					either b. construct_clauses_from_collections_custom_shortcode
					either c. construct_clauses_from_collections_smart_shortcode
			 ================================================================

		*/
		public function wps_products_shortcode($atts) {

			$shortcode_output = '';
			$shortcodeArgs = Utils::wps_format_products_shortcode_args($atts);
			$is_shortcode = true;

			ob_start();
			include($this->config->plugin_path . "public/templates/products-all.php");
			$products = ob_get_contents();
			ob_end_clean();

     	$shortcode_output .= $products;

     	return $shortcode_output;

		}


		/*

		[wps_collections] Shortcode

		*/
		public function wps_collections_shortcode($atts) {

			$shortcode_output = '';
			$shortcodeArgs = Utils::wps_format_collections_shortcode_args($atts);
			$is_shortcode = true;
			
			ob_start();
			include($this->config->plugin_path . "public/templates/collections-all.php");
			$collections = ob_get_contents();
			ob_end_clean();

		 $shortcode_output .= $collections;

		 return $shortcode_output;

		}




		public function wps_insert_cart_before_closing_body() {

			ob_start();
			include_once($this->config->plugin_path . "public/partials/cart/cart.php");
			$content = ob_get_contents();
			ob_end_clean();
			echo $content;

		}


		/*

		WP Shopify shortcode

		*/
		public function wps_cart_shortcode($atts) {

			$shortcode_output = '';
			$shortcodeArgs = Utils::wps_format_collections_shortcode_args($atts);

			ob_start();
			include($this->config->plugin_path . "public/partials/cart/button.php");
			$cart = ob_get_contents();
			ob_end_clean();

			$shortcode_output .= $cart;

			return $shortcode_output;

		}


		/*

		Get plugin settings

		*/
		public function wps_get_credentials() {

			echo json_encode( $this->config->wps_get_settings_connection() );
			die();

		}


		/*

		Single Template
		TODO: Combine with products template function below

		*/
		public function wps_product_single_template($template) {

			global $wp_query, $post;

			if(isset($post) && $post) {

				if ($post->post_type == "wps_products") {

					// echo $post->ID;

					$templateFile = $this->config->plugin_path . "public/templates/products-single.php";

					if(file_exists($templateFile)) {
						$template = $templateFile;
					}

				} else if($post->post_type == "wps_collections") {

					$templateFile = $this->config->plugin_path . "public/templates/collections-single.php";

					if(file_exists($templateFile)) {
						$template = $templateFile;
					}

				}

			} else {
				$template = false;

			}

			return $template;

		}


		/*

		Single Product Template

		*/
		public function wps_products_template($template) {

			global $wp_query, $post;
// echo 'hihih';

			if(isset($post) && $post) {

				if ($post->post_type == "wps_products") {

					$templateFile = $this->config->plugin_path . "public/templates/products-all.php";

					if(file_exists($templateFile)) {
						$template = $templateFile;
					}

				} else if($post->post_type == "wps_collections") {

					$templateFile = $this->config->plugin_path . "public/templates/collections-all.php";

					if(file_exists($templateFile)) {
						$template = $templateFile;
					}

				}

			} else {
				$template = false;

			}

			return $template;

		}


		/*

		TODO: Move?
		Find Variant ID from Options

		*/
		public function wps_get_variant_id() {

			$DB_Products = new DB_Products();
			$DB_Variants = new DB_Variants();
			$selectedOptions = $_POST['selectedOptions'];

			// TODO: combine below two lines with wps_get_product_variants
			$productData = $DB_Products->get_product($_POST['productID']);
			$variantData = $DB_Variants->get_product_variants($_POST['productID']);


			// $productVariants = maybe_unserialize( unserialize( $productData['variants'] ));

			// TODO: Move to Utils
			function array_filter_key($ar, $callback = 'empty') {
				$ar = (array)$ar;
				return array_intersect_key($ar, array_flip(array_filter(array_keys($ar), $callback)));
			}

			$refinedVariants = array();
			$refinedVariantsOptions = array();

			foreach ($variantData as $key => $variant) {

				$refinedVariantsOptions = array_filter_key($variant, function($key) {
					return strpos($key, 'option') === 0;
				});

				$refinedVariants[] = array(
					'id' => $variant->id,
					'options' => $refinedVariantsOptions
				);

			}

			foreach ($refinedVariants as $key => $variant) {

				if(count(array_intersect($variant['options'], $selectedOptions)) == count($selectedOptions)) {
					echo $variant['id'];
					die();

				}

			}

		}


    /*

    Notice view. TODO: Remove ob?

    */
    public function wps_notice() {
      return include_once($this->config->plugin_path . "public/partials/notices/notice.php");
    }


		/*

		Only hooks not meant for public consumption

		*/
		public function wps_frontend_hooks() {

			add_action( 'wp_enqueue_scripts', array($this, 'wps_public_styles') );
			add_action( 'wp_enqueue_scripts', array($this, 'wps_public_scripts') );

			add_filter( 'single_template', array($this, 'wps_product_single_template') );
			add_filter( 'template_include', array($this, 'wps_products_template') );

			add_shortcode( 'wps_products', array($this, 'wps_products_shortcode') );
			add_shortcode( 'wps_collections', array($this, 'wps_collections_shortcode') );


			// Cart Button Shortcode
			add_shortcode('wps_cart', array($this, 'wps_cart_shortcode'));

			// AJAX
			add_action( 'wp_ajax_wps_get_credentials', array($this, 'wps_get_credentials') );
			add_action( 'wp_ajax_nopriv_wps_get_credentials', array($this, 'wps_get_credentials') );

			add_action( 'wp_ajax_wps_get_variant_id', array($this, 'wps_get_variant_id') );
			add_action( 'wp_ajax_nopriv_wps_get_variant_id', array($this, 'wps_get_variant_id') );

			add_action( 'wp_footer', array($this, 'wps_insert_cart_before_closing_body') );
      add_action( 'wp_footer', array($this, 'wps_notice') );

		}

	}

}
