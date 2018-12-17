<?php

namespace WPS\Factories;

use WPS\Factories;
use WPS\HTTP;

if (!defined('ABSPATH')) {
	exit;
}

class HTTP_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			self::$instantiated = new HTTP(
				Factories\DB\Settings_Connection_Factory::Build()
			);

		}

		return self::$instantiated;

	}

}
