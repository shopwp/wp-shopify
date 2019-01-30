<?php

namespace WPS\Factories\Render;

if ( !defined('ABSPATH') ) {
	exit;
}

use WPS\Render;
use WPS\Factories;

class Products_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			self::$instantiated = new Render\Products(
				Factories\DB\Settings_Syncing_Factory::build()
			);

		}

		return self::$instantiated;

	}

}
