<?php

namespace WPS\Factories\API\Processing;

if ( !defined('ABSPATH') ) {
	exit;
}

use WPS\Factories;
use WPS\API;

class Posts_Products_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			self::$instantiated = new API\Processing\Posts_Products(
				Factories\Processing\Posts_Products_Factory::build(),
				Factories\Processing\Posts_Relationships::build()
			);

		}

		return self::$instantiated;

	}

}
