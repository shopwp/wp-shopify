<?php

namespace WPS\Factories;

use WPS\Factories\DB_Settings_Connection_Factory;

use WPS\HTTP;

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('HTTP_Factory')) {

  class HTTP_Factory {

		protected static $instantiated = null;

    public static function build() {

			if (is_null(self::$instantiated)) {

      	$HTTP = new HTTP(
					DB_Settings_Connection_Factory::Build()
				);

				self::$instantiated = $HTTP;

			}

      return self::$instantiated;

    }

  }

}
