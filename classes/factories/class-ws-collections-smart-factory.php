<?php

namespace WPS\Factories;

use WPS\WS\Collections_Smart as WS_Collections_Smart;

use WPS\Factories\DB_Settings_Syncing_Factory;
use WPS\Factories\DB_Settings_General_Factory;
use WPS\Factories\DB_Settings_Connection_Factory;
use WPS\Factories\DB_Collections_Smart_Factory;
use WPS\Factories\Messages_Factory;
use WPS\Factories\CPT_Model_Factory;
use WPS\Factories\WS_Factory;
use WPS\Factories\Async_Processing_Collections_Smart_Factory;
use WPS\Factories\Async_Processing_Posts_Collections_Smart_Factory;

use GuzzleHttp\Client as Guzzle;

require plugin_dir_path( __FILE__ ) . '../../vendor/autoload.php';


if (!defined('ABSPATH')) {
	exit;
}


if (!class_exists('WS_Collections_Smart_Factory')) {

  class WS_Collections_Smart_Factory {

		protected static $instantiated = null;

    public static function build() {

			if (is_null(self::$instantiated)) {

				$WS_Collections_Smart = new WS_Collections_Smart(
					DB_Settings_Syncing_Factory::build(),
					DB_Settings_General_Factory::build(),
					DB_Settings_Connection_Factory::build(),
					DB_Collections_Smart_Factory::build(),
					Messages_Factory::build(),
					new Guzzle(),
					CPT_Model_Factory::build(),
					WS_Factory::build(),
					Async_Processing_Collections_Smart_Factory::build(),
					Async_Processing_Posts_Collections_Smart_Factory::build()
				);

				self::$instantiated = $WS_Collections_Smart;

			}

			return self::$instantiated;

    }

  }

}
