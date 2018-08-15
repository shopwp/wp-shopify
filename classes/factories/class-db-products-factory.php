<?php

namespace WPS\Factories;

use WPS\DB\Products as DB_Products;

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('DB_Products_Factory')) {

  class DB_Products_Factory {

		protected static $instantiated = null;

    public static function build() {

			if (is_null(self::$instantiated)) {

	      $DB_Products = new DB_Products();

				self::$instantiated = $DB_Products;

			}

      return self::$instantiated;

    }

  }

}
