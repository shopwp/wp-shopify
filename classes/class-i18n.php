<?php

namespace WPS;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
	exit;
}


/*

Class Internationalization

*/
if (!class_exists('I18N')) {

	class I18N {

		protected static $instantiated = null;

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

		//
		// Load the plugin text domain for translation.
		//
		public function wps_load_plugin_textdomain() {

			load_plugin_textdomain(
				'wp-shopify',
				false,
				dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
			);

		}

		public function init() {
			add_action( 'plugins_loaded', array($this, 'wps_load_plugin_textdomain') );
		}

	}

}
