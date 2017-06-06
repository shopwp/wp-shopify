<?php

namespace WPS;

/*

Class Transients

*/
class Transients {

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

  check_rewrite_rules

  */
  public static function check_rewrite_rules() {

    if (get_transient('wps_settings_updated') !== false) {

      // error_log('WPS Settings HAVE been updated');
      flush_rewrite_rules();
      delete_transient('wps_settings_updated');

    } else {
      // error_log('WPS Settings havent been updated');
    }

  }

}
