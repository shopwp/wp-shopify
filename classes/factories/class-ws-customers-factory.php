<?php

namespace WPS\Factories;

if (!defined('ABSPATH')) {
	exit;
}

use WPS\WS\Customers as WS_Customers;

use WPS\Factories\DB_Customers_Factory;
use WPS\Factories\DB_Settings_Syncing_Factory;
use WPS\Factories\DB_Settings_General_Factory;
use WPS\Factories\Async_Processing_Customers_Factory;
use WPS\Factories\Shopify_API_Factory;


if (!class_exists('WS_Customers_Factory')) {

  class WS_Customers_Factory {

		protected static $instantiated = null;

    public static function build() {

			if (is_null(self::$instantiated)) {

				$WS_Customers = new WS_Customers(
					DB_Customers_Factory::build(),
					DB_Settings_Syncing_Factory::build(),
					DB_Settings_General_Factory::build(),
					Async_Processing_Customers_Factory::build(),
					Shopify_API_Factory::build()
				);

				self::$instantiated = $WS_Customers;

			}

			return self::$instantiated;

    }

  }

}
