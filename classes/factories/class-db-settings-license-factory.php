<?php

namespace WPS\Factories;

use WPS\DB\Settings_License as DB_Settings_License;

if (!defined('ABSPATH')) {
	exit;
}

class DB_Settings_License_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			$DB_Settings_License = new DB_Settings_License();

			self::$instantiated = $DB_Settings_License;

		}

		return self::$instantiated;

	}

}
