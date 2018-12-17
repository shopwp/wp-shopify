<?php

namespace WPS\Factories;

use WPS\Admin_Menus;
use WPS\Factories;

if (!defined('ABSPATH')) {
	exit;
}

class Admin_Menus_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			self::$instantiated = new Admin_Menus(
				Factories\Template_Loader_Factory::build(),
				Factories\DB\Collections_Factory::build(),
				Factories\DB\Products_Factory::build(),
				Factories\DB\Tags_Factory::build(),
				Factories\DB\Collects_Factory::build()
			);

		}

		return self::$instantiated;

	}

}
