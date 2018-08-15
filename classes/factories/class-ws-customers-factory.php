<?php

namespace WPS\Factories;

use WPS\WS\Customers as WS_Customers;

use WPS\Factories\DB_Customers_Factory;
use WPS\Factories\DB_Settings_Connection_Factory;
use WPS\Factories\DB_Settings_Syncing_Factory;
use WPS\Factories\DB_Settings_General_Factory;
use WPS\Factories\Messages_Factory;
use WPS\Factories\WS_Factory;
use WPS\Factories\Async_Processing_Customers_Factory;

use GuzzleHttp\Client as Guzzle;

require plugin_dir_path( __FILE__ ) . '../../vendor/autoload.php';

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('WS_Customers_Factory')) {

  class WS_Customers_Factory {

		protected static $instantiated = null;

    public static function build() {

			if (is_null(self::$instantiated)) {

				$WS_Customers = new WS_Customers(
					DB_Customers_Factory::build(),
					DB_Settings_Connection_Factory::build(),
					DB_Settings_Syncing_Factory::build(),
					DB_Settings_General_Factory::build(),
					Messages_Factory::build(),
					new Guzzle(),
					WS_Factory::build(),
					Async_Processing_Customers_Factory::build()
				);

				self::$instantiated = $WS_Customers;

			}

			return self::$instantiated;

    }

  }

}
