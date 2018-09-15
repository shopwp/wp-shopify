<?php

namespace WPS\Factories;

use WPS\Async_Processing_Collections_Custom;

use WPS\Factories\DB_Settings_Syncing_Factory;
use WPS\Factories\DB_Collections_Factory;


if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('Async_Processing_Collections_Custom_Factory')) {

  class Async_Processing_Collections_Custom_Factory {

		protected static $instantiated = null;

    public static function build() {

			if (is_null(self::$instantiated)) {

				$Async_Processing_Collections_Custom = new Async_Processing_Collections_Custom(
					DB_Settings_Syncing_Factory::build(),
					DB_Collections_Factory::build()
				);

				self::$instantiated = $Async_Processing_Collections_Custom;

			}

			return self::$instantiated;

    }

  }

}
