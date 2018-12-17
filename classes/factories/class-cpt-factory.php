<?php

namespace WPS\Factories;

use WPS\CPT;
use WPS\Factories;

if (!defined('ABSPATH')) {
	exit;
}

class CPT_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			$CPT = new CPT(
				Factories\DB\Settings_General_Factory::build(),
				Factories\DB\Products_Factory::build(),
				Factories\DB\Collections_Custom_Factory::build(),
				Factories\DB\Collections_Smart_Factory::build(),
				Factories\DB\Collects_Factory::build(),
				Factories\DB\Tags_Factory::build()
			);

			self::$instantiated = $CPT;

		}

		return self::$instantiated;

	}

}
