<?php

namespace WPS\Factories;

use WPS\Factories\DB_Settings_Connection_Factory;

use WPS\Shopify_API;

if (!defined('ABSPATH')) {
	exit;
}

class Shopify_API_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			$Shopify_API = new Shopify_API(
				DB_Settings_Connection_Factory::Build()
			);

			self::$instantiated = $Shopify_API;

		}

		return self::$instantiated;

	}

}
