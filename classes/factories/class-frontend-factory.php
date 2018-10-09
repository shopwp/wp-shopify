<?php

namespace WPS\Factories;

use WPS\Frontend;

use WPS\Factories\DB_Settings_General_Factory;
use WPS\Factories\DB_Settings_Connection_Factory;

if (!defined('ABSPATH')) {
	exit;
}

class Frontend_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			$Frontend = new Frontend(
				DB_Settings_General_Factory::build(),
				DB_Settings_Connection_Factory::build()
			);

			self::$instantiated = $Frontend;

		}

		return self::$instantiated;

	}

}
