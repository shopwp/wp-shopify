<?php

namespace WPS\Factories;

use WPS\Templates;

use WPS\Factories\Template_Loader_Factory;
use WPS\Factories\DB_Settings_General_Factory;
use WPS\Factories\Money_Factory;
use WPS\Factories\DB_Variants_Factory;
use WPS\Factories\DB_Products_Factory;
use WPS\Factories\DB_Images_Factory;
use WPS\Factories\DB_Tags_Factory;
use WPS\Factories\DB_Options_Factory;
use WPS\Factories\DB_Collections_Factory;

if (!defined('ABSPATH')) {
	exit;
}

class Templates_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			$Templates = new Templates(
				Template_Loader_Factory::build(),
				DB_Settings_General_Factory::build(),
				Money_Factory::build(),
				DB_Variants_Factory::build(),
				DB_Products_Factory::build(),
				DB_Images_Factory::build(),
				DB_Tags_Factory::build(),
				DB_Options_Factory::build(),
				DB_Collections_Factory::build()
			);

			self::$instantiated = $Templates;

		}

		return self::$instantiated;

	}

}
