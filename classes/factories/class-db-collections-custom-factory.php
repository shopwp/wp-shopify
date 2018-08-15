<?php

namespace WPS\Factories;

use WPS\DB\Collections_Custom as DB_Collections_Custom;

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('DB_Collections_Custom_Factory')) {

  class DB_Collections_Custom_Factory {

		protected static $instantiated = null;

    public static function build() {

			if (is_null(self::$instantiated)) {

				$DB_Collections_Custom = new DB_Collections_Custom();

				self::$instantiated = $DB_Collections_Custom;

			}

			return self::$instantiated;

		}


  }

}
