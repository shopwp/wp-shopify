<?php

namespace WPS\Factories;

use WPS\DB\Images as DB_Images;


if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('DB_Images_Factory')) {

  class DB_Images_Factory {

		protected static $instantiated = null;

    public static function build() {

			if (is_null(self::$instantiated)) {

				$DB_Images = new DB_Images();

				self::$instantiated = $DB_Images;

			}

			return self::$instantiated;

		}

  }

}
