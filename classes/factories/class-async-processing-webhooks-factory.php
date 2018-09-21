<?php

namespace WPS\Factories;

use WPS\Async_Processing_Webhooks;

use WPS\Factories\DB_Settings_Syncing_Factory;
use WPS\Factories\Webhooks_Factory;
use WPS\Factories\Shopify_API_Factory;

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('Async_Processing_Webhooks_Factory')) {

  class Async_Processing_Webhooks_Factory {

		protected static $instantiated = null;

    public static function build() {

			if (is_null(self::$instantiated)) {

				$Async_Processing_Webhooks = new Async_Processing_Webhooks(
					DB_Settings_Syncing_Factory::build(),
					Webhooks_Factory::build(),
					Shopify_API_Factory::build()
				);

				self::$instantiated = $Async_Processing_Webhooks;

			}

			return self::$instantiated;

    }

  }

}
