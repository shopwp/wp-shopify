<?php

namespace WPS;

use WPS\Messages;

if (!defined('ABSPATH')) {
	exit;
}


class Transients {


	public function __construct() {

	}


	/*

	Delete Transient

	*/
	public static function delete_single($transientName) {
		return delete_transient($transientName);
	}


	/*

	Set Transient

	*/
	public static function set($transientName, $value, $time = 0) {
		return set_transient($transientName, $value, $time);
	}


	/*

	Get Transient

	*/
	public static function get($transientName) {
		return get_transient($transientName);
	}


	/*

	check_rewrite_rules

	*/
	public static function check_rewrite_rules() {

		if (get_transient('wps_settings_updated') !== false) {

			flush_rewrite_rules();
			delete_transient('wps_settings_updated');

		}

	}


	/*

	Check Money Format

	*/
	public static function check_money_format() {

		if (get_transient('wps_money_format_updated') !== false) {
			delete_transient('wps_money_format_updated');
		}

	}


	/*

	Check Money Format

	*/
	public static function check_money_with_currency_format() {

		if (get_transient('wps_money_with_currency_format_updated') !== false) {
			delete_transient('wps_money_with_currency_format_updated');
		}

	}


	/*

	Check Migration Needed

	*/
	public static function database_migration_needed() {
		return get_site_option('wp_shopify_migration_needed');
	}


	/*

	Deletes custom options

	*/
	public static function delete_custom_options() {

		$results = [];

		$results['wp_shopify_is_ready'] 					= delete_option('wp_shopify_is_ready');
		$results['wp_shopify_migration_needed'] 	= delete_site_option('wp_shopify_migration_needed');

		return $results;

	}


	/*

	Delete cached prices
	TODO: Currently not used

	*/
	public static function delete_cached_single_product_prices_by_id($productID) {

		global $wpdb;

		$results = $wpdb->query("DELETE FROM $wpdb->options WHERE `option_name` = '_transient_wps_product_price_id_ " . $productID . "'");

		if ($results === false) {
			return Utils::wp_error( Messages::get('delete_product_prices') );

		} else {
			return true;
		}

	}


	/*

	Deletes single product cached prices

	*/
	public static function delete_cached_prices() {

		global $wpdb;

		$results = $wpdb->query("DELETE FROM $wpdb->options WHERE `option_name` LIKE '%\_transient\_wps\_product\_price\_id\_%'");

		if ($results === false) {
			return Utils::wp_error( Messages::get('delete_product_prices') );

		} else {
			return $results;
		}

	}


	/*

	Delete entire cache

	*/
	public static function delete_short_term_cache() {

		global $wpdb;

		$results = $wpdb->query("DELETE FROM $wpdb->options WHERE `option_name` LIKE '%\_transient\_wps\_%' OR `option_name` LIKE '%\_transient\_timeout_\wps\_%' OR `option_name` LIKE '%_wps_background_processing_process_lock%' OR `option_name` LIKE '%wp_wps_background_processing_batch%' OR `option_name` LIKE '%_transient_wps_async_processing_%' OR `option_name` LIKE '%wp_wps_background_processing%' OR `option_name` LIKE '%wps_sync_by_collections%' OR `option_name` LIKE '%wps_product_data_%'");


		if ($results === false) {
			return Utils::wp_error( Messages::get('delete_all_cache') );

		} else {
			return true;
		}

	}


	/*

	Clears the general plugin cache

	*/
	public static function delete_long_term_cache() {

		global $wpdb;

		$results = $wpdb->query("DELETE FROM $wpdb->options WHERE `option_name` LIKE '%\_transient\_wp_shopify\_%'");

		if ($results === false) {
			return Utils::wp_error( Messages::get('delete_cache_general') );

		} else {
			return true;
		}

	}


	/*

	Used within the Async_Processing_Database class

	*/
	public function delete() {
		return self::delete_all_cache();
	}


	/*

	Helper method

	*/
	public static function delete_all_cache() {

		$results = [];

		$results['delete_short_term_cache'] 	= self::delete_short_term_cache();
		$results['delete_long_term_cache'] 		= self::delete_long_term_cache();

		return $results;

	}


	/*

	Delete cached variants

	*/
	public static function delete_cached_variants() {

		global $wpdb;

		$results = $wpdb->query("DELETE FROM $wpdb->options WHERE `option_name` LIKE '%\_transient\_wps\_product\_with\_variants\_%'");

		if ($results === false) {
			return Utils::wp_error( Messages::get('delete_single_product_variants_cache') );

		} else {
			return $results;
		}

	}


	/*

	Delete cached settings

	*/
	public static function delete_cached_settings() {

		global $wpdb;

		$results = $wpdb->query("DELETE FROM $wpdb->options WHERE `option_name` LIKE '%\_transient\_wps\_settings\_%' OR `option_name` LIKE '%\_transient\_wps\_table\_single\_row\_%'");

		if ($results === false) {
			return Utils::wp_error( Messages::get('delete_cached_settings') );

		} else {
			return true;
		}

	}


	/*

	Delete cached product queries

	*/
	public static function delete_cached_product_queries() {

		global $wpdb;

		$results = $wpdb->query("DELETE FROM $wpdb->options WHERE `option_name` LIKE '%\_transient\_wps\_products\_query\_hash\_cache\_%'");

		if ($results === false) {
			return Utils::wp_error( Messages::get('delete_cached_products_queries') );

		} else {
			return $results;
		}

	}


	/*

	Delete cached single product options / variants

	*/
	public static function delete_cached_product_single() {

		global $wpdb;

		$results = $wpdb->query("DELETE FROM $wpdb->options WHERE `option_name` LIKE '%\_transient\_wps\_product\_single\_%'");

		if ($results === false) {
			return Utils::wp_error( Messages::get('delete_single_product_cache') );

		} else {
			return $results;
		}

	}


	/*

	Delete all cached single collections

	*/
	public static function delete_cached_single_collections() {

		global $wpdb;

		$results = $wpdb->query("DELETE FROM $wpdb->options WHERE `option_name` LIKE '%\_transient\_wps\_collection\_single\_%'");

		if ($results === false) {
			return Utils::wp_error( Messages::get('delete_single_collections_cache') );

		} else {
			return $results;
		}

	}


	/*

	Delete all cached single collections

	*/
	public static function delete_cached_single_collection_by_id($postID) {

		global $wpdb;

		$results = $wpdb->query("DELETE FROM $wpdb->options WHERE `option_name` = '_transient_wps_collection_single_" . $postID . "'");

		if ($results === false) {
			return Utils::wp_error( Messages::get('delete_single_collection_cache') );

		} else {
			return $results;
		}

	}


	/*

	Delete cached single product options / variants

	*/
	public static function delete_cached_single_product_by_id($postID) {

		global $wpdb;

		$resultsSingle = $wpdb->query("DELETE FROM $wpdb->options WHERE `option_name` = '_transient_wps_product_single_" . $postID . "'");
		$resultsImages = $wpdb->query("DELETE FROM $wpdb->options WHERE `option_name` = '_transient_wps_product_single_images_" . $postID . "'");
		$resultsTags = $wpdb->query("DELETE FROM $wpdb->options WHERE `option_name` = '_transient_wps_product_single_tags_" . $postID . "'");
		$resultsVariants = $wpdb->query("DELETE FROM $wpdb->options WHERE `option_name` = '_transient_wps_product_single_variants_" . $postID . "'");
		$resultsOptions = $wpdb->query("DELETE FROM $wpdb->options WHERE `option_name` = '_transient_wps_product_single_options_" . $postID . "'");
		$resultsProductData = $wpdb->query("DELETE FROM $wpdb->options WHERE `option_name` = '_transient_wps_product_data_" . $postID . "'");

		// TODO: Add error + message handling for these two operations
		$resultsAllVariants = $wpdb->query("DELETE FROM $wpdb->options WHERE `option_name` = '_transient_wps_product_single_all_variants_" . $postID . "'");
		$resultsInStockVariants = $wpdb->query("DELETE FROM $wpdb->options WHERE `option_name` = '_transient_wps_product_single_variants_in_stock_" . $postID . "'");


		/*

		Need to strictly check false for errors. 0 is returned when no rows were affected

		*/
		if ($resultsSingle === false) {
			return Utils::wp_error( Messages::get('delete_single_product_cache') );
		}

		if ($resultsImages === false) {
			return Utils::wp_error( Messages::get('delete_single_product_images_cache') );
		}

		if ($resultsTags === false) {
			return Utils::wp_error( Messages::get('delete_single_product_tags_cache') );
		}

		if ($resultsVariants === false) {
			return Utils::wp_error( Messages::get('delete_single_product_variants_cache') );
		}

		if ($resultsOptions === false) {
			return Utils::wp_error( Messages::get('delete_single_product_options_cache') );
		}

		if ($resultsProductData === false) {
			return Utils::wp_error( Messages::get('delete_product_data_cache') );
		}

		return [
			$resultsSingle,
			$resultsImages,
			$resultsTags,
			$resultsVariants,
			$resultsOptions,
			$resultsProductData,
			$resultsAllVariants,
			$resultsInStockVariants
		];

	}


	/*

	Delete cached collection queries

	*/
	public static function delete_cached_collection_queries() {

		global $wpdb;

		$results = $wpdb->query("DELETE FROM $wpdb->options WHERE `option_name` LIKE '%\_transient\_wps\_collections\_query\_hash\_cache\_%'");

		if ($results === false) {
			return Utils::wp_error( Messages::get('delete_cached_collection_queries') );
		}

		return $results;

	}


	/*

	Delete cached settings

	TODO: Currently not used

	*/
	public static function delete_cached_connections() {

		global $wpdb;

		$results = $wpdb->query("DELETE FROM $wpdb->options WHERE `option_name` LIKE '%\_transient\_wps\_connection\_%'");

		if ($results === false) {
			return Utils::wp_error( Messages::get('delete_cached_connection') );

		} else {
			return true;
		}

	}


	/*

	Delete cached admin notices

	*/
	public static function delete_cached_notices() {

		global $wpdb;

		$results = $wpdb->query("DELETE FROM $wpdb->options WHERE `option_name` LIKE '%\_transient\_wps\_admin\_dismissed\_notice\_%'");

		if ($results === false) {
			return Utils::wp_error( Messages::get('delete_cached_admin_notices') );

		} else {
			return true;
		}

	}


}
