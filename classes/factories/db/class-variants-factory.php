<?php

namespace WPS\Factories\DB;

use WPS\DB;
use WPS\Factories;

if (!defined('ABSPATH')) {
	exit;
}

class Variants_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			self::$instantiated = new DB\Variants(
				Factories\DB\Settings_Connection_Factory::build()
			);

		}

		return self::$instantiated;

	}

}
