<?php

namespace WPS\DB;

use WPS\Utils;
use WPS\Transients;
use WPS\Messages;

if (!defined('ABSPATH')) {
	exit;
}


class Settings_Syncing extends \WPS\DB {

	public $table_name_suffix;
	public $table_name;
	public $version;
	public $primary_key;
	public $lookup_key;
	public $cache_group;
	public $type;

	public $default_primary_key_value;
	public $default_is_syncing;
	public $default_syncing_totals_shop;
	public $default_syncing_totals_smart_collections;
	public $default_syncing_totals_custom_collections;
	public $default_syncing_totals_products;
	public $default_syncing_totals_collects;
	public $default_syncing_totals_orders;
	public $default_syncing_totals_customers;
	public $default_syncing_totals_webhooks;
	public $default_syncing_step_total;
	public $default_syncing_step_current;
	public $default_syncing_current_amounts_shop;
	public $default_syncing_current_amounts_smart_collections;
	public $default_syncing_current_amounts_custom_collections;
	public $default_syncing_current_amounts_products;
	public $default_syncing_current_amounts_collects;
	public $default_syncing_current_amounts_orders;
	public $default_syncing_current_amounts_customers;
	public $default_syncing_current_amounts_webhooks;
	public $default_syncing_start_time;
	public $default_syncing_end_time;
	public $default_syncing_errors;
	public $default_syncing_warnings;
	public $default_finished_webhooks_deletions;
	public $default_finished_product_posts_relationships;
	public $default_finished_collection_posts_relationships;
	public $default_finished_data_deletions;
	public $default_published_product_ids;


	public function __construct() {

		global $wpdb;

		$this->table_name_suffix         														= WPS_TABLE_NAME_SETTINGS_SYNCING;
		$this->table_name         																	= $this->get_table_name();
		$this->version         																			= '1.0';
		$this->primary_key     																			= 'id';
		$this->lookup_key     																			= 'id';
		$this->cache_group     																			= 'wps_db_syncing';
		$this->type     																						= 'settings_syncing';

		$this->default_is_syncing 																	= 0;
		$this->default_syncing_step_total 													= 0;
		$this->default_syncing_step_current 												= 0;
		$this->default_syncing_totals_shop 													= 1;
		$this->default_syncing_totals_smart_collections 						= 0;
		$this->default_syncing_totals_custom_collections 						= 0;
		$this->default_syncing_totals_products 											= 0;
		$this->default_syncing_totals_collects 											= 0;
		$this->default_syncing_totals_orders 												= 0;
		$this->default_syncing_totals_customers 										= 0;
		$this->default_syncing_totals_webhooks 											= 0;
		$this->default_syncing_current_amounts_shop 								= 0;
		$this->default_syncing_current_amounts_smart_collections 		= 0;
		$this->default_syncing_current_amounts_custom_collections 	= 0;
		$this->default_syncing_current_amounts_products 						= 0;
		$this->default_syncing_current_amounts_collects 						= 0;
		$this->default_syncing_current_amounts_orders 							= 0;
		$this->default_syncing_current_amounts_customers 						= 0;
		$this->default_syncing_current_amounts_webhooks 						= 0;
		$this->default_syncing_start_time 													= 0;
		$this->default_syncing_end_time 														= 0;
		$this->default_syncing_errors 															= null;
		$this->default_syncing_warnings 														= null;
		$this->default_finished_webhooks_deletions 									= 0;
		$this->default_finished_product_posts_relationships 				= 0;
		$this->default_finished_collection_posts_relationships 			= 0;
		$this->default_finished_data_deletions 											= 0;
		$this->default_published_product_ids 												= '';

	}


	public function get_columns() {

		return [
			'id'                        										=> '%d',
			'is_syncing'                										=> '%d',
			'syncing_step_total'														=> '%d',
			'syncing_step_current'													=> '%d',
			'syncing_totals_shop'														=> '%d',
			'syncing_totals_smart_collections'							=> '%d',
			'syncing_totals_custom_collections'							=> '%d',
			'syncing_totals_products'												=> '%d',
			'syncing_totals_collects'												=> '%d',
			'syncing_totals_orders'													=> '%d',
			'syncing_totals_customers'											=> '%d',
			'syncing_totals_webhooks'												=> '%d',
			'syncing_current_amounts_shop'									=> '%d',
			'syncing_current_amounts_smart_collections'			=> '%d',
			'syncing_current_amounts_custom_collections'		=> '%d',
			'syncing_current_amounts_products'							=> '%d',
			'syncing_current_amounts_collects'							=> '%d',
			'syncing_current_amounts_orders'								=> '%d',
			'syncing_current_amounts_customers'							=> '%d',
			'syncing_current_amounts_webhooks'							=> '%d',
			'syncing_start_time'														=> '%d',
			'syncing_end_time'															=> '%d',
			'syncing_errors'																=> '%s',
			'syncing_warnings'															=> '%s',
			'finished_webhooks_deletions'										=> '%d',
			'finished_product_posts_relationships'					=> '%d',
			'finished_collection_posts_relationships'				=> '%d',
			'finished_data_deletions'												=> '%d',
			'published_product_ids'													=> '%s'
		];

	}


	public function get_column_defaults() {

		return [
			'is_syncing'                										=> $this->default_is_syncing,
			'syncing_totals_shop'														=> $this->default_syncing_totals_shop,
			'syncing_totals_smart_collections'							=> $this->default_syncing_totals_smart_collections,
			'syncing_totals_custom_collections'							=> $this->default_syncing_totals_custom_collections,
			'syncing_totals_products'												=> $this->default_syncing_totals_products,
			'syncing_totals_collects'												=> $this->default_syncing_totals_collects,
			'syncing_totals_orders'													=> $this->default_syncing_totals_orders,
			'syncing_totals_customers'											=> $this->default_syncing_totals_customers,
			'syncing_totals_webhooks'												=> $this->default_syncing_totals_webhooks,
			'syncing_step_total'														=> $this->default_syncing_step_total,
			'syncing_step_current'													=> $this->default_syncing_step_current,
			'syncing_current_amounts_shop'									=> $this->default_syncing_current_amounts_shop,
			'syncing_current_amounts_smart_collections'			=> $this->default_syncing_current_amounts_smart_collections,
			'syncing_current_amounts_custom_collections'		=> $this->default_syncing_current_amounts_custom_collections,
			'syncing_current_amounts_products'							=> $this->default_syncing_current_amounts_products,
			'syncing_current_amounts_collects'							=> $this->default_syncing_current_amounts_collects,
			'syncing_current_amounts_orders'								=> $this->default_syncing_current_amounts_orders,
			'syncing_current_amounts_customers'							=> $this->default_syncing_current_amounts_customers,
			'syncing_current_amounts_webhooks'							=> $this->default_syncing_current_amounts_webhooks,
			'syncing_start_time'														=> $this->default_syncing_start_time,
			'syncing_end_time'															=> $this->default_syncing_end_time,
			'syncing_errors'																=> $this->default_syncing_errors,
			'syncing_warnings'															=> $this->default_syncing_warnings,
			'finished_webhooks_deletions'										=> $this->default_finished_webhooks_deletions,
			'finished_product_posts_relationships'					=> $this->default_finished_product_posts_relationships,
			'finished_collection_posts_relationships'				=> $this->default_finished_collection_posts_relationships,
			'finished_data_deletions'												=> $this->default_finished_data_deletions,
			'published_product_ids'													=> $this->default_published_product_ids
		];

	}



	/*

	Get the Shop syncing totals

	*/
	public function get_syncing_totals_shop() {

		$syncing_totals_shop = $this->get_column_single('syncing_totals_shop');

		if ( Utils::array_not_empty($syncing_totals_shop) && isset($syncing_totals_shop[0]->syncing_totals_shop) ) {
			return $syncing_totals_shop[0]->syncing_totals_shop;

		} else {
			return 0;
		}

	}


	/*

	Get the Smart Collections syncing totals

	*/
	public function get_syncing_totals_smart_collections() {

		$syncing_totals_smart_collections = $this->get_column_single('syncing_totals_smart_collections');

		if ( Utils::array_not_empty($syncing_totals_smart_collections) && isset($syncing_totals_smart_collections[0]->syncing_totals_smart_collections) ) {
			return $syncing_totals_smart_collections[0]->syncing_totals_smart_collections;

		} else {
			return 0;
		}

	}


	/*

	Get the Products syncing totals

	*/
	public function get_syncing_totals_products() {

		$syncing_totals_products = $this->get_column_single('syncing_totals_products');

		if ( Utils::array_not_empty($syncing_totals_products) && isset($syncing_totals_products[0]->syncing_totals_products) ) {
			return $syncing_totals_products[0]->syncing_totals_products;

		} else {
			return 0;
		}

	}


	/*

	Get the Collects syncing totals

	*/
	public function get_syncing_totals_collects() {

		$syncing_totals_collects = $this->get_column_single('syncing_totals_collects');

		if ( Utils::array_not_empty($syncing_totals_collects) && isset($syncing_totals_collects[0]->syncing_totals_collects) ) {
			return $syncing_totals_collects[0]->syncing_totals_collects;

		} else {
			return 0;
		}

	}


	/*

	Get the Orders syncing totals

	*/
	public function get_syncing_totals_orders() {

		$syncing_totals_orders = $this->get_column_single('syncing_totals_orders');

		if ( Utils::array_not_empty($syncing_totals_orders) && isset($syncing_totals_orders[0]->syncing_totals_orders) ) {
			return $syncing_totals_orders[0]->syncing_totals_orders;

		} else {
			return 0;
		}

	}


	/*

	Get the Customers syncing totals

	*/
	public function get_syncing_totals_customers() {

		$syncing_totals_customers = $this->get_column_single('syncing_totals_customers');

		if ( Utils::array_not_empty($syncing_totals_customers) && isset($syncing_totals_customers[0]->syncing_totals_customers) ) {
			return $syncing_totals_customers[0]->syncing_totals_customers;

		} else {
			return 0;
		}

	}


	/*

	Get the Webhooks syncing totals

	*/
	public function get_syncing_totals_webhooks() {

		$syncing_totals_webhooks = $this->get_column_single('syncing_totals_webhooks');

		if ( Utils::array_not_empty($syncing_totals_webhooks) && isset($syncing_totals_webhooks[0]->syncing_totals_webhooks) ) {
			return $syncing_totals_webhooks[0]->syncing_totals_webhooks;

		} else {
			return 0;
		}

	}


	/*

	Get the syncing totals

	*/
	public function syncing_totals() {

		return [
			'shop' 									=> $this->get_syncing_totals_shop(),
			'smart_collections' 		=> $this->get_syncing_totals_smart_collections(),
			'custom_collections' 		=> $this->get_syncing_totals_custom_collections(),
			'products' 							=> $this->get_syncing_totals_products(),
			'collects' 							=> $this->get_syncing_totals_collects(),
		];

	}


	/*

	Get the syncing totals

	*/
	public function set_syncing_totals($counts, $exclusions = []) {

		$counts_shop = isset($counts['shop']) ? $counts['shop'] : 0;
		$counts_smart_collections = isset($counts['smart_collections']) ? $counts['smart_collections'] : 0;
		$counts_custom_collections = isset($counts['custom_collections']) ? $counts['custom_collections'] : 0;
		$counts_products = isset($counts['products']) ? $counts['products'] : 0;
		$counts_collects = isset($counts['collects']) ? $counts['collects'] : 0;
		$counts_orders = isset($counts['orders']) ? $counts['orders'] : 0;
		$counts_customers = isset($counts['customers']) ? $counts['customers'] : 0;
		$counts_webhooks = isset($counts['webhooks']) ? $counts['webhooks'] : 0;


		if ($exclusions) {
			$exclusions = array_flip($exclusions);
		}

		$shop_totals = $exclusions && isset($exclusions['shop']) ? 0 : $counts_shop;
		$smart_collections_totals = $exclusions && isset($exclusions['smart_collections']) ? 0 : ($counts_smart_collections * 2);
		$custom_collections_totals = $exclusions && isset($exclusions['custom_collections']) ? 0 : ($counts_custom_collections * 2);
		$products_totals = $exclusions && isset($exclusions['products']) ? 0 : ($counts_products * 6);
		$collects_totals = $exclusions && isset($exclusions['collects']) ? 0 : $counts_collects;
		$orders_totals = $exclusions && isset($exclusions['orders']) ? 0 : $counts_orders;
		$customers_totals = $exclusions && isset($exclusions['customers']) ? 0 : $counts_customers;
		$webhooks_totals = $exclusions && isset($exclusions['webhooks']) ? 0 : $counts_webhooks;


		return [
			'shop' => $this->update_column_single(['syncing_totals_shop' => $shop_totals], ['id' => 1]),
			'smart_collections' => $this->update_column_single(['syncing_totals_smart_collections' => $smart_collections_totals], ['id' => 1]),
			'custom_collections' => $this->update_column_single(['syncing_totals_custom_collections' => $custom_collections_totals], ['id' => 1]),
			'products' => $this->update_column_single(['syncing_totals_products' => $products_totals], ['id' => 1]),
			'collects' => $this->update_column_single(['syncing_totals_collects' => $collects_totals], ['id' => 1]),
		];

	}


	/*

	Reset syncing totals

	*/
	public function reset_syncing_totals() {

		return [
			'shop' => $this->update_column_single(['syncing_totals_shop' => 0], ['id' => 1]),
			'smart_collections' => $this->update_column_single(['syncing_totals_smart_collections' => 0], ['id' => 1]),
			'custom_collections' => $this->update_column_single(['syncing_totals_custom_collections' => 0], ['id' => 1]),
			'products' => $this->update_column_single(['syncing_totals_products' => 0], ['id' => 1]),
			'collects' => $this->update_column_single(['syncing_totals_collects' => 0], ['id' => 1]),
		];

	}


	/*

	Reset syncing timing

	*/
	public function reset_syncing_timing() {

		return [
			'syncing_start_time' 	=> $this->update_column_single(['syncing_start_time' => 0], ['id' => 1]),
			'syncing_end_time' 		=> $this->update_column_single(['syncing_end_time' => 0], ['id' => 1])
		];

	}


	/*

	Reset syncing posts relationships

	*/
	public function reset_syncing_posts_relationships() {

		return [
			'syncing_start_time' 	=> $this->update_column_single(['finished_product_posts_relationships' => 0], ['id' => 1]),
			'syncing_end_time' 		=> $this->update_column_single(['finished_collection_posts_relationships' => 0], ['id' => 1])
		];

	}


	/*

	Reset syncing posts relationships

	*/
	public function reset_syncing_published_product_ids() {
		return $this->update_column_single(['published_product_ids' => ''], ['id' => 1]);
	}


	/*

	Reset syncing notices + errors

	*/
	public function reset_syncing_notices() {

		return [
			'syncing_errors' 			=> $this->update_column_single(['syncing_errors' => false], ['id' => 1]),
			'syncing_warnings' 		=> $this->update_column_single(['syncing_warnings' => false], ['id' => 1])
		];

	}


	/*

	Gets any syncing errors

	*/
	public function get_syncing_errors() {

		$syncing_errors = $this->get_column_single('syncing_errors');

		if ( Utils::array_not_empty($syncing_errors) && isset($syncing_errors[0]->syncing_errors) ) {
			return $syncing_errors[0]->syncing_errors;

		} else {
			return false;
		}

	}


	/*

	Gets any syncing warnings

	*/
	public function get_syncing_warnings() {

		$syncing_warnings = $this->get_column_single('syncing_warnings');

		if ( Utils::array_not_empty($syncing_warnings) && isset($syncing_warnings[0]->syncing_warnings) ) {
			return $syncing_warnings[0]->syncing_warnings;

		} else {
			return false;
		}

	}


	/*

	Gets syncing notices

	*/
	public function syncing_notices() {

		return [
			'syncing_errors' 			=> maybe_unserialize( $this->get_syncing_errors() ),
			'syncing_warnings' 		=> maybe_unserialize( $this->get_syncing_warnings() )
		];


	}


	/*

	Gets the get_syncing_current_amounts_products

	*/
	public function get_syncing_current_amounts_products() {

		$syncing_current_amounts_products = $this->get_column_single('syncing_current_amounts_products');

		if ( Utils::array_not_empty($syncing_current_amounts_products) && isset($syncing_current_amounts_products[0]->syncing_current_amounts_products) ) {
			return $syncing_current_amounts_products[0]->syncing_current_amounts_products;

		} else {
			return false;
		}

	}


	/*

	Checks if syncing products or not

	*/
	public function is_syncing_products() {

		$syncing_current_amounts_products = $this->get_syncing_current_amounts_products();

		if ($syncing_current_amounts_products !== false && $syncing_current_amounts_products !== '0') {
			return true;

		} else {
			return false;
		}

	}








	/*

	Gets the get_syncing_current_amounts_smart_collections

	*/
	public function get_syncing_current_amounts_smart_collections() {

		$syncing_current_amounts_smart_collections = $this->get_column_single('syncing_current_amounts_smart_collections');

		if ( Utils::array_not_empty($syncing_current_amounts_smart_collections) && isset($syncing_current_amounts_smart_collections[0]->syncing_current_amounts_smart_collections) ) {
			return $syncing_current_amounts_smart_collections[0]->syncing_current_amounts_smart_collections;

		} else {
			return false;
		}

	}


	/*

	Checks whether we're currently syncing collections

	*/
	public function is_syncing_collections() {

		$smart_collections = $this->get_syncing_current_amounts_smart_collections();
		$custom_collections = $this->get_syncing_current_amounts_custom_collections();

		if ($smart_collections !== false && $smart_collections !== '0' || $custom_collections !== false && $custom_collections !== '0') {
			return true;

		} else {
			return false;
		}

	}



	/*

	Gets the get_syncing_current_amounts_smart_collections

	*/
	public function get_syncing_current_amounts_shop() {

		$syncing_current_amounts_shop = $this->get_column_single('syncing_current_amounts_shop');

		if ( Utils::array_not_empty($syncing_current_amounts_shop) && isset($syncing_current_amounts_shop[0]->syncing_current_amounts_shop) ) {
			return $syncing_current_amounts_shop[0]->syncing_current_amounts_shop;

		} else {
			return 0;
		}

	}


	/*

	Gets the get_syncing_current_amounts_smart_collections

	*/
	public function get_syncing_current_amounts_custom_collections() {

		$syncing_current_amounts_custom_collections = $this->get_column_single('syncing_current_amounts_custom_collections');

		if ( Utils::array_not_empty($syncing_current_amounts_custom_collections) && isset($syncing_current_amounts_custom_collections[0]->syncing_current_amounts_custom_collections) ) {
			return $syncing_current_amounts_custom_collections[0]->syncing_current_amounts_custom_collections;

		} else {
			return 0;
		}

	}


	/*

	Gets the get_syncing_current_amounts_smart_collections

	*/
	public function get_syncing_current_amounts_collects() {

		$syncing_current_amounts_collects = $this->get_column_single('syncing_current_amounts_collects');

		if ( Utils::array_not_empty($syncing_current_amounts_collects) && isset($syncing_current_amounts_collects[0]->syncing_current_amounts_collects) ) {
			return $syncing_current_amounts_collects[0]->syncing_current_amounts_collects;

		} else {
			return 0;
		}

	}


	public function add_to_current_collects_amount($collects_to_increment) {

		// Don't update if not needed
		if ($collects_to_increment <= 0) {
			return;
		}

		$current_amount_total = $this->get_syncing_current_amounts_collects();

		$current_amount_total_new = $current_amount_total + $collects_to_increment;

		return $this->update_column_single(['syncing_current_amounts_collects' => $current_amount_total_new], ['id' => 1]);

	}


	/*

	Gets the get_syncing_current_amounts_smart_collections

	*/
	public function get_syncing_current_amounts_orders() {

		$syncing_current_amounts_orders = $this->get_column_single('syncing_current_amounts_orders');

		if ( Utils::array_not_empty($syncing_current_amounts_orders) && isset($syncing_current_amounts_orders[0]->syncing_current_amounts_orders) ) {
			return $syncing_current_amounts_orders[0]->syncing_current_amounts_orders;

		} else {
			return 0;
		}

	}


	/*

	Gets the get_syncing_current_amounts_smart_collections

	*/
	public function get_syncing_current_amounts_customers() {

		$syncing_current_amounts_customers = $this->get_column_single('syncing_current_amounts_customers');

		if ( Utils::array_not_empty($syncing_current_amounts_customers) && isset($syncing_current_amounts_customers[0]->syncing_current_amounts_customers) ) {
			return $syncing_current_amounts_customers[0]->syncing_current_amounts_customers;

		} else {
			return 0;
		}

	}


	/*

	Gets the get_syncing_current_amounts_smart_collections

	*/
	public function get_syncing_current_amounts_webhooks() {

		$syncing_current_amounts_webhooks = $this->get_column_single('syncing_current_amounts_webhooks');

		if ( Utils::array_not_empty($syncing_current_amounts_webhooks) && isset($syncing_current_amounts_webhooks[0]->syncing_current_amounts_webhooks) ) {
			return $syncing_current_amounts_webhooks[0]->syncing_current_amounts_webhooks;

		} else {
			return 0;
		}

	}


	/*

	Get the syncing current amounts

	*/
	public function syncing_current_amounts() {

		return [
			'shop' 											=> $this->get_syncing_current_amounts_shop(),
			'smart_collections' 				=> $this->get_syncing_current_amounts_smart_collections(),
			'custom_collections' 				=> $this->get_syncing_current_amounts_custom_collections(),
			'products' 									=> $this->get_syncing_current_amounts_products(),
			'collects' 									=> $this->get_syncing_current_amounts_collects(),
		];

	}


	/*

	Reset syncing current amounts

	*/
	public function reset_syncing_current_amounts() {

		return [
			'shop' => $this->update_column_single(['syncing_current_amounts_shop' => 0], ['id' => 1]),
			'smart_collections' => $this->update_column_single(['syncing_current_amounts_smart_collections' => 0], ['id' => 1]),
			'custom_collections' => $this->update_column_single(['syncing_current_amounts_custom_collections' => 0], ['id' => 1]),
			'products' => $this->update_column_single(['syncing_current_amounts_products' => 0], ['id' => 1]),
			'collects' => $this->update_column_single(['syncing_current_amounts_collects' => 0], ['id' => 1]),
		];

	}


	/*

	Gets the get_syncing_current_amounts_smart_collections

	*/
	public function get_finished_product_posts_relationships() {

		$finished_product_posts_relationships = $this->get_column_single('finished_product_posts_relationships');

		if ( Utils::array_not_empty($finished_product_posts_relationships) && isset($finished_product_posts_relationships[0]->finished_product_posts_relationships) ) {
			return (boolean) $finished_product_posts_relationships[0]->finished_product_posts_relationships;

		} else {
			return false;
		}

	}


	/*

	Gets the get_syncing_current_amounts_smart_collections

	*/
	public function get_finished_collection_posts_relationships() {

		$finished_collection_posts_relationships = $this->get_column_single('finished_collection_posts_relationships');

		if ( Utils::array_not_empty($finished_collection_posts_relationships) && isset($finished_collection_posts_relationships[0]->finished_collection_posts_relationships) ) {
			return (boolean) $finished_collection_posts_relationships[0]->finished_collection_posts_relationships;

		} else {
			return false;
		}

	}


	/*

	Checks for the webhooks removal status

	*/
	public function posts_relationships_status() {

		if ($this->is_syncing_products()) {
			$products_posts_status = $this->get_finished_product_posts_relationships();

		} else {
			$products_posts_status = true;
		}

		if ($this->is_syncing_collections()) {
			$collections_posts_status = $this->get_finished_collection_posts_relationships();

		} else {
			$collections_posts_status = true;
		}


		if ($products_posts_status && $collections_posts_status) {
			return true;

		} else {
			return false;
		}


	}


	/*

	Wrapper function for checking whether the syncing has any fatal errors

	*/
	public function has_fatal_errors() {

		if ( $this->get_syncing_errors() ) {
			return true;

		} else {
			return false;
		}

	}


	/*

	Checks whether the app is syncing or not

	*/
	public function is_syncing() {

		$syncing_row = Utils::convert_array_to_object( $this->get() );

		if (!Utils::has($syncing_row, 'is_syncing')) {
			return false;
		}

		if ($syncing_row->is_syncing == 0 || $syncing_row->is_syncing == '0') {
			return false;

		} else {
			return true;

		}

	}


	/*

	End WordPress if not syncing

	*/
	public function die_if_not_syncing($break_from_loops = false) {

		if (!$this->is_syncing()) {
			wp_die();
		}

	}


	/*

	Turns syncing on

	*/
	public function toggle_syncing($state) {
		return $this->update_column_single(['is_syncing' => $state], ['id' => 1]);
	}


	/*

	Gets the get_syncing_current_amounts_smart_collections

	*/
	public function get_syncing_totals_custom_collections() {

		$syncing_totals_custom_collections = $this->get_column_single('syncing_totals_custom_collections');

		if ( Utils::array_not_empty($syncing_totals_custom_collections) && isset($syncing_totals_custom_collections[0]->syncing_totals_custom_collections) ) {
			return $syncing_totals_custom_collections[0]->syncing_totals_custom_collections;

		} else {
			return 0;
		}

	}


	/*

	Gets the get_syncing_current_amounts_smart_collections

	*/
	public function get_published_product_ids() {

		$published_product_ids = $this->get_column_single('published_product_ids');

		if ( Utils::array_not_empty($published_product_ids) && isset($published_product_ids[0]->published_product_ids) ) {

			$pub_ids = $published_product_ids[0]->published_product_ids;

			return maybe_unserialize($pub_ids);

		} else {
			return [];
		}

	}


	/*

	Represents the actual (true) number of custom collections that exist in Shopify

	*/
	public function syncing_totals_custom_collections_actual() {

		$total_gross = $this->get_syncing_totals_custom_collections();
		return $total_gross / 2;

	}


	/*

	Represents the actual (true) number of smart collections that exist in Shopify

	*/
	public function syncing_totals_smart_collections_actual() {

		$total_gross = $this->get_syncing_totals_smart_collections();
		return $total_gross / 2;

	}


	/*

	Represents the actual (true) number of products that exist in Shopify

	*/
	public function syncing_totals_products_actual() {

		$total_gross = $this->get_syncing_totals_products();
		return $total_gross / 6;

	}


	/*

	Update current amount per type during the syncing process

	$key presents the type of data as String. For example: 'orders', 'products', etc

	*/
	public function increment_current_amount($key, $amount_to_increment = false) {

		global $wpdb;

		if (!$amount_to_increment) {
			$amount_to_increment = 1;
		}

		$query = 'UPDATE ' . $this->table_name . ' SET syncing_current_amounts_' . $key . ' = syncing_current_amounts_' . $key . ' + ' . $amount_to_increment;

		return $wpdb->query($query);

	}


	/*

	Update current deletion_amount per type during the syncing process

	$key presents the type of data as String. For example: 'webhooks', 'products', etc

	*/
	public function increment_current_deletion_amount($key, $amount_to_increment = false) {

		global $wpdb;

		if (!$amount_to_increment) {
			$amount_to_increment = 1;
		}

		$query = 'UPDATE ' . $this->table_name . ' SET syncing_current_deletion_amounts_' . $key . ' = syncing_current_deletion_amounts_' . $key . ' + ' . $amount_to_increment;

		return $wpdb->query($query);

	}


	/*

	Checks if all syncing is completed

	Compares the current_amounts and totals

	*/
	public function all_syncing_complete() {

		$current_amounts 		= $this->syncing_current_amounts();
		$syncing_totals 		= $this->syncing_totals();

		sort($current_amounts);
		sort($syncing_totals);

		if ( !$this->is_syncing() || $current_amounts == $syncing_totals) {
			return true;

		} else {
			return false;
		}

	}


	/*

	Sets post relationships status

	*/
	public function set_finished_product_posts_relationships($status) {
		return $this->update_column_single(['finished_product_posts_relationships' => $status], ['id' => 1]);
	}


	/*

	Sets post relationships status

	*/
	public function set_published_product_ids($published_product_ids) {

		// Reset first
		$this->reset_syncing_published_product_ids();

		return $this->update_column_single(['published_product_ids' => maybe_serialize($published_product_ids)], ['id' => 1]);

	}


	/*

	Sets collection posts relationships status

	*/
	public function set_finished_collection_posts_relationships($status) {
		return $this->update_column_single(['finished_collection_posts_relationships' => $status], ['id' => 1]);
	}


	/*

	Sets the total amount of webhooks to sync

	*/
	public function set_syncing_totals_webhooks() {
		return $this->update_column_single(['syncing_totals_webhooks' => WPS_TOTAL_WEBHOOKS_COUNT], ['id' => 1]);
	}


	/*

	Turns syncing flag off

	*/
	public function turn_syncing_off() {
		$this->toggle_syncing(0);
	}


	/*

	Turns syncing flag on

	*/
	public function turn_syncing_on() {
		$this->toggle_syncing(1);
	}


	/*

	Resets the finished webhooks deletions flag to 0

	*/
	public function reset_webhooks_deletions_status() {
		return $this->update_column_single(['finished_webhooks_deletions' => 0], ['id' => 1]);
	}


	/*

	Resets the finished data deletions flag to 0

	*/
	public function reset_data_deletions_status() {
		return $this->update_column_single(['finished_data_deletions' => 0], ['id' => 1]);
	}


	/*

	Turns off webhooks finished deletions flag

	*/
	public function reset_posts_relationships_status() {

		return [
			$this->update_column_single(['finished_product_posts_relationships' => 0], ['id' => 1]),
			$this->update_column_single(['finished_collection_posts_relationships' => 0], ['id' => 1])
		];

	}


	/*

	Zeros out all syncing totals

	*/
	public function reset_all_syncing_totals() {

		$this->reset_syncing_current_amounts();
		$this->reset_syncing_totals();
		$this->reset_syncing_timing();
		$this->reset_syncing_posts_relationships();

	}


	public function reset_all_syncing_status() {

		$this->reset_webhooks_deletions_status();
		$this->reset_data_deletions_status();
		$this->reset_posts_relationships_status();
	}


	/*

	Wrapper function to clear syncing cache

	*/
	public function reset_syncing_cache() {

		update_site_option('wps_settings_updated', true);

		$this->turn_syncing_off();

		$this->reset_all_syncing_totals();
		$this->reset_all_syncing_status();

		flush_rewrite_rules();

		return Transients::delete_short_term_cache();

	}


	/*

	Prepares a list of notices (errors or warnings) to be saved

	*/
	public function prepare_notice_for_save($current_notices, $error_message, $type) {

		$current_notices = maybe_unserialize($current_notices);

		if (empty($current_notices)) {
			$current_notices = [];
		}

		$current_notices[$type][] = $error_message;

		return maybe_serialize($current_notices);

	}


	/*

	Saves an error notice

	*/
	public function save_error($error_message) {

		$current_errors = $this->get_syncing_errors();

		$serialized_errors = $this->prepare_notice_for_save($current_errors, $error_message, 'error');

		return $this->update_column_single(['syncing_errors' => $serialized_errors], ['id' => 1]);

	}


	/*

	Saves an warning notice

	*/
	public function save_warning($error_message) {

		$current_warnings = $this->get_syncing_warnings();
		$serialized_warnings = $this->prepare_notice_for_save($current_warnings, $error_message, 'warning');

		return $this->update_column_single(['syncing_warnings' => $serialized_warnings], ['id' => 1]);

	}


	/*

	Wrapper for saving a notice (error or warning)

	*/
	public function save_notice($maybe_wp_error) {

		if ( !is_wp_error($maybe_wp_error) ) {
			return $this->save_error($maybe_wp_error);
		}

		$error_message 	= $maybe_wp_error->get_error_message();
		$type						= $maybe_wp_error->get_error_code();

		if ($error_message) {

			if ($type === 'error') {
				return $this->save_error($error_message);
			}

			if ($type === 'warning') {
				return $this->save_warning($error_message);
			}

		}

	}


	/*

	Saves error and stops the syncing process

	*/
	public function save_notice_and_stop_sync($WP_Error) {

		$this->save_notice($WP_Error);
		$this->expire_sync();

	}


	/*

	Ends a progress bar instance

	*/
	public function expire_sync() {

		if ($this->is_syncing()) {
			$this->reset_all_syncing_totals();
			$this->reset_syncing_cache();
		}

	}


	public function throw_max_allowed_packet() {
		return Utils::wp_error( Messages::get('max_allowed_packet') );
	}


	/*

	Checks for the webhooks removal status

	*/
	public function webhooks_removal_status() {

		$finished_webhooks_deletions = $this->get_column_single('finished_webhooks_deletions');

		if ( Utils::array_not_empty($finished_webhooks_deletions) && isset($finished_webhooks_deletions[0]->finished_webhooks_deletions) ) {
			return (boolean) $finished_webhooks_deletions[0]->finished_webhooks_deletions;

		} else {
			return false;
		}

	}


	/*

	Checks for the webhooks removal status

	*/
	public function data_removal_status() {

		$finished_data_deletions = $this->get_column_single('finished_data_deletions');

		if ( Utils::array_not_empty($finished_data_deletions) && isset($finished_data_deletions[0]->finished_data_deletions) ) {
			return (boolean) $finished_data_deletions[0]->finished_data_deletions;

		} else {
			return false;
		}

	}


	/*

	Sets the total amount of webhooks to sync

	*/
	public function set_finished_webhooks_deletions($status) {
		return $this->update_column_single(['finished_webhooks_deletions' => $status], ['id' => 1]);
	}


	/*

	Sets the total amount of webhooks to sync

	*/
	public function set_finished_data_deletions($status) {
		return $this->update_column_single(['finished_data_deletions' => $status], ['id' => 1]);
	}


	/*

	Helper for saving warnings during syncing

	*/
	public function maybe_save_warning_from_insert($result, $type, $identifier) {

		if ($result === false) {
			$this->save_warning("Unable to sync " . $type . ": " . $identifier);
		}

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

	Creates a table query string

	*/
	public function create_table_query($table_name = false) {

		if ( !$table_name ) {
			$table_name = $this->table_name;
		}

		$collate = $this->collate();

		return "CREATE TABLE $table_name (
			id bigint(100) unsigned NOT NULL AUTO_INCREMENT,
			is_syncing tinyint(1) DEFAULT '{$this->default_is_syncing}',
			syncing_totals_shop bigint(100) unsigned DEFAULT '{$this->default_syncing_totals_shop}',
			syncing_totals_smart_collections bigint(100) unsigned DEFAULT '{$this->default_syncing_totals_smart_collections}',
			syncing_totals_custom_collections bigint(100) unsigned DEFAULT '{$this->default_syncing_totals_custom_collections}',
			syncing_totals_products bigint(100) unsigned DEFAULT '{$this->default_syncing_totals_products}',
			syncing_totals_collects bigint(100) unsigned DEFAULT '{$this->default_syncing_totals_collects}',
			syncing_totals_orders bigint(100) unsigned DEFAULT '{$this->default_syncing_totals_orders}',
			syncing_totals_customers bigint(100) unsigned DEFAULT '{$this->default_syncing_totals_customers}',
			syncing_totals_webhooks bigint(100) unsigned DEFAULT '{$this->default_syncing_totals_webhooks}',
			syncing_step_total bigint(100) unsigned DEFAULT  '{$this->default_syncing_step_total}',
			syncing_step_current bigint(100) unsigned DEFAULT '{$this->default_syncing_step_current}',
			syncing_current_amounts_shop bigint(100) unsigned DEFAULT '{$this->default_syncing_current_amounts_shop}',
			syncing_current_amounts_smart_collections bigint(100) unsigned DEFAULT '{$this->default_syncing_current_amounts_smart_collections}',
			syncing_current_amounts_custom_collections bigint(100) unsigned DEFAULT '{$this->default_syncing_current_amounts_custom_collections}',
			syncing_current_amounts_products bigint(100) unsigned DEFAULT '{$this->default_syncing_current_amounts_products}',
			syncing_current_amounts_collects bigint(100) unsigned DEFAULT '{$this->default_syncing_current_amounts_collects}',
			syncing_current_amounts_orders bigint(100) unsigned DEFAULT '{$this->default_syncing_current_amounts_orders}',
			syncing_current_amounts_customers bigint(100) unsigned DEFAULT '{$this->default_syncing_current_amounts_customers}',
			syncing_current_amounts_webhooks bigint(100) unsigned DEFAULT '{$this->default_syncing_current_amounts_webhooks}',
			syncing_start_time bigint(100) unsigned DEFAULT '{$this->default_syncing_start_time}',
			syncing_end_time bigint(100) unsigned DEFAULT '{$this->default_syncing_end_time}',
			syncing_errors LONGTEXT DEFAULT '{$this->default_syncing_errors}',
			syncing_warnings LONGTEXT DEFAULT '{$this->default_syncing_warnings}',
			finished_webhooks_deletions tinyint(1) DEFAULT '{$this->default_finished_webhooks_deletions}',
			finished_product_posts_relationships tinyint(1) DEFAULT '{$this->default_finished_product_posts_relationships}',
			finished_collection_posts_relationships tinyint(1) DEFAULT '{$this->default_finished_collection_posts_relationships}',
			finished_data_deletions tinyint(1) DEFAULT '{$this->default_finished_data_deletions}',
			published_product_ids longtext DEFAULT '{$this->default_published_product_ids}',
			PRIMARY KEY  (id)
		) ENGINE=InnoDB $collate";

	}


}
