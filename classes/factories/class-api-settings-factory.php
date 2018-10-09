<?php

namespace WPS\Factories;

if ( !defined('ABSPATH') ) {
	exit;
}

use WPS\API\Settings;
use WPS\Factories\DB_Settings_General_Factory;


class API_Settings_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			$API_Settings = new Settings(
				DB_Settings_General_Factory::build()
			);

			self::$instantiated = $API_Settings;

		}

		return self::$instantiated;

	}

}
