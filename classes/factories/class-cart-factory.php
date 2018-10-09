<?php

namespace WPS\Factories;

use WPS\Cart;

use WPS\Factories\WS_Factory;

if (!defined('ABSPATH')) {
	exit;
}

class Cart_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			$Cart = new Cart(
				WS_Factory::build()
			);

			self::$instantiated = $Cart;

		}

		return self::$instantiated;

	}

}
