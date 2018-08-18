<?php

namespace WPS;

if (!defined('ABSPATH')) {
	exit;
}


if ( !class_exists('Config') ) {

	class Config {

		public $plugin_dir_path;
		public $plugin_url;
		public $plugin_name;
		public $plugin_name_full;
		public $plugin_name_full_encoded;
		public $plugin_name_js;
		public $plugin_text_domain;
		public $plugin_version;
		public $plugin_author;
		public $plugin_nonce_action_backend;
		public $plugin_nonce_action_frontend;
		public $plugin_root_file;
		public $plugin_free_root_file;
		public $plugin_file;
		public $plugin_basename;
		public $checkout_base_url;
		public $plugin_env;
		public $languages_folder;
		public $relative_template_dir;
		public $products_post_type_slug;
		public $collections_post_type_slug;
		public $shopify_rate_limit;
		public $plugin_default_currency;
		public $plugin_default_currency_symbol;
		public $cart_cache_expiration;
		public $settings_connection_option_name;
		public $settings_general_option_name;
		public $settings_license_option_name;
		public $plugin_table_name_images;
		public $plugin_table_name_variants;
		public $plugin_table_name_tags;
		public $plugin_table_name_shop;
		public $plugin_table_name_settings_license;
		public $plugin_table_name_settings_general;
		public $plugin_table_name_settings_connection;
		public $plugin_table_name_settings_syncing;
		public $plugin_table_name_products;
		public $plugin_table_name_orders;
		public $plugin_table_name_options;
		public $plugin_table_name_customers;
		public $plugin_table_name_collects;
		public $plugin_table_name_collections_custom;
		public $plugin_table_name_collections_smart;
		public $plugin_table_name_wp_posts;
		public $plugin_table_name_wp_postmeta;
		public $plugin_table_name_wp_term_relationships;
		public $plugin_table_name_wp_options;

		public $fallback_image_alt_text;
		public $total_webhooks_count;

		public function __construct() {

			global $wpdb;

			$this->plugin_dir_path 													= plugin_dir_path( __DIR__ );
			$this->plugin_url 															= plugin_dir_url( __DIR__ );
			$this->plugin_name 															= 'wps';
			$this->plugin_name_full 												= 'WP Shopify';
			$this->plugin_name_full_encoded 								= urlencode($this->plugin_name_full);
			$this->plugin_name_js 													= 'WP_Shopify';
			$this->plugin_text_domain 											= 'wp-shopify';
			$this->plugin_version 													= '1.2.1';
			$this->plugin_author 														= 'WP Shopify';

			$this->plugin_nonce_action_backend 							= 'wp-shopify-backend';
			$this->plugin_nonce_action_frontend 						= 'wp-shopify-frontend';

			$this->plugin_free_folder_name 									= 'wpshopify';
			$this->plugin_pro_folder_name 									= 'wp-shopify-pro';

			$this->plugin_root_file 												= $this->plugin_dir_path . $this->plugin_text_domain . '.php';
			$this->plugin_free_root_file 										= WP_PLUGIN_DIR . '/' . $this->plugin_free_folder_name . '/' . $this->plugin_text_domain . '.php';
			$this->plugin_pro_root_file 										= WP_PLUGIN_DIR . '/' . $this->plugin_pro_folder_name . '/' . $this->plugin_text_domain . '.php';

			$this->plugin_pro_subdirectory_name							= $this->plugin_pro_folder_name . '/' . $this->plugin_text_domain . '.php';
			$this->plugin_free_subdirectory_name						= $this->plugin_free_folder_name . '/' . $this->plugin_text_domain . '.php';

			$this->plugin_file 															= plugin_basename($this->plugin_root_file);
			$this->plugin_basename 													= plugin_basename( $this->plugin_dir_path . $this->plugin_text_domain . '.php' );

			$this->checkout_base_url 												= 'https://checkout.shopify.com';
			$this->plugin_env 															= 'https://wpshop.io';
			$this->languages_folder 												= '/languages/';
			$this->relative_template_dir 										= 'public/templates';
			$this->products_post_type_slug 									= 'wps_products';
			$this->collections_post_type_slug 							= 'wps_collections';
			$this->shopify_rate_limit 											= '39/40';
			$this->plugin_default_currency 									= 'USD';
			$this->plugin_default_currency_symbol 					= '$';
			$this->cart_cache_expiration 										= 259200; // Checkout is cached for three days
			$this->fallback_image_alt_text 									= 'Shop Product';
			$this->total_webhooks_count 										= 27;
			$this->shopify_domain_suffix 										= '.myshopify.com';
			$this->shopify_header_verify_webhooks 					= 'HTTP_X_SHOPIFY_HMAC_SHA256';
			$this->shopify_header_verify_domain 						= 'X-Shopify-Shop-Domain';

			// Settings
			$this->settings_connection_option_name 					= $this->plugin_name . '_settings_connection';
			$this->settings_general_option_name 						= $this->plugin_name . '_settings_general';
			$this->settings_license_option_name 						= $this->plugin_name . '_settings_license';

			$this->plugin_table_name_images									=	$wpdb->prefix . 'wps_images';
			$this->plugin_table_name_variants								=	$wpdb->prefix . 'wps_variants';
			$this->plugin_table_name_tags										=	$wpdb->prefix . 'wps_tags';
			$this->plugin_table_name_shop										=	$wpdb->prefix . 'wps_shop';
			$this->plugin_table_name_settings_license				=	$wpdb->prefix . 'wps_settings_license';
			$this->plugin_table_name_settings_general				=	$wpdb->prefix . 'wps_settings_general';
			$this->plugin_table_name_settings_connection		=	$wpdb->prefix . 'wps_settings_connection';
			$this->plugin_table_name_settings_syncing				=	$wpdb->prefix . 'wps_settings_syncing';
			$this->plugin_table_name_products								=	$wpdb->prefix . 'wps_products';
			$this->plugin_table_name_orders									=	$wpdb->prefix . 'wps_orders';
			$this->plugin_table_name_options								=	$wpdb->prefix . 'wps_options';
			$this->plugin_table_name_customers							=	$wpdb->prefix . 'wps_customers';
			$this->plugin_table_name_collects								=	$wpdb->prefix . 'wps_collects';
			$this->plugin_table_name_collections_custom			=	$wpdb->prefix . 'wps_collections_custom';
			$this->plugin_table_name_collections_smart			=	$wpdb->prefix . 'wps_collections_smart';
			$this->plugin_table_name_wp_posts								=	$wpdb->prefix . 'posts';
			$this->plugin_table_name_wp_postmeta						=	$wpdb->prefix . 'postmeta';
			$this->plugin_table_name_wp_term_relationships	=	$wpdb->prefix . 'term_relationships';
			$this->plugin_table_name_wp_options							=	$wpdb->prefix . 'options';
			$this->plugin_table_migration_suffix						=	'_migrate';


			// $this->plugin_table_name_collections_smart,
			// $this->plugin_table_name_collections_custom,
			// $this->plugin_table_name_shop


			if ( !defined('WPS_SHOPIFY_HEADER_VERIFY_WEBHOOKS') ) {
				define('WPS_SHOPIFY_HEADER_VERIFY_WEBHOOKS', $this->shopify_header_verify_webhooks);
			}

			if ( !defined('WPS_SHOPIFY_HEADER_VERIFY_DOMAIN') ) {
				define('WPS_SHOPIFY_HEADER_VERIFY_DOMAIN', $this->shopify_header_verify_domain);
			}

			if ( !defined('WPS_SETTINGS_CONNECTION_OPTION_NAME') ) {
				define('WPS_SETTINGS_CONNECTION_OPTION_NAME', $this->settings_connection_option_name);
			}

			if ( !defined('WPS_SETTINGS_GENERAL_OPTION_NAME') ) {
				define('WPS_SETTINGS_GENERAL_OPTION_NAME', $this->settings_general_option_name);
			}

			if ( !defined('WPS_SETTINGS_LICENSE_OPTION_NAME') ) {
				define('WPS_SETTINGS_LICENSE_OPTION_NAME', $this->settings_license_option_name);
			}

			if ( !defined('WPS_PLUGIN_NAME') ) {
				define('WPS_PLUGIN_NAME', $this->plugin_name);
			}

			if ( !defined('WPS_PLUGIN_NAME_FULL') ) {
				define('WPS_PLUGIN_NAME_FULL', $this->plugin_name_full);
			}

			if ( !defined('WPS_PLUGIN_NAME_ENCODED') ) {
				define('WPS_PLUGIN_NAME_ENCODED', $this->plugin_name_full_encoded);
			}

			if ( !defined('WPS_PLUGIN_NAME_JS') ) {
				define('WPS_PLUGIN_NAME_JS', $this->plugin_name_js);
			}

			if ( !defined('WPS_PLUGIN_TEXT_DOMAIN') ) {
				define('WPS_PLUGIN_TEXT_DOMAIN', $this->plugin_text_domain);
			}

			if ( !defined('WPS_NEW_PLUGIN_VERSION') ) {
				define('WPS_NEW_PLUGIN_VERSION', $this->plugin_version);
			}

			if ( !defined('WPS_NEW_PLUGIN_AUTHOR') ) {
				define('WPS_NEW_PLUGIN_AUTHOR', $this->plugin_author);
			}

			if ( !defined('WPS_PLUGIN_URL') ) {
				define('WPS_PLUGIN_URL', $this->plugin_url);
			}

			if ( !defined('WPS_TEXT_DOMAIN') ) {
				define('WPS_TEXT_DOMAIN', $this->plugin_text_domain);
			}

			if ( !defined('WPS_PLUGIN_ROOT_PATH') ) {
				define('WPS_PLUGIN_ROOT_PATH', $this->plugin_root_file);
			}

			if ( !defined('WPS_FREE_FILE_ROOT') ) {
				define('WPS_FREE_FILE_ROOT', $this->plugin_free_root_file);
			}

			if ( !defined('WPS_PRO_FILE_ROOT') ) {
				define('WPS_PRO_FILE_ROOT', $this->plugin_pro_root_file);
			}

			if ( !defined('WPS_PLUGIN_DIR_PATH') ) {
				define('WPS_PLUGIN_DIR_PATH', $this->plugin_dir_path);
			}

			if ( !defined('WPS_PLUGIN_URL') ) {
				define('WPS_PLUGIN_URL', $this->plugin_url);
			}

			if ( !defined('WPS_CART_CACHE_EXPIRATION') ) {
				define('WPS_CART_CACHE_EXPIRATION', $this->cart_cache_expiration);
			}

			if ( !defined('WPS_PLUGIN_ENV') ) {
				define('WPS_PLUGIN_ENV', $this->plugin_env);
			}

			if ( !defined('WPS_PLUGIN_ROOT_FILE') ) {
				define('WPS_PLUGIN_ROOT_FILE', $this->plugin_file);
			}

			if ( !defined('WPS_PLUGIN_BASENAME') ) {
				define('WPS_PLUGIN_BASENAME', $this->plugin_basename);
			}

			if ( !defined('WPS_RELATIVE_TEMPLATE_DIR') ) {
				define('WPS_RELATIVE_TEMPLATE_DIR', $this->relative_template_dir);
			}

			if ( !defined('WPS_CHECKOUT_BASE_URL') ) {
				define('WPS_CHECKOUT_BASE_URL', $this->checkout_base_url);
			}

			if ( !defined('WPS_SHOPIFY_RATE_LIMIT') ) {
				define('WPS_SHOPIFY_RATE_LIMIT', $this->shopify_rate_limit);
			}

			if ( !defined('WPS_LANGUAGES_FOLDER') ) {
				define('WPS_LANGUAGES_FOLDER', $this->languages_folder);
			}

			if ( !defined('WPS_PRODUCTS_POST_TYPE_SLUG') ) {
				define('WPS_PRODUCTS_POST_TYPE_SLUG', $this->products_post_type_slug);
			}

			if ( !defined('WPS_COLLECTIONS_POST_TYPE_SLUG') ) {
				define('WPS_COLLECTIONS_POST_TYPE_SLUG', $this->collections_post_type_slug);
			}

			if ( !defined('WPS_TABLE_NAME_IMAGES') ) {
				define('WPS_TABLE_NAME_IMAGES', $this->plugin_table_name_images);
			}

			if ( !defined('WPS_TABLE_NAME_VARIANTS') ) {
				define('WPS_TABLE_NAME_VARIANTS', $this->plugin_table_name_variants);
			}

			if ( !defined('WPS_TABLE_NAME_TAGS') ) {
				define('WPS_TABLE_NAME_TAGS', $this->plugin_table_name_tags);
			}

			if ( !defined('WPS_TABLE_NAME_SHOP') ) {
				define('WPS_TABLE_NAME_SHOP', $this->plugin_table_name_shop);
			}

			if ( !defined('WPS_TABLE_NAME_SETTINGS_LICENSE') ) {
				define('WPS_TABLE_NAME_SETTINGS_LICENSE', $this->plugin_table_name_settings_license);
			}

			if ( !defined('WPS_TABLE_NAME_SETTINGS_GENERAL') ) {
				define('WPS_TABLE_NAME_SETTINGS_GENERAL', $this->plugin_table_name_settings_general);
			}

			if ( !defined('WPS_TABLE_NAME_SETTINGS_CONNECTION') ) {
				define('WPS_TABLE_NAME_SETTINGS_CONNECTION', $this->plugin_table_name_settings_connection);
			}

			if ( !defined('WPS_TABLE_NAME_SETTINGS_SYNCING') ) {
				define('WPS_TABLE_NAME_SETTINGS_SYNCING', $this->plugin_table_name_settings_syncing);
			}

			if ( !defined('WPS_TABLE_NAME_PRODUCTS') ) {
				define('WPS_TABLE_NAME_PRODUCTS', $this->plugin_table_name_products);
			}

			if ( !defined('WPS_TABLE_NAME_ORDERS') ) {
				define('WPS_TABLE_NAME_ORDERS', $this->plugin_table_name_orders);
			}

			if ( !defined('WPS_TABLE_NAME_OPTIONS') ) {
				define('WPS_TABLE_NAME_OPTIONS', $this->plugin_table_name_options);
			}

			if ( !defined('WPS_TABLE_NAME_CUSTOMERS') ) {
				define('WPS_TABLE_NAME_CUSTOMERS', $this->plugin_table_name_customers);
			}

			if ( !defined('WPS_TABLE_NAME_COLLECTS') ) {
				define('WPS_TABLE_NAME_COLLECTS', $this->plugin_table_name_collects);
			}

			if ( !defined('WPS_TABLE_NAME_COLLECTIONS_SMART') ) {
				define('WPS_TABLE_NAME_COLLECTIONS_SMART', $this->plugin_table_name_collections_smart);
			}

			if ( !defined('WPS_TABLE_NAME_COLLECTIONS_CUSTOM') ) {
				define('WPS_TABLE_NAME_COLLECTIONS_CUSTOM', $this->plugin_table_name_collections_custom);
			}

			if ( !defined('WPS_TABLE_NAME_WP_POSTS') ) {
				define('WPS_TABLE_NAME_WP_POSTS', $this->plugin_table_name_wp_posts);
			}

			if ( !defined('WPS_TABLE_NAME_WP_POSTMETA') ) {
				define('WPS_TABLE_NAME_WP_POSTMETA', $this->plugin_table_name_wp_postmeta);
			}

			if ( !defined('WPS_TABLE_NAME_WP_TERM_RELATIONSHIPS') ) {
				define('WPS_TABLE_NAME_WP_TERM_RELATIONSHIPS', $this->plugin_table_name_wp_term_relationships);
			}

			if ( !defined('WPS_TABLE_NAME_WP_OPTIONS') ) {
				define('WPS_TABLE_NAME_WP_OPTIONS', $this->plugin_table_name_wp_options);
			}

			if ( !defined('WPS_DEFAULT_CURRENCY') ) {
				define('WPS_DEFAULT_CURRENCY', $this->plugin_default_currency);
			}

			if ( !defined('WPS_DEFAULT_CURRENCY_SYMBOL') ) {
				define('WPS_DEFAULT_CURRENCY_SYMBOL', $this->plugin_default_currency_symbol);
			}

			if ( !defined('WPS_BACKEND_NONCE_ACTION') ) {
				define('WPS_BACKEND_NONCE_ACTION', $this->plugin_nonce_action_backend);
			}

			if ( !defined('WPS_FRONTEND_NONCE_ACTION') ) {
				define('WPS_FRONTEND_NONCE_ACTION', $this->plugin_nonce_action_frontend);
			}

			if ( !defined('WPS_FALLBACK_IMAGE_ALT_TEXT') ) {
				define('WPS_FALLBACK_IMAGE_ALT_TEXT', $this->fallback_image_alt_text);
			}

			if ( !defined('WPS_TOTAL_WEBHOOKS_COUNT') ) {
				define('WPS_TOTAL_WEBHOOKS_COUNT', $this->total_webhooks_count);
			}

			if ( !defined('WPS_SHOPIFY_DOMAIN_SUFFIX') ) {
				define('WPS_SHOPIFY_DOMAIN_SUFFIX', $this->shopify_domain_suffix);
			}

			if ( !defined('WPS_TABLE_MIGRATION_SUFFIX') ) {
				define('WPS_TABLE_MIGRATION_SUFFIX', $this->plugin_table_migration_suffix);
			}




			if ( !defined('WPS_PRO_SUBDIRECTORY_NAME') ) {
				define('WPS_PRO_SUBDIRECTORY_NAME', $this->plugin_pro_subdirectory_name);
			}

			if ( !defined('WPS_FREE_SUBDIRECTORY_NAME') ) {
				define('WPS_FREE_SUBDIRECTORY_NAME', $this->plugin_free_subdirectory_name);
			}


		}

	}

}
