<?php

namespace WPS\Factories;

use WPS\Money;
use WPS\Factories;

if (!defined('ABSPATH')) {
	exit;
}

class Money_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			$Money = new Money(
				Factories\DB\Settings_General_Factory::build(),
				Factories\DB\Shop_Factory::build(),
				Factories\DB\Variants_Factory::build()
			);

			self::$instantiated = $Money;

		}

		return self::$instantiated;

	}

}
