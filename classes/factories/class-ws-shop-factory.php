<?php

namespace WPS\Factories;

use WPS\WS\Shop as WS_Shop;

use WPS\Factories\DB_Settings_Connection_Factory;
use WPS\Factories\DB_Settings_General_Factory;
use WPS\Factories\DB_Settings_Syncing_Factory;
use WPS\Factories\DB_Shop_Factory;
use WPS\Factories\Messages_Factory;

use GuzzleHttp\Client as Guzzle;

require plugin_dir_path( __FILE__ ) . '../../vendor/autoload.php';

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('WS_Shop_Factory')) {

  class WS_Shop_Factory {

		protected static $instantiated = null;

    public static function build() {

			if (is_null(self::$instantiated)) {

				$WS_Shop = new WS_Shop(
					DB_Settings_Connection_Factory::build(),
					DB_Settings_General_Factory::build(),
					DB_Settings_Syncing_Factory::build(),
					DB_Shop_Factory::build(),
					Messages_Factory::build(),
					new Guzzle()
				);

				self::$instantiated = $WS_Shop;

			}

			return self::$instantiated;

    }

  }

}
