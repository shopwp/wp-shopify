<?php

namespace WPS\Factories;

use WPS\Frontend;
use WPS\Factories;

if (!defined('ABSPATH')) {
	exit;
}

class Frontend_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			$Frontend = new Frontend(
				Factories\DB\Settings_General_Factory::build(),
				Factories\DB\Settings_Connection_Factory::build()
			);

			self::$instantiated = $Frontend;

		}

		return self::$instantiated;

	}

}
