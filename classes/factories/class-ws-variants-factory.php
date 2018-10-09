<?php

namespace WPS\Factories;

use WPS\WS\Variants;

use WPS\Factories\DB_Products_Factory;
use WPS\Factories\DB_Variants_Factory;

if (!defined('ABSPATH')) {
	exit;
}

class WS_Variants_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			$WS_Variants = new Variants(
				DB_Products_Factory::build(),
				DB_Variants_Factory::build()
			);

			self::$instantiated = $WS_Variants;

		}

		return self::$instantiated;

	}

}
