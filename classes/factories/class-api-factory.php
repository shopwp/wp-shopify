<?php

namespace WPS\Factories;

if ( !defined('ABSPATH') ) {
	exit;
}

use WPS\API;
use WPS\Factories\DB_Settings_General_Factory;


class API_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			$API = new API(
				DB_Settings_General_Factory::build()
			);

			self::$instantiated = $API;

		}

		return self::$instantiated;

	}

}
