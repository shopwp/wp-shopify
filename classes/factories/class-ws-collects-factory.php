<?php

namespace WPS\Factories;

if (!defined('ABSPATH')) {
	exit;
}

use WPS\WS\Collects as WS_Collects;

use WPS\Factories\DB_Settings_General_Factory;
use WPS\Factories\DB_Settings_Syncing_Factory;
use WPS\Factories\Async_Processing_Collects_Factory;
use WPS\Factories\Shopify_API_Factory;


if (!class_exists('WS_Collects_Factory')) {

  class WS_Collects_Factory {

		protected static $instantiated = null;

    public static function build() {

			if (is_null(self::$instantiated)) {

				$WS_Collects = new WS_Collects(
					DB_Settings_General_Factory::build(),
					DB_Settings_Syncing_Factory::build(),
					Async_Processing_Collects_Factory::build(),
					Shopify_API_Factory::build()
				);

				self::$instantiated = $WS_Collects;

			}

			return self::$instantiated;

    }

  }

}
