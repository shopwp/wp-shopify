<?php

namespace WPS;
require plugin_dir_path( __FILE__ ) . '../vendor/autoload.php';

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
	exit;
}

use WPS\WS;
use WPS\Messages;
use GuzzleHttp\Client as Guzzle;

/*

Class Waypoint

*/
class Waypoints {

  protected static $instantiated = null;
  private $Config;
  private $WS;
  private $messages;

	/*

	Initialize the class and set its properties.

	*/
	public function __construct($Config) {
		$this->config = $Config;
    $this->ws = new WS($this->config);
    $this->messages = new Messages();
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

}
