<?php

namespace WPS;

/*

Class Internationalization

*/
class Messages {

	protected static $instantiated = null;

	public static $message_nonce_invalid = esc_html__('Hmm that request has been rejected for security reasons. Please clear your browser cache and try again.', 'wp-shopify');

	public static $message_connection_not_found = esc_html__('No connection details found. Please reconnect.', 'wp-shopify');
	public static $message_connection_save_error = esc_html__('Unable to save Shopify connection details. Please try refresh your browser and again.', 'wp-shopify');
	public static $message_connection_invalid_access_token = esc_html__('Invalid access token. Please try reconnecting WordPress to your Shopify site.', 'wp-shopify');

	public static $message_syncing_products_error = esc_html__('Syncing canceled early at insert_products(). Please refresh your browser and try again.', 'wp-shopify');
	public static $message_syncing_variants_error = esc_html__('Syncing canceled early at insert_variants(). Please refresh your browser and try again.', 'wp-shopify');
	public static $message_syncing_options_error = esc_html__('Syncing canceled early at insert_options(). Please refresh your browser and try again.', 'wp-shopify');
	public static $message_syncing_orders_error = esc_html__('Syncing canceled early at insert_orders(). Please refresh your browser and try again.', 'wp-shopify');
	public static $message_syncing_customers_error = esc_html__('Syncing canceled early at insert_customers(). Please refresh your browser and try again.', 'wp-shopify');

	public static $message_webhooks_no_id_set = esc_html__('No webhook ID set. Please try reconnecting WordPress to your Shopify site.', 'wp-shopify');



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

}
