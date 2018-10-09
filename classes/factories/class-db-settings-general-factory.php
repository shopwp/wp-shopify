<?php

namespace WPS\Factories;

if (!defined('ABSPATH')) {
	exit;
}

use WPS\DB\Settings_General as DB_Settings_General;

class DB_Settings_General_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			$DB_Settings_General = new DB_Settings_General();

			self::$instantiated = $DB_Settings_General;

		}

		return self::$instantiated;

	}

}
