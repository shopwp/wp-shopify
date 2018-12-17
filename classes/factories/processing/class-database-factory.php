<?php

namespace WPS\Factories\Processing;

use WPS\Processing;
use WPS\Factories;


if (!defined('ABSPATH')) {
	exit;
}

class Database_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			$Async_Processing_Database = new Processing\Database(
				Factories\Config_Factory::build(),
				Factories\DB\Collections_Custom_Factory::build(),
				Factories\DB\Collections_Smart_Factory::build(),
				Factories\DB\Collects_Factory::build(),
				Factories\DB\Customers_Factory::build(),
				Factories\DB\Images_Factory::build(),
				Factories\DB\Options_Factory::build(),
				Factories\DB\Orders_Factory::build(),
				Factories\DB\Products_Factory::build(),
				Factories\DB\Settings_Connection_Factory::build(),
				Factories\DB\Settings_General_Factory::build(),
				Factories\DB\Settings_License_Factory::build(),
				Factories\DB\Settings_Syncing_Factory::build(),
				Factories\DB\Shop_Factory::build(),
				Factories\DB\Tags_Factory::build(),
				Factories\DB\Variants_Factory::build(),
				Factories\Transients_Factory::build(),
				Factories\DB\Posts_Factory::build(),
				Factories\API\Settings\License_Factory::build()
			);

			self::$instantiated = $Async_Processing_Database;

		}

		return self::$instantiated;

	}

}
