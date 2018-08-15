<?php

namespace WPS\Factories;

use WPS\DB\Customers as DB_Customers;

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('DB_Customers_Factory')) {

  class DB_Customers_Factory {

		protected static $instantiated = null;

    public static function build() {

			if (is_null(self::$instantiated)) {

				$DB_Customers = new DB_Customers();

				self::$instantiated = $DB_Customers;

			}

			return self::$instantiated;

		}

  }

}
