<?php

namespace WPS\Factories;

if (!defined('ABSPATH')) {
	exit;
}

use WPS\WS\Webhooks;

use WPS\Factories\DB_Settings_Syncing_Factory;
use WPS\Factories\Webhooks_Factory;
use WPS\Factories\Async_Processing_Webhooks_Factory;
use WPS\Factories\Async_Processing_Webhooks_Deletions_Factory;
use WPS\Factories\Shopify_API_Factory;

class WS_Webhooks_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			$Webhooks = new Webhooks(
				DB_Settings_Syncing_Factory::build(),
				Webhooks_Factory::build(),
				Async_Processing_Webhooks_Factory::build(),
				Async_Processing_Webhooks_Deletions_Factory::build(),
				Shopify_API_Factory::build()
			);

			self::$instantiated = $Webhooks;

		}

		return self::$instantiated;

	}

}
