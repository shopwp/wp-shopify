<?php

namespace WPS\Factories;

use WPS\Activator;
use WPS\Factories;

if (!defined('ABSPATH')) {
	exit;
}

class Activator_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			$Activator = new Activator(
				Factories\DB\Settings_Connection_Factory::build(),
				Factories\DB\Settings_General_Factory::build(),
				Factories\DB\Settings_License_Factory::build(),
				Factories\DB\Shop_Factory::build(),
				Factories\DB\Products_Factory::build(),
				Factories\DB\Variants_Factory::build(),
				Factories\DB\Collects_Factory::build(),
				Factories\DB\Options_Factory::build(),
				Factories\DB\Collections_Custom_Factory::build(),
				Factories\DB\Collections_Smart_Factory::build(),
				Factories\DB\Images_Factory::build(),
				Factories\DB\Tags_Factory::build(),
				Factories\CPT_Factory::build(),
				Factories\DB\Customers_Factory::build(),
				Factories\DB\Orders_Factory::build(),
				Factories\DB\Settings_Syncing_Factory::build(),
				Factories\Routes_Factory::build()
			);

			self::$instantiated = $Activator;

		}

		return self::$instantiated;

	}

}
