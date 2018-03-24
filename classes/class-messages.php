<?php

namespace WPS;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
	exit;
}

/*

Class Messages

*/
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
	public $message_products_from_collection_not_found;

	public $message_shopify_api_303;
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


	public function __construct() {

		$this->message_nonce_invalid = esc_html__('Error: Your request has been rejected for security reasons. Please clear your browser cache and try again.', 'wp-shopify');

		$this->message_connection_not_syncing = esc_html__('Error: Syncing canceled early at', 'wp-shopify');
		$this->message_connection_not_found = esc_html__('Error: No connection details found. Please reconnect.', 'wp-shopify');
		$this->message_connection_save_error = esc_html__('Error: Unable to save Shopify connection details. Please try refresh your browser and again.', 'wp-shopify');
		$this->message_connection_invalid_access_token = esc_html__('Error: Invalid access token. Please try reconnecting WordPress to your Shopify site.', 'wp-shopify');
		$this->message_connection_disconnect_invalid_access_token = esc_html__('Error: Unable to disconnect Shopify store. Missing or invalid access token.', 'wp-shopify');

		$this->message_syncing_products_error = esc_html__('Error: Syncing canceled early at insert_products().', 'wp-shopify');
		$this->message_syncing_variants_error = esc_html__('Error: Syncing canceled early at insert_variants().', 'wp-shopify');
		$this->message_syncing_options_error = esc_html__('Error: Syncing canceled early at insert_options().', 'wp-shopify');
		$this->message_syncing_orders_error = esc_html__('Error: Syncing canceled early at insert_orders().', 'wp-shopify');
		$this->message_syncing_images_error = esc_html__('Error: Syncing canceled early at insert_images().', 'wp-shopify');
		$this->message_syncing_customers_error = esc_html__('Error: Syncing canceled early at insert_customers().', 'wp-shopify');

		$this->message_delete_shop_error = esc_html__('Warning: Unable to delete shop data.', 'wp-shopify');
		$this->message_delete_connection_error = esc_html__('Warning: Unable to delete connection settings.', 'wp-shopify');
		$this->message_delete_cpt_products_error = esc_html__('Warning: Some products custom post types could not be deleted. Please try again.', 'wp-shopify');
		$this->message_delete_cpt_collections_error = esc_html__('Warning: Some collections custom post types could not be deleted. Please try again.', 'wp-shopify');
		$this->message_delete_product_images_error = esc_html__('Warning: Unable to delete product images.', 'wp-shopify');
		$this->message_delete_product_inventory_error = esc_html__('Warning: Unable to delete product inventory.', 'wp-shopify');
		$this->message_delete_collects_error = esc_html__('Warning: Unable to delete collects.', 'wp-shopify');
		$this->message_insert_collects_error = esc_html__('Warning: Unable to insert certain collects.', 'wp-shopify');
		$this->message_insert_collects_error_missing = esc_html__('Warning: Unable to insert certain collects, none found.', 'wp-shopify');

		$this->message_delete_product_tags_error = esc_html__('Warning: Unable to delete product tags.', 'wp-shopify');
		$this->message_delete_product_options_error = esc_html__('Warning: Unable to delete product options.', 'wp-shopify');
		$this->message_delete_product_variants_error = esc_html__('Warning: Unable to delete product variants.', 'wp-shopify');
		$this->message_delete_products_error = esc_html__('Warning: Unable to delete products.', 'wp-shopify');
		$this->message_delete_custom_collections_error = esc_html__('Warning: Unable to delete custom collections.', 'wp-shopify');
		$this->message_insert_custom_collections_error = esc_html__('Warning: Unable to insert certain custom collections.', 'wp-shopify');
		$this->message_delete_smart_collections_error = esc_html__('Warning: Unable to delete smart collections.', 'wp-shopify');
		$this->message_insert_smart_collections_error = esc_html__('Warning: Unable to insert certain smart collections.', 'wp-shopify');
		$this->message_delete_orders_error = esc_html__('Warning: Unable to delete orders.', 'wp-shopify');
		$this->message_delete_customers_error = esc_html__('Warning: Unable to delete customers.', 'wp-shopify');

		$this->message_products_curency_format_not_found = esc_html__('Error: Currency format not found. Please try again.', 'wp-shopify');
		$this->message_products_out_of_stock = esc_html__('Out of stock. Please choose another product combination.', 'wp-shopify');
		$this->message_products_options_unavailable = esc_html__('Error: Selected option(s) aren\'t available. Please select a different combination.', 'wp-shopify');
		$this->message_products_options_not_found = esc_html__('Error: Unable to find selected options. Please try again.', 'wp-shopify');

		$this->message_webhooks_no_id_set = esc_html__('Error: No webhook ID set. Please try reconnecting WordPress to your Shopify site.', 'wp-shopify');
		$this->message_webhooks_delete_error = esc_html__('Error: Unable to remove webhook', 'wp-shopify');
		$this->message_webhooks_sync_warning = esc_html__('Warning: Unable to sync webhook: ', 'wp-shopify');

		$this->message_license_invalid_or_missing = esc_html__('Error: This license key is either missing or invalid. Please verify your key by logging into your account at wpshop.io.', 'wp-shopify');

		$this->message_license_unable_to_delete = esc_html__('Error: Unable to delete license key. Please refresh your browser and try again.', 'wp-shopify');


		$this->message_smart_collections_not_found = esc_html__('Warning: Unable to sync smart collections, none found.', 'wp-shopify');
		$this->message_custom_collections_not_found = esc_html__('Warning: Unable to sync custom collections, none found.', 'wp-shopify');
		$this->message_message_orders_not_found = esc_html__('Warning: Unable to sync orders, none found.', 'wp-shopify');
		$this->message_message_customers_not_found = esc_html__('Warning: Unable to sync customers, none found.', 'wp-shopify');
		$this->message_shop_not_found = esc_html__('Warning: Unable to sync general shop data, none found.', 'wp-shopify');
		$this->message_products_not_found = esc_html__('Warning: Unable to sync products, none found.', 'wp-shopify');
		$this->message_products_from_collection_not_found = esc_html__('Warning: Unable to find products attached to any collections.', 'wp-shopify');
		$this->message_message_variants_not_found = esc_html__('Warning: Unable to sync variants, none found.', 'wp-shopify');
		$this->message_collects_not_found = esc_html__('Warning: Unable to sync collects, none found.', 'wp-shopify');

		$this->message_orders_insert_error = esc_html__('Warning: Unable to sync 1 or more orders.', 'wp-shopify');


		/*

		Shopify API Errors

		*/
		$this->message_shopify_api_303 = esc_html__('Error: The response to the request is found under a different URI in the Location header and can be retrieved using a GET method on that resource.', 'wp-shopify');
		$this->message_shopify_api_400 = esc_html__('Error: The request was not understood by the server, generally due to bad syntax or because the Content-Type header was not correctly set to application/json.', 'wp-shopify');
		$this->message_shopify_api_401 = esc_html__('Error: The necessary authentication credentials are not present in the request or are incorrect.', 'wp-shopify');
		$this->message_shopify_api_402 = esc_html__('Error: The requested shop is currently frozen.', 'wp-shopify');
		$this->message_shopify_api_403 = esc_html__('Error: The server is refusing to respond to the request. This is generally because you have not requested the appropriate scope for this action.', 'wp-shopify');
		$this->message_shopify_api_404 = esc_html__('Error: The requested resource was not found but could be available again in the future.', 'wp-shopify');
		$this->message_shopify_api_406 = esc_html__('Error: The requested resource is only capable of generating content not acceptable according to the Accept headers sent in the request.', 'wp-shopify');
		$this->message_shopify_api_422 = esc_html__('Error: The request body was well-formed but contains semantical errors. The response body will provide more details in the errors parameter.', 'wp-shopify');
		$this->message_shopify_api_429 = esc_html__('Error: The request was not accepted because the application has exceeded the rate limit. See the API Call Limit documentation for a breakdown of Shopify\'s rate-limiting mechanism.', 'wp-shopify');
		$this->message_shopify_api_500 = esc_html__('Error: An internal error occurred in Shopify. Please post to the API & Technology forum so that Shopify staff can investigate.', 'wp-shopify');
		$this->message_shopify_api_501 = esc_html__('Error: The requested endpoint is not available on that particular shop, e.g. requesting access to a Plus-specific API on a non-Plus shop. This response may also indicate that this endpoint is reserved for future use.', 'wp-shopify');
		$this->message_shopify_api_503 = esc_html__('Error: The server is currently unavailable. Check the status page for reported service outages.', 'wp-shopify');
		$this->message_shopify_api_504 = esc_html__('Error: The request could not complete in time. Try breaking it down in multiple smaller requests.', 'wp-shopify');

		$this->message_shopify_api_generic = esc_html__('Error: An unkown Shopify API response was received during syncing. Please try disconnecting and reconnecting your store.', 'wp-shopify');

	}

}
