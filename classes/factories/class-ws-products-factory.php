<?php

namespace WPS\Factories;

if (!defined('ABSPATH')) {
	exit;
}

use WPS\WS\Products as WS_Products;

use WPS\Factories\DB_Settings_General_Factory;
use WPS\Factories\DB_Products_Factory;
use WPS\Factories\DB_Tags_Factory;
use WPS\Factories\DB_Variants_Factory;
use WPS\Factories\DB_Options_Factory;
use WPS\Factories\DB_Images_Factory;
use WPS\Factories\CPT_Model_Factory;
use WPS\Factories\Async_Processing_Posts_Products_Factory;
use WPS\Factories\Async_Processing_Products_Factory;
use WPS\Factories\Async_Processing_Tags_Factory;
use WPS\Factories\Async_Processing_Variants_Factory;
use WPS\Factories\Async_Processing_Options_Factory;
use WPS\Factories\Async_Processing_Images_Factory;
use WPS\Factories\DB_Settings_Syncing_Factory;
use WPS\Factories\HTTP_Factory;


if (!class_exists('WS_Products_Factory')) {

  class WS_Products_Factory {

		protected static $instantiated = null;

    public static function build() {

			if (is_null(self::$instantiated)) {

				$WS_Products = new WS_Products(
					DB_Settings_General_Factory::build(),
					DB_Products_Factory::build(),
					DB_Tags_Factory::build(),
					DB_Variants_Factory::build(),
					DB_Options_Factory::build(),
					DB_Images_Factory::build(),
					CPT_Model_Factory::build(),
					Async_Processing_Posts_Products_Factory::build(),
					Async_Processing_Products_Factory::build(),
					Async_Processing_Tags_Factory::build(),
					Async_Processing_Variants_Factory::build(),
					Async_Processing_Options_Factory::build(),
					Async_Processing_Images_Factory::build(),
					DB_Settings_Syncing_Factory::build(),
					HTTP_Factory::build()
				);

				self::$instantiated = $WS_Products;

			}

			return self::$instantiated;

    }

  }

}
