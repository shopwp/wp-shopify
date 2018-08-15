<?php

namespace WPS\Factories;

use WPS\WS;
use GuzzleHttp\Client as Guzzle;

use WPS\Factories\Messages_Factory;
use WPS\Factories\DB_Settings_Connection_Factory;
use WPS\Factories\DB_Settings_General_Factory;
use WPS\Factories\DB_Settings_Syncing_Factory;


require plugin_dir_path( __FILE__ ) . '../../vendor/autoload.php';

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('WS_Factory')) {

  class WS_Factory {

		protected static $instantiated = null;

    public static function build() {

			if (is_null(self::$instantiated)) {

				$WS = new WS(
					new Guzzle(),
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
