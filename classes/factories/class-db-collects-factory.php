<?php

namespace WPS\Factories;

use WPS\DB\Collects as DB_Collects;

if (!defined('ABSPATH')) {
	exit;
}

class DB_Collects_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			$DB_Collects = new DB_Collects();

			self::$instantiated = $DB_Collects;

		}

		return self::$instantiated;

	}

}
