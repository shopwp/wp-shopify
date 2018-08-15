<?php

namespace WPS\Factories;

use WPS\WS\Orders as WS_Orders;

use WPS\Factories\DB_Orders_Factory;
use WPS\Factories\DB_Settings_General_Factory;
use WPS\Factories\DB_Settings_Connection_Factory;
use WPS\Factories\Messages_Factory;
use WPS\Factories\WS_Factory;
use WPS\Factories\Async_Processing_Orders_Factory;

use GuzzleHttp\Client as Guzzle;

require plugin_dir_path( __FILE__ ) . '../../vendor/autoload.php';


if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('WS_Orders_Factory')) {

  class WS_Orders_Factory {

		protected static $instantiated = null;

    public static function build() {

			if (is_null(self::$instantiated)) {

				$WS_Orders = new WS_Orders(
					DB_Orders_Factory::build(),
					DB_Settings_General_Factory::build(),
					Messages_Factory::build(),
					DB_Settings_Connection_Factory::build(),
					DB_Settings_Syncing_Factory::build(),
					new Guzzle(),
					WS_Factory::build(),
					Async_Processing_Orders_Factory::build()
				);

				self::$instantiated = $WS_Orders;

			}

			return self::$instantiated;

    }

  }

}
