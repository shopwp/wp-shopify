<?php

namespace WPS;

use WPS\DB\Products as DB_Products;
use WPS\DB\Variants as DB_Variants;
use WPS\DB\Settings_General;
use WPS\DB\Settings_Connection;
use WPS\DB\Shop;


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

			$DB_Settings_General = new Settings_General();

			if(!is_admin()) {

				$styles_all = $DB_Settings_General->get_column_single('styles_all');
				$styles_core = $DB_Settings_General->get_column_single('styles_core');
				$styles_grid = $DB_Settings_General->get_column_single('styles_grid');

				if (is_array($styles_all)) {

					if ($styles_all[0]->styles_all) {

						wp_enqueue_style( $this->config->plugin_name . '-styles-all', $this->config->plugin_url . 'css/public.min.css', array(), $this->config->plugin_version, 'all' );

					} else {

						if ($styles_core[0]->styles_core) {
							wp_enqueue_style( $this->config->plugin_name . '-styles-core', $this->config->plugin_url . 'css/core.min.css', array(), $this->config->plugin_version, 'all' );
						}

						if ($styles_grid[0]->styles_grid) {

							wp_enqueue_style( $this->config->plugin_name . '-styles-grid', $this->config->plugin_url . 'css/grid.min.css', array(), $this->config->plugin_version, 'all' );

						}

					}

				} else {

				}

			}

		}


		/*

		Public scripts

		*/
		public function wps_public_scripts() {

			if (get_transient('wps_connection_connected')) {
	      $connected = get_transient('wps_connection_connected');

	    } else {
				$DB_Settings_Connection = new Settings_Connection();
	      set_transient('wps_connection_connected', $DB_Settings_Connection->check_connection());

				$connected = get_transient('wps_connection_connected');

	    }


			if (!is_admin()) {

				global $post;
				$DB_Settings_General = new Settings_General();

				// Shopify JS SDK
				wp_enqueue_script('shopify-js-sdk', '//sdks.shopifycdn.com/js-buy-sdk/v0/latest/shopify-buy.umd.polyfilled.min.js', array(), $this->config->plugin_version, false );

				// Promise polyfill
				wp_enqueue_script($this->config->plugin_name . '-promise-polyfill', $this->config->plugin_url . 'public/js/app/vendor/es6-promise.auto.min.js', array('jquery'), $this->config->plugin_version, true);

				// WP Shopify JS Public
				wp_enqueue_script($this->config->plugin_name . '-public', $this->config->plugin_url . 'dist/public.min.js', array('jquery', 'shopify-js-sdk'), $this->config->plugin_version, true);

				wp_localize_script($this->config->plugin_name . '-public', $this->config->plugin_name, array(
						'ajax' => admin_url( 'admin-ajax.php' ),
						'pluginsPath' => plugins_url(),
						'productsSlug' => $DB_Settings_General->products_slug()[0]->url_products,
						'is_connected' => $connected,
						'is_recently_connected' => get_transient('wps_recently_connected'),
						'post_id' => is_object($post) ? $post->ID : false
					)
				);

			}

			// Sets recently connected to false by default
			if (get_transient('wps_recently_connected')) {
				set_transient('wps_recently_connected', false);
			}


		}


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
			 need to fork where the execution goes by calling one of two functions depending
			 on what we're dealing with. They are:

			 construct_clauses_from_products_shortcode
			 construct_clauses_from_collections_shortcode

			 ================================================================
			 wps_products_shortcode ->
			 wps_format_products_shortcode_args ->
			 wps_map_products_args_to_query ->
			 wps_products_display -> (via WP_Query) -> wps_clauses_mod
					either a. construct_clauses_from_products_shortcode
					either b. construct_clauses_from_collections_shortcode
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

		WP Shopify cart

		*/
		public function wps_insert_cart_before_closing_body() {

			ob_start();
			include_once($this->config->plugin_path . "public/partials/cart/cart.php");
			$content = ob_get_contents();
			ob_end_clean();
			echo $content;

		}


		/*

		Get plugin settings

		*/
		public function wps_get_credentials() {
			wp_send_json_success($this->config->wps_get_settings_connection());
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


			if (isset($_POST['selectedOptions']) && is_array($_POST['selectedOptions'])) {

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

				$constructedOptions = Utils::construct_option_selections($selectedOptions);

				// TODO -- Breakout into own function
				$found = false;

				foreach ($refinedVariants as $key => $variant) {

					$cleanVariants = array_filter($variant['options']);

					if ( $cleanVariants === $constructedOptions ) {

						$variantObj = $DB_Variants->get_by('id', $variant['id']);

						if ($variantObj->inventory_quantity > 0) {
							$found = true;
							wp_send_json_success($variant['id']);

						} else {
							wp_send_json_error('Out of stock');

						}

					}

				}


				if (!$found) {
					wp_send_json_error('Selected option(s) aren\'t available. Please select a different combination.');
				}


			} else {
				wp_send_json_error('Unable to find selected options. Please try again.');

			}

		}


    /*

    Notice view. TODO: Remove ob?

    */
    public function wps_notice() {
      return include_once($this->config->plugin_path . "public/partials/notices/notice.php");
    }


		/*

		Get plugin setting for currency symbol toggle

		*/
		public function wps_get_currency_format() {

			$DB_Settings_General = new Settings_General();

			$result = $DB_Settings_General->get_column_single('price_with_currency');

			if (isset($result[0]) && $result[0]->price_with_currency) {

				wp_send_json_success($result[0]->price_with_currency);

			} else {
				wp_send_json_error('Currency format not found. Please try again.');

			}

		}



		/*

		Get plugin setting for currency symbol toggle

		*/
		public function wps_has_money_format_changed() {

			$DB_Shop = new Shop();

			$current_money_format = $DB_Shop->get_shop('money_format');

			if (isset($current_money_format[0]) && $current_money_format[0]) {
				$current_money_format = $current_money_format[0]->money_format;
			} else {
				$current_money_format = false;
			}

			$money_with_currency_format = $DB_Shop->get_shop('money_with_currency_format');

			if (isset($money_with_currency_format[0]) && $money_with_currency_format[0]) {
				$money_with_currency_format = $money_with_currency_format[0]->money_with_currency_format;
			} else {
				$money_with_currency_format = false;
			}


			if ($_POST['format'] === $current_money_format || $_POST['format'] === $money_with_currency_format) {
				wp_send_json_success(false);

			} else {
				wp_send_json_success(true);

			}

		}



		/*

		Get plugin setting money_format

		*/
		public function wps_get_money_format() {

			$DB_Shop = new Shop();
			$moneyFormat = $DB_Shop->get_shop('money_format');

			if (isset($moneyFormat[0]) && $moneyFormat[0]->money_format) {

				$moneyFormat = (string)$moneyFormat[0]->money_format;
				wp_send_json_success($moneyFormat);

			} else {
				wp_send_json_success(false);

			}

		}


		/*

		Get plugin setting money_format

		*/
		public function wps_get_money_format_with_currency() {

			$DB_Shop = new Shop();
			$moneyFormat = $DB_Shop->get_shop('money_with_currency_format');

			if (isset($moneyFormat[0]) && $moneyFormat[0]->money_with_currency_format) {

				$moneyFormat = (string)$moneyFormat[0]->money_with_currency_format;
				wp_send_json_success($moneyFormat);

			} else {
				wp_send_json_success(false);

			}

		}




		/*

		Get plugin setting money_format

		*/
		public function wps_get_cache_flush_status() {

			$DB_Settings_Connection = new Settings_Connection();
			$needsCacheFlush = $DB_Settings_Connection->get_column_single('needs_cache_flush');

			if (is_null($needsCacheFlush) || !empty($needsCacheFlush->last_error)) {
				wp_send_json_error($needsCacheFlush);

			} else {

				if (is_array($needsCacheFlush) && isset($needsCacheFlush[0]) ) {

					wp_send_json_success($needsCacheFlush[0]->needs_cache_flush);

				} else {
					wp_send_json_error($needsCacheFlush);

				}

			}

		}


		/*

		Get plugin setting money_format

		*/
		public function wps_update_cache_flush_status() {

			$DB_Settings_Connection = new Settings_Connection();
			$connectionData = $DB_Settings_Connection->get();

			$connectionData->needs_cache_flush = $_POST['status'];
			$connectionData = (array) $connectionData;

			$response = $DB_Settings_Connection->insert_connection($connectionData);

			if (is_wp_error($response)) {
				wp_send_json_error($response->get_error_message());

			} else {
				wp_send_json_success($response);
			}


		}




		/*

		Before Checkout Hook

		*/
		public function wps_add_checkout_before_hook() {

			$cart = $_POST['cart'];
			$exploded = explode($cart['domain'], $cart['checkoutUrl']);
			$landing_site = $exploded[1];

			$landing_site_hash = Utils::wps_hash($landing_site);

			wp_send_json_success();

		}





		/*

		Only hooks not meant for public consumption

		*/
		public function wps_frontend_hooks() {

			$DB_Settings_General = new Settings_General();

			add_action( 'wp_enqueue_scripts', array($this, 'wps_public_styles') );
			add_action( 'wp_enqueue_scripts', array($this, 'wps_public_scripts') );

			add_filter( 'single_template', array($this, 'wps_product_single_template') );
			add_filter( 'archive_template', array($this, 'wps_products_template') );

			add_shortcode( 'wps_products', array($this, 'wps_products_shortcode') );
			add_shortcode( 'wps_collections', array($this, 'wps_collections_shortcode') );


			// Cart Button Shortcode
			add_shortcode('wps_cart', array($this, 'wps_cart_shortcode'));

			// AJAX
			add_action( 'wp_ajax_wps_update_cache_flush_status', array($this, 'wps_update_cache_flush_status') );
			add_action( 'wp_ajax_nopriv_wps_update_cache_flush_status', array($this, 'wps_update_cache_flush_status') );

			add_action( 'wp_ajax_wps_get_cache_flush_status', array($this, 'wps_get_cache_flush_status') );
			add_action( 'wp_ajax_nopriv_wps_get_cache_flush_status', array($this, 'wps_get_cache_flush_status') );

			add_action( 'wp_ajax_wps_get_credentials', array($this, 'wps_get_credentials') );
			add_action( 'wp_ajax_nopriv_wps_get_credentials', array($this, 'wps_get_credentials') );

			add_action( 'wp_ajax_wps_get_variant_id', array($this, 'wps_get_variant_id') );
			add_action( 'wp_ajax_nopriv_wps_get_variant_id', array($this, 'wps_get_variant_id') );

			add_action( 'wp_ajax_wps_get_currency_format', array($this, 'wps_get_currency_format') );
			add_action( 'wp_ajax_nopriv_wps_get_currency_format', array($this, 'wps_get_currency_format') );

			add_action( 'wp_ajax_wps_has_money_format_changed', array($this, 'wps_has_money_format_changed') );
			add_action( 'wp_ajax_nopriv_wps_has_money_format_changed', array($this, 'wps_has_money_format_changed') );

			add_action( 'wp_ajax_wps_get_money_format', array($this, 'wps_get_money_format') );
			add_action( 'wp_ajax_nopriv_wps_get_money_format', array($this, 'wps_get_money_format') );

			add_action( 'wp_ajax_wps_get_money_format_with_currency', array($this, 'wps_get_money_format_with_currency') );
			add_action( 'wp_ajax_nopriv_wps_get_money_format_with_currency', array($this, 'wps_get_money_format_with_currency') );


			/*

			Only load cart HTML if enabled within settings

			*/

			if ($DB_Settings_General->get_column_single('cart_loaded')[0]->cart_loaded) {
				add_action( 'wp_footer', array($this, 'wps_insert_cart_before_closing_body') );
				add_action( 'wp_footer', array($this, 'wps_notice') );
			}


			/*

			Checkout Hook

			*/
			add_action( 'wp_ajax_wps_add_checkout_before_hook', array($this, 'wps_add_checkout_before_hook') );
			add_action( 'wp_ajax_nopriv_wps_add_checkout_before_hook', array($this, 'wps_add_checkout_before_hook') );



		}

	}

}
