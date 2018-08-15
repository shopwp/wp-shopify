<?php

namespace WPS\Factories;

use WPS\DB\Collections_Smart as DB_Collections_Smart;

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('DB_Collections_Smart_Factory')) {

  class DB_Collections_Smart_Factory {

		protected static $instantiated = null;

    public static function build() {

			if (is_null(self::$instantiated)) {

				$DB_Collections_Smart = new DB_Collections_Smart();

				self::$instantiated = $DB_Collections_Smart;

			}

			return self::$instantiated;

		}


  }

}
