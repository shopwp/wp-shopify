<?php

namespace WPS\Factories;

if (!defined('ABSPATH')) {
	exit;
}

use WPS\WS\Settings_Connection as WS_Settings_Connection;

use WPS\Vendor\GuzzleHttp\Client as GuzzleClient;
use WPS\Factories\DB_Settings_Connection_Factory;
use WPS\Factories\DB_Settings_General_Factory;
use WPS\Factories\Messages_Factory;


if (!class_exists('WS_Settings_Connection_Factory')) {

  class WS_Settings_Connection_Factory {

		protected static $instantiated = null;

    public static function build() {

			if (is_null(self::$instantiated)) {

				$WS_Settings_Connection = new WS_Settings_Connection(
					DB_Settings_Connection_Factory::build(),
					Messages_Factory::build(),
					new GuzzleClient(),
					DB_Settings_General_Factory::build()
				);

				self::$instantiated = $WS_Settings_Connection;

			}

			return self::$instantiated;

    }

  }

}
