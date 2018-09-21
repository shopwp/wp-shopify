<?php

namespace WPS\Factories;

use WPS\WS\Collections_Custom as WS_Collections_Custom;

use WPS\Factories\DB_Settings_Syncing_Factory;
use WPS\Factories\DB_Settings_General_Factory;
use WPS\Factories\DB_Collections_Custom_Factory;
use WPS\Factories\CPT_Model_Factory;
use WPS\Factories\Async_Processing_Collections_Custom_Factory;
use WPS\Factories\Async_Processing_Posts_Collections_Custom_Factory;
use WPS\Factories\Shopify_API_Factory;

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('WS_Collections_Custom_Factory')) {

  class WS_Collections_Custom_Factory {

		protected static $instantiated = null;

    public static function build() {

			if (is_null(self::$instantiated)) {

				$WS_Collections_Custom = new WS_Collections_Custom(
					DB_Settings_Syncing_Factory::build(),
					DB_Settings_General_Factory::build(),
					DB_Collections_Custom_Factory::build(),
					CPT_Model_Factory::build(),
					Async_Processing_Collections_Custom_Factory::build(),
					Async_Processing_Posts_Collections_Custom_Factory::build(),
					Shopify_API_Factory::build()
				);

				self::$instantiated = $WS_Collections_Custom;

			}

			return self::$instantiated;

    }

  }

}
