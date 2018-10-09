<?php

namespace WPS\Factories;

use WPS\Activator;

use WPS\Factories\DB_Settings_License_Factory;
use WPS\Factories\DB_Orders_Factory;
use WPS\Factories\DB_Shop_Factory;
use WPS\Factories\DB_Customers_Factory;
use WPS\Factories\DB_Variants_Factory;
use WPS\Factories\DB_Options_Factory;
use WPS\Factories\DB_Collects_Factory;
use WPS\Factories\DB_Products_Factory;
use WPS\Factories\DB_Settings_General_Factory;
use WPS\Factories\DB_Collections_Custom_Factory;
use WPS\Factories\DB_Collections_Smart_Factory;
use WPS\Factories\DB_Settings_Connection_Factory;
use WPS\Factories\DB_Settings_Syncing_Factory;
use WPS\Factories\DB_Images_Factory;
use WPS\Factories\DB_Tags_Factory;
use WPS\Factories\CPT_Factory;


if (!defined('ABSPATH')) {
	exit;
}

class Activator_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			$Activator = new Activator(
				DB_Settings_Connection_Factory::build(),
				DB_Settings_General_Factory::build(),
				DB_Settings_License_Factory::build(),
				DB_Shop_Factory::build(),
				DB_Products_Factory::build(),
				DB_Variants_Factory::build(),
				DB_Collects_Factory::build(),
				DB_Options_Factory::build(),
				DB_Collections_Custom_Factory::build(),
				DB_Collections_Smart_Factory::build(),
				DB_Images_Factory::build(),
				DB_Tags_Factory::build(),
				CPT_Factory::build(),
				DB_Customers_Factory::build(),
				DB_Orders_Factory::build(),
				DB_Settings_Syncing_Factory::build()
			);

			self::$instantiated = $Activator;

		}

		return self::$instantiated;

	}

}
