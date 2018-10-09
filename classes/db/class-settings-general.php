<?php

namespace WPS\DB;

use WPS\Utils;


if (!defined('ABSPATH')) {
	exit;
}


class Settings_General extends \WPS\DB {

	// Table info
	public $table_name_suffix;
	public $table_name;
	public $version;
	public $primary_key;
	public $lookup_key;
	public $cache_group;
	public $type;

	// Defaults
	public $default_webhooks;
	public $default_id;
	public $default_plugin_version;
	public $default_plugin_author;
	public $default_plugin_textdomain;
	public $default_plugin_name;
	public $default_num_posts;
	public $default_title_as_alt;
	public $default_price_with_currency;
	public $default_styles_all;
	public $default_selective_sync_all;
	public $default_selective_sync_products;
	public $default_selective_sync_collections;
	public $default_selective_sync_customers;
	public $default_selective_sync_orders;
	public $default_selective_sync_shop;
	public $default_products_link_to_shopify;
	public $default_show_breadcrumbs;
	public $default_hide_pagination;
	public $default_is_free;
	public $default_is_pro;
	public $default_related_products_show;
	public $default_related_products_sort;
	public $default_related_products_amount;
	public $default_allow_insecure_webhooks;
	public $default_save_connection_only;
	public $default_app_uninstalled;
	public $default_items_per_request;
	public $default_enable_beta;
	public $default_enable_cart_terms;
	public $default_cart_terms_content;
	public $default_url_products;
	public $default_url_collections;
	public $default_add_to_cart_color;
	public $default_variant_color;
	public $default_checkout_color;
	public $default_cart_counter_color;
	public $default_cart_icon_color;

	public function __construct() {

		$this->table_name_suffix  										= WPS_TABLE_NAME_SETTINGS_GENERAL;
		$this->table_name         										= $this->get_table_name();
		$this->version                        				= '1.0';
		$this->primary_key                    				= 'id';
		$this->lookup_key                    					= 'id';
		$this->cache_group                    				= 'wps_db_general';
		$this->type     															= 'settings_general';

		$this->default_webhooks                 			= Utils::convert_to_https_url( get_home_url() );
		$this->default_id                       			= 0;
		$this->default_plugin_version           			= WPS_NEW_PLUGIN_VERSION;
		$this->default_plugin_author            			= WPS_NEW_PLUGIN_AUTHOR;
		$this->default_plugin_textdomain        			= WPS_PLUGIN_NAME;
		$this->default_plugin_name              			= WPS_PLUGIN_NAME_FULL;
		$this->default_num_posts                			= get_option('posts_per_page');
		$this->default_title_as_alt             			= 0;
		$this->default_cart_loaded              			= 1;
		$this->default_price_with_currency      			= 0;
		$this->default_styles_all               			= 1;
		$this->default_styles_core              			= 0;
		$this->default_styles_grid              			= 0;
		$this->default_selective_sync_all      				= 1;
		$this->default_selective_sync_products  			= 0;
		$this->default_sync_by_collections 						= '';
		$this->default_selective_sync_collections 		= 0;
		$this->default_selective_sync_customers     	= 0;
		$this->default_selective_sync_orders        	= 0;
		$this->default_selective_sync_shop          	= 1;
		$this->default_products_link_to_shopify     	= 0;
		$this->default_show_breadcrumbs       				= 0;
		$this->default_hide_pagination       					= 0;
		$this->default_is_free        								= 0;
		$this->default_is_pro        									= 0;
		$this->default_related_products_show        	= 1;
		$this->default_related_products_sort       		= 'random';
		$this->default_related_products_amount      	= 4;
		$this->default_allow_insecure_webhooks      	= 0;
		$this->default_save_connection_only        		= 0;
		$this->default_app_uninstalled        				= 0;
		$this->default_items_per_request        			= WPS_MAX_ITEMS_PER_REQUEST;
		$this->default_enable_beta        						= 0;
		$this->default_enable_cart_terms        			= 0;
		$this->default_url_products        						= 'products';
		$this->default_url_collections        				= 'collections';
		$this->default_cart_terms_content        			= WPS_DEFAULT_CART_TERMS_CONTENT;
		$this->default_add_to_cart_color        			= WPS_DEFAULT_ADD_TO_CART_COLOR;
		$this->default_variant_color        					= WPS_DEFAULT_VARIANT_COLOR;
		$this->default_checkout_color									= WPS_DEFAULT_VARIANT_COLOR;
		$this->default_cart_counter_color							= WPS_DEFAULT_CART_COUNTER_COLOR;
		$this->default_cart_icon_color								= WPS_DEFAULT_CART_ICON_COLOR;
	}


	/*

	Default column schemas

	*/
	public function get_columns() {

		return [
			'id'                            						=> '%d',
			'url_products'                  						=> '%s',
			'url_collections'               						=> '%s',
			'url_webhooks'                  						=> '%s',
			'num_posts'                     						=> '%d',
			'styles_all'                    						=> '%d',
			'styles_core'                   						=> '%d',
			'styles_grid'                   						=> '%d',
			'plugin_name'                   						=> '%s',
			'plugin_textdomain'             						=> '%s',
			'plugin_version'                						=> '%s',
			'plugin_author'                 						=> '%s',
			'price_with_currency'           						=> '%d',
			'cart_loaded'                   						=> '%d',
			'selective_sync_all'            						=> '%d',
			'selective_sync_products'       						=> '%d',
			'sync_by_collections'												=> '%s',
			'selective_sync_collections'    						=> '%d',
			'selective_sync_customers'      						=> '%d',
			'selective_sync_orders'         						=> '%d',
			'selective_sync_shop'           						=> '%d',
			'products_link_to_shopify'      						=> '%d',
			'show_breadcrumbs'      										=> '%d',
			'hide_pagination'      											=> '%d',
			'is_free'              											=> '%d',
			'is_pro'              											=> '%d',
			'related_products_show'											=> '%d',
			'related_products_sort'											=> '%s',
			'related_products_amount'       						=> '%d',
			'allow_insecure_webhooks'       						=> '%d',
			'save_connection_only'       								=> '%d',
			'title_as_alt'       												=> '%d',
			'app_uninstalled'       										=> '%d',
			'items_per_request'       									=> '%d',
			'enable_beta'       												=> '%d',
			'enable_cart_terms'       									=> '%d',
			'cart_terms_content'       									=> '%s',
			'add_to_cart_color'       									=> '%s',
			'variant_color'       											=> '%s',
			'checkout_color'       											=> '%s',
			'cart_counter_color'       									=> '%s',
			'cart_icon_color'       										=> '%s'
		];

	}


	/*

	Default table values

	*/
	public function get_column_defaults() {

		return [
			'id'                            						=> $this->default_id,
			'url_products'                  						=> $this->default_url_products,
			'url_collections'               						=> $this->default_url_collections,
			'url_webhooks'                  						=> $this->default_webhooks,
			'num_posts'                     						=> $this->default_num_posts,
			'styles_all'                    						=> $this->default_styles_all,
			'styles_core'                   						=> $this->default_styles_core,
			'styles_grid'                   						=> $this->default_styles_grid,
			'plugin_name'                   						=> $this->default_plugin_name,
			'plugin_textdomain'             						=> $this->default_plugin_textdomain,
			'plugin_version'                						=> $this->default_plugin_version,
			'plugin_author'                 						=> $this->default_plugin_author,
			'price_with_currency'           						=> $this->default_price_with_currency,
			'cart_loaded'                   						=> $this->default_cart_loaded,
			'selective_sync_all'            						=> $this->default_selective_sync_all,
			'selective_sync_products'       						=> $this->default_selective_sync_products,
			'sync_by_collections'												=> $this->default_sync_by_collections,
			'selective_sync_collections'    						=> $this->default_selective_sync_collections,
			'selective_sync_customers'      						=> $this->default_selective_sync_customers,
			'selective_sync_orders'         						=> $this->default_selective_sync_orders,
			'selective_sync_shop'           						=> $this->default_selective_sync_shop,
			'products_link_to_shopify'      						=> $this->default_products_link_to_shopify,
			'show_breadcrumbs'      										=> $this->default_show_breadcrumbs,
			'hide_pagination'      											=> $this->default_hide_pagination,
			'is_free'      															=> $this->default_is_free,
			'is_pro'      															=> $this->default_is_pro,
			'related_products_show'											=> $this->default_related_products_show,
			'related_products_sort'											=> $this->default_related_products_sort,
			'related_products_amount'       						=> $this->default_related_products_amount,
			'allow_insecure_webhooks'       						=> $this->default_allow_insecure_webhooks,
			'save_connection_only'       								=> $this->default_save_connection_only,
			'title_as_alt'       												=> $this->default_title_as_alt,
			'app_uninstalled'       										=> $this->default_app_uninstalled,
			'items_per_request'       									=> $this->default_items_per_request,
			'enable_beta'       												=> $this->default_enable_beta,
			'enable_cart_terms'       									=> $this->default_enable_cart_terms,
			'cart_terms_content'       									=> $this->default_cart_terms_content,
			'add_to_cart_color'       									=> $this->default_add_to_cart_color,
			'variant_color'       											=> $this->default_variant_color,
			'checkout_color'       											=> $this->default_checkout_color,
			'cart_counter_color'       									=> $this->default_cart_counter_color,
			'cart_icon_color'														=> $this->default_cart_icon_color
		];

	}


	/*

	Runs on plugin activation, sets default row

	*/
	public function init($network_wide = false) {

		// Creates custom tables for each blog
		if ( is_multisite() && $network_wide ) {

			$blog_ids = $this->get_network_sites();
			$result = [];

			// $site_blog_id is a string!
			foreach ( $blog_ids as $site_blog_id ) {

				switch_to_blog( $site_blog_id );

				$result = $this->init_table_defaults();

				restore_current_blog();

			}

		} else {

			$result = $this->init_table_defaults();

		}

		return $result;

	}


	/*

	Sets table defaults

	*/
	public function init_table_defaults() {

		$results = [];

		if ( !$this->table_has_been_initialized('id') ) {
			$results = $this->insert_default_values();
		}

		return $results;

	}


	/*

	Insert connection data

	*/
	public function update_general($general_data) {
		return $this->update($this->lookup_key, 1, $general_data);
	}


	/*

	Get num posts value

	*/
	public function get_num_posts() {

		global $wpdb;

		if (get_transient('wps_settings_num_posts')) {
			$results = get_transient('wps_settings_num_posts');

		} else {

			$query = "SELECT num_posts FROM " . $this->table_name;
			$data = $wpdb->get_results($query);

			if (isset($data[0]->num_posts) && $data[0]->num_posts) {
				$results = $data[0]->num_posts;

			} else {
				$results = get_option('posts_per_page');

			}

			set_transient('wps_settings_num_posts', $results);

		}


		return $results;

	}


	/*

	Get the current products slug

	*/
	public function products_slug() {

		$url_products = $this->get_column_single('url_products');

		if ( Utils::array_not_empty($url_products) && isset($url_products[0]->url_products) ) {
			return $url_products[0]->url_products;

		} else {
			return false;
		}

	}


	/*

	Get the current collections slug

	*/
	public function collections_slug() {

		$url_collections = $this->get_column_single('url_collections');

		if ( Utils::array_not_empty($url_collections) && isset($url_collections[0]->url_collections) ) {
			return $url_collections[0]->url_collections;

		} else {
			return false;
		}


	}


	/*

	Get the value for whether products link to Shopify or not

	*/
	public function products_link_to_shopify() {

		$products_link_to_shopify = $this->get_column_single('products_link_to_shopify');

		if ( Utils::array_not_empty($products_link_to_shopify) && isset($products_link_to_shopify[0]->products_link_to_shopify) ) {
			return $products_link_to_shopify[0]->products_link_to_shopify;

		} else {
			return false;
		}

	}


	/*

	Get the value for whether products link to Shopify or not

	*/
	public function hide_pagination() {

		$hide_pagination = $this->get_column_single('hide_pagination');

		if ( Utils::array_not_empty($hide_pagination) && isset($hide_pagination[0]->hide_pagination) ) {
			return $hide_pagination[0]->hide_pagination;

		} else {
			return false;
		}

	}


	/*

	Breadcrumbs state

	*/
	public function show_breadcrumbs() {

		$show_breadcrumbs = $this->get_column_single('show_breadcrumbs');

		if ( Utils::array_not_empty($show_breadcrumbs) && isset($show_breadcrumbs[0]->show_breadcrumbs) ) {
			return $show_breadcrumbs[0]->show_breadcrumbs;

		} else {
			return false;
		}

	}


	/*

	Show cart terms

	*/
	public function enable_cart_terms() {

		$enable_cart_terms = $this->get_column_single('enable_cart_terms');

		if ( Utils::array_not_empty($enable_cart_terms) && isset($enable_cart_terms[0]->enable_cart_terms) ) {
			return $enable_cart_terms[0]->enable_cart_terms;

		} else {
			return false;
		}

	}


	/*

	Show cart terms

	*/
	public function cart_terms_content() {

		$cart_terms_content = $this->get_column_single('cart_terms_content');

		if ( Utils::array_not_empty($cart_terms_content) && isset($cart_terms_content[0]->cart_terms_content) ) {
			return $cart_terms_content[0]->cart_terms_content;

		} else {
			return false;
		}

	}


	/*

	Gets free tier status

	*/
	public function is_free_tier() {

		$is_free = $this->get_column_single('is_free');

		if ( Utils::array_not_empty($is_free) && isset($is_free[0]->is_free) ) {
			return $is_free[0]->is_free;

		} else {
			return false;
		}

	}


	/*

	Gets pro tier status

	*/
	public function is_pro_tier() {

		$is_pro = $this->get_column_single('is_pro');

		if ( Utils::array_not_empty($is_pro) && isset($is_pro[0]->is_pro) ) {
			return $is_pro[0]->is_pro;

		} else {
			return false;
		}

	}


	/*

	Get the value for whether products link to Shopify or not
	IMPORTANT: Must pass second param: ['id' => 1]

	Defaults to true

	*/
	public function set_free_tier($value = 1) {
		return $this->update_column_single(['is_free' => $value], ['id' => 1]);
	}


	/*

	Get the value for whether products link to Shopify or not

	*/
	public function set_pro_tier($value = 1) {
		return $this->update_column_single(['is_pro' => $value], ['id' => 1]);
	}


	/*

	Get the value for whether products link to Shopify or not

	*/
	public function related_products_amount() {

		$related_products_amount = $this->get_column_single('related_products_amount');

		if ( Utils::array_not_empty($related_products_amount) && isset($related_products_amount[0]->related_products_amount) ) {
			return $related_products_amount[0]->related_products_amount;

		} else {
			return false;
		}

	}


	/*

	Get the value for whether products link to Shopify or not

	*/
	public function related_products_show() {

		$related_products_show = $this->get_column_single('related_products_show');

		if ( Utils::array_not_empty($related_products_show) && isset($related_products_show[0]->related_products_show) ) {
			return $related_products_show[0]->related_products_show;

		} else {
			return false;
		}

	}


	/*

	Get the value for whether products link to Shopify or not

	*/
	public function related_products_sort() {

		$related_products_sort = $this->get_column_single('related_products_sort');

		if ( Utils::array_not_empty($related_products_sort) && isset($related_products_sort[0]->related_products_sort) ) {
			return $related_products_sort[0]->related_products_sort;

		} else {
			return false;
		}

	}


	/*

	Allows for bypassing of the webhooks security check

	*/
	public function allow_insecure_webhooks() {

		$allow_insecure_webhooks = $this->get_column_single('allow_insecure_webhooks');

		if ( Utils::array_not_empty($allow_insecure_webhooks) && isset($allow_insecure_webhooks[0]->allow_insecure_webhooks) ) {
			return $allow_insecure_webhooks[0]->allow_insecure_webhooks;

		} else {
			return false;
		}

	}


	/*

	Allows for syncing products by collection

	*/
	public function sync_by_collections() {

		$sync_by_collections = $this->get_column_single('sync_by_collections');

		if ( Utils::array_not_empty($sync_by_collections) && isset($sync_by_collections[0]->sync_by_collections) ) {
			return $sync_by_collections[0]->sync_by_collections;

		} else {
			return false;
		}

	}


	/*

	Checks whether to save connection only. Useful for using specific settings / tools without syncing data.

	*/
	public function save_connection_only() {

		$save_connection_only = $this->get_column_single('save_connection_only');

		if ( Utils::array_not_empty($save_connection_only) && isset($save_connection_only[0]->save_connection_only) ) {
			return $save_connection_only[0]->save_connection_only;

		} else {
			return false;
		}

	}


	/*

	Get the value for whether products link to Shopify or not

	*/
	public function settings_id() {

		$id = $this->get_column_single('id');

		if ( Utils::array_not_empty($id) && isset($id[0]->id) ) {
			return $id[0]->id;

		} else {
			return false;
		}

	}


	/*

	App Uninstalled

	*/
	public function app_uninstalled() {

		$app_uninstalled = $this->get_column_single('app_uninstalled');

		if ( Utils::array_not_empty($app_uninstalled) && isset($app_uninstalled[0]->app_uninstalled) ) {

			if ($app_uninstalled[0]->app_uninstalled == '1') {
				return true;

			} else {
				return false;
			}

		} else {
			return false;
		}

	}


	/*

	Get app_uninstalled status

	*/
	public function set_app_uninstalled($value = 1) {
		return $this->update_column_single(['app_uninstalled' => $value], ['id' => 1]);
	}


	/*

	Gets the current plugin version number

	*/
	public function get_current_plugin_version() {

		$plugin_version = $this->get_column_single('plugin_version');

		if ( Utils::array_not_empty($plugin_version) && isset($plugin_version[0]->plugin_version) ) {
			return $plugin_version[0]->plugin_version;

		} else {
			return $plugin_version;
		}

	}


	/*

	Gets the enable beta updates setting

	*/
	public function get_enable_beta() {

		$enable_beta = $this->get_column_single('enable_beta');

		if ( Utils::array_not_empty($enable_beta) && isset($enable_beta[0]->enable_beta) ) {

			if ($enable_beta[0]->enable_beta == '1') {
				return true;

			} else {
				return false;
			}

		} else {
			return false;
		}

	}


	/*

	selective_sync_status

	*/
	public function selective_sync_status() {

		return [
			'all'                 => $this->get_selective_sync_all_status(),
			'products'            => $this->get_selective_sync_products_status(),
			'smart_collections'   => $this->get_selective_sync_collections_status(),
			'custom_collections'  => $this->get_selective_sync_collections_status(),
			'customers'           => $this->get_selective_sync_customers_status(),
			'orders'              => $this->get_selective_sync_orders_status(),
			'shop'                => $this->get_selective_sync_shop_status()
		];

	}


	/*

	Gets the status of selective_sync_all

	*/
	public function get_selective_sync_all_status() {

		$selective_sync_all = $this->get_column_single('selective_sync_all');

		if ( Utils::array_not_empty($selective_sync_all) && isset($selective_sync_all[0]->selective_sync_all) ) {
			return (int) $selective_sync_all[0]->selective_sync_all;

		} else {
			return 0;
		}

	}


	/*

	Gets the status of selective_sync_products

	*/
	public function get_selective_sync_products_status() {

		$selective_sync_products = $this->get_column_single('selective_sync_products');

		if ( Utils::array_not_empty($selective_sync_products) && isset($selective_sync_products[0]->selective_sync_products) ) {
			return (int) $selective_sync_products[0]->selective_sync_products;

		} else {
			return 0;
		}

	}


	/*

	Gets the status of selective_sync_collections

	*/
	public function get_selective_sync_collections_status() {

		$selective_sync_collections = $this->get_column_single('selective_sync_collections');

		if ( Utils::array_not_empty($selective_sync_collections) && isset($selective_sync_collections[0]->selective_sync_collections) ) {
			return (int) $selective_sync_collections[0]->selective_sync_collections;

		} else {
			return 0;
		}

	}


	/*

	Gets the status of selective_sync_customers

	*/
	public function get_selective_sync_customers_status() {

		$selective_sync_customers = $this->get_column_single('selective_sync_customers');

		if ( Utils::array_not_empty($selective_sync_customers) && isset($selective_sync_customers[0]->selective_sync_customers) ) {
			return (int) $selective_sync_customers[0]->selective_sync_customers;

		} else {
			return 0;
		}

	}


	/*

	Gets the status of selective_sync_orders

	*/
	public function get_selective_sync_orders_status() {

		$selective_sync_orders = $this->get_column_single('selective_sync_orders');

		if ( Utils::array_not_empty($selective_sync_orders) && isset($selective_sync_orders[0]->selective_sync_orders) ) {
			return (int) $selective_sync_orders[0]->selective_sync_orders;

		} else {
			return 0;
		}

	}


	/*

	Gets the status of selective_sync_shop

	*/
	public function get_selective_sync_shop_status() {

		$selective_sync_shop = $this->get_column_single('selective_sync_shop');

		if ( Utils::array_not_empty($selective_sync_shop) && isset($selective_sync_shop[0]->selective_sync_shop) ) {
			return (int) $selective_sync_shop[0]->selective_sync_shop;

		} else {
			return 0;
		}

	}


	/*

	Gets the status of selective_sync_shop

	*/
	public function get_items_per_request() {

		$items_per_request = $this->get_column_single('items_per_request');

		if ( Utils::array_not_empty($items_per_request) && isset($items_per_request[0]->items_per_request) ) {
			return (int) $items_per_request[0]->items_per_request;

		} else {
			return WPS_MAX_ITEMS_PER_REQUEST;
		}

	}


	/*

	Updates the plugin version

	*/
	public function update_plugin_version($new_version_number) {
		return $this->update_column_single( [ 'plugin_version' => $new_version_number ], [ 'id' => $this->settings_id() ]);
	}


	/*

	Is syncing products by collections?

	*/
	public function is_syncing_by_collection() {

		if ( empty($this->sync_by_collections()) ) {
			return false;

		} else {
			return true;
		}

	}


	/*

	Reset syncing timing

	*/
	public function reset_sync_by_collections() {
		return $this->update_column_single(['sync_by_collections' => false], ['id' => 1]);
	}


	/*

	Reset syncing timing

	*/
	public function update_webhooks_callback_url_to_https() {
		return $this->update_column_single( ['url_webhooks' => Utils::convert_to_https_url( get_home_url() )], ['id' => 1] );
	}


	/*

	Grabs the ids that we saved to the db column

	*/
	public function get_sync_by_collections_ids() {
		return maybe_unserialize( $this->sync_by_collections() );
	}


	/*

	Gets the add to cart color

	*/
	public function get_add_to_cart_color() {

		$add_to_cart_color = $this->get_column_single('add_to_cart_color');

		if ( Utils::array_not_empty($add_to_cart_color) && isset($add_to_cart_color[0]->add_to_cart_color) ) {
			return $add_to_cart_color[0]->add_to_cart_color;

		} else {
			return $add_to_cart_color;
		}

	}


	/*

	Gets the add to cart color

	*/
	public function get_variant_color() {

		$variant_color = $this->get_column_single('variant_color');

		if ( Utils::array_not_empty($variant_color) && isset($variant_color[0]->variant_color) ) {
			return $variant_color[0]->variant_color;

		} else {
			return $variant_color;
		}

	}


	/*

	Gets the add to cart color

	*/
	public function get_checkout_color() {

		$checkout_color = $this->get_column_single('checkout_color');

		if ( Utils::array_not_empty($checkout_color) && isset($checkout_color[0]->checkout_color) ) {
			return $checkout_color[0]->checkout_color;

		} else {
			return $checkout_color;
		}

	}


	/*

	Gets the add to cart color

	*/
	public function get_cart_counter_color() {

		$cart_counter_color = $this->get_column_single('cart_counter_color');

		if ( Utils::array_not_empty($cart_counter_color) && isset($cart_counter_color[0]->cart_counter_color) ) {
			return $cart_counter_color[0]->cart_counter_color;

		} else {
			return $cart_counter_color;
		}

	}


	/*

	Gets the add to cart color

	*/
	public function get_cart_icon_color() {

		$cart_icon_color = $this->get_column_single('cart_icon_color');

		if ( Utils::array_not_empty($cart_icon_color) && isset($cart_icon_color[0]->cart_icon_color) ) {
			return $cart_icon_color[0]->cart_icon_color;

		} else {
			return $cart_icon_color;
		}

	}


	/*

	Reset syncing timing

	*/
	public function update_variant_color($color) {
		return $this->update_column_single( ['variant_color' => $color], ['id' => 1] );
	}


	/*

	Reset syncing timing

	*/
	public function update_checkout_color($color) {
		return $this->update_column_single( ['checkout_color' => $color], ['id' => 1] );
	}


	/*

	Update cart counter color

	*/
	public function update_cart_counter_color($color) {
		return $this->update_column_single( ['cart_counter_color' => $color], ['id' => 1] );
	}


	/*

	Reset syncing timing

	*/
	public function update_add_to_cart_color($color) {
		return $this->update_column_single( ['add_to_cart_color' => $color], ['id' => 1] );
	}


	/*

	Reset syncing timing

	*/
	public function update_cart_icon_color($color) {
		return $this->update_column_single( ['cart_icon_color' => $color], ['id' => 1] );
	}


	/*

	Creates a table query string

	*/
	public function create_table_query($table_name = false) {

		if ( !$table_name ) {
			$table_name = $this->table_name;
		}

		$collate = $this->collate();

		return "CREATE TABLE $table_name (
			id bigint(100) NOT NULL AUTO_INCREMENT,
			url_products varchar(100) NOT NULL DEFAULT '{$this->default_url_products}',
			url_collections varchar(100) NOT NULL DEFAULT '{$this->default_url_collections}',
			url_webhooks varchar(100) NOT NULL DEFAULT '{$this->default_webhooks}',
			num_posts bigint(100) DEFAULT NULL,
			styles_all tinyint(1) DEFAULT '{$this->default_styles_all}',
			styles_core tinyint(1) DEFAULT '{$this->default_styles_core}',
			styles_grid tinyint(1) DEFAULT '{$this->default_styles_grid}',
			plugin_name varchar(100) NOT NULL DEFAULT '{$this->default_plugin_name}',
			plugin_textdomain varchar(100) NOT NULL DEFAULT '{$this->default_plugin_textdomain}',
			plugin_version varchar(100) NOT NULL DEFAULT '{$this->default_plugin_version}',
			plugin_author varchar(100) NOT NULL DEFAULT '{$this->default_plugin_author}',
			price_with_currency tinyint(1) DEFAULT '{$this->default_price_with_currency}',
			cart_loaded tinyint(1) DEFAULT '{$this->default_cart_loaded}',
			title_as_alt tinyint(1) DEFAULT '{$this->default_title_as_alt}',
			selective_sync_all tinyint(1) DEFAULT '{$this->default_selective_sync_all}',
			selective_sync_products tinyint(1) DEFAULT '{$this->default_selective_sync_products}',
			sync_by_collections LONGTEXT DEFAULT '{$this->default_sync_by_collections}',
			selective_sync_collections tinyint(1) DEFAULT '{$this->default_selective_sync_collections}',
			selective_sync_customers tinyint(1) DEFAULT '{$this->default_selective_sync_customers}',
			selective_sync_orders tinyint(1) DEFAULT '{$this->default_selective_sync_orders}',
			selective_sync_shop tinyint(1) DEFAULT '{$this->default_selective_sync_shop}',
			products_link_to_shopify tinyint(1) DEFAULT '{$this->default_products_link_to_shopify}',
			show_breadcrumbs tinyint(1) DEFAULT '{$this->default_show_breadcrumbs}',
			hide_pagination tinyint(1) DEFAULT '{$this->default_hide_pagination}',
			is_free tinyint(1) unsigned NOT NULL DEFAULT '{$this->default_is_free}',
			is_pro tinyint(1) unsigned NOT NULL DEFAULT '{$this->default_is_pro}',
			related_products_show tinyint(1) unsigned NOT NULL DEFAULT '{$this->default_related_products_show}',
			related_products_sort varchar(100) NOT NULL DEFAULT '{$this->default_related_products_sort}',
			related_products_amount tinyint(1) unsigned NOT NULL DEFAULT '{$this->default_related_products_amount}',
			allow_insecure_webhooks tinyint(1) unsigned NOT NULL DEFAULT '{$this->default_allow_insecure_webhooks}',
			save_connection_only tinyint(1) unsigned NOT NULL DEFAULT '{$this->default_save_connection_only}',
			app_uninstalled tinyint(1) unsigned NOT NULL DEFAULT '{$this->default_app_uninstalled}',
			items_per_request bigint(10) NOT NULL DEFAULT '{$this->default_items_per_request}',
			enable_beta tinyint(1) unsigned NOT NULL DEFAULT '{$this->default_enable_beta}',
			enable_cart_terms tinyint(1) unsigned NOT NULL DEFAULT '{$this->default_enable_cart_terms}',
			cart_terms_content LONGTEXT NULL,
			add_to_cart_color varchar(100) NOT NULL DEFAULT '{$this->default_add_to_cart_color}',
			variant_color varchar(100) NOT NULL DEFAULT '{$this->default_variant_color}',
			checkout_color varchar(100) NOT NULL DEFAULT '{$this->default_checkout_color}',
			cart_counter_color varchar(100) NOT NULL DEFAULT '{$this->default_cart_counter_color}',
			cart_icon_color varchar(100) NOT NULL DEFAULT '{$this->default_cart_icon_color}',
			PRIMARY KEY  (id)
		) ENGINE=InnoDB $collate";

	}


}
