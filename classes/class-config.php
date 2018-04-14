<?php

namespace WPS;

use WPS\DB\Settings_Connection;
use WPS\DB\Settings_General;
use WPS\DB\Settings_License;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
	exit;
}


/*

Config Class

*/
if ( !class_exists('Config') ) {

	class Config {

		public $plugin_name;
		public $plugin_name_full;
		public $plugin_name_full_encoded;
		public $plugin_name_js;
		public $plugin_version;
		public $plugin_path;
		public $plugin_basename;
		public $plugin_url;
		public $plugin_root_file;
		public $plugin_env;
		public $plugin_file;
		public $plugin_author;
		public $plugin_text_domain;

		public $settings_connection_option_name;
		public $settings_general_option_name;
		public $settings_license_option_name;

		public $cart_cache_expiration;

		public static $plugin_nonce_action_backend;
		public static $plugin_nonce_action_frontend;
		public static $plugin_nonce_action_uninstall;
		public static $plugin_nonce_action_cache;

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
			

			if ( !defined('WPS_PLUGIN_DIR') ) {
				define('WPS_PLUGIN_DIR', $this->plugin_path);
			}

			if ( !defined('WPS_PLUGIN_URL') ) {
				define('WPS_PLUGIN_URL', $this->plugin_url);
			}

			if ( !defined('WPS_RELATIVE_TEMPLATE_DIR') ) {
				define('WPS_RELATIVE_TEMPLATE_DIR', 'public/templates');
			}

			if ( !defined('WPS_CHECKOUT_BASE_URL') ) {
				define('WPS_CHECKOUT_BASE_URL', 'https://checkout.shopify.com');
			}

			$this->plugin_name_full = 'WP Shopify';
			$this->plugin_name_full_encoded = urlencode($this->plugin_name_full);
			$this->plugin_name = 'wps';
			$this->plugin_text_domain = 'wp-shopify';
			$this->plugin_name_js = 'WP_Shopify';
			$this->plugin_version = '1.0.49';
			$this->plugin_author = 'Andrew Robbins';

			self::$plugin_nonce_action_backend = 'wp-shopify-backend';
			self::$plugin_nonce_action_frontend = 'wp-shopify-frontend';
			self::$plugin_nonce_action_uninstall = 'wp-shopify-uninstall';
			self::$plugin_nonce_action_cache = 'wp-shopify-cache';

			$this->plugin_root_file = $this->plugin_path . $this->plugin_name . '.php';
			$this->plugin_file = plugin_basename($this->plugin_root_file);

			$this->plugin_basename = plugin_basename( $this->plugin_path . $this->plugin_name . '.php' );
			$this->plugin_env = 'https://wpshop.io';

			// Don't clear cart cache for three days 259200
			$this->cart_cache_expiration = 259200;

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

		Get the backend nonce action

		*/
		public static function get_cache_nonce_action() {
			return self::$plugin_nonce_action_cache;
		}


		/*

		Get the backend nonce action

		*/
		public static function get_uninstall_nonce_action() {
			return self::$plugin_nonce_action_uninstall;
		}


		/*

		Get the backend nonce action

		*/
		public static function get_backendend_nonce_action() {
			return self::$plugin_nonce_action_backend;
		}


		/*

		Get the frontend nonce action

		*/
		public static function get_frontend_nonce_action() {
			return self::$plugin_nonce_action_frontend;
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


		/*

		Gets the current plugin version number

		*/
		public function get_current_plugin_version() {

			$DB_Settings_General = new Settings_General();
			$currentPluginVersion = $DB_Settings_General->get_column_single('plugin_version');

			if (isset($currentPluginVersion) && $currentPluginVersion) {
				$databaseVersion = $currentPluginVersion[0]->plugin_version;

			} else {
				$databaseVersion = '0.0.0'; // If code falls here, the version will always force an update
			}

			return $databaseVersion;

		}


		/*

		Gets the current plugin version number

		*/
		public function get_new_plugin_version() {
			return $this->plugin_version;
		}


	}

}
