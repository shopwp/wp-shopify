<?php

namespace WPS\Factories;

if (!defined('ABSPATH')) {
	exit;
}

use WPS\WS\Settings_Connection as WS_Settings_Connection;

use WPS\Factories\DB_Settings_Connection_Factory;
use WPS\Factories\DB_Settings_General_Factory;
use WPS\Factories\Shopify_API_Factory;

class WS_Settings_Connection_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			$WS_Settings_Connection = new WS_Settings_Connection(
				DB_Settings_Connection_Factory::build(),
				DB_Settings_General_Factory::build(),
				Shopify_API_Factory::build()
			);

			self::$instantiated = $WS_Settings_Connection;

		}

		return self::$instantiated;

	}

}
