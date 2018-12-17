<?php

namespace WPS\Factories\API\Misc;

if ( !defined('ABSPATH') ) {
	exit;
}

use WPS\Factories;
use WPS\API;

class Notices_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			self::$instantiated = new API\Misc\Notices(
				Factories\DB\Settings_General_Factory::build(),
				Factories\Backend_Factory::build()
			);

		}

		return self::$instantiated;

	}

}
