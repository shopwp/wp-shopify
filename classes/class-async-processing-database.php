<?php

namespace WPS;

use WPS\Utils;
use WPS\Transients;

if (!defined('ABSPATH')) {
	exit;
}


class Async_Processing_Database extends Vendor_Background_Process {

	protected $action = 'wps_background_processing_deletions';

	protected $Config;
	protected $DB_Collections_Custom;
	protected $DB_Collections_Smart;
	protected $DB_Collects;
	protected $DB_Customers;
	protected $DB_Images;
	protected $DB_Options;
	protected $DB_Orders;
	protected $DB_Products;
	protected $DB_Settings_Connection;
	protected $DB_Settings_General;
	protected $DB_Settings_License;
	protected $DB_Settings_Syncing;
	protected $DB_Shop;
	protected $DB_Tags;
	protected $DB_Variants;
	protected $Transients;
	protected $WS_Webhooks;
	protected $WS_CPT;
	protected $WS_Settings_License;
	protected $License;


	public function __construct($Config, $DB_Collections_Custom, $DB_Collections_Smart, $DB_Collects, $DB_Customers, $DB_Images, $DB_Options, $DB_Orders, $DB_Products, $DB_Settings_Connection, $DB_Settings_General, $DB_Settings_License, $DB_Settings_Syncing, $DB_Shop, $DB_Tags, $DB_Variants, $Transients, $WS_Webhooks, $WS_CPT, $WS_Settings_License, $License) {

		$this->Config 														= $Config;
		$this->DB_Collections_Custom 							= $DB_Collections_Custom;
		$this->DB_Collections_Smart 							= $DB_Collections_Smart;
		$this->DB_Collects 												= $DB_Collects;
		$this->DB_Customers 											= $DB_Customers;
		$this->DB_Images 													= $DB_Images;
		$this->DB_Options 												= $DB_Options;
		$this->DB_Orders 													= $DB_Orders;
		$this->DB_Products 												= $DB_Products;
		$this->DB_Settings_Connection 						= $DB_Settings_Connection;
		$this->DB_Settings_General 								= $DB_Settings_General;
		$this->DB_Settings_License 								= $DB_Settings_License;
		$this->DB_Settings_Syncing 								= $DB_Settings_Syncing;
		$this->DB_Shop 														= $DB_Shop;
		$this->DB_Tags 														= $DB_Tags;
		$this->DB_Variants 												= $DB_Variants;
		$this->Transients 												= $Transients;

		$this->WS_Webhooks 												= $WS_Webhooks;
		$this->WS_CPT 														= $WS_CPT;
		$this->WS_Settings_License 								= $WS_Settings_License;
		$this->License 														= $this->License;

		$this->DB 																= $DB_Variants; // Convenience

		parent::__construct();

	}


	/*

	When uninstalling the plugin

	*/
	public function uninstall_plugin() {

		$results = [];

		if ($this->DB_Settings_General->is_free_tier() && $this->DB_Settings_General->is_pro_tier() ) {
			$this->DB_Settings_General->set_free_tier(0);

		} else {


			$results['delete_posts'] 									= $this->delete_posts();
			$results['drop_custom_tables'] 						= $this->drop_custom_tables();
			$results['drop_custom_migration_tables'] 	= $this->drop_custom_migration_tables(WPS_TABLE_MIGRATION_SUFFIX);

		}

		$results['delete_all_cache'] 								= Transients::delete_all_cache();
		$results['delete_custom_options'] 					= Transients::delete_custom_options();

		return $results;

	}


	/*

	When uninstalling the plugin

	*/
	public function uninstall_plugin_multisite() {

		$results = [];
		$blog_ids = $this->DB->get_network_sites();

		foreach ( $blog_ids as $site_blog_id ) {

			switch_to_blog( $site_blog_id );

			$results['blog_' . $site_blog_id] = $this->uninstall_plugin();

			restore_current_blog();

		}

		return $results;

	}


	/*

	Drop custom tables

	Tested

	NOT USING BACKGROUND PROCESS

	*/
	public function drop_custom_tables() {

		$results = [];

		$results['shop'] 									= $this->DB_Shop->delete_table();
		$results['settings_general'] 			= $this->DB_Settings_General->delete_table();
		$results['settings_license'] 			= $this->DB_Settings_License->delete_table();
		$results['settings_connection'] 	= $this->DB_Settings_Connection->delete_table();
		$results['settings_syncing'] 			= $this->DB_Settings_Syncing->delete_table();
		$results['collections_smart'] 		= $this->DB_Collections_Smart->delete_table();
		$results['collections_custom'] 		= $this->DB_Collections_Custom->delete_table();
		$results['products'] 							= $this->DB_Products->delete_table();
		$results['variants'] 							= $this->DB_Variants->delete_table();
		$results['options'] 							= $this->DB_Options->delete_table();
		$results['tags'] 									= $this->DB_Tags->delete_table();
		$results['collects'] 							= $this->DB_Collects->delete_table();
		$results['images'] 								= $this->DB_Images->delete_table();


		return Utils::return_only_error_messages( Utils::return_only_errors($results) );

	}


	/*

	Drop custom migration tables

	Tested

	NOT USING BACKGROUND PROCESS

	*/
	public function drop_custom_migration_tables($table_suffix) {

		$results = [];

		$results['shop' . $table_suffix] 									= $this->DB_Shop->delete_migration_table($table_suffix);
		$results['settings_general' . $table_suffix] 			= $this->DB_Settings_General->delete_migration_table($table_suffix);
		$results['settings_license' . $table_suffix] 			= $this->DB_Settings_License->delete_migration_table($table_suffix);
		$results['settings_connection' . $table_suffix] 	= $this->DB_Settings_Connection->delete_migration_table($table_suffix);
		$results['settings_syncing' . $table_suffix] 			= $this->DB_Settings_Syncing->delete_migration_table($table_suffix);
		$results['collections_smart' . $table_suffix] 		= $this->DB_Collections_Smart->delete_migration_table($table_suffix);
		$results['collections_custom' . $table_suffix] 		= $this->DB_Collections_Custom->delete_migration_table($table_suffix);
		$results['products' . $table_suffix] 							= $this->DB_Products->delete_migration_table($table_suffix);
		$results['variants' . $table_suffix] 							= $this->DB_Variants->delete_migration_table($table_suffix);
		$results['options' . $table_suffix] 							= $this->DB_Options->delete_migration_table($table_suffix);
		$results['tags' . $table_suffix] 									= $this->DB_Tags->delete_migration_table($table_suffix);
		$results['collects' . $table_suffix] 							= $this->DB_Collects->delete_migration_table($table_suffix);
		$results['images' . $table_suffix] 								= $this->DB_Images->delete_migration_table($table_suffix);


		return Utils::return_only_error_messages( Utils::return_only_errors($results) );

	}


	/*

	Drop databases used during uninstall

	Tested

	*/
	public function delete_posts() {
		return $this->WS_CPT->delete_posts();
	}


	/*

	Deletes both synced data AND custom post types but no:

	- Connection data
	- License data

	*/
	public function delete_posts_and_synced_data() {

		$this->push_to_queue('WS_CPT');
		$this->push_to_queue('DB_Collections_Custom');
		$this->push_to_queue('DB_Collections_Smart');
		$this->push_to_queue('DB_Collects');
		$this->push_to_queue('DB_Images');
		$this->push_to_queue('DB_Options');
		$this->push_to_queue('DB_Products');
		$this->push_to_queue('DB_Shop');
		$this->push_to_queue('DB_Tags');
		$this->push_to_queue('DB_Variants');
		$this->push_to_queue('Transients');


		$this->save()->dispatch();

	}


	/*

	Deletes only synced Shopify data. Keeps custom post types, license, etc.

	*/
	public function delete_only_synced_data() {

		$selective_sync = $this->DB_Settings_General->selective_sync_status();

		if ($selective_sync['products'] === 1 || $selective_sync['all'] === 1) {

			$this->push_to_queue('DB_Products');
			$this->push_to_queue('DB_Shop');
			$this->push_to_queue('DB_Variants');
			$this->push_to_queue('DB_Tags');
			$this->push_to_queue('DB_Collects');
			$this->push_to_queue('DB_Images');
			$this->push_to_queue('DB_Options');

		}

		if ($selective_sync['smart_collections'] === 1 || $selective_sync['all'] === 1) {
			$this->push_to_queue('DB_Collections_Smart');
		}

		if ($selective_sync['custom_collections'] === 1 || $selective_sync['all'] === 1) {
			$this->push_to_queue('DB_Collections_Custom');
		}


		$this->push_to_queue('Transients');

		$this->save()->dispatch();


	}


	/*

	Override this method to perform any actions required during the async request.

	*/
	protected function task($object_name) {

		$class_object = $this->$object_name;

		if ($class_object) {

			if ($object_name === 'WS_CPT') {
				$class_object->delete_posts();

			} else if ($object_name === 'DB_Settings_Syncing') {
				$class_object->reset_syncing_current_amounts();

			} else if ($object_name === 'WS_Webhooks' || $object_name === 'Transients') {
				return false;

			} else {
				$class_object->truncate();

			}

		}

		return false;

	}


	/*

	Find the difference between tables in the database
	and tables in the database schemea. Used during plugin updates
	to dynamically update the database.

	*/
	public function get_table_delta() {

		$tables = [];
		$final_delta = [];

		$tables[] = $this->DB_Products;
		$tables[] = $this->DB_Variants;
		$tables[] = $this->DB_Tags;
		$tables[] = $this->DB_Shop;
		$tables[] = $this->DB_Options;
		$tables[] = $this->DB_Images;
		$tables[] = $this->DB_Collects;
		$tables[] = $this->DB_Collections_Smart;
		$tables[] = $this->DB_Collections_Custom;
		$tables[] = $this->DB_Settings_License;
		$tables[] = $this->DB_Settings_Connection;
		$tables[] = $this->DB_Settings_General;
		$tables[] = $this->DB_Settings_Syncing;


		foreach ($tables as $key => $table) {

			// Contains full table name /w prefix
			$table_name = $table->get_table_name();

			if ( $table->table_exists($table_name) ) {

				if ( Utils::different_row_amount( $table->get_columns(), $table->get_columns_current() ) ) {
					$final_delta[$table_name] = $table;
				}

			} else {

				// Create table since it doesn't exist
				$result = $table->create_table( Utils::is_network_wide() );

			}

		}

		return array_filter($final_delta);

	}


	/*

	Useful for creating new tables and updating existing tables to a new structure.
	Does NOT remove columns or delete tables

	*/
	public function sync_table_deltas() {

		// Next get all tables
		$tables = $this->get_table_delta();

		if ( Utils::array_not_empty($tables) ) {

			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

			foreach ($tables as $table) {
				$results = \dbDelta( $table->create_table_query($table->table_name) );
			}

		}

	}


	/*

	When the background process completes ...

	*/
	protected function complete() {

		$this->DB_Settings_Syncing->set_finished_data_deletions(1);

		parent::complete();

	}


	/*

	Hooks

	*/
	public function hooks() {

		add_action('wp_ajax_delete_only_synced_data', [$this, 'delete_only_synced_data']);
		add_action('wp_ajax_nopriv_delete_only_synced_data', [$this, 'delete_only_synced_data']);

		add_action('wp_ajax_delete_posts_and_synced_data', [$this, 'delete_posts_and_synced_data']);
		add_action('wp_ajax_nopriv_delete_posts_and_synced_data', [$this, 'delete_posts_and_synced_data']);

	}


	public function init() {
		$this->hooks();
	}


}
