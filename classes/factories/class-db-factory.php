<?php

namespace WPS\Factories;

use WPS\DB;

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('DB_Factory')) {

  class DB_Factory {

		protected static $instantiated = null;

    public static function build() {

			if (is_null(self::$instantiated)) {

				$DB = new DB();

				self::$instantiated = $DB;

			}

			return self::$instantiated;

		}


  }

}
