<?php

namespace WPS\Factories;

use WPS\License;
use WPS\Factories\WS_Settings_License_Factory;
use WPS\Factories\DB_Settings_License_Factory;

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('License_Factory')) {

  class License_Factory {

		protected static $instantiated = null;

    public static function build() {

			if (is_null(self::$instantiated)) {

				$License = new License(
					WS_Settings_License_Factory::build(),
					DB_Settings_License_Factory::build()
				);

				self::$instantiated = $License;

			}

			return self::$instantiated;

		}

  }

}
