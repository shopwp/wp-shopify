<?php

namespace WPS\Factories;

use WPS\WS\Variants as WS_Variants;

use WPS\Factories\DB_Products_Factory;
use WPS\Factories\DB_Settings_General_Factory;
use WPS\Factories\Messages_Factory;
use WPS\Factories\DB_Variants_Factory;
use WPS\Factories\DB_Settings_Connection_Factory;

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('WS_Variants_Factory')) {

  class WS_Variants_Factory {

		protected static $instantiated = null;

    public static function build() {

			if (is_null(self::$instantiated)) {

				$WS_Variants = new WS_Variants(
					DB_Products_Factory::build(),
					DB_Settings_General_Factory::build(),
					Messages_Factory::build(),
					DB_Variants_Factory::build(),
					DB_Settings_Connection_Factory::build()
				);

				self::$instantiated = $WS_Variants;

			}

			return self::$instantiated;

    }

  }

}
