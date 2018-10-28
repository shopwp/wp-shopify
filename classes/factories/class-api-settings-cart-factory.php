<?php

namespace WPS\Factories;

if ( !defined('ABSPATH') ) {
	exit;
}

use WPS\API\Settings_Cart;
use WPS\Factories\DB_Settings_General_Factory;


class API_Settings_Cart_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			$API_Settings_Cart = new Settings_Cart(
				DB_Settings_General_Factory::build()
			);

			self::$instantiated = $API_Settings_Cart;

		}

		return self::$instantiated;

	}

}
