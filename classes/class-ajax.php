<?php

namespace WPS;

//require_once plugin_dir_path( __FILE__ ) . '../admin/class-admin.php';

/*

Class Ajax

*/
class AJAX {

  protected static $instantiated = null;
  private $Config;

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
	public static function instance() {

		if (is_null(self::$instantiated)) {
			self::$instantiated = new self();
		}

		return self::$instantiated;

	}


  /*

	Get the plugin options

	*/
	public function wps_get_options() {

		echo json_encode( 'from class-ajax' );
		die();

	}

}
