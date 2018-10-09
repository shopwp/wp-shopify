<?php

namespace WPS\Factories;

if (!defined('ABSPATH')) {
	exit;
}

use WPS\Async_Processing_Posts_Collections_Custom;

use WPS\Factories\DB_Settings_Syncing_Factory;
use WPS\Factories\CPT_Query_Factory;

class Async_Processing_Posts_Collections_Custom_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			$Async_Processing_Posts_Collections_Custom = new Async_Processing_Posts_Collections_Custom(
				DB_Settings_Syncing_Factory::build(),
				CPT_Query_Factory::build()
			);

			self::$instantiated = $Async_Processing_Posts_Collections_Custom;

		}

		return self::$instantiated;

	}

}
