<?php

namespace WPS\Factories\Processing;

use WPS\Processing;
use WPS\Factories;

if (!defined('ABSPATH')) {
	exit;
}

class Webhooks_Deletions_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			self::$instantiated = new Processing\Webhooks_Deletions(
				Factories\DB\Settings_Syncing_Factory::build(),
				Factories\Shopify_API_Factory::build()
			);

		}

		return self::$instantiated;

	}

}
