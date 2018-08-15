<?php

namespace WPS\Factories;

use WPS\Backend;
use WPS\Factories\DB_Settings_General_Factory;
use WPS\Factories\DB_Settings_Connection_Factory;

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('Backend_Factory')) {

  class Backend_Factory {

		protected static $instantiated = null;

    public static function build() {

			if (is_null(self::$instantiated)) {

				$Backend = new Backend(
					DB_Settings_General_Factory::build(),
					DB_Settings_Connection_Factory::build()
				);

				self::$instantiated = $Backend;

			}

			return self::$instantiated;

		}

  }

}
