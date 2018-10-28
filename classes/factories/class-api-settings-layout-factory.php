<?php

namespace WPS\Factories;

if ( !defined('ABSPATH') ) {
	exit;
}

use WPS\API\Settings_Layout;
use WPS\Factories\DB_Settings_General_Factory;


class API_Settings_Layout_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			$API_Settings_Layout = new Settings_Layout(
				DB_Settings_General_Factory::build()
			);

			self::$instantiated = $API_Settings_Layout;

		}

		return self::$instantiated;

	}

}
