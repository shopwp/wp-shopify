<?php

namespace WPS\Factories;

use WPS\CPT_Query;

use WPS\Factories\DB_Settings_General_Factory;
use WPS\Factories\DB_Settings_Connection_Factory;

if (!defined('ABSPATH')) {
	exit;
}

class CPT_Query_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			$CPT_Query = new CPT_Query(
				DB_Settings_General_Factory::build(),
				DB_Settings_Connection_Factory::build()
			);

			self::$instantiated = $CPT_Query;

		}

		return self::$instantiated;

	}

}
