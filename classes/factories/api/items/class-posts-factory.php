<?php

namespace WPS\Factories\API\Items;

if ( !defined('ABSPATH') ) {
	exit;
}

use WPS\API;
use WPS\Factories;

class Posts_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			self::$instantiated = new API\Items\Posts(
				Factories\DB\Posts_Factory::build(),
				Factories\Processing\Posts_Relationships_Products_Factory::build(),
				Factories\Processing\Posts_Relationships_Collections_Factory::build(),
				Factories\DB\Products_Factory::build()
			);

		}

		return self::$instantiated;

	}

}
