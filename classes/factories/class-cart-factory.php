<?php

namespace WPS\Factories;

use WPS\Cart;

use WPS\Factories\Messages_Factory;
use WPS\Factories\WS_Factory;

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('Cart_Factory')) {

  class Cart_Factory {

		protected static $instantiated = null;

    public static function build() {

			if (is_null(self::$instantiated)) {

				$Cart = new Cart(
					Messages_Factory::build(),
					WS_Factory::build()
				);

				self::$instantiated = $Cart;

			}

			return self::$instantiated;

    }

  }

}
