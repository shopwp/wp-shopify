<?php

namespace WPS;

if (!defined('ABSPATH')) {
	exit;
}


if (!class_exists('Messages')) {

	class Messages {

		public $message_nonce_invalid;
		public $message_connection_not_syncing;
		public $message_connection_not_found;
		public $message_connection_save_error;
		public $message_connection_invalid_access_token;
		public $message_connection_disconnect_invalid_access_token;
		public $message_syncing_products_error;
		public $message_syncing_variants_error;
		public $message_syncing_options_error;
		public $message_syncing_orders_error;
		public $message_syncing_images_error;
		public $message_syncing_customers_error;
		public $message_delete_shop_error;
		public $message_delete_connection_error;
		public $message_delete_cpt_products_error;
		public $message_delete_cpt_collections_error;
		public $message_delete_product_images_error;
		public $message_delete_product_inventory_error;
		public $message_delete_collects_error;
		public $message_insert_collects_error;
		public $message_insert_collects_error_missing;
		public $message_delete_product_tags_error;
		public $message_delete_product_options_error;
		public $message_delete_product_variants_error;
		public $message_delete_products_error;
		public $message_delete_custom_collections_error;
		public $message_insert_custom_collections_error;
		public $message_delete_smart_collections_error;
		public $message_insert_smart_collections_error;
		public $message_delete_orders_error;
		public $message_orders_insert_error;
		public $message_delete_customers_error;
		public $message_products_curency_format_not_found;
		public $message_products_out_of_stock;
		public $message_products_options_unavailable;
		public $message_products_options_not_found;
		public $message_webhooks_no_id_set;
		public $message_webhooks_delete_error;
		public $message_license_invalid_or_missing;
		public $message_license_unable_to_delete;
		public $message_products_not_found;
		public $message_collects_not_found;
		public $message_smart_collections_not_found;
		public $message_custom_collections_not_found;
		public $message_message_orders_not_found;
		public $message_message_customers_not_found;
		public $message_shop_not_found;
		public $message_message_variants_not_found;
		public $message_message_webhooks_not_found;
		public $message_products_from_collection_not_found;
		public $message_shopify_api_400;
		public $message_shopify_api_401;
		public $message_shopify_api_402;
		public $message_shopify_api_403;
		public $message_shopify_api_404;
		public $message_shopify_api_406;
		public $message_shopify_api_422;
		public $message_shopify_api_429;
		public $message_shopify_api_500;
		public $message_shopify_api_501;
		public $message_shopify_api_503;
		public $message_shopify_api_504;
		public $message_shopify_api_generic;
		public $message_delete_single_product_cache;
		public $message_delete_product_prices;
		public $message_delete_cached_products_queries;
		public $message_delete_single_product_images_cache;
		public $message_delete_single_product_tags_cache;
		public $message_delete_single_product_variants_cache;
		public $message_delete_single_product_options_cache;
		public $message_delete_cached_admin_notices;
		public $message_delete_cached_connection;
		public $message_delete_cached_settings;
		public $message_delete_cached_collection_queries;
		public $message_delete_single_collection_cache;
		public $message_delete_single_collections_cache;
		public $message_delete_all_cache;
		public $message_delete_cache_general;
		public $message_saving_native_cpt_data;
		public $message_app_uninstalled;
		public $message_invalid_server_connection;
		public $message_missing_collects_for_page;
		public $message_missing_products_for_page;
		public $message_missing_shop_for_page;
		public $message_missing_orders_for_page;
		public $message_missing_customers_for_page;
		public $message_missing_collections_for_page;
		public $message_insecure_connection;
		public $message_database_migration_needed;


		public function __construct() {

			/*

			Admin notices

			*/
			$this->message_saving_native_cpt_data = esc_html__('WP Shopify Warning: Any custom changes made to the post title or post content could potentially be erased as a result of resyncing. Consider making changes to these fields within Shopify instead. Custom fields added either natively by WordPress or through plugins like ACF will NOT be erased upon re-sync.', WPS_PLUGIN_TEXT_DOMAIN);


			$this->message_app_uninstalled = esc_html__('WP Shopify Warning: It looks like your Shopify private app has been deleted! WP Shopify won\'t continue to work until you create a new one. Disconnect your current store from the Connect tab to clear the old connection and then enter your new credentials.', WPS_PLUGIN_TEXT_DOMAIN);


			$this->message_database_migration_needed = __('WP Shopify Warning: You need to migrate your database tables to the new version of WP Shopify. It\'s important that you do this before using the plugin or you will encounter errors. <a href="' . admin_url('admin.php?page=wps-settings&activetab=tab-misc') . '" class="">Click here to start the upgrade</a>.', WPS_PLUGIN_TEXT_DOMAIN);


			/*

			Client-side Messages

			*/
			$this->message_insecure_connection = esc_html__('Sorry, a secure connection could not be established with the store. Please try clearing your browser cache and reloading the page.', WPS_PLUGIN_TEXT_DOMAIN);



			/*

			Cache

			*/
			$this->message_unable_to_cache_checkout = esc_html__('WP Shopify Warning: Unable to cache checkout.', WPS_PLUGIN_TEXT_DOMAIN);
			$this->message_missing_checkout_id = esc_html__('WP Shopify Warning: Can\'t find checkout id to cache.', WPS_PLUGIN_TEXT_DOMAIN);
			$this->message_delete_single_product_cache = esc_html__('WP Shopify Warning: Unable to delete single product cache.', WPS_PLUGIN_TEXT_DOMAIN);
			$this->message_delete_single_product_images_cache = esc_html__('WP Shopify Warning: Unable to delete single product images cache.', WPS_PLUGIN_TEXT_DOMAIN);
			$this->message_delete_single_product_tags_cache = esc_html__('WP Shopify Warning: Unable to delete single product tags cache.', WPS_PLUGIN_TEXT_DOMAIN);
			$this->message_delete_single_product_variants_cache = esc_html__('WP Shopify Warning: Unable to delete single product variants cache.', WPS_PLUGIN_TEXT_DOMAIN);
			$this->message_delete_single_product_options_cache = esc_html__('WP Shopify Warning: Unable to delete single product options cache.', WPS_PLUGIN_TEXT_DOMAIN);
			$this->message_delete_product_prices = esc_html__('WP Shopify Warning: Unable to delete cached product prices.', WPS_PLUGIN_TEXT_DOMAIN);
			$this->message_delete_cached_settings = esc_html__('WP Shopify Warning: Unable to delete cached settings.', WPS_PLUGIN_TEXT_DOMAIN);
			$this->message_delete_cached_admin_notices = esc_html__('WP Shopify Warning: Unable to delete cached admin notices.', WPS_PLUGIN_TEXT_DOMAIN);
			$this->message_delete_cached_connection = esc_html__('WP Shopify Warning: Unable to delete cached connection.', WPS_PLUGIN_TEXT_DOMAIN);
			$this->message_delete_cached_collection_queries = esc_html__('WP Shopify Warning: Unable to delete cached collection queries.', WPS_PLUGIN_TEXT_DOMAIN);
			$this->message_delete_single_collection_cache = esc_html__('WP Shopify Warning: Unable to delete single cached collection.', WPS_PLUGIN_TEXT_DOMAIN);
			$this->message_delete_single_collections_cache = esc_html__('WP Shopify Warning: Unable to delete all cached single collections.', WPS_PLUGIN_TEXT_DOMAIN);
			$this->message_delete_cached_products_queries = esc_html__('WP Shopify Warning: Unable to delete cached product queries.', WPS_PLUGIN_TEXT_DOMAIN);
			$this->message_delete_all_cache = esc_html__('WP Shopify Warning: Unable to delete all cache, please try again.', WPS_PLUGIN_TEXT_DOMAIN);
			$this->message_delete_cache_general = esc_html__('WP Shopify Warning: Unable to delete general plugin cache, please try again.', WPS_PLUGIN_TEXT_DOMAIN);

			$this->message_nonce_invalid = esc_html__('Error: Your request has been rejected for security reasons. Please clear your browser cache and try again.', WPS_PLUGIN_TEXT_DOMAIN);

			$this->message_connection_not_syncing = esc_html__('Error: Syncing canceled early. Please refresh the page.', WPS_PLUGIN_TEXT_DOMAIN);
			$this->message_connection_not_found = esc_html__('Error: No connection details found. Please try reconnecting your Shopify store.', WPS_PLUGIN_TEXT_DOMAIN);
			$this->message_connection_save_error = esc_html__('Error: Unable to save Shopify connection details. Please refresh your browser and try again.', WPS_PLUGIN_TEXT_DOMAIN);
			$this->message_connection_invalid_access_token = esc_html__('Error: Invalid access token. Please try reconnecting WordPress to your Shopify site.', WPS_PLUGIN_TEXT_DOMAIN);
			$this->message_connection_disconnect_invalid_access_token = esc_html__('Error: Unable to disconnect Shopify store. Missing or invalid access token.', WPS_PLUGIN_TEXT_DOMAIN);

			$this->message_syncing_products_error = esc_html__('Error: Syncing canceled early at insert_products().', WPS_PLUGIN_TEXT_DOMAIN);
			$this->message_syncing_variants_error = esc_html__('Error: Syncing canceled early at insert_variants().', WPS_PLUGIN_TEXT_DOMAIN);
			$this->message_syncing_options_error = esc_html__('Error: Syncing canceled early at insert_options().', WPS_PLUGIN_TEXT_DOMAIN);
			$this->message_syncing_orders_error = esc_html__('Error: Syncing canceled early at insert_orders().', WPS_PLUGIN_TEXT_DOMAIN);
			$this->message_syncing_images_error = esc_html__('Error: Syncing canceled early at insert_images().', WPS_PLUGIN_TEXT_DOMAIN);
			$this->message_syncing_customers_error = esc_html__('Error: Syncing canceled early at insert_customers().', WPS_PLUGIN_TEXT_DOMAIN);

			$this->message_delete_shop_error = esc_html__('Warning: Unable to delete shop data.', WPS_PLUGIN_TEXT_DOMAIN);
			$this->message_delete_connection_error = esc_html__('Warning: Unable to delete connection settings.', WPS_PLUGIN_TEXT_DOMAIN);
			$this->message_delete_cpt_products_error = esc_html__('Warning: Some products custom post types could not be deleted. Please try again.', WPS_PLUGIN_TEXT_DOMAIN);
			$this->message_delete_cpt_collections_error = esc_html__('Warning: Some collections custom post types could not be deleted. Please try again.', WPS_PLUGIN_TEXT_DOMAIN);
			$this->message_delete_product_images_error = esc_html__('Warning: Unable to delete product images.', WPS_PLUGIN_TEXT_DOMAIN);
			$this->message_delete_product_inventory_error = esc_html__('Warning: Unable to delete product inventory.', WPS_PLUGIN_TEXT_DOMAIN);
			$this->message_delete_collects_error = esc_html__('Warning: Unable to delete collects.', WPS_PLUGIN_TEXT_DOMAIN);
			$this->message_insert_collects_error = esc_html__('Warning: Unable to insert certain collects.', WPS_PLUGIN_TEXT_DOMAIN);
			$this->message_insert_collects_error_missing = esc_html__('Warning: Unable to insert certain collects, none found.', WPS_PLUGIN_TEXT_DOMAIN);

			$this->message_delete_product_tags_error = esc_html__('Warning: Unable to delete product tags.', WPS_PLUGIN_TEXT_DOMAIN);
			$this->message_delete_product_options_error = esc_html__('Warning: Unable to delete product options.', WPS_PLUGIN_TEXT_DOMAIN);
			$this->message_delete_product_variants_error = esc_html__('Warning: Unable to delete product variants.', WPS_PLUGIN_TEXT_DOMAIN);
			$this->message_delete_products_error = esc_html__('Warning: Unable to delete products.', WPS_PLUGIN_TEXT_DOMAIN);
			$this->message_delete_custom_collections_error = esc_html__('Warning: Unable to delete custom collections.', WPS_PLUGIN_TEXT_DOMAIN);
			$this->message_insert_custom_collections_error = esc_html__('Warning: Unable to insert certain custom collections.', WPS_PLUGIN_TEXT_DOMAIN);
			$this->message_delete_smart_collections_error = esc_html__('Warning: Unable to delete smart collections.', WPS_PLUGIN_TEXT_DOMAIN);
			$this->message_insert_smart_collections_error = esc_html__('Warning: Unable to insert certain smart collections.', WPS_PLUGIN_TEXT_DOMAIN);
			$this->message_delete_orders_error = esc_html__('Warning: Unable to delete orders.', WPS_PLUGIN_TEXT_DOMAIN);
			$this->message_delete_customers_error = esc_html__('Warning: Unable to delete customers.', WPS_PLUGIN_TEXT_DOMAIN);

			$this->message_products_curency_format_not_found = esc_html__('Error: Currency format not found. Please try again.', WPS_PLUGIN_TEXT_DOMAIN);
			$this->message_products_out_of_stock = esc_html__('Out of stock. Please choose another product combination.', WPS_PLUGIN_TEXT_DOMAIN);
			$this->message_products_options_unavailable = esc_html__('Error: Selected option(s) aren\'t available. Please select a different combination.', WPS_PLUGIN_TEXT_DOMAIN);
			$this->message_products_options_not_found = esc_html__('Error: Unable to find selected options. Please try again.', WPS_PLUGIN_TEXT_DOMAIN);

			$this->message_webhooks_no_id_set = esc_html__('Error: No webhook ID set. Please try reconnecting WordPress to your Shopify site.', WPS_PLUGIN_TEXT_DOMAIN);
			$this->message_webhooks_delete_error = esc_html__('Error: Unable to remove webhook', WPS_PLUGIN_TEXT_DOMAIN);
			$this->message_webhooks_sync_warning = esc_html__('Warning: Unable to sync webhook: ', WPS_PLUGIN_TEXT_DOMAIN);

			$this->message_license_invalid_or_missing = esc_html__('Error: This license key is either missing or invalid. Please verify your key by logging into your account at wpshop.io.', WPS_PLUGIN_TEXT_DOMAIN);

			$this->message_license_unable_to_delete = esc_html__('Error: Unable to delete license key. Please refresh your browser and try again.', WPS_PLUGIN_TEXT_DOMAIN);


			$this->message_smart_collections_not_found = esc_html__('Warning: Unable to sync smart collections, none found.', WPS_PLUGIN_TEXT_DOMAIN);
			$this->message_custom_collections_not_found = esc_html__('Warning: Unable to sync custom collections, none found.', WPS_PLUGIN_TEXT_DOMAIN);
			$this->message_message_orders_not_found = esc_html__('Warning: Unable to sync orders, none found.', WPS_PLUGIN_TEXT_DOMAIN);
			$this->message_message_customers_not_found = esc_html__('Warning: Unable to sync customers, none found.', WPS_PLUGIN_TEXT_DOMAIN);

			$this->message_products_not_found = esc_html__('Warning: Unable to sync products, none found.', WPS_PLUGIN_TEXT_DOMAIN);
			$this->message_products_from_collection_not_found = esc_html__('Warning: Unable to find products attached to any collections.', WPS_PLUGIN_TEXT_DOMAIN);
			$this->message_message_variants_not_found = esc_html__('Warning: Unable to sync variants, none found.', WPS_PLUGIN_TEXT_DOMAIN);
			$this->message_message_webhooks_not_found = esc_html__('Warning: Unable to sync webhooks, none found.', WPS_PLUGIN_TEXT_DOMAIN);
			$this->message_collects_not_found = esc_html__('Warning: Unable to sync collects, none found.', WPS_PLUGIN_TEXT_DOMAIN);

			$this->message_orders_insert_error = esc_html__('Warning: Unable to sync 1 or more orders.', WPS_PLUGIN_TEXT_DOMAIN);


			/*

			Shopify API Errors

			*/
			$this->message_shopify_api_400 = esc_html__('400 Error: The request was not understood by the server. Please check <a href="https://wpshop.io/docs/syncing-errors" target="_blank">our documentation</a> for a potential solution.', WPS_PLUGIN_TEXT_DOMAIN);
			$this->message_shopify_api_401 = esc_html__('401 Error: The necessary authentication credentials are not present in the request or are incorrect. Please check <a href="https://wpshop.io/docs/syncing-errors" target="_blank">our documentation</a> for a potential solution.', WPS_PLUGIN_TEXT_DOMAIN);
			$this->message_shopify_api_402 = esc_html__('402 Error: The requested shop is currently frozen. Please check <a href="https://wpshop.io/docs/syncing-errors" target="_blank">our documentation</a> for a potential solution.', WPS_PLUGIN_TEXT_DOMAIN);
			$this->message_shopify_api_403 = esc_html__('403 Error: The server is refusing to respond to the request. This is generally because you have not requested the appropriate scope for this action. Please check <a href="https://wpshop.io/docs/syncing-errors" target="_blank">our documentation</a> for a potential solution.', WPS_PLUGIN_TEXT_DOMAIN);
			$this->message_shopify_api_404 = __('404 Error: The requested resource was not found. Please check <a href="https://wpshop.io/docs/syncing-errors" target="_blank">our documentation</a> for a potential solution.', WPS_PLUGIN_TEXT_DOMAIN);
			$this->message_shopify_api_406 = esc_html__('406 Error: The requested resource contained the wrong HTTP method or an invalid URL. Please check <a href="https://wpshop.io/docs/syncing-errors" target="_blank">our documentation</a> for a potential solution.', WPS_PLUGIN_TEXT_DOMAIN);
			$this->message_shopify_api_422 = esc_html__('422 Error: The request body was well-formed but contains semantical errors. Please check <a href="https://wpshop.io/docs/syncing-errors" target="_blank">our documentation</a> for a potential solution.', WPS_PLUGIN_TEXT_DOMAIN);
			$this->message_shopify_api_429 = esc_html__('429 Error: The request was not accepted because the application has exceeded the rate limit. See the API Call Limit documentation for a breakdown of Shopify\'s rate-limiting mechanism. Please check <a href="https://wpshop.io/docs/syncing-errors" target="_blank">our documentation</a> for a potential solution.', WPS_PLUGIN_TEXT_DOMAIN);
			$this->message_shopify_api_500 = esc_html__('500 Error: An internal error occurred at Shopify. Please check <a href="https://wpshop.io/docs/syncing-errors" target="_blank">our documentation</a> for a potential solution.', WPS_PLUGIN_TEXT_DOMAIN);
			$this->message_shopify_api_501 = esc_html__('501 Error: The requested endpoint is not available on that particular shop. Please check <a href="https://wpshop.io/docs/syncing-errors" target="_blank">our documentation</a> for a potential solution.', WPS_PLUGIN_TEXT_DOMAIN);
			$this->message_shopify_api_503 = esc_html__('503 Error: The server is currently unavailable. Check the Shopify <a href="https://status.shopify.com/" target="_blank">status page</a> for reported service outages. Also please check <a href="https://wpshop.io/docs/syncing-errors" target="_blank">our documentation</a> for a potential solution.', WPS_PLUGIN_TEXT_DOMAIN);
			$this->message_shopify_api_504 = esc_html__('504 Error: The request could not complete in time. Please check <a href="https://wpshop.io/docs/syncing-errors" target="_blank">our documentation</a> for a potential solution.', WPS_PLUGIN_TEXT_DOMAIN);

			$this->message_shopify_api_generic = esc_html__('Error: An unknown Shopify API response was received during syncing. Please try disconnecting and reconnecting your store. Please check <a href="https://wpshop.io/docs/syncing-errors" target="_blank">our documentation</a> for a potential solution.', WPS_PLUGIN_TEXT_DOMAIN);

			$this->message_invalid_server_connection = esc_html__('521 Error: Unable to establish an active connection with the web server. Please check <a href="https://wpshop.io/docs/syncing-errors" target="_blank">our documentation</a> for a potential solution.', WPS_PLUGIN_TEXT_DOMAIN);

			$this->message_syncing_status_missing = esc_html__('Failed to update sync status during the syncing process. Please clear the plugin transient cache and try again. ', WPS_PLUGIN_TEXT_DOMAIN);


			/*

			Missing data warnings during page batch requests

			*/
			$this->message_missing_collects_for_page = esc_html__('Warning: Some collects were possibly missed during the syncing process. If you notice any absent content, try clearing the plugin cache and resyncing.', WPS_PLUGIN_TEXT_DOMAIN);
			$this->message_missing_products_for_page = esc_html__('Warning: Some products were possibly missed during the syncing process. If you notice any absent content, try clearing the plugin cache and resyncing.', WPS_PLUGIN_TEXT_DOMAIN);
			$this->message_missing_shop_for_page = esc_html__('Warning: Some general shop data was possibly missed during the syncing process. If you notice any absent content, try clearing the plugin cache and resyncing.', WPS_PLUGIN_TEXT_DOMAIN);
			$this->message_missing_orders_for_page = esc_html__('Warning: Some orders were possibly missed during the syncing process. If you notice any absent content, try clearing the plugin cache and resyncing.', WPS_PLUGIN_TEXT_DOMAIN);
			$this->message_missing_customers_for_page = esc_html__('Warning: Some customers were possibly missed during the syncing process. If you notice any absent content, try clearing the plugin cache and resyncing.', WPS_PLUGIN_TEXT_DOMAIN);
			$this->message_missing_collections_for_page = esc_html__('Warning: Some collections were possibly missed during the syncing process. If you notice any absent content, try clearing the plugin cache and resyncing.', WPS_PLUGIN_TEXT_DOMAIN);

		}

	}

}
