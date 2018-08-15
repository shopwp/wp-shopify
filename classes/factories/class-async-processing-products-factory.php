<?php

namespace WPS\Factories;

use WPS\Async_Processing_Products;

use WPS\Factories\DB_Settings_Syncing_Factory;
use WPS\Factories\DB_Products_Factory;
use WPS\Factories\WS_Factory;


if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('Async_Processing_Products_Factory')) {

  class Async_Processing_Products_Factory {

		protected static $instantiated = null;

    public static function build() {

			if (is_null(self::$instantiated)) {

				$Async_Processing_Products = new Async_Processing_Products(
					DB_Settings_Syncing_Factory::build(),
					DB_Products_Factory::build(),
					WS_Factory::build()
				);

				self::$instantiated = $Async_Processing_Products;

			}

			return self::$instantiated;


    }

  }

}
