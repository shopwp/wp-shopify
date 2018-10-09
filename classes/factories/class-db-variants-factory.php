<?php

namespace WPS\Factories;

use WPS\DB\Variants;
use WPS\Factories\DB_Settings_Connection_Factory;

if (!defined('ABSPATH')) {
	exit;
}

class DB_Variants_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			$DB_Variants = new Variants(
				DB_Settings_Connection_Factory::build()
			);

			self::$instantiated = $DB_Variants;

		}

		return self::$instantiated;

	}

}
