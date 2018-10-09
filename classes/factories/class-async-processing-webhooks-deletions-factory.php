<?php

namespace WPS\Factories;

use WPS\Async_Processing_Webhooks_Deletions;

use WPS\Factories\DB_Settings_Syncing_Factory;
use WPS\Factories\Shopify_API_Factory;

if (!defined('ABSPATH')) {
	exit;
}

class Async_Processing_Webhooks_Deletions_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			$Async_Processing_Webhooks_Deletions = new Async_Processing_Webhooks_Deletions(
				DB_Settings_Syncing_Factory::build(),
				Shopify_API_Factory::build()
			);

			self::$instantiated = $Async_Processing_Webhooks_Deletions;

		}

		return self::$instantiated;

	}

}
