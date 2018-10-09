<?php

namespace WPS\Factories;

use WPS\Money;

use WPS\Factories\DB_Settings_General_Factory;
use WPS\Factories\DB_Shop_Factory;
use WPS\Factories\DB_Variants_Factory;

if (!defined('ABSPATH')) {
	exit;
}

class Money_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			$Money = new Money(
				DB_Settings_General_Factory::build(),
				DB_Shop_Factory::build(),
				DB_Variants_Factory::build()
			);

			self::$instantiated = $Money;

		}

		return self::$instantiated;

	}

}
