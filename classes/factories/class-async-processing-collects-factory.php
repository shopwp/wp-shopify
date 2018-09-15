<?php

namespace WPS\Factories;

use WPS\Async_Processing_Collects;

use WPS\Factories\DB_Settings_Syncing_Factory;
use WPS\Factories\DB_Collects_Factory;

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('Async_Processing_Collects_Factory')) {

  class Async_Processing_Collects_Factory {

		protected static $instantiated = null;

    public static function build() {

			if (is_null(self::$instantiated)) {

				$Async_Processing_Collects = new Async_Processing_Collects(
					DB_Settings_Syncing_Factory::build(),
					DB_Collects_Factory::build()
				);

				self::$instantiated = $Async_Processing_Collects;

			}

			return self::$instantiated;

    }

  }

}
