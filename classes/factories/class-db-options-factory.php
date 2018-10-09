<?php

namespace WPS\Factories;

use WPS\DB\Options as DB_Options;

if (!defined('ABSPATH')) {
	exit;
}

class DB_Options_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			$DB_Options = new DB_Options();

			self::$instantiated = $DB_Options;

		}

		return self::$instantiated;

	}

}
