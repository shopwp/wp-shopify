<?php

namespace WPS\Factories;

use WPS\DB\Collections as DB_Collections;

use WPS\Factories\DB_Collects_Factory;
use WPS\Factories\WS_Collects_Factory;
use WPS\Factories\CPT_Model_Factory;
use WPS\Factories\DB_Collections_Smart_Factory;
use WPS\Factories\DB_Collections_Custom_Factory;

if (!defined('ABSPATH')) {
	exit;
}

class DB_Collections_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			$DB_Collections = new DB_Collections(
				DB_Collects_Factory::build(),
				WS_Collects_Factory::build(),
				CPT_Model_Factory::build(),
				DB_Collections_Smart_Factory::build(),
				DB_Collections_Custom_Factory::build()
			);

			self::$instantiated = $DB_Collections;

		}

		return self::$instantiated;

	}

}
