<?php

namespace WPS;

use WPS\Options;


if (!defined('ABSPATH')) {
	exit;
}


class Transients {


	public function __construct() {

	}


	/*

	Delete Transient

	*/
	public static function delete_single($name) {
		return delete_transient($name);
	}


	/*

	Set Transient

	$time = 0 = does not expire

	*/
	public static function set($name, $value, $time = 0) {
		return set_transient($name, $value, $time);
	}


	/*

	Get Transient

	*/
	public static function get($name) {
		return get_transient($name);
	}


	/*

	Check Migration Needed

	*/
	public static function database_migration_needed() {
		return Options::get('wp_shopify_migration_needed');
	}


	public static function delete_all_custom_options() {

		global $wpdb;

		$plugin_options = $wpdb->get_results("SELECT option_name FROM $wpdb->options WHERE option_name LIKE 'wp_shopify_%' OR option_name LIKE 'wps_settings_%'" );

		foreach($plugin_options as $option) {
			Options::delete($option->option_name);
		}

	}

	/*

	Deletes custom options

	*/
	public static function delete_custom_options() {

		$results = [];

		$results['wp_shopify_custom_options']	= self::delete_all_custom_options();

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

			return Utils::wp_error([
				'message_lookup' 	=> 'delete_product_prices',
				'call_method' 		=> __METHOD__,
				'call_line' 			=> __LINE__
			]);

		} else {
			return true;
		}

	}


	/*

	Deletes single product cached prices

	*/
	public static function delete_cached_prices() {

		global $wpdb;

		$results = $wpdb->query("DELETE FROM " . $wpdb->options . " WHERE `option_name` LIKE '%_transient_wps_product_price_id_%'");

		if ($results === false) {

			return Utils::wp_error([
				'message_lookup' 	=> 'delete_product_prices',
				'call_method' 		=> __METHOD__,
				'call_line' 			=> __LINE__
			]);

		} else {
			return $results;
		}

	}


	/*

	Delete entire cache

	*/
	public static function delete_short_term_cache() {

		global $wpdb;

		$results = $wpdb->query("DELETE FROM " . $wpdb->options . " WHERE `option_name` LIKE '%_transient_wps_%' OR `option_name` LIKE '%_transient_timeout_wps_%' OR `option_name` LIKE '%_wps_background_processing_process_lock%' OR `option_name` LIKE '%wp_wps_background_processing_batch%' OR `option_name` LIKE '%_transient_wps_async_processing_%' OR `option_name` LIKE '%wp_wps_background_processing%' OR `option_name` LIKE '%wps_sync_by_collections%' OR `option_name` LIKE '%wps_product_data_%'");


		if ($results === false) {

			return Utils::wp_error([
				'message_lookup' 	=> 'delete_all_cache',
				'call_method' 		=> __METHOD__,
				'call_line' 			=> __LINE__
			]);

		} else {
			return true;
		}

	}


	/*

	Clears the general plugin cache

	*/
	public static function delete_long_term_cache() {

		global $wpdb;

		$results = $wpdb->query("DELETE FROM " . $wpdb->options . " WHERE `option_name` LIKE '%wp_shopify_%'");

		if ($results === false) {

			return Utils::wp_error([
				'message_lookup' 	=> 'delete_cache_general',
				'call_method' 		=> __METHOD__,
				'call_line' 			=> __LINE__
			]);

		} else {
			return true;
		}

	}


	/*

	Clears the table exists cache

	*/
	public static function delete_table_exists_cache() {

		global $wpdb;

		$results = $wpdb->query("DELETE FROM " . $wpdb->options . " WHERE `option_name` LIKE '%_transient_wp_shopify_table_exists_%'");

		if ($results === false) {

			return Utils::wp_error([
				'message_lookup' 	=> 'delete_cache_general',
				'call_method' 		=> __METHOD__,
				'call_line' 			=> __LINE__
			]);

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

		$results = $wpdb->query("DELETE FROM " . $wpdb->options . " WHERE `option_name` LIKE '%_transient_wps_product_with_variants_%'");

		if ($results === false) {

			return Utils::wp_error([
				'message_lookup' 	=> 'delete_single_product_variants_cache',
				'call_method' 		=> __METHOD__,
				'call_line' 			=> __LINE__
			]);

		} else {
			return $results;
		}

	}


	/*

	Delete cached settings

	*/
	public static function delete_cached_settings() {

		global $wpdb;

		$results = $wpdb->query("DELETE FROM " . $wpdb->options . " WHERE `option_name` LIKE '%_transient_wps_settings_%' OR `option_name` LIKE '%_transient_wps_table_single_row_%'");

		if ($results === false) {

			return Utils::wp_error([
				'message_lookup' 	=> 'delete_cached_settings',
				'call_method' 		=> __METHOD__,
				'call_line' 			=> __LINE__
			]);

		} else {
			return true;
		}

	}


	/*

	Delete cached product queries

	*/
	public static function delete_cached_product_queries() {

		global $wpdb;

		$results = $wpdb->query("DELETE FROM " . $wpdb->options . " WHERE `option_name` LIKE '%_transient_wp_shopify_products_query_hash_cache_%'");

		if ($results === false) {

			return Utils::wp_error([
				'message_lookup' 	=> 'delete_cached_products_queries',
				'call_method' 		=> __METHOD__,
				'call_line' 			=> __LINE__
			]);

		} else {
			return $results;
		}

	}


	/*

	Delete cached single product options / variants

	*/
	public static function delete_cached_product_single() {

		global $wpdb;

		$results = $wpdb->query("DELETE FROM " . $wpdb->options . " WHERE `option_name` LIKE '%_transient_wps_product_single_%'");

		if ($results === false) {

			return Utils::wp_error([
				'message_lookup' 	=> 'delete_single_product_cache',
				'call_method' 		=> __METHOD__,
				'call_line' 			=> __LINE__
			]);

		} else {
			return $results;
		}

	}


	/*

	Delete all cached single collections

	*/
	public static function delete_cached_single_collections() {

		global $wpdb;

		$results = $wpdb->query("DELETE FROM " . $wpdb->options . " WHERE `option_name` LIKE '%_transient_wps_collection_single_%'");

		if ($results === false) {

			return Utils::wp_error([
				'message_lookup' 	=> 'delete_single_collections_cache',
				'call_method' 		=> __METHOD__,
				'call_line' 			=> __LINE__
			]);

		} else {
			return $results;
		}

	}


	/*

	Delete all cached single collections

	*/
	public static function delete_cached_single_collection_by_id($postID) {

		global $wpdb;

		$results = $wpdb->query("DELETE FROM " . $wpdb->options . " WHERE `option_name` = '_transient_wps_collection_single_" . $postID . "'");

		if ($results === false) {

			return Utils::wp_error([
				'message_lookup' 	=> 'delete_single_collection_cache',
				'call_method' 		=> __METHOD__,
				'call_line' 			=> __LINE__
			]);

		} else {
			return $results;
		}

	}


	/*

	Delete cached single product options / variants

	*/
	public static function delete_cached_single_product_by_id($postID) {

		global $wpdb;

		$resultsSingle = $wpdb->query("DELETE FROM " . $wpdb->options . " WHERE `option_name` = '_transient_wps_product_single_" . $postID . "'");
		$resultsImages = $wpdb->query("DELETE FROM " . $wpdb->options . " WHERE `option_name` = '_transient_wps_product_single_images_" . $postID . "'");
		$resultsTags = $wpdb->query("DELETE FROM " . $wpdb->options . " WHERE `option_name` = '_transient_wps_product_single_tags_" . $postID . "'");
		$resultsVariants = $wpdb->query("DELETE FROM " . $wpdb->options . " WHERE `option_name` = '_transient_wps_product_single_variants_" . $postID . "'");
		$resultsOptions = $wpdb->query("DELETE FROM " . $wpdb->options . " WHERE `option_name` = '_transient_wps_product_single_options_" . $postID . "'");
		$resultsProductData = $wpdb->query("DELETE FROM " . $wpdb->options . " WHERE `option_name` = '_transient_wps_product_data_" . $postID . "'");

		// TODO: Add error + message handling for these two operations
		$resultsAllVariants = $wpdb->query("DELETE FROM " . $wpdb->options . " WHERE `option_name` = '_transient_wps_product_single_all_variants_" . $postID . "'");

		$resultsInStockVariants = $wpdb->query("DELETE FROM " . $wpdb->options . " WHERE `option_name` = '_transient_wps_product_single_variants_in_stock_" . $postID . "'");


		/*

		Need to strictly check false for errors. 0 is returned when no rows were affected

		*/
		if ($resultsSingle === false) {

			return Utils::wp_error([
				'message_lookup' 	=> 'delete_single_product_cache',
				'call_method' 		=> __METHOD__,
				'call_line' 			=> __LINE__
			]);

		}

		if ($resultsImages === false) {

			return Utils::wp_error([
				'message_lookup' 	=> 'delete_single_product_images_cache',
				'call_method' 		=> __METHOD__,
				'call_line' 			=> __LINE__
			]);

		}

		if ($resultsTags === false) {

			return Utils::wp_error([
				'message_lookup' 	=> 'delete_single_product_tags_cache',
				'call_method' 		=> __METHOD__,
				'call_line' 			=> __LINE__
			]);

		}

		if ($resultsVariants === false) {

			return Utils::wp_error([
				'message_lookup' 	=> 'delete_single_product_variants_cache',
				'call_method' 		=> __METHOD__,
				'call_line' 			=> __LINE__
			]);

		}

		if ($resultsOptions === false) {

			return Utils::wp_error([
				'message_lookup' 	=> 'delete_single_product_options_cache',
				'call_method' 		=> __METHOD__,
				'call_line' 			=> __LINE__
			]);

		}

		if ($resultsProductData === false) {

			return Utils::wp_error([
				'message_lookup' 	=> 'delete_product_data_cache',
				'call_method' 		=> __METHOD__,
				'call_line' 			=> __LINE__
			]);

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

		$results = $wpdb->query("DELETE FROM " . $wpdb->options . " WHERE `option_name` LIKE '%_transient_wps_collections_query_hash_cache_%'");

		if ($results === false) {

			return Utils::wp_error([
				'message_lookup' 	=> 'delete_cached_collection_queries',
				'call_method' 		=> __METHOD__,
				'call_line' 			=> __LINE__
			]);

		}

		return $results;

	}


	/*

	Delete cached settings

	TODO: Currently not used

	*/
	public static function delete_cached_connections() {

		global $wpdb;

		$results = $wpdb->query("DELETE FROM " . $wpdb->options . " WHERE `option_name` LIKE '%_transient_wps_connection_%'");

		if ($results === false) {

			return Utils::wp_error([
				'message_lookup' 	=> 'delete_cached_connection',
				'call_method' 		=> __METHOD__,
				'call_line' 			=> __LINE__
			]);

		} else {
			return true;
		}

	}


	/*

	Delete cached admin notices

	*/
	public static function delete_cached_notices() {

		global $wpdb;

		$results = $wpdb->query("DELETE FROM " . $wpdb->options . " WHERE `option_name` LIKE '%_transient_wps_admin_dismissed_notice_%'");

		if ($results === false) {

			return Utils::wp_error([
				'message_lookup' 	=> 'delete_cached_admin_notices',
				'call_method' 		=> __METHOD__,
				'call_line' 			=> __LINE__
			]);

		} else {
			return true;
		}

	}


}
