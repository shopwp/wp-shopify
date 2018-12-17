<?php

namespace WPS\Factories;

use WPS\CPT_Meta;
use WPS\Factories;

if (!defined('ABSPATH')) {
	exit;
}

class CPT_Meta_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			self::$instantiated = new CPT_Meta(
				Factories\DB\Products_Factory::build(),
				Factories\DB\Tags_Factory::build(),
				Factories\DB\Posts_Factory::build(),
				Factories\DB\Collections_Smart_Factory::build(),
				Factories\DB\Collections_Custom_Factory::build()
			);

		}

		return self::$instantiated;

	}

}
