<?php

namespace WPS;

use WPS\DB\Settings_Connection;
use WPS\DB\Settings_General;
use WPS\DB\Settings_License;


/*

Main class

*/
class Config {

	public $plugin_name;
	public $plugin_name_full;
	public $plugin_name_full_encoded;
	public $plugin_version;
	public $plugin_path;
	public $plugin_basename;
	public $plugin_url;
	public $plugin_root_file;
	public $plugin_env;
	public $plugin_file;
	public $plugin_author;

	public $settings_connection_option_name;
	public $settings_general_option_name;
	public $settings_license_option_name;

	protected static $instantiated = null;

	/*

	Define the core functionality of the plugin.

	Set the plugin name and the plugin version that can be used throughout the plugin.
	Load the dependencies, define the locale, and set the hooks for the admin area and
	the public-facing side of the site.

	TODO: Figure out a way to make these hardcoded values dynamic

	*/
	public function __construct() {

		$this->plugin_path = plugin_dir_path( __DIR__ );
		$this->plugin_url = plugin_dir_url( __DIR__ );

		$this->plugin_name_full = 'WP Shopify';
		$this->plugin_name_full_encoded = urlencode($this->plugin_name_full);
		$this->plugin_name = 'wps';
		$this->plugin_version = '1.0.1';
		$this->plugin_author = 'Andrew Robbins';

		$this->plugin_root_file = $this->plugin_path . $this->plugin_name . '.php';
		$this->plugin_file = plugin_basename($this->plugin_root_file);

		$this->plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_name . '.php' );
		$this->plugin_env = 'https://wpshop.io';

		// Settings
		$this->settings_connection_option_name = $this->plugin_name . '_settings_connection';
		$this->settings_general_option_name = $this->plugin_name . '_settings_general';
		$this->settings_license_option_name = $this->plugin_name . '_settings_license';

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

	Get Connection Settings

	*/
	public function wps_get_settings_connection() {

		$DB_Settings_Connection = new Settings_Connection();
		return $DB_Settings_Connection->get();

	}


	/*

	Get General Settings

	*/
	public function wps_get_settings_general() {

		$DB_Settings_General = new Settings_General();
		return $DB_Settings_General->get();

	}


	/*

	Get License Settings

	*/
	public function wps_get_settings_license() {

		$DB_Settings_License = new Settings_License();
		return $DB_Settings_License->get();

	}


}
