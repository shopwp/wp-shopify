<?php

namespace WPS\Factories;

use WPS\WS\Settings_License as WS_Settings_License;

use WPS\Factories\DB_Settings_License_Factory;
use WPS\Factories\HTTP_Factory;


if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('WS_Settings_License_Factory')) {

  class WS_Settings_License_Factory {

		protected static $instantiated = null;

    public static function build() {

			if (is_null(self::$instantiated)) {

				$WS_Settings_License = new WS_Settings_License(
					DB_Settings_License_Factory::build(),
					HTTP_Factory::build()
				);

				self::$instantiated = $WS_Settings_License;

			}

			return self::$instantiated;

    }

  }

}
