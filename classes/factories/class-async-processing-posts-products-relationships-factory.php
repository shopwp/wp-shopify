<?php

namespace WPS\Factories;

use WPS\Async_Processing_Posts_Products_Relationships;

use WPS\Factories\DB_Products_Factory;
use WPS\Factories\DB_Settings_Syncing_Factory;
use WPS\Factories\DB_Tags_Factory;

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('Async_Processing_Posts_Products_Relationships_Factory')) {

  class Async_Processing_Posts_Products_Relationships_Factory {

		protected static $instantiated = null;

    public static function build() {

			if (is_null(self::$instantiated)) {

				$Async_Processing_Posts_Products_Relationships = new Async_Processing_Posts_Products_Relationships(
					DB_Products_Factory::build(),
					DB_Settings_Syncing_Factory::build(),
					DB_Tags_Factory::build()
				);

				self::$instantiated = $Async_Processing_Posts_Products_Relationships;

			}

			return self::$instantiated;


    }

  }

}
