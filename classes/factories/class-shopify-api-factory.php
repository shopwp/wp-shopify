<?php

namespace WPS\Factories;

use WPS\Factories;
use WPS\Shopify_API;

if (!defined('ABSPATH')) {
	exit;
}

class Shopify_API_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			self::$instantiated = new Shopify_API(
				Factories\DB\Settings_Connection_Factory::Build()
			);

		}

		return self::$instantiated;

	}

}
