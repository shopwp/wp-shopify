<?php

namespace WPS\Factories;

use WPS\Async_Processing_Orders;

use WPS\Factories\DB_Settings_Syncing_Factory;
use WPS\Factories\DB_Orders_Factory;
use WPS\Factories\WS_Factory;

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('Async_Processing_Orders_Factory')) {

  class Async_Processing_Orders_Factory {

		protected static $instantiated = null;

    public static function build() {

			if (is_null(self::$instantiated)) {

				$Async_Processing_Orders = new Async_Processing_Orders(
					DB_Settings_Syncing_Factory::build(),
					DB_Orders_Factory::build(),
					WS_Factory::build()
				);

				self::$instantiated = $Async_Processing_Orders;

			}

			return self::$instantiated;

    }

  }

}
