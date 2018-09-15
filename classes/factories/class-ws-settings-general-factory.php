<?php

namespace WPS\Factories;

use WPS\WS\Settings_General as WS_Settings_General;

use WPS\Factories\DB_Settings_General_Factory;
use WPS\Factories\DB_Shop_Factory;
use WPS\Factories\DB_Collections_Factory;

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('WS_Settings_General_Factory')) {

  class WS_Settings_General_Factory {

		protected static $instantiated = null;

    public static function build() {

			if (is_null(self::$instantiated)) {

				$WS_Settings_General = new WS_Settings_General(
					DB_Settings_General_Factory::build(),
					DB_Shop_Factory::build(),
					DB_Collections_Factory::build()
				);

				self::$instantiated = $WS_Settings_General;

			}

			return self::$instantiated;

    }

  }

}
