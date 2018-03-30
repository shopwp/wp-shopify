<?php

namespace WPS;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
	exit;
}


/*

Fired during plugin deactivation

*/
if ( !class_exists('Deactivator') ) {

	class Deactivator {

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
		public static function instance($Config) {

			if (is_null(self::$instantiated)) {
				self::$instantiated = new self($Config);
			}

			return self::$instantiated;

		}


		/*

		Things to do on plugin deactivation

		*/
		public function deactivate() {
			delete_option('rewrite_rules');
		}

		public function init() {
			register_deactivation_hook($this->plugin_basename, [$this, 'deactivate']);
		}

	}

}
