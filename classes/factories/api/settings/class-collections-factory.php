<?php

namespace WPS\Factories\API\Settings;

if ( !defined('ABSPATH') ) {
	exit;
}

use WPS\API;
use WPS\Factories;

class Collections_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			self::$instantiated = new API\Settings\Collections(
				Factories\DB\Settings_General_Factory::build()
			);

		}

		return self::$instantiated;

	}

}
