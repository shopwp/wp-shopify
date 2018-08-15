<?php

namespace WPS\Factories;

use WPS\WS\Options as WS_Options;

use WPS\Factories\DB_Options_Factory;
use WPS\Factories\DB_Settings_General_Factory;
use WPS\Factories\Messages_Factory;

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('WS_Options_Factory')) {

  class WS_Options_Factory {

		protected static $instantiated = null;

    public static function build() {

			if (is_null(self::$instantiated)) {

				$WS_Options = new WS_Options(
					DB_Options_Factory::build(),
					DB_Settings_General_Factory::build(),
					Messages_Factory::build()
				);

				self::$instantiated = $WS_Options;

			}

			return self::$instantiated;

    }

  }

}
