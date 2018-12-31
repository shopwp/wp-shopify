<?php

namespace WPS\DB;

use WPS\Utils;
use WPS\Options;


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
	public $default_checkout_button_target;
	public $default_cart_counter_color;
	public $default_cart_icon_color;
	public $default_products_heading_toggle;
	public $default_products_heading;
	public $default_collections_heading_toggle;
	public $default_collections_heading;
	public $default_related_products_heading_toggle;
	public $default_related_products_heading;
	public $default_products_images_sizing_toggle;
	public $default_products_images_sizing_width;
	public $default_products_images_sizing_height;
	public $default_products_images_sizing_crop;
	public $default_products_images_sizing_scale;
	public $default_collections_images_sizing_toggle;
	public $default_collections_images_sizing_width;
	public $default_collections_images_sizing_height;
	public $default_collections_images_sizing_crop;
	public $default_collections_images_sizing_scale;
	public $default_related_products_images_sizing_toggle;
	public $default_related_products_images_sizing_width;
	public $default_related_products_images_sizing_height;
	public $default_related_products_images_sizing_crop;
	public $default_related_products_images_sizing_scale;
	public $default_enable_custom_checkout_domain;
	public $default_products_compare_at;
	public $default_products_show_price_range;
	public $default_show_fixed_cart_tab;
	public $default_cart_icon_fixed_color;
	public $default_cart_counter_fixed_color;
	public $default_cart_fixed_background_color;


	public function __construct() {

		$this->table_name_suffix  												= WPS_TABLE_NAME_SETTINGS_GENERAL;
		$this->table_name         												= $this->get_table_name();
		$this->version                        						= '1.0';
		$this->primary_key                    						= 'id';
		$this->lookup_key                    							= 'id';
		$this->cache_group                    						= 'wps_db_general';
		$this->type     																	= 'settings_general';
		$this->default_webhooks                 					= Utils::convert_to_https_url( Utils::get_site_url() );
		$this->default_plugin_version           					= WPS_NEW_PLUGIN_VERSION;
		$this->default_plugin_author            					= WPS_NEW_PLUGIN_AUTHOR;
		$this->default_plugin_textdomain        					= WPS_PLUGIN_NAME;
		$this->default_plugin_name              					= WPS_PLUGIN_NAME_FULL;
		$this->default_num_posts                					= Options::get('posts_per_page');
		$this->default_title_as_alt             					= 0;
		$this->default_cart_loaded              					= 1;
		$this->default_price_with_currency      					= 0;
		$this->default_styles_all               					= 1;
		$this->default_styles_core              					= 0;
		$this->default_styles_grid              					= 0;
		$this->default_selective_sync_all      						= 1;
		$this->default_selective_sync_products  					= 0;
		$this->default_sync_by_collections 								= '';
		$this->default_selective_sync_collections 				= 0;
		$this->default_selective_sync_customers     			= 0;
		$this->default_selective_sync_orders        			= 0;
		$this->default_selective_sync_shop          			= 1;
		$this->default_products_link_to_shopify     			= 0;
		$this->default_show_breadcrumbs       						= 0;
		$this->default_hide_pagination       							= 0;
		$this->default_is_free        										= 0;
		$this->default_is_pro        											= 0;
		$this->default_related_products_show        			= 1;
		$this->default_related_products_sort       				= 'random';
		$this->default_related_products_amount      			= 4;
		$this->default_allow_insecure_webhooks      			= 0;
		$this->default_save_connection_only        				= 0;
		$this->default_app_uninstalled        						= 0;
		$this->default_items_per_request        					= WPS_MAX_ITEMS_PER_REQUEST;
		$this->default_enable_beta        								= 0;
		$this->default_enable_cart_terms        					= 0;
		$this->default_url_products        								= 'products';
		$this->default_url_collections        						= 'collections';
		$this->default_cart_terms_content        					= WPS_DEFAULT_CART_TERMS_CONTENT;
		$this->default_add_to_cart_color        					= WPS_DEFAULT_ADD_TO_CART_COLOR;
		$this->default_variant_color        							= WPS_DEFAULT_VARIANT_COLOR;
		$this->default_checkout_color											= WPS_DEFAULT_VARIANT_COLOR;
		$this->default_checkout_button_target							= WPS_DEFAULT_CHECKOUT_BUTTON_TARGET;
		$this->default_cart_counter_color									= WPS_DEFAULT_CART_COUNTER_COLOR;
		$this->default_cart_icon_color										= WPS_DEFAULT_CART_ICON_COLOR;
		$this->default_cart_icon_fixed_color							= WPS_DEFAULT_CART_ICON_FIXED_COLOR;
		$this->default_cart_counter_fixed_color						= WPS_DEFAULT_CART_COUNTER_FIXED_COLOR;
		$this->default_cart_fixed_background_color				= WPS_DEFAULT_CART_FIXED_BACKGROUND_COLOR;
		$this->default_products_heading_toggle						= 1;
		$this->default_products_heading										= WPS_DEFAULT_PRODUCTS_HEADING;
		$this->default_collections_heading_toggle					= 1;
		$this->default_collections_heading								= WPS_DEFAULT_COLLECTIONS_HEADING;
		$this->default_related_products_heading_toggle		= 1;
		$this->default_related_products_heading						= WPS_DEFAULT_RELATED_PRODUCTS_HEADING;
		$this->default_enable_custom_checkout_domain      = WPS_DEFAULT_ENABLE_CUSTOM_CHECKOUT_DOMAIN;
		$this->default_products_compare_at      					= WPS_DEFAULT_PRODUCTS_COMPARE_AT;
		$this->default_products_show_price_range      		= WPS_DEFAULT_PRODUCTS_SHOW_PRICE_RANGE;


		$this->default_products_images_sizing_toggle						= 0;
		$this->default_products_images_sizing_width							= WPS_DEFAULT_PRODUCTS_IMAGES_SIZING_WIDTH;
		$this->default_products_images_sizing_height						= WPS_DEFAULT_PRODUCTS_IMAGES_SIZING_HEIGHT;
		$this->default_products_images_sizing_crop							= WPS_DEFAULT_PRODUCTS_IMAGES_SIZING_CROP;
		$this->default_products_images_sizing_scale							= WPS_DEFAULT_PRODUCTS_IMAGES_SIZING_SCALE;

		$this->default_collections_images_sizing_toggle					= 0;
		$this->default_collections_images_sizing_width					= WPS_DEFAULT_COLLECTIONS_IMAGES_SIZING_WIDTH;
		$this->default_collections_images_sizing_height					= WPS_DEFAULT_COLLECTIONS_IMAGES_SIZING_HEIGHT;
		$this->default_collections_images_sizing_crop						= WPS_DEFAULT_COLLECTIONS_IMAGES_SIZING_CROP;
		$this->default_collections_images_sizing_scale					= WPS_DEFAULT_COLLECTIONS_IMAGES_SIZING_SCALE;

		$this->default_related_products_images_sizing_toggle		= 0;
		$this->default_related_products_images_sizing_width			= WPS_DEFAULT_RELATED_PRODUCTS_IMAGES_SIZING_WIDTH;
		$this->default_related_products_images_sizing_height		= WPS_DEFAULT_RELATED_PRODUCTS_IMAGES_SIZING_HEIGHT;
		$this->default_related_products_images_sizing_crop			= WPS_DEFAULT_RELATED_PRODUCTS_IMAGES_SIZING_CROP;
		$this->default_related_products_images_sizing_scale			= WPS_DEFAULT_RELATED_PRODUCTS_IMAGES_SIZING_SCALE;

		$this->default_show_fixed_cart_tab											= 0;

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
			'cart_icon_color'       										=> '%s',
			'products_heading_toggle'										=> '%d',
			'products_heading'       										=> '%s',
			'collections_heading_toggle'       					=> '%d',
			'collections_heading'       								=> '%s',
			'related_products_heading_toggle'     			=> '%d',
			'related_products_heading'       						=> '%s',
			'products_images_sizing_toggle'       			=> '%d',
			'products_images_sizing_width'       				=> '%d',
			'products_images_sizing_height'       			=> '%d',
			'products_images_sizing_crop'       				=> '%s',
			'products_images_sizing_scale'       				=> '%d',
			'collections_images_sizing_toggle'    			=> '%d',
			'collections_images_sizing_width'     			=> '%d',
			'collections_images_sizing_height'    			=> '%d',
			'collections_images_sizing_crop'      			=> '%s',
			'collections_images_sizing_scale'     			=> '%d',
			'related_products_images_sizing_toggle'    	=> '%d',
			'related_products_images_sizing_width'     	=> '%d',
			'related_products_images_sizing_height'    	=> '%d',
			'related_products_images_sizing_crop'      	=> '%s',
			'related_products_images_sizing_scale'     	=> '%d',
			'enable_custom_checkout_domain'							=> '%d',
			'products_compare_at'												=> '%d',
			'products_show_price_range'									=> '%d',
			'checkout_button_target'										=> '%s',
			'show_fixed_cart_tab'												=> '%d',
			'cart_icon_fixed_color'											=> '%s',
			'cart_counter_fixed_color'									=> '%s',
			'cart_fixed_background_color'								=> '%s'
		];

	}


	/*

	Default table values

	*/
	public function get_column_defaults($blog_id = false) {

		return [
			'url_products'                  						=> $this->default_url_products,
			'url_collections'               						=> $this->default_url_collections,
			'url_webhooks'                  						=> Utils::convert_to_https_url( Utils::get_site_url($blog_id) ),
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
			'cart_icon_color'														=> $this->default_cart_icon_color,
			'products_heading_toggle'										=> $this->default_products_heading_toggle,
			'products_heading'													=> $this->default_products_heading,
			'collections_heading_toggle'								=> $this->default_collections_heading_toggle,
			'collections_heading'												=> $this->default_collections_heading,
			'related_products_heading_toggle'						=> $this->default_related_products_heading_toggle,
			'related_products_heading'									=> $this->default_related_products_heading,
			'products_images_sizing_toggle'       			=> $this->default_products_images_sizing_toggle,
			'products_images_sizing_width'       				=> $this->default_products_images_sizing_width,
			'products_images_sizing_height'       			=> $this->default_products_images_sizing_height,
			'products_images_sizing_crop'       				=> $this->default_products_images_sizing_crop,
			'products_images_sizing_scale'       				=> $this->default_products_images_sizing_scale,
			'collections_images_sizing_toggle'    			=> $this->default_collections_images_sizing_toggle,
			'collections_images_sizing_width'     			=> $this->default_collections_images_sizing_width,
			'collections_images_sizing_height'    			=> $this->default_collections_images_sizing_height,
			'collections_images_sizing_crop'      			=> $this->default_collections_images_sizing_crop,
			'collections_images_sizing_scale'     			=> $this->default_collections_images_sizing_scale,
			'related_products_images_sizing_toggle'    	=> $this->default_related_products_images_sizing_toggle,
			'related_products_images_sizing_width'     	=> $this->default_related_products_images_sizing_width,
			'related_products_images_sizing_height'    	=> $this->default_related_products_images_sizing_height,
			'related_products_images_sizing_crop'      	=> $this->default_related_products_images_sizing_crop,
			'related_products_images_sizing_scale'     	=> $this->default_related_products_images_sizing_scale,
			'enable_custom_checkout_domain'     				=> $this->default_enable_custom_checkout_domain,
			'products_compare_at'												=> $this->default_products_compare_at,
			'products_show_price_range'									=> $this->default_products_show_price_range,
			'checkout_button_target'										=> $this->default_checkout_button_target,
			'show_fixed_cart_tab'												=> $this->default_show_fixed_cart_tab,
			'cart_icon_fixed_color'											=> $this->default_cart_icon_fixed_color,
			'cart_counter_fixed_color'									=> $this->default_cart_counter_fixed_color,
			'cart_fixed_background_color'								=> $this->default_cart_fixed_background_color
		];

	}


	/*

	Runs on plugin activation, sets default row

	*/
	public function init($blog_id = false) {
		return $this->init_table_defaults($blog_id);
	}


	/*

	Sets table defaults

	*/
	public function init_table_defaults($blog_id = false) {

		$results = [];

		if ( !$this->table_has_been_initialized('id') ) {
			$results = $this->insert_default_values($blog_id);
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

		if ( !$this->table_exists($this->table_name) ) {
			return false;
		}

		if (get_transient('wps_settings_num_posts')) {
			$results = get_transient('wps_settings_num_posts');

		} else {

			$query = "SELECT num_posts FROM " . $this->table_name;
			$data = $wpdb->get_results($query);

			if (isset($data[0]->num_posts) && $data[0]->num_posts) {
				$results = $data[0]->num_posts;

			} else {
				$results = Options::get('posts_per_page');

			}

			set_transient('wps_settings_num_posts', $results);

		}


		return $results;

	}


	/*

	Get the current products slug

	*/
	public function show_fixed_cart_tab() {

		$show_fixed_cart_tab = $this->get_column_single('show_fixed_cart_tab');

		if ( Utils::array_not_empty($show_fixed_cart_tab) && isset($show_fixed_cart_tab[0]->show_fixed_cart_tab) ) {
			return (bool) $show_fixed_cart_tab[0]->show_fixed_cart_tab;

		} else {
			return false;
		}

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

	Returns the plugin nice name. I.e., WP Shopify Pro / WP Shopify

	*/
	public function plugin_nice_name() {

		if ( $this->is_pro_tier() ) {
			return WPS_PLUGIN_NAME_FULL_PRO;
		}

		return WPS_PLUGIN_NAME_FULL;

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
	public function get_price_with_currency() {

		$price_with_currency = $this->get_column_single('price_with_currency');

		if ( Utils::array_not_empty($price_with_currency) && isset($price_with_currency[0]->price_with_currency) ) {
			return (bool) $price_with_currency[0]->price_with_currency;

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
		return $this->update_column_single( ['url_webhooks' => Utils::convert_to_https_url( Utils::get_site_url() )], ['id' => 1] );
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

	Gets the products heading

	*/
	public function get_products_heading() {

		$products_heading = $this->get_column_single('products_heading');

		if ( Utils::array_not_empty($products_heading) && isset($products_heading[0]->products_heading) ) {
			return $products_heading[0]->products_heading;

		} else {
			return $products_heading;
		}

	}


	/*

	Gets the collections heading

	*/
	public function get_collections_heading() {

		$collections_heading = $this->get_column_single('collections_heading');

		if ( Utils::array_not_empty($collections_heading) && isset($collections_heading[0]->collections_heading) ) {
			return $collections_heading[0]->collections_heading;

		} else {
			return $collections_heading;
		}

	}


	/*

	Gets the collections heading

	*/
	public function get_related_products_heading() {

		$related_products_heading = $this->get_column_single('related_products_heading');

		if ( Utils::array_not_empty($related_products_heading) && isset($related_products_heading[0]->related_products_heading) ) {
			return $related_products_heading[0]->related_products_heading;

		} else {
			return $related_products_heading;
		}

	}


	/*

	Gets products heading toggle

	*/
	public function get_products_heading_toggle() {

		$products_heading_toggle = $this->get_column_single('products_heading_toggle');

		if ( Utils::array_not_empty($products_heading_toggle) && isset($products_heading_toggle[0]->products_heading_toggle) ) {
			return $products_heading_toggle[0]->products_heading_toggle;

		} else {
			return $products_heading_toggle;
		}

	}


	/*

	Gets collections heading toggle

	*/
	public function get_collections_heading_toggle() {

		$collections_heading_toggle = $this->get_column_single('collections_heading_toggle');

		if ( Utils::array_not_empty($collections_heading_toggle) && isset($collections_heading_toggle[0]->collections_heading_toggle) ) {
			return $collections_heading_toggle[0]->collections_heading_toggle;

		} else {
			return $collections_heading_toggle;
		}

	}


	/*

	Gets collections heading toggle

	*/
	public function get_related_products_heading_toggle() {

		$related_products_heading_toggle = $this->get_column_single('related_products_heading_toggle');

		if ( Utils::array_not_empty($related_products_heading_toggle) && isset($related_products_heading_toggle[0]->related_products_heading_toggle) ) {
			return $related_products_heading_toggle[0]->related_products_heading_toggle;

		} else {
			return $related_products_heading_toggle;
		}

	}


	/*

	Gets collections heading toggle

	*/
	public function get_products_images_sizing_toggle() {

		$products_images_sizing_toggle = $this->get_column_single('products_images_sizing_toggle');

		if ( Utils::array_not_empty($products_images_sizing_toggle) && isset($products_images_sizing_toggle[0]->products_images_sizing_toggle) ) {
			return (bool) $products_images_sizing_toggle[0]->products_images_sizing_toggle;

		} else {
			return $products_images_sizing_toggle;
		}

	}


	/*

	Gets collections heading toggle

	*/
	public function get_products_images_sizing_width() {

		$products_images_sizing_width = $this->get_column_single('products_images_sizing_width');

		if ( Utils::array_not_empty($products_images_sizing_width) && isset($products_images_sizing_width[0]->products_images_sizing_width) ) {
			return (int) $products_images_sizing_width[0]->products_images_sizing_width;

		} else {
			return $products_images_sizing_width;
		}

	}


	/*

	Gets collections heading toggle

	*/
	public function get_products_images_sizing_height() {

		$products_images_sizing_height = $this->get_column_single('products_images_sizing_height');

		if ( Utils::array_not_empty($products_images_sizing_height) && isset($products_images_sizing_height[0]->products_images_sizing_height) ) {
			return (int) $products_images_sizing_height[0]->products_images_sizing_height;

		} else {
			return $products_images_sizing_height;
		}

	}


	/*

	Gets collections heading toggle

	*/
	public function get_products_images_sizing_crop() {

		$products_images_sizing_crop = $this->get_column_single('products_images_sizing_crop');

		if ( Utils::array_not_empty($products_images_sizing_crop) && isset($products_images_sizing_crop[0]->products_images_sizing_crop) ) {

			$saved_value = $products_images_sizing_crop[0]->products_images_sizing_crop;

			if (empty($saved_value)) {
				return false;
			}

			return $saved_value;

		} else {
			return $products_images_sizing_crop;
		}

	}


	/*

	Gets collections heading toggle

	*/
	public function get_products_images_sizing_scale() {

		$products_images_sizing_scale = $this->get_column_single('products_images_sizing_scale');

		if ( Utils::array_not_empty($products_images_sizing_scale) && isset($products_images_sizing_scale[0]->products_images_sizing_scale) ) {

			$saved_value = $products_images_sizing_scale[0]->products_images_sizing_scale;

			if ($saved_value === 0) {
				return false;
			}

			return $saved_value;


		} else {
			return $products_images_sizing_scale;
		}

	}


	/*

	Gets collections heading toggle

	*/
	public function get_collections_images_sizing_toggle() {

		$collections_images_sizing_toggle = $this->get_column_single('collections_images_sizing_toggle');

		if ( Utils::array_not_empty($collections_images_sizing_toggle) && isset($collections_images_sizing_toggle[0]->collections_images_sizing_toggle) ) {
			return (bool) $collections_images_sizing_toggle[0]->collections_images_sizing_toggle;

		} else {
			return $collections_images_sizing_toggle;
		}

	}


	/*

	Gets collections heading toggle

	*/
	public function get_collections_images_sizing_width() {

		$collections_images_sizing_width = $this->get_column_single('collections_images_sizing_width');

		if ( Utils::array_not_empty($collections_images_sizing_width) && isset($collections_images_sizing_width[0]->collections_images_sizing_width) ) {
			return (int) $collections_images_sizing_width[0]->collections_images_sizing_width;

		} else {
			return $collections_images_sizing_width;
		}

	}


	/*

	Gets collections heading toggle

	*/
	public function get_collections_images_sizing_height() {

		$collections_images_sizing_height = $this->get_column_single('collections_images_sizing_height');

		if ( Utils::array_not_empty($collections_images_sizing_height) && isset($collections_images_sizing_height[0]->collections_images_sizing_height) ) {
			return (int) $collections_images_sizing_height[0]->collections_images_sizing_height;

		} else {
			return $collections_images_sizing_height;
		}

	}


	/*

	Gets collections heading toggle

	*/
	public function get_collections_images_sizing_crop() {

		$collections_images_sizing_crop = $this->get_column_single('collections_images_sizing_crop');

		if ( Utils::array_not_empty($collections_images_sizing_crop) && isset($collections_images_sizing_crop[0]->collections_images_sizing_crop) ) {

			$saved_value = $collections_images_sizing_crop[0]->collections_images_sizing_crop;

			if (empty($saved_value)) {
				return false;
			}

			return $saved_value;

		} else {
			return $collections_images_sizing_crop;
		}

	}


	/*

	Gets collections heading toggle

	*/
	public function get_collections_images_sizing_scale() {

		$collections_images_sizing_scale = $this->get_column_single('collections_images_sizing_scale');

		if ( Utils::array_not_empty($collections_images_sizing_scale) && isset($collections_images_sizing_scale[0]->collections_images_sizing_scale) ) {

			$saved_value = $collections_images_sizing_scale[0]->collections_images_sizing_scale;

			if ($saved_value === 0) {
				return false;
			}

			return $saved_value;


		} else {
			return $collections_images_sizing_scale;
		}

	}


	/*

	Gets collections heading toggle

	*/
	public function get_related_products_images_sizing_toggle() {

		$related_products_images_sizing_toggle = $this->get_column_single('related_products_images_sizing_toggle');

		if ( Utils::array_not_empty($related_products_images_sizing_toggle) && isset($related_products_images_sizing_toggle[0]->related_products_images_sizing_toggle) ) {
			return (bool) $related_products_images_sizing_toggle[0]->related_products_images_sizing_toggle;

		} else {
			return $related_products_images_sizing_toggle;
		}

	}


	/*

	Gets related_products heading toggle

	*/
	public function get_related_products_images_sizing_width() {

		$related_products_images_sizing_width = $this->get_column_single('related_products_images_sizing_width');

		if ( Utils::array_not_empty($related_products_images_sizing_width) && isset($related_products_images_sizing_width[0]->related_products_images_sizing_width) ) {
			return (int) $related_products_images_sizing_width[0]->related_products_images_sizing_width;

		} else {
			return $related_products_images_sizing_width;
		}

	}


	/*

	Gets related_products heading toggle

	*/
	public function get_related_products_images_sizing_height() {

		$related_products_images_sizing_height = $this->get_column_single('related_products_images_sizing_height');

		if ( Utils::array_not_empty($related_products_images_sizing_height) && isset($related_products_images_sizing_height[0]->related_products_images_sizing_height) ) {
			return (int) $related_products_images_sizing_height[0]->related_products_images_sizing_height;

		} else {
			return $related_products_images_sizing_height;
		}

	}


	/*

	Gets related_products heading toggle

	*/
	public function get_related_products_images_sizing_crop() {

		$related_products_images_sizing_crop = $this->get_column_single('related_products_images_sizing_crop');

		if ( Utils::array_not_empty($related_products_images_sizing_crop) && isset($related_products_images_sizing_crop[0]->related_products_images_sizing_crop) ) {

			$saved_value = $related_products_images_sizing_crop[0]->related_products_images_sizing_crop;

			if (empty($saved_value)) {
				return false;
			}

			return $saved_value;

		} else {
			return $related_products_images_sizing_crop;
		}

	}


	/*

	Gets related_products heading toggle

	*/
	public function get_related_products_images_sizing_scale() {

		$related_products_images_sizing_scale = $this->get_column_single('related_products_images_sizing_scale');

		if ( Utils::array_not_empty($related_products_images_sizing_scale) && isset($related_products_images_sizing_scale[0]->related_products_images_sizing_scale) ) {

			$saved_value = $related_products_images_sizing_scale[0]->related_products_images_sizing_scale;

			if ($saved_value === 0) {
				return false;
			}

			return $saved_value;


		} else {
			return $related_products_images_sizing_scale;
		}

	}


	/*

	Gets related_products heading toggle

	*/
	public function get_enable_custom_checkout_domain() {

		$enable_custom_checkout_domain = $this->get_column_single('enable_custom_checkout_domain');

		if ( Utils::array_not_empty($enable_custom_checkout_domain) && isset($enable_custom_checkout_domain[0]->enable_custom_checkout_domain) ) {
			return (bool) $enable_custom_checkout_domain[0]->enable_custom_checkout_domain;

		} else {
			return $enable_custom_checkout_domain;
		}

	}


	/*

	Gets get_products_compare_at

	*/
	public function get_products_compare_at() {

		$products_compare_at = $this->get_column_single('products_compare_at');

		if ( Utils::array_not_empty($products_compare_at) && isset($products_compare_at[0]->products_compare_at) ) {
			return (bool) $products_compare_at[0]->products_compare_at;

		} else {
			return $products_compare_at;
		}

	}


	/*

	Gets get_products_compare_at

	*/
	public function get_products_show_price_range() {

		$products_show_price_range = $this->get_column_single('products_show_price_range');

		if ( Utils::array_not_empty($products_show_price_range) && isset($products_show_price_range[0]->products_show_price_range) ) {
			return (bool) $products_show_price_range[0]->products_show_price_range;

		} else {
			return $products_show_price_range;
		}

	}


	/*

	Reset syncing timing

	*/
	public function update_products_compare_at($compare_at) {
		return $this->update_column_single( ['products_compare_at' => $compare_at], ['id' => 1] );
	}


	/*

	Reset syncing timing

	*/
	public function update_related_products_heading($heading) {
		return $this->update_column_single( ['related_products_heading' => $heading], ['id' => 1] );
	}


	/*

	Reset syncing timing

	*/
	public function update_related_products_heading_toggle($heading) {
		return $this->update_column_single( ['related_products_heading_toggle' => $heading], ['id' => 1] );
	}


	/*

	Reset syncing timing

	*/
	public function update_collections_heading($heading) {
		return $this->update_column_single( ['collections_heading' => $heading], ['id' => 1] );
	}


	/*

	Reset syncing timing

	*/
	public function update_products_heading($heading) {
		return $this->update_column_single( ['products_heading' => $heading], ['id' => 1] );
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

	update_add_to_cart_color

	*/
	public function update_add_to_cart_color($color) {
		return $this->update_column_single( ['add_to_cart_color' => $color], ['id' => 1] );
	}


	/*

	update_cart_icon_color

	*/
	public function update_cart_icon_color($color) {
		return $this->update_column_single( ['cart_icon_color' => $color], ['id' => 1] );
	}


	/*

	update_cart_icon_fixed_color

	*/
	public function update_cart_icon_fixed_color($color) {
		return $this->update_column_single( ['cart_icon_fixed_color' => $color], ['id' => 1] );
	}


	/*

	update_products_heading_toggle

	*/
	public function update_products_heading_toggle($toggle) {
		return $this->update_column_single( ['products_heading_toggle' => $toggle], ['id' => 1] );
	}


	/*

	update_collections_heading_toggle

	*/
	public function update_collections_heading_toggle($toggle) {
		return $this->update_column_single( ['collections_heading_toggle' => $toggle], ['id' => 1] );
	}


	/*

	update_products_images_sizing_toggle

	*/
	public function update_products_images_sizing_toggle($toggle) {
		return $this->update_column_single( ['products_images_sizing_toggle' => $toggle], ['id' => 1] );
	}


	/*

	update_products_images_sizing_width

	*/
	public function update_products_images_sizing_width($width) {
		return $this->update_column_single( ['products_images_sizing_width' => $width], ['id' => 1] );
	}


	/*

	update_products_images_sizing_height

	*/
	public function update_products_images_sizing_height($height) {
		return $this->update_column_single( ['products_images_sizing_height' => $height], ['id' => 1] );
	}


	/*

	update_products_images_sizing_crop

	*/
	public function update_products_images_sizing_crop($crop) {
		return $this->update_column_single( ['products_images_sizing_crop' => $crop], ['id' => 1] );
	}


	/*

	update_products_images_sizing_scale

	*/
	public function update_products_images_sizing_scale($scale) {
		return $this->update_column_single( ['products_images_sizing_scale' => $scale], ['id' => 1] );
	}


	/*

	update_collections_images_sizing_toggle

	*/
	public function update_collections_images_sizing_toggle($toggle) {
		return $this->update_column_single( ['collections_images_sizing_toggle' => $toggle], ['id' => 1] );
	}


	/*

	update_collections_images_sizing_width

	*/
	public function update_collections_images_sizing_width($width) {
		return $this->update_column_single( ['collections_images_sizing_width' => $width], ['id' => 1] );
	}


	/*

	update_collections_images_sizing_height

	*/
	public function update_collections_images_sizing_height($height) {
		return $this->update_column_single( ['collections_images_sizing_height' => $height], ['id' => 1] );
	}


	/*

	update_collections_images_sizing_crop

	*/
	public function update_collections_images_sizing_crop($crop) {
		return $this->update_column_single( ['collections_images_sizing_crop' => $crop], ['id' => 1] );
	}


	/*

	update_collections_images_sizing_scale

	*/
	public function update_collections_images_sizing_scale($scale) {
		return $this->update_column_single( ['collections_images_sizing_scale' => $scale], ['id' => 1] );
	}


	/*

	update_related_products_images_sizing_toggle

	*/
	public function update_related_products_images_sizing_toggle($toggle) {
		return $this->update_column_single( ['related_products_images_sizing_toggle' => $toggle], ['id' => 1] );
	}


	/*

	update_related_products_images_sizing_width

	*/
	public function update_related_products_images_sizing_width($width) {
		return $this->update_column_single( ['related_products_images_sizing_width' => $width], ['id' => 1] );
	}


	/*

	update_related_products_images_sizing_height

	*/
	public function update_related_products_images_sizing_height($height) {
		return $this->update_column_single( ['related_products_images_sizing_height' => $height], ['id' => 1] );
	}


	/*

	update_related_products_images_sizing_crop

	*/
	public function update_related_products_images_sizing_crop($crop) {
		return $this->update_column_single( ['related_products_images_sizing_crop' => $crop], ['id' => 1] );
	}


	/*

	update_related_products_images_sizing_scale

	*/
	public function update_related_products_images_sizing_scale($scale) {
		return $this->update_column_single( ['related_products_images_sizing_scale' => $scale], ['id' => 1] );
	}


	/*

	update_enable_custom_checkout_domain

	*/
	public function update_enable_custom_checkout_domain($enable) {
		return $this->update_column_single( ['enable_custom_checkout_domain' => $enable], ['id' => 1] );
	}


	/*

	General wrapper for updating a single col value

	*/
	public function update_setting($column_name, $column_value) {
		return $this->update_column_single( [$column_name => $column_value], ['id' => 1] );
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
			products_heading_toggle tinyint(1) unsigned NOT NULL DEFAULT '{$this->default_products_heading_toggle}',
			products_heading varchar(100) NOT NULL DEFAULT '{$this->default_products_heading}',
			collections_heading_toggle tinyint(1) unsigned NOT NULL DEFAULT '{$this->default_collections_heading_toggle}',
			collections_heading varchar(100) NOT NULL DEFAULT '{$this->default_collections_heading}',
			related_products_heading_toggle tinyint(1) unsigned NOT NULL DEFAULT '{$this->default_related_products_heading_toggle}',
			related_products_heading varchar(100) NOT NULL DEFAULT '{$this->default_related_products_heading}',
			products_images_sizing_toggle tinyint(1) unsigned NOT NULL DEFAULT '{$this->default_products_images_sizing_toggle}',
			products_images_sizing_width int(5) unsigned NOT NULL DEFAULT '{$this->default_products_images_sizing_width}',
			products_images_sizing_height int(5) unsigned NOT NULL DEFAULT '{$this->default_products_images_sizing_height}',
			products_images_sizing_crop varchar(100) NOT NULL DEFAULT '{$this->default_products_images_sizing_crop}',
			products_images_sizing_scale int(1) NOT NULL DEFAULT '{$this->default_products_images_sizing_scale}',
			collections_images_sizing_toggle tinyint(1) unsigned NOT NULL DEFAULT '{$this->default_collections_images_sizing_toggle}',
			collections_images_sizing_width int(5) unsigned NOT NULL DEFAULT '{$this->default_collections_images_sizing_width}',
			collections_images_sizing_height int(5) unsigned NOT NULL DEFAULT '{$this->default_collections_images_sizing_height}',
			collections_images_sizing_crop varchar(100) NOT NULL DEFAULT '{$this->default_collections_images_sizing_crop}',
			collections_images_sizing_scale int(1) NOT NULL DEFAULT '{$this->default_collections_images_sizing_scale}',
			related_products_images_sizing_toggle tinyint(1) unsigned NOT NULL DEFAULT '{$this->default_related_products_images_sizing_toggle}',
			related_products_images_sizing_width int(5) unsigned NOT NULL DEFAULT '{$this->default_related_products_images_sizing_width}',
			related_products_images_sizing_height int(5) unsigned NOT NULL DEFAULT '{$this->default_related_products_images_sizing_height}',
			related_products_images_sizing_crop varchar(100) NOT NULL DEFAULT '{$this->default_related_products_images_sizing_crop}',
			related_products_images_sizing_scale int(1) NOT NULL DEFAULT '{$this->default_related_products_images_sizing_scale}',
			enable_custom_checkout_domain tinyint(1) unsigned NOT NULL DEFAULT '{$this->default_enable_custom_checkout_domain}',
			products_compare_at tinyint(1) unsigned NOT NULL DEFAULT '{$this->default_products_compare_at}',
			products_show_price_range tinyint(1) unsigned NOT NULL DEFAULT '{$this->default_products_show_price_range}',
			checkout_button_target varchar(100) NOT NULL DEFAULT '{$this->default_checkout_button_target}',
			show_fixed_cart_tab tinyint(1) NOT NULL DEFAULT '{$this->default_show_fixed_cart_tab}',
			cart_icon_fixed_color varchar(100) NOT NULL DEFAULT '{$this->default_cart_icon_fixed_color}',
			cart_counter_fixed_color varchar(100) NOT NULL DEFAULT '{$this->default_cart_counter_fixed_color}',
			cart_fixed_background_color varchar(100) NOT NULL DEFAULT '{$this->default_cart_fixed_background_color}',
			PRIMARY KEY  (id)
		) ENGINE=InnoDB $collate";

	}


}
