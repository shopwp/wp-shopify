<?php

namespace WPS;

/*

Fired during plugin deactivation

*/
class Deactivator {

	protected static $instantiated = null;
	private $Config;
	public $plugin_basename;

	/*

	Initialize the class and set its properties.

	*/
	public function __construct($Config) {
		// $this->connection = $this->config->wps_get_settings_connection();
		// $this->license 		= $this->config->wps_get_settings_license();
		// $this->general 		= $this->config->wps_get_settings_general();


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

	Things to do on plugin deactivation

	*/
	public function deactivate() {
		flush_rewrite_rules();
	}


	public function init() {
		register_activation_hook($this->plugin_basename, [$this, 'deactivate']);
	}

}
