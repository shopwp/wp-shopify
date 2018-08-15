<?php

namespace WPS\Factories;

use WPS\Query;

use WPS\Factories\Template_loader_Factory;
use WPS\Factories\DB_Collections_Factory;
use WPS\Factories\DB_Settings_General_Factory;
use WPS\Factories\DB_Images_Factory;
use WPS\Factories\Pagination_Factory;
use WPS\Factories\DB_Products_Factory;


if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('Query_Factory')) {

  class Query_Factory {

		protected static $instantiated = null;

    public static function build() {

			if (is_null(self::$instantiated)) {

				$Query = new Query(
					Template_loader_Factory::build(),
					DB_Collections_Factory::build(),
					DB_Settings_General_Factory::build(),
					DB_Images_Factory::build(),
					Pagination_Factory::build(),
					DB_Products_Factory::build()
				);

				self::$instantiated = $Query;

			}

			return self::$instantiated;

		}

  }

}
