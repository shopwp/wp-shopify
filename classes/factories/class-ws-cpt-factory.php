<?php

namespace WPS\Factories;

use WPS\WS\CPT as WS_CPT;

use WPS\Factories\DB_Factory;
use WPS\Factories\Async_Processing_Posts_Products_Relationships_Factory;
use WPS\Factories\Async_Processing_Posts_Collections_Relationships_Factory;

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('WS_CPT_Factory')) {

  class WS_CPT_Factory {

		protected static $instantiated = null;

    public static function build() {

			if (is_null(self::$instantiated)) {

				$WS_CPT = new WS_CPT(
					DB_Factory::build(),
					Async_Processing_Posts_Products_Relationships_Factory::build(),
					Async_Processing_Posts_Collections_Relationships_Factory::build()
				);

				self::$instantiated = $WS_CPT;

			}

      return self::$instantiated;

    }

  }

}
