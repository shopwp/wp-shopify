<?php

namespace WPS\Factories;

if (!defined('ABSPATH')) {
	exit;
}

use WPS\WS\Collections_Smart as WS_Collections_Smart;

use WPS\Factories\DB_Settings_Syncing_Factory;
use WPS\Factories\DB_Settings_General_Factory;
use WPS\Factories\DB_Collections_Smart_Factory;
use WPS\Factories\CPT_Model_Factory;
use WPS\Factories\Async_Processing_Collections_Smart_Factory;
use WPS\Factories\Async_Processing_Posts_Collections_Smart_Factory;
use WPS\Factories\HTTP_Factory;

if (!class_exists('WS_Collections_Smart_Factory')) {

  class WS_Collections_Smart_Factory {

		protected static $instantiated = null;

    public static function build() {

			if (is_null(self::$instantiated)) {

				$WS_Collections_Smart = new WS_Collections_Smart(
					DB_Settings_Syncing_Factory::build(),
					DB_Settings_General_Factory::build(),
					DB_Collections_Smart_Factory::build(),
					CPT_Model_Factory::build(),
					Async_Processing_Collections_Smart_Factory::build(),
					Async_Processing_Posts_Collections_Smart_Factory::build(),
					HTTP_Factory::build()
				);

				self::$instantiated = $WS_Collections_Smart;

			}

			return self::$instantiated;

    }

  }

}
