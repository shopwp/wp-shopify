<?php

namespace WPS\Factories;

use WPS\Admin_Menus;

use WPS\Factories\Template_Loader_Factory;
use WPS\Factories\DB_Collections_Factory;
use WPS\Factories\DB_Products_Factory;
use WPS\Factories\DB_Tags_Factory;
use WPS\Factories\DB_Collects_Factory;


if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('Admin_Menus_Factory')) {

  class Admin_Menus_Factory {

		protected static $instantiated = null;

    public static function build() {

			if (is_null(self::$instantiated)) {

				$Admin_Menus = new Admin_Menus(
					Template_Loader_Factory::build(),
					DB_Collections_Factory::build(),
					DB_Products_Factory::build(),
					DB_Tags_Factory::build(),
					DB_Collects_Factory::build()
				);

				self::$instantiated = $Admin_Menus;

			}

			return self::$instantiated;

		}

  }

}
