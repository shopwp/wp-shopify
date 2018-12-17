<?php

namespace WPS\Factories;

use WPS\CPT_Query;
use WPS\Factories;

if (!defined('ABSPATH')) {
	exit;
}

class CPT_Query_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			self::$instantiated = new CPT_Query(
				Factories\DB\Settings_General_Factory::build(),
				Factories\DB\Settings_Connection_Factory::build(),
				Factories\CPT_Meta_Factory::build()
			);

		}

		return self::$instantiated;

	}

}
