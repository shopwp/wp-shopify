<?php

namespace WPS\Factories;

if (!defined('ABSPATH')) {
	exit;
}

use WPS\WS;

use WPS\Factories\Messages_Factory;
use WPS\Factories\DB_Settings_Connection_Factory;
use WPS\Factories\DB_Settings_General_Factory;
use WPS\Factories\DB_Settings_Syncing_Factory;
use WPS\Vendor\GuzzleHttp\Client as GuzzleClient;


if (!class_exists('WS_Factory')) {

  class WS_Factory {

		protected static $instantiated = null;

    public static function build() {

			if (is_null(self::$instantiated)) {

				$WS = new WS(
					new GuzzleClient(),
					Messages_Factory::build(),
					DB_Settings_Connection_Factory::build(),
					DB_Settings_General_Factory::build(),
					DB_Settings_Syncing_Factory::build()
				);

				self::$instantiated = $WS;

			}

			return self::$instantiated;

    }

  }

}
