<?php

namespace WPS\Factories\API\Processing;

if ( !defined('ABSPATH') ) {
	exit;
}

use WPS\Factories;
use WPS\API;

class Shop_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			self::$instantiated = new API\Processing\Shop(
				Factories\Processing\Shop_Factory::build()
			);

		}

		return self::$instantiated;

	}

}
