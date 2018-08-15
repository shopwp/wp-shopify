<?php

namespace WPS\Factories;

use WPS\DB\Variants as DB_Variants;
use WPS\Factories\DB_Settings_Connection_Factory;

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('DB_Variants_Factory')) {

  class DB_Variants_Factory {

		protected static $instantiated = null;

    public static function build() {

			if (is_null(self::$instantiated)) {

				$DB_Variants = new DB_Variants(
					DB_Settings_Connection_Factory::build()
				);

				self::$instantiated = $DB_Variants;

			}

			return self::$instantiated;

		}

  }

}
