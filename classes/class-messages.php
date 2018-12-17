<?php

namespace WPS;

if (!defined('ABSPATH')) {
	exit;
}


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
	public static $connection_invalid_storefront_access_token;
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
	public static $syncing_status_update_failed;
	public static $missing_collects_for_page;
	public static $missing_products_for_page;
	public static $missing_product_ids;
	public static $missing_shop_for_page;
	public static $missing_orders_for_page;
	public static $missing_customers_for_page;
	public static $missing_collections_for_page;
	public static $missing_webhooks_for_page;

	public static $missing_smart_collections_for_page;
	public static $missing_custom_collections_for_page;

	public static $missing_shopify_domain;
	public static $max_allowed_packet;
	public static $max_post_body_size;
	public static $syncing_docs_check;
	public static $max_column_size_reached;

	public static $migration_table_creation_error;
	public static $migration_table_already_exists;
	public static $charset_not_found;
	public static $unable_to_convert_to_object;
	public static $unable_to_convert_to_array;
	public static $request_url_not_found;
	public static $api_invalid_endpoint;


	/*

	New messages

	*/
	public static $smart_collections_count_not_found;
	public static $custom_collections_count_not_found;
	public static $shop_count_not_found;
	public static $products_count_not_found;
	public static $collects_count_not_found;
	public static $orders_count_not_found;
	public static $customers_count_not_found;
	public static $failed_to_set_post_id_custom_table;
	public static $failed_to_set_lookup_key_post_meta_table;
	public static $max_memory_exceeded;
	public static $wp_cron_disabled;
	public static $failed_to_find_batch;


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


	public static function message_exist($prop) {
		return property_exists(__CLASS__, $prop);
	}



	public static function trace($params) {
		return '<p>This occured while calling: ' . $params['call_method'] . ' on line ' . $params['call_line'] . '</p> ' . self::get('syncing_docs_check');
	}

	public static function get_message_aux($params) {

		if ( array_key_exists('message_aux', $params) ) {
			$message_aux = $params['message_aux'];

		} else {
			$message_aux = '';
		}

		return $message_aux;

	}

	public static function error($params) {

		$message_aux = self::get_message_aux($params);

		if ( !self::message_exist($params['message_lookup']) ) {
			return $params['message_lookup'] . $message_aux . self::trace($params);
		}

		return self::get($params['message_lookup']) . $message_aux . self::trace($params);

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
		self::$nonce_invalid = esc_html__('<b>Error:</b> Your request has been rejected for security reasons. Please clear your browser cache and try again.', WPS_PLUGIN_TEXT_DOMAIN);
		self::$connection_not_syncing = esc_html__('<b>Error:</b> Syncing canceled early. Please refresh the page.', WPS_PLUGIN_TEXT_DOMAIN);
		self::$connection_not_found = esc_html__('<b>Error:</b> No connection details found. Please try reconnecting your Shopify store.', WPS_PLUGIN_TEXT_DOMAIN);
		self::$connection_save_error = esc_html__('<b>Error:</b> Unable to save Shopify connection details. Please refresh your browser and try again.', WPS_PLUGIN_TEXT_DOMAIN);
		self::$connection_invalid_storefront_access_token = esc_html__('<b>Error:</b> Invalid storefront access token. Double check your credentials and try again.', WPS_PLUGIN_TEXT_DOMAIN);
		self::$connection_disconnect_invalid_access_token = esc_html__('<b>Error:</b> Unable to disconnect Shopify store. Missing or invalid access token.', WPS_PLUGIN_TEXT_DOMAIN);
		self::$syncing_products_error = esc_html__('<b>Error:</b> Syncing canceled early at insert_products().', WPS_PLUGIN_TEXT_DOMAIN);
		self::$syncing_variants_error = esc_html__('<b>Error:</b> Syncing canceled early at insert_variants().', WPS_PLUGIN_TEXT_DOMAIN);
		self::$syncing_options_error = esc_html__('<b>Error:</b> Syncing canceled early at insert_options().', WPS_PLUGIN_TEXT_DOMAIN);
		self::$syncing_orders_error = esc_html__('<b>Error:</b> Syncing canceled early at insert_orders().', WPS_PLUGIN_TEXT_DOMAIN);
		self::$syncing_images_error = esc_html__('<b>Error:</b> Syncing canceled early at insert_images().', WPS_PLUGIN_TEXT_DOMAIN);
		self::$syncing_customers_error = esc_html__('<b>Error:</b> Syncing canceled early at insert_customers().', WPS_PLUGIN_TEXT_DOMAIN);

		self::$delete_shop_error = esc_html__('<b>Warning:</b> Unable to delete shop data.', WPS_PLUGIN_TEXT_DOMAIN);
		self::$delete_connection_error = esc_html__('<b>Warning:</b> Unable to delete connection settings.', WPS_PLUGIN_TEXT_DOMAIN);
		self::$delete_cpt_products_error = esc_html__('<b>Warning:</b> Some products custom post types could not be deleted. Please try again.', WPS_PLUGIN_TEXT_DOMAIN);
		self::$delete_cpt_collections_error = esc_html__('<b>Warning:</b> Some collections custom post types could not be deleted. Please try again.', WPS_PLUGIN_TEXT_DOMAIN);
		self::$delete_product_images_error = esc_html__('<b>Warning:</b> Unable to delete product images.', WPS_PLUGIN_TEXT_DOMAIN);
		self::$delete_product_inventory_error = esc_html__('<b>Warning:</b> Unable to delete product inventory.', WPS_PLUGIN_TEXT_DOMAIN);
		self::$delete_collects_error = esc_html__('<b>Warning:</b> Unable to delete collects.', WPS_PLUGIN_TEXT_DOMAIN);
		self::$insert_collects_error = esc_html__('<b>Warning:</b> Unable to insert certain collects.', WPS_PLUGIN_TEXT_DOMAIN);
		self::$insert_collects_error_missing = esc_html__('<b>Warning:</b> Unable to insert certain collects, none found.', WPS_PLUGIN_TEXT_DOMAIN);
		self::$delete_product_tags_error = esc_html__('<b>Warning:</b> Unable to delete product tags.', WPS_PLUGIN_TEXT_DOMAIN);
		self::$delete_product_options_error = esc_html__('<b>Warning:</b> Unable to delete product options.', WPS_PLUGIN_TEXT_DOMAIN);
		self::$delete_product_variants_error = esc_html__('<b>Warning:</b> Unable to delete product variants.', WPS_PLUGIN_TEXT_DOMAIN);
		self::$delete_products_error = esc_html__('<b>Warning:</b> Unable to delete products.', WPS_PLUGIN_TEXT_DOMAIN);
		self::$delete_custom_collections_error = esc_html__('<b>Warning:</b> Unable to delete custom collections.', WPS_PLUGIN_TEXT_DOMAIN);
		self::$insert_custom_collections_error = esc_html__('<b>Warning:</b> Unable to insert certain custom collections.', WPS_PLUGIN_TEXT_DOMAIN);
		self::$delete_smart_collections_error = esc_html__('<b>Warning:</b> Unable to delete smart collections.', WPS_PLUGIN_TEXT_DOMAIN);
		self::$insert_smart_collections_error = esc_html__('<b>Warning:</b> Unable to insert certain smart collections.', WPS_PLUGIN_TEXT_DOMAIN);
		self::$delete_orders_error = esc_html__('<b>Warning:</b> Unable to delete orders.', WPS_PLUGIN_TEXT_DOMAIN);
		self::$delete_customers_error = esc_html__('<b>Warning:</b> Unable to delete customers.', WPS_PLUGIN_TEXT_DOMAIN);
		self::$products_curency_format_not_found = esc_html__('<b>Error:</b> Currency format not found. Please try again.', WPS_PLUGIN_TEXT_DOMAIN);
		self::$products_out_of_stock = esc_html__('Sorry, this product variant is out of stock. Please choose another combination.', WPS_PLUGIN_TEXT_DOMAIN);
		self::$products_options_unavailable = esc_html__('<b>Error:</b> Selected option(s) aren\'t available. Please select a different combination.', WPS_PLUGIN_TEXT_DOMAIN);
		self::$products_options_not_found = esc_html__('<b>Error:</b> Unable to find selected options. Please try again.', WPS_PLUGIN_TEXT_DOMAIN);
		self::$webhooks_no_id_set = esc_html__('<b>Error:</b> No webhook ID set. Please try reconnecting WordPress to your Shopify site.', WPS_PLUGIN_TEXT_DOMAIN);
		self::$webhooks_delete_error = esc_html__('<b>Error:</b> Unable to remove webhook', WPS_PLUGIN_TEXT_DOMAIN);
		self::$webhooks_sync_warning = esc_html__('<b>Warning:</b> Unable to sync webhook: ', WPS_PLUGIN_TEXT_DOMAIN);
		self::$license_invalid_or_missing = esc_html__('<b>Error:</b> This license key is either missing or invalid. Please verify your key by logging into your account at wpshop.io.', WPS_PLUGIN_TEXT_DOMAIN);
		self::$license_unable_to_delete = esc_html__('<b>Error:</b> Unable to delete license key. Please refresh your browser and try again.', WPS_PLUGIN_TEXT_DOMAIN);
		self::$smart_collections_not_found = esc_html__('<b>Warning:</b> Unable to sync smart collections, none found.', WPS_PLUGIN_TEXT_DOMAIN);




















		self::$custom_collections_not_found = esc_html__('<b>Warning:</b> Unable to sync custom collections, none found.', WPS_PLUGIN_TEXT_DOMAIN);
		self::$orders_not_found = esc_html__('<b>Warning:</b> Unable to sync orders, none found.', WPS_PLUGIN_TEXT_DOMAIN);
		self::$customers_not_found = esc_html__('<b>Warning:</b> Unable to sync customers, none found.', WPS_PLUGIN_TEXT_DOMAIN);
		self::$products_not_found = esc_html__('<b>Warning:</b> Unable to sync products, none found.', WPS_PLUGIN_TEXT_DOMAIN);
		self::$products_from_collection_not_found = esc_html__('<b>Warning:</b> Unable to find products attached to any collections.', WPS_PLUGIN_TEXT_DOMAIN);
		self::$variants_not_found = esc_html__('<b>Warning:</b> Unable to sync variants, none found.', WPS_PLUGIN_TEXT_DOMAIN);
		self::$webhooks_not_found = esc_html__('<b>Warning:</b> Unable to sync webhooks, none found.', WPS_PLUGIN_TEXT_DOMAIN);
		self::$collects_not_found = esc_html__('<b>Warning:</b> Unable to sync collects, none found.', WPS_PLUGIN_TEXT_DOMAIN);
		self::$orders_insert_error = esc_html__('<b>Warning:</b> Unable to sync 1 or more orders.', WPS_PLUGIN_TEXT_DOMAIN);


		/*

		Shopify API Errors

		*/
		self::$shopify_api_400 = esc_html__('<b>400 Error:</b> The request was not understood by the server. ', WPS_PLUGIN_TEXT_DOMAIN);
		self::$shopify_api_401 = esc_html__('<b>401 Error:</b> The necessary authentication credentials are not present in the request or are incorrect. ', WPS_PLUGIN_TEXT_DOMAIN);
		self::$shopify_api_402 = esc_html__('<b>402 Error:</b> The requested shop is currently frozen. ', WPS_PLUGIN_TEXT_DOMAIN);
		self::$shopify_api_403 = esc_html__('<b>403 Error:</b> The server is refusing to respond to the request. This is generally because you have not requested the appropriate scope for this action. ', WPS_PLUGIN_TEXT_DOMAIN);
		self::$shopify_api_404 = __('<b>404 Error:</b> The requested resource was not found. ', WPS_PLUGIN_TEXT_DOMAIN);
		self::$shopify_api_406 = esc_html__('<b>406 Error:</b> The requested resource contained the wrong HTTP method or an invalid URL. ', WPS_PLUGIN_TEXT_DOMAIN);
		self::$shopify_api_422 = esc_html__('<b>422 Error:</b> The request body was well-formed but contains semantical errors. ', WPS_PLUGIN_TEXT_DOMAIN);

		self::$api_invalid_endpoint = esc_html__('<p class="wps-syncing-error-message"><b>400 Error:</b> The request endpoint was mal-formed.</p>', WPS_PLUGIN_TEXT_DOMAIN);


		self::$syncing_docs_check = esc_html__('<p class="wps-syncing-docs-check">🔮 Please check our documentation for <a href="https://wpshop.io/docs/syncing-errors" target="_blank">possible solutions to this specific error.</a></p>', WPS_PLUGIN_TEXT_DOMAIN);


		self::$max_post_body_size = esc_html__('<p class="wps-syncing-error-message"><b>413 Error:</b> The Shopify data is too large for your server to handle.</p>', WPS_PLUGIN_TEXT_DOMAIN);



		self::$shopify_api_429 = esc_html__('<b>429 Error:</b> The request was not accepted because the application has exceeded the rate limit. See the API Call Limit documentation for a breakdown of Shopify\'s rate-limiting mechanism.', WPS_PLUGIN_TEXT_DOMAIN);
		self::$shopify_api_500 = esc_html__('<b>500 Error:</b> An internal error occurred at Shopify. ', WPS_PLUGIN_TEXT_DOMAIN);
		self::$shopify_api_501 = esc_html__('<b>501 Error:</b> The requested endpoint is not available on that particular shop. ', WPS_PLUGIN_TEXT_DOMAIN);
		self::$shopify_api_503 = esc_html__('<b>503 Error:</b> The server is currently unavailable. Check the Shopify <a href="https://status.shopify.com/" target="_blank">status page</a> for reported service outages. ', WPS_PLUGIN_TEXT_DOMAIN);
		self::$shopify_api_504 = esc_html__('<b>504 Error:</b> The request could not complete in time. ', WPS_PLUGIN_TEXT_DOMAIN);
		self::$shopify_api_generic = esc_html__('<b>Error:</b> An unknown Shopify API response was received during syncing. Please try disconnecting and reconnecting your store. ', WPS_PLUGIN_TEXT_DOMAIN);
		self::$invalid_server_connection = esc_html__('<b>521 Error:</b> Unable to establish an active connection with the web server. ', WPS_PLUGIN_TEXT_DOMAIN);
		self::$syncing_status_update_failed = esc_html__('Failed to update sync status during the syncing process. Please clear the plugin transient cache and try again. ', WPS_PLUGIN_TEXT_DOMAIN);


		/*

		Missing data warnings during page batch requests

		*/
		self::$missing_collects_for_page = esc_html__('<b>Warning:</b> Some collects were possibly missed during the syncing process. If you notice any absent content, try clearing the plugin cache and resync.', WPS_PLUGIN_TEXT_DOMAIN);

		self::$missing_products_for_page = esc_html__('<b>Warning:</b> Some products were possibly missed during the syncing process. If you notice any absent content, try clearing the plugin cache and resync.', WPS_PLUGIN_TEXT_DOMAIN);

		self::$missing_product_ids = esc_html__('<b>Warning:</b> Some product ids were possibly missing during the syncing process. If you notice any absent content, try clearing the plugin cache and resync.', WPS_PLUGIN_TEXT_DOMAIN);


		self::$missing_shop_for_page = esc_html__('<b>Warning:</b> Some general shop data was possibly missed during the syncing process. If you notice any absent content, try clearing the plugin cache and resync.', WPS_PLUGIN_TEXT_DOMAIN);
		self::$missing_orders_for_page = esc_html__('<b>Warning:</b> Some orders were possibly missed during the syncing process. If you notice any absent content, try clearing the plugin cache and resync.', WPS_PLUGIN_TEXT_DOMAIN);
		self::$missing_customers_for_page = esc_html__('<b>Warning:</b> Some customers were possibly missed during the syncing process. If you notice any absent content, try clearing the plugin cache and resync.', WPS_PLUGIN_TEXT_DOMAIN);
		self::$missing_collections_for_page = esc_html__('<b>Warning:</b> Some collections were possibly missed during the syncing process. If you notice any absent content, try clearing the plugin cache and resync.', WPS_PLUGIN_TEXT_DOMAIN);

		self::$missing_webhooks_for_page = esc_html__('<b>Warning:</b> Some webhooks were possibly missed during the syncing process. If you notice any content not syncing automatically, try using the "Reconnect Automatic Syncing" tool.', WPS_PLUGIN_TEXT_DOMAIN);

		self::$missing_smart_collections_for_page = self::$missing_collections_for_page;
		self::$missing_custom_collections_for_page = self::$missing_collections_for_page;

		/*

		Syncing related

		*/
		self::$missing_shopify_domain = __('Please make sure you\'ve entered your Shopify domain.', WPS_PLUGIN_TEXT_DOMAIN);


		/*

		Server-related errors

		*/
		self::$max_allowed_packet = esc_html__('<b>Database Error:</b> The data you\'re trying to sync is too large for the database to handle. Try adjusting the "Items per request" option within the plugin settings.', WPS_PLUGIN_TEXT_DOMAIN);

		self::$max_memory_exceeded = esc_html__('<p class="wps-syncing-error-message"><b>Server Error:</b> The maximum amount of server memory was exceeded.</p>', WPS_PLUGIN_TEXT_DOMAIN);


		self::$migration_table_creation_error = esc_html__('<p class="wps-syncing-error-message"><b>Database Error:</b> Unable to create migration table.</p>', WPS_PLUGIN_TEXT_DOMAIN);

		self::$migration_table_already_exists = esc_html__('<p class="wps-syncing-error-message"><b>Database Error:</b> Unable to create migration table as it already exists.</p>', WPS_PLUGIN_TEXT_DOMAIN);

		self::$charset_not_found = esc_html__('<p class="wps-syncing-error-message"><b>Database Error:</b> Unable to find charset for table:</p>', WPS_PLUGIN_TEXT_DOMAIN);

		self::$unable_to_convert_to_object = esc_html__('<p class="wps-syncing-error-message"><b>Type Error:</b> Unabled to convert data type to Object.</p>', WPS_PLUGIN_TEXT_DOMAIN);

		self::$unable_to_convert_to_array = esc_html__('<p class="wps-syncing-error-message"><b>Type Error:</b> Unabled to convert data type to Array.</p>', WPS_PLUGIN_TEXT_DOMAIN);


		self::$request_url_not_found = esc_html__('<p class="wps-syncing-error-message"><b>HTTP Error:</b> Request URL not found.</p>', WPS_PLUGIN_TEXT_DOMAIN);









		/*

		New Messages

		*/
		self::$smart_collections_count_not_found = esc_html__('<b>Warning:</b> No Smart Collections were found during sync.', WPS_PLUGIN_TEXT_DOMAIN);

		self::$custom_collections_count_not_found = esc_html__('<b>Warning:</b> No Custom Collections were found during sync.', WPS_PLUGIN_TEXT_DOMAIN);

		self::$shop_count_not_found = esc_html__('<b>Warning:</b> No Shop data was found during sync.', WPS_PLUGIN_TEXT_DOMAIN);

		self::$products_count_not_found = esc_html__('<b>Warning:</b> No Products were found during sync.', WPS_PLUGIN_TEXT_DOMAIN);

		self::$collects_count_not_found = esc_html__('<b>Warning:</b> No Collects were found during sync.', WPS_PLUGIN_TEXT_DOMAIN);

		self::$orders_count_not_found = esc_html__('<b>Warning:</b> No Orders were found during sync.', WPS_PLUGIN_TEXT_DOMAIN);

		self::$customers_count_not_found = esc_html__('<b>Warning:</b> No Customers were found during sync.', WPS_PLUGIN_TEXT_DOMAIN);

		self::$failed_to_set_post_id_custom_table = esc_html__('<b>Warning:</b> Failed to assign Post ID  ', WPS_PLUGIN_TEXT_DOMAIN);

		self::$failed_to_set_lookup_key_post_meta_table = esc_html__('<b>Warning:</b> Failed to assign Shopify ID ', WPS_PLUGIN_TEXT_DOMAIN);

		self::$wp_cron_disabled = esc_html__('<b>Error:</b> The WordPress Cron is disabled.', WPS_PLUGIN_TEXT_DOMAIN);

		self::$failed_to_find_batch = esc_html__('<b>Error:</b> Failed to save batch during processing. ', WPS_PLUGIN_TEXT_DOMAIN);


	}


}
