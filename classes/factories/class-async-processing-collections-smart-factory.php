<?php

namespace WPS\Factories;

use WPS\Async_Processing_Collections_Smart;

use WPS\Factories\DB_Settings_Syncing_Factory;
use WPS\Factories\DB_Collections_Smart_Factory;
use WPS\Factories\WS_Factory;


if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('Async_Processing_Collections_Smart_Factory')) {

  class Async_Processing_Collections_Smart_Factory {

		protected static $instantiated = null;

    public static function build() {

			if (is_null(self::$instantiated)) {

				$Async_Processing_Collections_Smart = new Async_Processing_Collections_Smart(
					DB_Settings_Syncing_Factory::build(),
					DB_Collections_Smart_Factory::build(),
					WS_Factory::build()
				);

				self::$instantiated = $Async_Processing_Collections_Smart;

			}

			return self::$instantiated;

    }

  }

}
