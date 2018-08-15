<?php

namespace WPS\Factories;

use WPS\CPT;

use WPS\Factories\DB_Settings_General_Factory;
use WPS\Factories\DB_Products_Factory;
use WPS\Factories\DB_Collections_Custom_Factory;
use WPS\Factories\DB_Collections_Smart_Factory;
use WPS\Factories\DB_Collects_Factory;
use WPS\Factories\DB_Tags_Factory;

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('CPT_Factory')) {

  class CPT_Factory {

		protected static $instantiated = null;

    public static function build() {

			if (is_null(self::$instantiated)) {

				$CPT = new CPT(
					DB_Settings_General_Factory::build(),
					DB_Products_Factory::build(),
					DB_Collections_Custom_Factory::build(),
					DB_Collections_Smart_Factory::build(),
					DB_Collects_Factory::build(),
					DB_Tags_Factory::build()
				);

				self::$instantiated = $CPT;

			}

			return self::$instantiated;

    }

  }

}
