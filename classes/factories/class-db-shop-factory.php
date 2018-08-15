<?php

namespace WPS\Factories;

use WPS\DB\Shop as DB_Shop;

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('DB_Shop_Factory')) {

  class DB_Shop_Factory {

		protected static $instantiated = null;

    public static function build() {

			if (is_null(self::$instantiated)) {

				$DB_Shop = new DB_Shop();

				self::$instantiated = $DB_Shop;

			}

			return self::$instantiated;

		}

  }

}
