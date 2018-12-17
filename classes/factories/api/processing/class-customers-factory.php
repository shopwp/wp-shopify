<?php

namespace WPS\Factories\API\Processing;

if ( !defined('ABSPATH') ) {
	exit;
}

use WPS\Factories;
use WPS\API;

class Customers_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			self::$instantiated = new API\Processing\Customers(
				Factories\Processing\Customers_Factory::build()
			);

		}

		return self::$instantiated;

	}

}
