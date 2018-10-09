<?php

namespace WPS\Factories;


use WPS\Migrations\Migrations_122;

use WPS\Factories\DB_Orders_Factory;
use WPS\Factories\DB_Customers_Factory;
use WPS\Factories\DB_Variants_Factory;
use WPS\Factories\DB_Options_Factory;
use WPS\Factories\DB_Collects_Factory;
use WPS\Factories\DB_Products_Factory;
use WPS\Factories\DB_Collections_Custom_Factory;
use WPS\Factories\DB_Collections_Smart_Factory;
use WPS\Factories\DB_Images_Factory;
use WPS\Factories\DB_Tags_Factory;


if (!defined('ABSPATH')) {
	exit;
}

class Migrations_122_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			$Migrations_122 = new Migrations_122(
				DB_Products_Factory::build(),
				DB_Variants_Factory::build(),
				DB_Collects_Factory::build(),
				DB_Options_Factory::build(),
				DB_Collections_Custom_Factory::build(),
				DB_Collections_Smart_Factory::build(),
				DB_Images_Factory::build(),
				DB_Tags_Factory::build(),
				DB_Customers_Factory::build(),
				DB_Orders_Factory::build()
			);

			self::$instantiated = $Migrations_122;

		}

		return self::$instantiated;

	}

}
