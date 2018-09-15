<?php

namespace WPS\Factories;

if (!defined('ABSPATH')) {
	exit;
}

use WPS\Async_Processing_Posts_Collections_Smart;

use WPS\Factories\DB_Settings_Syncing_Factory;
use WPS\Factories\CPT_Query_Factory;

if (!class_exists('Async_Processing_Posts_Collections_Smart_Factory')) {

  class Async_Processing_Posts_Collections_Smart_Factory {

		protected static $instantiated = null;

    public static function build() {

			if (is_null(self::$instantiated)) {

				$Async_Processing_Posts_Collections_Smart = new Async_Processing_Posts_Collections_Smart(
					DB_Settings_Syncing_Factory::build(),
					CPT_Query_Factory::build()
				);

				self::$instantiated = $Async_Processing_Posts_Collections_Smart;

			}

			return self::$instantiated;

    }

  }

}
