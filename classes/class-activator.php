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

// use WPS\DB\Inventory as Inventory;


/*

Fired during plugin install / activate

*/
class Activator {

	protected static $instantiated = null;
	private $Config;
	public $plugin_basename;

	/*

	Initialize the class and set its properties.

	*/
	public function __construct($Config) {
		$this->plugin_basename = $Config->plugin_basename;
	}


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

	Things to do on plugin activate

	*/
	public function activate() {

		if (!current_user_can('activate_plugins')) {
			return;

		} else {
			$this->init_settings();
			$this->create_db_tables();
		}

		delete_option('_site_transient_update_plugins');
		flush_rewrite_rules();

	}


	/*

	Init

	*/
	public function init() {
		register_activation_hook($this->plugin_basename, [$this, 'activate']);
	}


}
