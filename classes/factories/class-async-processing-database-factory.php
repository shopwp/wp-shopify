<?php

namespace WPS\Factories;

use WPS\Async_Processing_Database;

use WPS\Factories\Config_Factory;
use WPS\Factories\License_Factory;
use WPS\Factories\Transients_Factory;
use WPS\Factories\DB_Settings_License_Factory;
use WPS\Factories\DB_Orders_Factory;
use WPS\Factories\DB_Tags_Factory;
use WPS\Factories\DB_Shop_Factory;
use WPS\Factories\DB_Images_Factory;
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
use WPS\Factories\WS_Webhooks_Factory;
use WPS\Factories\WS_CPT_Factory;
use WPS\Factories\WS_Settings_License_Factory;


if (!defined('ABSPATH')) {
	exit;
}


class Async_Processing_Database_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			$Async_Processing_Database = new Async_Processing_Database(
				Config_Factory::build(),
				DB_Collections_Custom_Factory::build(),
				DB_Collections_Smart_Factory::build(),
				DB_Collects_Factory::build(),
				DB_Customers_Factory::build(),
				DB_Images_Factory::build(),
				DB_Options_Factory::build(),
				DB_Orders_Factory::build(),
				DB_Products_Factory::build(),
				DB_Settings_Connection_Factory::build(),
				DB_Settings_General_Factory::build(),
				DB_Settings_License_Factory::build(),
				DB_Settings_Syncing_Factory::build(),
				DB_Shop_Factory::build(),
				DB_Tags_Factory::build(),
				DB_Variants_Factory::build(),
				Transients_Factory::build(),
				WS_Webhooks_Factory::build(),
				WS_CPT_Factory::build(),
				WS_Settings_License_Factory::build(),
				License_Factory::build()
			);

			self::$instantiated = $Async_Processing_Database;

		}

		return self::$instantiated;

	}

}
