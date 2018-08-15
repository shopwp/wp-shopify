<?php

namespace WPS;

if (!defined('ABSPATH')) {
	exit;
}

if ( !class_exists('Activator') ) {

	class Activator {

		private $DB_Settings_Connection;
		private $DB_Settings_General;
		private $DB_Settings_License;
		private $DB_Shop;
		private $DB_Products;
		private $DB_Variants;
		private $DB_Collects;
		private $DB_Options;
		private $DB_Collections_Custom;
		private $DB_Collections_Smart;
		private $DB_Images;
		private $DB_Tags;
		private $CPT;
		private $DB_Customers;
		private $DB_Orders;
		private $DB_Settings_Syncing;

		public function __construct($DB_Settings_Connection, $DB_Settings_General, $DB_Settings_License, $DB_Shop, $DB_Products, $DB_Variants, $DB_Collects, $DB_Options, $DB_Collections_Custom, $DB_Collections_Smart, $DB_Images, $DB_Tags, $CPT, $DB_Customers, $DB_Orders, $DB_Settings_Syncing) {

			$this->DB_Settings_Connection 			= $DB_Settings_Connection;
			$this->DB_Settings_General 					= $DB_Settings_General;
			$this->DB_Settings_License 					= $DB_Settings_License;
			$this->DB_Shop 											= $DB_Shop;
			$this->DB_Products 									= $DB_Products;
			$this->DB_Variants 									= $DB_Variants;
			$this->DB_Collects 									= $DB_Collects;
			$this->DB_Options 									= $DB_Options;
			$this->DB_Collections_Custom 				= $DB_Collections_Custom;
			$this->DB_Collections_Smart 				= $DB_Collections_Smart;
			$this->DB_Images 										= $DB_Images;
			$this->DB_Tags 											= $DB_Tags;
			$this->CPT 													= $CPT;

			// Pro only
			$this->DB_Customers 								= $DB_Customers;
			$this->DB_Orders 										= $DB_Orders;

			$this->DB_Settings_Syncing					= $DB_Settings_Syncing;

		}


		/*

		Sets default plugin settings and inserts default rows

		*/
		public function set_default_table_values() {

			$this->DB_Settings_General->init();
			$this->DB_Settings_Syncing->init();

		}


		public function create_migration_db_tables() {

			$create_migration_db_tables_results = [];

			$create_migration_db_tables_results['DB_Settings_Connection'] = $this->DB_Settings_Connection->create_migration_table();
			$create_migration_db_tables_results['DB_Settings_General'] = $this->DB_Settings_General->create_migration_table();
			$create_migration_db_tables_results['DB_Settings_License'] = $this->DB_Settings_License->create_migration_table();
			$create_migration_db_tables_results['DB_Shop'] = $this->DB_Shop->create_migration_table();
			$create_migration_db_tables_results['DB_Products'] = $this->DB_Products->create_migration_table();
			$create_migration_db_tables_results['DB_Variants'] = $this->DB_Variants->create_migration_table();
			$create_migration_db_tables_results['DB_Collects'] = $this->DB_Collects->create_migration_table();
			$create_migration_db_tables_results['DB_Options'] = $this->DB_Options->create_migration_table();
			$create_migration_db_tables_results['DB_Collections_Custom'] = $this->DB_Collections_Custom->create_migration_table();
			$create_migration_db_tables_results['DB_Collections_Smart'] = $this->DB_Collections_Smart->create_migration_table();
			$create_migration_db_tables_results['DB_Images'] = $this->DB_Images->create_migration_table();
			$create_migration_db_tables_results['DB_Tags'] = $this->DB_Tags->create_migration_table();
			$create_migration_db_tables_results['DB_Settings_Syncing'] = $this->DB_Settings_Syncing->create_migration_table();


			return Utils::return_only_error_messages( Utils::return_only_errors($create_migration_db_tables_results) );

		}


		public function run_insert_to_queries() {

			$insert_to_queries_results = [];

			$insert_to_queries_results['DB_Settings_Connection'] = $this->DB_Settings_Connection->migration_insert_into_query();
			$insert_to_queries_results['DB_Settings_General'] = $this->DB_Settings_General->migration_insert_into_query();
			$insert_to_queries_results['DB_Settings_License'] = $this->DB_Settings_License->migration_insert_into_query();
			$insert_to_queries_results['DB_Shop'] = $this->DB_Shop->migration_insert_into_query();
			$insert_to_queries_results['DB_Products'] = $this->DB_Products->migration_insert_into_query();
			$insert_to_queries_results['DB_Variants'] = $this->DB_Variants->migration_insert_into_query();
			$insert_to_queries_results['DB_Collects'] = $this->DB_Collects->migration_insert_into_query();
			$insert_to_queries_results['DB_Options'] = $this->DB_Options->migration_insert_into_query();
			$insert_to_queries_results['DB_Collections_Custom'] = $this->DB_Collections_Custom->migration_insert_into_query();
			$insert_to_queries_results['DB_Collections_Smart'] = $this->DB_Collections_Smart->migration_insert_into_query();
			$insert_to_queries_results['DB_Images'] = $this->DB_Images->migration_insert_into_query();
			$insert_to_queries_results['DB_Tags'] = $this->DB_Tags->migration_insert_into_query();
			$insert_to_queries_results['DB_Settings_Syncing'] = $this->DB_Settings_Syncing->migration_insert_into_query();


			return Utils::return_only_errors($insert_to_queries_results);

		}



		public function delete_old_tables() {

			$delete_old_tables_results = [];

			$delete_old_tables_results['DB_Settings_Connection'] = $this->DB_Settings_Connection->delete_table();
			$delete_old_tables_results['DB_Settings_General'] = $this->DB_Settings_General->delete_table();
			$delete_old_tables_results['DB_Settings_License'] = $this->DB_Settings_License->delete_table();
			$delete_old_tables_results['DB_Shop'] = $this->DB_Shop->delete_table();
			$delete_old_tables_results['DB_Products'] = $this->DB_Products->delete_table();
			$delete_old_tables_results['DB_Variants'] = $this->DB_Variants->delete_table();
			$delete_old_tables_results['DB_Collects'] = $this->DB_Collects->delete_table();
			$delete_old_tables_results['DB_Options'] = $this->DB_Options->delete_table();
			$delete_old_tables_results['DB_Collections_Custom'] = $this->DB_Collections_Custom->delete_table();
			$delete_old_tables_results['DB_Collections_Smart'] = $this->DB_Collections_Smart->delete_table();
			$delete_old_tables_results['DB_Images'] = $this->DB_Images->delete_table();
			$delete_old_tables_results['DB_Tags'] = $this->DB_Tags->delete_table();
			$delete_old_tables_results['DB_Settings_Syncing'] = $this->DB_Settings_Syncing->delete_table();


			return Utils::return_only_errors($delete_old_tables_results);

		}


		public function rename_migration_tables() {

			$rename_migration_tables_results = [];

			$rename_migration_tables_results['DB_Settings_Connection'] = $this->DB_Settings_Connection->rename_migration_table();
			$rename_migration_tables_results['DB_Settings_General'] = $this->DB_Settings_General->rename_migration_table();
			$rename_migration_tables_results['DB_Settings_License'] = $this->DB_Settings_License->rename_migration_table();
			$rename_migration_tables_results['DB_Shop'] = $this->DB_Shop->rename_migration_table();
			$rename_migration_tables_results['DB_Products'] = $this->DB_Products->rename_migration_table();
			$rename_migration_tables_results['DB_Variants'] = $this->DB_Variants->rename_migration_table();
			$rename_migration_tables_results['DB_Collects'] = $this->DB_Collects->rename_migration_table();
			$rename_migration_tables_results['DB_Options'] = $this->DB_Options->rename_migration_table();
			$rename_migration_tables_results['DB_Collections_Custom'] = $this->DB_Collections_Custom->rename_migration_table();
			$rename_migration_tables_results['DB_Collections_Smart'] = $this->DB_Collections_Smart->rename_migration_table();
			$rename_migration_tables_results['DB_Images'] = $this->DB_Images->rename_migration_table();
			$rename_migration_tables_results['DB_Tags'] = $this->DB_Tags->rename_migration_table();
			$rename_migration_tables_results['DB_Settings_Syncing'] = $this->DB_Settings_Syncing->rename_migration_table();


			return Utils::return_only_error_messages( Utils::return_only_errors($rename_migration_tables_results) );

		}


		/*

		Create DB Tables

		*/
		public function create_db_tables() {

			$this->DB_Settings_Connection->create_table();
			$this->DB_Settings_General->create_table();
			$this->DB_Settings_License->create_table();
			$this->DB_Shop->create_table();
			$this->DB_Products->create_table();
			$this->DB_Variants->create_table();
			$this->DB_Collects->create_table();
			$this->DB_Options->create_table();
			$this->DB_Collections_Custom->create_table();
			$this->DB_Collections_Smart->create_table();
			$this->DB_Images->create_table();
			$this->DB_Tags->create_table();
			$this->DB_Settings_Syncing->create_table();


		}


		public function set_table_charset_cache() {

			$this->DB_Settings_General->get_table_charset(WPS_TABLE_NAME_WP_OPTIONS);
			$this->DB_Settings_General->get_table_charset(WPS_TABLE_NAME_PRODUCTS);
			$this->DB_Settings_General->get_table_charset(WPS_TABLE_NAME_COLLECTIONS_SMART);
			$this->DB_Settings_General->get_table_charset(WPS_TABLE_NAME_COLLECTIONS_CUSTOM);
			$this->DB_Settings_General->get_table_charset(WPS_TABLE_NAME_VARIANTS);
			$this->DB_Settings_General->get_table_charset(WPS_TABLE_NAME_IMAGES);
			$this->DB_Settings_General->get_table_charset(WPS_TABLE_NAME_TAGS);
			$this->DB_Settings_General->get_table_charset(WPS_TABLE_NAME_SHOP);


		}


		public function bootstrap_tables() {

			$this->create_db_tables();
			$this->set_default_table_values();
			$this->set_table_charset_cache();

		}




		public function run_table_migration() {

			$create_tables_result = $this->create_migration_db_tables();

			if ( Utils::array_not_empty($create_tables_result) ) {
				Transients::delete_all_cache();
				wp_send_json_error($create_tables_result);
			}


			$insert_queries_result = $this->run_insert_to_queries();

			if ( Utils::array_not_empty($insert_queries_result) ) {
				Transients::delete_all_cache();
				wp_send_json_error($insert_queries_result);
			}


			$delete_old_tables_result = $this->delete_old_tables();

			if ( Utils::array_not_empty($delete_old_tables_result) ) {
				Transients::delete_all_cache();
				wp_send_json_error($delete_old_tables_result);
			}


			$rename_tables_result = $this->rename_migration_tables();

			if ( Utils::array_not_empty($rename_tables_result) ) {
				Transients::delete_all_cache();
				wp_send_json_error($rename_tables_result);
			}




			Transients::delete_all_cache();

			wp_send_json_success();

		}



		public function is_pro_active() {

			if ( is_plugin_active(WPS_PRO_SUBDIRECTORY_NAME) ) {
			  return true;

			} else {
				return false;
			}

		}


		public function is_free_active() {

			if ( is_plugin_active(WPS_FREE_SUBDIRECTORY_NAME) ) {
			  return true;

			} else {
				return false;
			}

		}


		/*

		Runs when the plugin is activated as a result of register_activation_hook. Runs for both Free and Pro versions

		*/
		public function on_activation() {


			if (!current_user_can('activate_plugins')) {
				wp_die('Sorry you\'re not allowed to do that.');
			}

			if ( !function_exists('version_compare') || version_compare(PHP_VERSION, '5.6.0', '<' )) {
				wp_die('This plugin requires PHP Version 5.6 +. Sorry about that.');
			}


			$this->bootstrap_tables();
			$this->CPT->init();

			delete_option('_site_transient_update_plugins');

			flush_rewrite_rules();

		}


		public function deactivate_free_version() {

			if ( $this->is_free_active() ) {

				require_once(ABSPATH . 'wp-admin/includes/plugin.php');
				\deactivate_plugins(WPS_FREE_FILE_ROOT);

			}

			// If for some reason is_fre is set but the free version isnt actually active
			if ( !$this->is_free_active() && $this->DB_Settings_General->is_free_tier() ) {
				$this->DB_Settings_General->set_free_tier(0);
			}

		}


		public function toggle_activation_flags() {


			if ( $this->is_free_active() ) {
				$this->DB_Settings_General->set_free_tier();
			}

		}


		public function hooks() {


			register_activation_hook(WPS_FREE_FILE_ROOT, [$this, 'on_activation']);

			add_action('admin_init', [$this, 'toggle_activation_flags']);

		}


		public function init() {
			$this->hooks();
		}


	}

}
