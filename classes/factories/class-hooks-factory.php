<?php

namespace WPS\Factories;

use WPS\Hooks;

use WPS\Factories\Utils_Factory;
use WPS\Factories\DB_Settings_General_Factory;
use WPS\Factories\DB_Shop_Factory;
use WPS\Factories\Templates_Factory;
use WPS\Factories\Async_Processing_Database_Factory;
use WPS\Factories\Pagination_Factory;
use WPS\Factories\DB_Settings_Syncing_Factory;
use WPS\Factories\DB_Settings_License_Factory;
use WPS\Factories\Activator_Factory;
use WPS\Factories\Messages_Factory;


if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('Hooks_Factory')) {

  class Hooks_Factory {

		protected static $instantiated = null;

    public static function build() {

			if (is_null(self::$instantiated)) {

				$Hooks = new Hooks(
					Utils_Factory::build(),
					DB_Settings_General_Factory::build(),
					DB_Shop_Factory::build(),
					Templates_Factory::build(),
					Async_Processing_Database_Factory::build(),
					Pagination_Factory::build(),
					DB_Settings_Syncing_Factory::build(),
					DB_Settings_License_Factory::build(),
					Activator_Factory::build(),
					Messages_Factory::build()
				);

				self::$instantiated = $Hooks;

			}

			return self::$instantiated;

		}

  }

}
