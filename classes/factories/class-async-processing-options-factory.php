<?php

namespace WPS\Factories;

use WPS\Async_Processing_Options;

use WPS\Factories\DB_Settings_Syncing_Factory;
use WPS\Factories\DB_Options_Factory;


if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('Async_Processing_Options_Factory')) {

  class Async_Processing_Options_Factory {

		protected static $instantiated = null;

    public static function build() {

			if (is_null(self::$instantiated)) {

				$Async_Processing_Options = new Async_Processing_Options(
					DB_Settings_Syncing_Factory::build(),
					DB_Options_Factory::build()
				);

				self::$instantiated = $Async_Processing_Options;

			}

			return self::$instantiated;

    }

  }

}
