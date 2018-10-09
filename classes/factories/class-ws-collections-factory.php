<?php

namespace WPS\Factories;

use WPS\WS\Collections as WS_Collections;

use WPS\Factories\WS_Collections_Smart_Factory;
use WPS\Factories\WS_Collections_Custom_Factory;
use WPS\Factories\DB_Settings_General_Factory;
use WPS\Factories\DB_Settings_Connection_Factory;
use WPS\Factories\Shopify_API_Factory;


if (!defined('ABSPATH')) {
	exit;
}


class WS_Collections_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			$WS_Collections = new WS_Collections(
				WS_Collections_Smart_Factory::build(),
				WS_Collections_Custom_Factory::build(),
				DB_Settings_General_Factory::build(),
				DB_Settings_Connection_Factory::build(),
				Shopify_API_Factory::build()
			);

			self::$instantiated = $WS_Collections;

		}

		return self::$instantiated;

	}

}
