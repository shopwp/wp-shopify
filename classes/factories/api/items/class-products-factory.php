<?php

namespace WPS\Factories\API\Items;

if ( !defined('ABSPATH') ) {
	exit;
}

use WPS\API;
use WPS\Factories;

class Products_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			self::$instantiated = new API\Items\Products(
				Factories\DB\Settings_General_Factory::build(),
				Factories\DB\Settings_Syncing_Factory::build(),
				Factories\Shopify_API_Factory::build(),
				Factories\Processing\Products_Factory::build(),
				Factories\Processing\Variants_Factory::build(),
				Factories\Processing\Posts_Products_Factory::build(),
				Factories\Processing\Tags_Factory::build(),
				Factories\Processing\Options_Factory::build(),
				Factories\Processing\Images_Factory::build()
			);

		}

		return self::$instantiated;

	}

}
