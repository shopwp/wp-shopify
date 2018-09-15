<?php

namespace WPS\Factories;

use WPS\Async_Processing_Customers;

use WPS\Factories\DB_Settings_Syncing_Factory;
use WPS\Factories\DB_Customers_Factory;

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('Async_Processing_Customers_Factory')) {

  class Async_Processing_Customers_Factory {

		protected static $instantiated = null;

    public static function build() {

			if (is_null(self::$instantiated)) {

				$Async_Processing_Customers = new Async_Processing_Customers(
					DB_Settings_Syncing_Factory::build(),
					DB_Customers_Factory::build()
				);

				self::$instantiated = $Async_Processing_Customers;

			}

			return self::$instantiated;

    }

  }

}
