<?php

namespace WPS;


/*

Class Checkouts

*/
class Checkouts {

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

  Saving webhook plugin settings
  TODO: Same as function above, combine into utility

  */
  public function wps_on_checkout() {

  }


  public function init() {

  }


}
