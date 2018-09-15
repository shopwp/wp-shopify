<?php

namespace WPS;

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('Messages')) {

	class Messages {

		protected static $instance;

		public static $saving_native_cpt_data;
		public static $app_uninstalled;
		public static $database_migration_needed;
		public static $insecure_connection;
		public static $unable_to_cache_checkout;
		public static $missing_checkout_id;
		public static $delete_single_product_cache;
		public static $delete_single_product_images_cache;
		public static $delete_single_product_tags_cache;
		public static $delete_single_product_variants_cache;
		public static $delete_single_product_options_cache;
		public static $delete_product_prices;
		public static $delete_cached_settings;
		public static $delete_cached_admin_notices;
		public static $delete_cached_connection;
		public static $delete_cached_collection_queries;
		public static $delete_single_collection_cache;
		public static $delete_single_collections_cache;
		public static $delete_cached_products_queries;
		public static $delete_all_cache;
		public static $delete_cache_general;
		public static $delete_product_data_cache;
		public static $nonce_invalid;
		public static $connection_not_syncing;
		public static $connection_not_found;
		public static $connection_save_error;
		public static $connection_invalid_access_token;
		public static $connection_disconnect_invalid_access_token;
		public static $syncing_products_error;
		public static $syncing_variants_error;
		public static $syncing_options_error;
		public static $syncing_orders_error;
		public static $syncing_images_error;
		public static $syncing_customers_error;
		public static $delete_shop_error;
		public static $delete_connection_error;
		public static $delete_cpt_products_error;
		public static $delete_cpt_collections_error;
		public static $delete_product_images_error;
		public static $delete_product_inventory_error;
		public static $delete_collects_error;
		public static $insert_collects_error;
		public static $insert_collects_error_missing;
		public static $delete_product_tags_error;
		public static $delete_product_options_error;
		public static $delete_product_variants_error;
		public static $delete_products_error;
		public static $delete_custom_collections_error;
		public static $insert_custom_collections_error;
		public static $delete_smart_collections_error;
		public static $insert_smart_collections_error;
		public static $delete_orders_error;
		public static $delete_customers_error;
		public static $products_curency_format_not_found;
		public static $products_out_of_stock;
		public static $products_options_unavailable;
		public static $products_options_not_found;
		public static $webhooks_no_id_set;
		public static $webhooks_delete_error;
		public static $webhooks_sync_warning;
		public static $license_invalid_or_missing;
		public static $license_unable_to_delete;
		public static $smart_collections_not_found;
		public static $custom_collections_not_found;
		public static $orders_not_found;
		public static $customers_not_found;
		public static $products_not_found;
		public static $products_from_collection_not_found;
		public static $variants_not_found;
		public static $webhooks_not_found;
		public static $collects_not_found;
		public static $orders_insert_error;
		public static $shopify_api_400;
		public static $shopify_api_401;
		public static $shopify_api_402;
		public static $shopify_api_403;
		public static $shopify_api_404;
		public static $shopify_api_406;
		public static $shopify_api_422;
		public static $shopify_api_429;
		public static $shopify_api_500;
		public static $shopify_api_501;
		public static $shopify_api_503;
		public static $shopify_api_504;
		public static $shopify_api_generic;
		public static $invalid_server_connection;
		public static $syncing_status_missing;
		public static $missing_collects_for_page;
		public static $missing_products_for_page;
		public static $missing_shop_for_page;
		public static $missing_orders_for_page;
		public static $missing_customers_for_page;
		public static $missing_collections_for_page;
		public static $missing_shopify_domain;
		public static $max_allowed_packet;


		public static function get_instance() {

			if (self::$instance == null) {
				self::$instance = new self();
			}

			return self::$instance;

    }


		public static function get($message_name) {

			$Messages = self::get_instance();

			return $Messages::${$message_name};

    }


		public function __construct() {




			/*

			Admin notices

			*/
			self::$saving_native_cpt_data = esc_html__('WP Shopify Warning: Any custom changes made to the post title or post content could potentially be erased as a result of resyncing. Consider making changes to these fields within Shopify instead. Custom fields added either natively by WordPress or through plugins like ACF will NOT be erased upon re-sync.', WPS_PLUGIN_TEXT_DOMAIN);

			self::$app_uninstalled = esc_html__('WP Shopify Warning: It looks like your Shopify private app has been deleted! WP Shopify won\'t continue to work until you create a new one. Disconnect your current store from the Connect tab to clear the old connection and then enter your new credentials.', WPS_PLUGIN_TEXT_DOMAIN);

			self::$database_migration_needed = __('WP Shopify Warning: You need to migrate your database tables to the new version of WP Shopify. It\'s important that you do this before using the plugin or you will encounter errors. <a href="' . admin_url('admin.php?page=wps-settings&activetab=tab-misc') . '" class="">Click here to start the upgrade</a>.', WPS_PLUGIN_TEXT_DOMAIN);


			/*

			Client-side Messages

			*/
			self::$insecure_connection = esc_html__('Sorry, a secure connection could not be established with the store. Please try clearing your browser cache and reloading the page.', WPS_PLUGIN_TEXT_DOMAIN);


			/*

			Cache

			*/
			self::$unable_to_cache_checkout = esc_html__('WP Shopify Warning: Unable to cache checkout.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$missing_checkout_id = esc_html__('WP Shopify Warning: Can\'t find checkout id to cache.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$delete_single_product_cache = esc_html__('WP Shopify Warning: Unable to delete single product cache.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$delete_single_product_images_cache = esc_html__('WP Shopify Warning: Unable to delete single product images cache.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$delete_single_product_tags_cache = esc_html__('WP Shopify Warning: Unable to delete single product tags cache.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$delete_single_product_variants_cache = esc_html__('WP Shopify Warning: Unable to delete single product variants cache.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$delete_single_product_options_cache = esc_html__('WP Shopify Warning: Unable to delete single product options cache.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$delete_product_prices = esc_html__('WP Shopify Warning: Unable to delete cached product prices.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$delete_cached_settings = esc_html__('WP Shopify Warning: Unable to delete cached settings.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$delete_cached_admin_notices = esc_html__('WP Shopify Warning: Unable to delete cached admin notices.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$delete_cached_connection = esc_html__('WP Shopify Warning: Unable to delete cached connection.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$delete_cached_collection_queries = esc_html__('WP Shopify Warning: Unable to delete cached collection queries.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$delete_single_collection_cache = esc_html__('WP Shopify Warning: Unable to delete single cached collection.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$delete_single_collections_cache = esc_html__('WP Shopify Warning: Unable to delete all cached single collections.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$delete_cached_products_queries = esc_html__('WP Shopify Warning: Unable to delete cached product queries.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$delete_all_cache = esc_html__('WP Shopify Warning: Unable to delete all cache, please try again.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$delete_cache_general = esc_html__('WP Shopify Warning: Unable to delete general plugin cache, please try again.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$delete_product_data_cache = esc_html__('WP Shopify Warning: Unable to delete single product data cache. Make sure to manually clear via WP Shopify - Tools.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$nonce_invalid = esc_html__('Error: Your request has been rejected for security reasons. Please clear your browser cache and try again.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$connection_not_syncing = esc_html__('Error: Syncing canceled early. Please refresh the page.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$connection_not_found = esc_html__('Error: No connection details found. Please try reconnecting your Shopify store.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$connection_save_error = esc_html__('Error: Unable to save Shopify connection details. Please refresh your browser and try again.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$connection_invalid_access_token = esc_html__('Error: Invalid access token. Please try reconnecting WordPress to your Shopify site.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$connection_disconnect_invalid_access_token = esc_html__('Error: Unable to disconnect Shopify store. Missing or invalid access token.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$syncing_products_error = esc_html__('Error: Syncing canceled early at insert_products().', WPS_PLUGIN_TEXT_DOMAIN);
			self::$syncing_variants_error = esc_html__('Error: Syncing canceled early at insert_variants().', WPS_PLUGIN_TEXT_DOMAIN);
			self::$syncing_options_error = esc_html__('Error: Syncing canceled early at insert_options().', WPS_PLUGIN_TEXT_DOMAIN);
			self::$syncing_orders_error = esc_html__('Error: Syncing canceled early at insert_orders().', WPS_PLUGIN_TEXT_DOMAIN);
			self::$syncing_images_error = esc_html__('Error: Syncing canceled early at insert_images().', WPS_PLUGIN_TEXT_DOMAIN);
			self::$syncing_customers_error = esc_html__('Error: Syncing canceled early at insert_customers().', WPS_PLUGIN_TEXT_DOMAIN);

			self::$delete_shop_error = esc_html__('Warning: Unable to delete shop data.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$delete_connection_error = esc_html__('Warning: Unable to delete connection settings.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$delete_cpt_products_error = esc_html__('Warning: Some products custom post types could not be deleted. Please try again.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$delete_cpt_collections_error = esc_html__('Warning: Some collections custom post types could not be deleted. Please try again.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$delete_product_images_error = esc_html__('Warning: Unable to delete product images.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$delete_product_inventory_error = esc_html__('Warning: Unable to delete product inventory.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$delete_collects_error = esc_html__('Warning: Unable to delete collects.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$insert_collects_error = esc_html__('Warning: Unable to insert certain collects.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$insert_collects_error_missing = esc_html__('Warning: Unable to insert certain collects, none found.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$delete_product_tags_error = esc_html__('Warning: Unable to delete product tags.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$delete_product_options_error = esc_html__('Warning: Unable to delete product options.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$delete_product_variants_error = esc_html__('Warning: Unable to delete product variants.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$delete_products_error = esc_html__('Warning: Unable to delete products.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$delete_custom_collections_error = esc_html__('Warning: Unable to delete custom collections.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$insert_custom_collections_error = esc_html__('Warning: Unable to insert certain custom collections.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$delete_smart_collections_error = esc_html__('Warning: Unable to delete smart collections.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$insert_smart_collections_error = esc_html__('Warning: Unable to insert certain smart collections.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$delete_orders_error = esc_html__('Warning: Unable to delete orders.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$delete_customers_error = esc_html__('Warning: Unable to delete customers.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$products_curency_format_not_found = esc_html__('Error: Currency format not found. Please try again.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$products_out_of_stock = esc_html__('Sorry, this product variant is out of stock. Please choose another combination.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$products_options_unavailable = esc_html__('Error: Selected option(s) aren\'t available. Please select a different combination.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$products_options_not_found = esc_html__('Error: Unable to find selected options. Please try again.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$webhooks_no_id_set = esc_html__('Error: No webhook ID set. Please try reconnecting WordPress to your Shopify site.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$webhooks_delete_error = esc_html__('Error: Unable to remove webhook', WPS_PLUGIN_TEXT_DOMAIN);
			self::$webhooks_sync_warning = esc_html__('Warning: Unable to sync webhook: ', WPS_PLUGIN_TEXT_DOMAIN);
			self::$license_invalid_or_missing = esc_html__('Error: This license key is either missing or invalid. Please verify your key by logging into your account at wpshop.io.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$license_unable_to_delete = esc_html__('Error: Unable to delete license key. Please refresh your browser and try again.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$smart_collections_not_found = esc_html__('Warning: Unable to sync smart collections, none found.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$custom_collections_not_found = esc_html__('Warning: Unable to sync custom collections, none found.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$orders_not_found = esc_html__('Warning: Unable to sync orders, none found.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$customers_not_found = esc_html__('Warning: Unable to sync customers, none found.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$products_not_found = esc_html__('Warning: Unable to sync products, none found.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$products_from_collection_not_found = esc_html__('Warning: Unable to find products attached to any collections.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$variants_not_found = esc_html__('Warning: Unable to sync variants, none found.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$webhooks_not_found = esc_html__('Warning: Unable to sync webhooks, none found.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$collects_not_found = esc_html__('Warning: Unable to sync collects, none found.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$orders_insert_error = esc_html__('Warning: Unable to sync 1 or more orders.', WPS_PLUGIN_TEXT_DOMAIN);


			/*

			Shopify API Errors

			*/
			self::$shopify_api_400 = esc_html__('400 Error: The request was not understood by the server. Please check <a href="https://wpshop.io/docs/syncing-errors" target="_blank">our documentation</a> for more info on this specific error.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$shopify_api_401 = esc_html__('401 Error: The necessary authentication credentials are not present in the request or are incorrect. Please check <a href="https://wpshop.io/docs/syncing-errors" target="_blank">our documentation</a> for more info on this specific error.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$shopify_api_402 = esc_html__('402 Error: The requested shop is currently frozen. Please check <a href="https://wpshop.io/docs/syncing-errors" target="_blank">our documentation</a> for more info on this specific error.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$shopify_api_403 = esc_html__('403 Error: The server is refusing to respond to the request. This is generally because you have not requested the appropriate scope for this action. Please check <a href="https://wpshop.io/docs/syncing-errors" target="_blank">our documentation</a> for more info on this specific error.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$shopify_api_404 = __('404 Error: The requested resource was not found. Please check <a href="https://wpshop.io/docs/syncing-errors" target="_blank">our documentation</a> for more info on this specific error.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$shopify_api_406 = esc_html__('406 Error: The requested resource contained the wrong HTTP method or an invalid URL. Please check <a href="https://wpshop.io/docs/syncing-errors" target="_blank">our documentation</a> for more info on this specific error.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$shopify_api_422 = esc_html__('422 Error: The request body was well-formed but contains semantical errors. Please check <a href="https://wpshop.io/docs/syncing-errors" target="_blank">our documentation</a> for more info on this specific error.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$shopify_api_429 = esc_html__('429 Error: The request was not accepted because the application has exceeded the rate limit. See the API Call Limit documentation for a breakdown of Shopify\'s rate-limiting mechanism. Please check <a href="https://wpshop.io/docs/syncing-errors" target="_blank">our documentation</a> for more info on this specific error.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$shopify_api_500 = esc_html__('500 Error: An internal error occurred at Shopify. Please check <a href="https://wpshop.io/docs/syncing-errors" target="_blank">our documentation</a> for more info on this specific error.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$shopify_api_501 = esc_html__('501 Error: The requested endpoint is not available on that particular shop. Please check <a href="https://wpshop.io/docs/syncing-errors" target="_blank">our documentation</a> for more info on this specific error.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$shopify_api_503 = esc_html__('503 Error: The server is currently unavailable. Check the Shopify <a href="https://status.shopify.com/" target="_blank">status page</a> for reported service outages. Also please check <a href="https://wpshop.io/docs/syncing-errors" target="_blank">our documentation</a> for more info on this specific error.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$shopify_api_504 = esc_html__('504 Error: The request could not complete in time. Please check <a href="https://wpshop.io/docs/syncing-errors" target="_blank">our documentation</a> for more info on this specific error.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$shopify_api_generic = esc_html__('Error: An unknown Shopify API response was received during syncing. Please try disconnecting and reconnecting your store. Please check <a href="https://wpshop.io/docs/syncing-errors" target="_blank">our documentation</a> for more info on this specific error.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$invalid_server_connection = esc_html__('521 Error: Unable to establish an active connection with the web server. Please check <a href="https://wpshop.io/docs/syncing-errors" target="_blank">our documentation</a> for more info on this specific error.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$syncing_status_missing = esc_html__('Failed to update sync status during the syncing process. Please clear the plugin transient cache and try again. ', WPS_PLUGIN_TEXT_DOMAIN);


			/*

			Missing data warnings during page batch requests

			*/
			self::$missing_collects_for_page = esc_html__('Warning: Some collects were possibly missed during the syncing process. If you notice any absent content, try clearing the plugin cache and resyncing.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$missing_products_for_page = esc_html__('Warning: Some products were possibly missed during the syncing process. If you notice any absent content, try clearing the plugin cache and resyncing.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$missing_shop_for_page = esc_html__('Warning: Some general shop data was possibly missed during the syncing process. If you notice any absent content, try clearing the plugin cache and resyncing.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$missing_orders_for_page = esc_html__('Warning: Some orders were possibly missed during the syncing process. If you notice any absent content, try clearing the plugin cache and resyncing.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$missing_customers_for_page = esc_html__('Warning: Some customers were possibly missed during the syncing process. If you notice any absent content, try clearing the plugin cache and resyncing.', WPS_PLUGIN_TEXT_DOMAIN);
			self::$missing_collections_for_page = esc_html__('Warning: Some collections were possibly missed during the syncing process. If you notice any absent content, try clearing the plugin cache and resyncing.', WPS_PLUGIN_TEXT_DOMAIN);


			/*

			Syncing related

			*/
			self::$missing_shopify_domain = __('Please make sure you\'ve entered your Shopify domain.', WPS_PLUGIN_TEXT_DOMAIN);


			/*

			Server-related errors

			*/
			self::$max_allowed_packet = esc_html__('Error: The data you\'re trying to sync is too large for the database to handle. Try adjusting the "Items per request" option within the plugin settings. Also, please check <a href="https://wpshop.io/docs/syncing-errors" target="_blank">our documentation</a> for more info on this specific error. ', WPS_PLUGIN_TEXT_DOMAIN);

		}


	}

}
