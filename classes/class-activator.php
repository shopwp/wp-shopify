<?php

namespace WPS;

use WPS\DB\Settings_Connection;
use WPS\DB\Settings_General;
use WPS\DB\Settings_License;
use WPS\DB\Shop;
use WPS\DB\Products;
use WPS\DB\Variants;
use WPS\DB\Collects;
use WPS\DB\Options;
use WPS\DB\Collections_Custom;
use WPS\DB\Collections_Smart;
use WPS\DB\Images;
use WPS\DB\Tags;
use WPS\CPT;
use WPS\Utils;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
	exit;
}


/*

Fired during plugin install / activate

*/
if ( !class_exists('Activator') ) {

	class Activator {

		protected static $instantiated = null;
		private $Config;
		public $config;

		/*

		Initialize the class and set its properties.

		*/
		public function __construct($Config) {
			$this->config = $Config;
		}


		/*

		Creates a new class if one hasn't already been created.
		Ensures only one instance is used.

		*/
		public static function instance($Config) {

			if (is_null(self::$instantiated)) {
				self::$instantiated = new self($Config);
			}

			return self::$instantiated;

		}


		/*

		init_settings

		*/
		public function init_settings() {

		}


		/*

		Create DB Tables

		*/
		public function create_db_tables() {

			global $wpdb;

			$DB_Settings_Connection = new Settings_Connection();
			$DB_Settings_General = new Settings_General();
			$DB_Settings_License = new Settings_License();
			$DB_Shop = new Shop();
			$DB_Products = new Products();
			$DB_Variants = new Variants();
			$DB_Collects = new Collects();
			$DB_Options = new Options();
			$DB_Collections_Custom = new Collections_Custom();
			$DB_Collections_Smart = new Collections_Smart();
			$DB_Images = new Images();
			$DB_Tags = new Tags();



			/*

			Create Tables

			*/
			$DB_Settings_Connection->create_table();
			$DB_Settings_General->create_table();
			$DB_Settings_License->create_table();
			$DB_Shop->create_table();
			$DB_Products->create_table();
			$DB_Variants->create_table();
			$DB_Collects->create_table();
			$DB_Options->create_table();
			$DB_Collections_Custom->create_table();
			$DB_Collections_Smart->create_table();
			$DB_Images->create_table();
			$DB_Tags->create_table();



			/*

			Set any default plugin settings

			*/
			$DB_Settings_General->init_general();

		}


		/*

		Runs when the plugin is activated as a result of register_activation_hook. Runs for both Free and Pro versions

		*/
		public function on_activation() {

			if (!current_user_can('activate_plugins')) {
				return;
			}

			$plugin_settings = new Settings_General();
			$CPT = new CPT($this->config);
			$this->init_settings();
			$this->create_db_tables();
			$CPT->init();

			delete_option('_site_transient_update_plugins');

			require_once(ABSPATH . 'wp-admin/includes/plugin.php');

			if (is_plugin_active(WPS_FREE_FILE_ROOT) ) {

				\deactivate_plugins(WPS_FREE_FILE_ROOT);

				$plugin_settings->set_pro_tier();

			} else {

				$plugin_settings->set_free_tier();

			}

			flush_rewrite_rules();

		}


	}

}
