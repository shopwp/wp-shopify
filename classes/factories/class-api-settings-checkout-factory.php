<?php

namespace WPS\Factories;

if ( !defined('ABSPATH') ) {
	exit;
}

use WPS\API\Settings_Checkout;
use WPS\Factories\DB_Settings_General_Factory;


class API_Settings_Checkout_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			$API_Settings_Checkout = new Settings_Checkout(
				DB_Settings_General_Factory::build()
			);

			self::$instantiated = $API_Settings_Checkout;

		}

		return self::$instantiated;

	}

}
