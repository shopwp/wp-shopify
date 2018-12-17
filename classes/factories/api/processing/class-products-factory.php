<?php

namespace WPS\Factories\API\Processing;

if ( !defined('ABSPATH') ) {
	exit;
}

use WPS\Factories;
use WPS\API;

class Products_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			self::$instantiated = new API\Processing\Products(
				Factories\Processing\Products_Factory::build()
			);

		}

		return self::$instantiated;

	}

}
