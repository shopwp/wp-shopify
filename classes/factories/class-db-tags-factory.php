<?php

namespace WPS\Factories;

use WPS\DB\Tags as DB_Tags;

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('DB_Tags_Factory')) {

  class DB_Tags_Factory {

		protected static $instantiated = null;

    public static function build() {

			if (is_null(self::$instantiated)) {

				$DB_Tags = new DB_Tags();

				self::$instantiated = $DB_Tags;

			}

			return self::$instantiated;

		}

  }

}
