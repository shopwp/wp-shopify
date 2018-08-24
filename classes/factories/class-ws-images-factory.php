<?php

namespace WPS\Factories;

if (!defined('ABSPATH')) {
	exit;
}


use WPS\WS\Images as WS_Images;

use WPS\Factories\DB_Images_Factory;
use WPS\Factories\DB_Settings_General_Factory;
use WPS\Factories\Messages_Factory;
use WPS\Vendor\GuzzleHttp\Client as GuzzleClient;


if (!class_exists('WS_Images_Factory')) {

  class WS_Images_Factory {

		protected static $instantiated = null;

    public static function build() {

			if (is_null(self::$instantiated)) {

				$WS_Images = new WS_Images(
					DB_Images_Factory::build(),
					DB_Settings_General_Factory::build(),
					Messages_Factory::build(),
					new GuzzleClient()
				);

				self::$instantiated = $WS_Images;

			}

			return self::$instantiated;

    }

  }

}
