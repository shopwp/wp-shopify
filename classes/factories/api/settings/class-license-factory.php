<?php

namespace WPS\Factories\API\Settings;

if ( !defined('ABSPATH') ) {
	exit;
}

use WPS\API;
use WPS\Factories;

class License_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			self::$instantiated = new API\Settings\License(
				Factories\DB\Settings_License_Factory::build(),
				Factories\HTTP_Factory::build()
			);

		}

		return self::$instantiated;

	}

}
