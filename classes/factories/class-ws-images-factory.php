<?php

namespace WPS\Factories;

use WPS\WS\Images as WS_Images;

use WPS\Factories\DB_Images_Factory;
use WPS\Factories\DB_Settings_General_Factory;
use WPS\Factories\Messages_Factory;

use GuzzleHttp\Client as Guzzle;

require plugin_dir_path( __FILE__ ) . '../../vendor/autoload.php';


if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('WS_Images_Factory')) {

  class WS_Images_Factory {

		protected static $instantiated = null;

    public static function build() {

			if (is_null(self::$instantiated)) {

				$WS_Images = new WS_Images(
					DB_Images_Factory::build(),
					DB_Settings_General_Factory::build(),
					Messages_Factory::build(),
					new Guzzle()
				);

				self::$instantiated = $WS_Images;

			}

			return self::$instantiated;

    }

  }

}
