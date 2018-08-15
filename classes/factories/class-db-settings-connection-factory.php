<?php

namespace WPS\Factories;

use WPS\DB\Settings_Connection as DB_Settings_Connection;

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('DB_Settings_Connection_Factory')) {

  class DB_Settings_Connection_Factory {

		protected static $instantiated = null;

    public static function build() {

			if (is_null(self::$instantiated)) {
				
      	$DB_Settings_Connection = new DB_Settings_Connection();

				self::$instantiated = $DB_Settings_Connection;

			}

      return self::$instantiated;

    }

  }

}
