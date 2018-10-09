<?php

namespace WPS\Factories;

use WPS\Async_Processing_Posts_Products;

use WPS\Factories\DB_Settings_Syncing_Factory;
use WPS\Factories\DB_Products_Factory;
use WPS\Factories\CPT_Query_Factory;

if (!defined('ABSPATH')) {
	exit;
}

class Async_Processing_Posts_Products_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			$Async_Processing_Posts = new Async_Processing_Posts_Products(
				DB_Settings_Syncing_Factory::build(),
				DB_Products_Factory::build(),
				CPT_Query_Factory::build()
			);

			self::$instantiated = $Async_Processing_Posts;

		}

		return self::$instantiated;


	}

}
