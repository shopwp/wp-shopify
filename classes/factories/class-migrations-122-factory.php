<?php

namespace WPS\Factories;

use WPS\Migrations\Migrations_122;
use WPS\Factories;


if (!defined('ABSPATH')) {
	exit;
}

class Migrations_122_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			self::$instantiated = new Migrations_122(
				Factories\DB\Products_Factory::build(),
				Factories\DB\Variants_Factory::build(),
				Factories\DB\Collects_Factory::build(),
				Factories\DB\Options_Factory::build(),
				Factories\DB\Collections_Custom_Factory::build(),
				Factories\DB\Collections_Smart_Factory::build(),
				Factories\DB\Images_Factory::build(),
				Factories\DB\Tags_Factory::build(),
				Factories\DB\Customers_Factory::build(),
				Factories\DB\Orders_Factory::build()
			);

		}

		return self::$instantiated;

	}

}
