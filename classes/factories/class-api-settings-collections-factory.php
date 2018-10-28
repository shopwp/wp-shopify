<?php

namespace WPS\Factories;

if ( !defined('ABSPATH') ) {
	exit;
}

use WPS\API\Settings_Collections;
use WPS\Factories\DB_Settings_General_Factory;


class API_Settings_Collections_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			$API_Settings_Collections = new Settings_Collections(
				DB_Settings_General_Factory::build()
			);

			self::$instantiated = $API_Settings_Collections;

		}

		return self::$instantiated;

	}

}
