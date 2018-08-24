<?php

namespace WPS\Factories;

if (!defined('ABSPATH')) {
	exit;
}

use WPS\WS\Webhooks as WS_Webhooks;

use WPS\Factories\DB_Settings_Connection_Factory;
use WPS\Factories\DB_Settings_General_Factory;
use WPS\Factories\DB_Settings_Syncing_Factory;
use WPS\Factories\Webhooks_Factory;
use WPS\Factories\Messages_Factory;
use WPS\Factories\WS_Factory;
use WPS\Factories\Async_Processing_Webhooks_Factory;
use WPS\Factories\Async_Processing_Webhooks_Deletions_Factory;
use WPS\Vendor\GuzzleHttp\Client as GuzzleClient;

if (!class_exists('WS_Webhooks_Factory')) {

  class WS_Webhooks_Factory {

		protected static $instantiated = null;

    public static function build() {

			if (is_null(self::$instantiated)) {

				$Webhooks = new WS_Webhooks(
					DB_Settings_Connection_Factory::build(),
					DB_Settings_General_Factory::build(),
					DB_Settings_Syncing_Factory::build(),
					Webhooks_Factory::build(),
					Messages_Factory::build(),
					new GuzzleClient(),
					WS_Factory::build(),
					Async_Processing_Webhooks_Factory::build(),
					Async_Processing_Webhooks_Deletions_Factory::build()
				);

				self::$instantiated = $Webhooks;

			}

			return self::$instantiated;

		}

  }

}
