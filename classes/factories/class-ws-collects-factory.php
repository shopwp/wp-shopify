<?php

namespace WPS\Factories;

if (!defined('ABSPATH')) {
	exit;
}

use WPS\WS\Collects as WS_Collects;

use WPS\Factories\DB_Collects_Factory;
use WPS\Factories\DB_Settings_General_Factory;
use WPS\Factories\DB_Settings_Connection_Factory;
use WPS\Factories\DB_Settings_Syncing_Factory;
use WPS\Factories\WS_Factory;
use WPS\Factories\Messages_Factory;
use WPS\Factories\Async_Processing_Collects_Factory;
use WPS\Vendor\GuzzleHttp\Client as GuzzleClient;


if (!class_exists('WS_Collects_Factory')) {

  class WS_Collects_Factory {

		protected static $instantiated = null;

    public static function build() {

			if (is_null(self::$instantiated)) {

				$WS_Collects = new WS_Collects(
					DB_Collects_Factory::build(),
					DB_Settings_General_Factory::build(),
					DB_Settings_Connection_Factory::build(),
					DB_Settings_Syncing_Factory::build(),
					WS_Factory::build(),
					Messages_Factory::build(),
					new GuzzleClient(),
					Async_Processing_Collects_Factory::build()
				);

				self::$instantiated = $WS_Collects;

			}

			return self::$instantiated;

    }

  }

}
