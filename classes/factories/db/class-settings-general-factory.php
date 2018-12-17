<?php

namespace WPS\Factories\DB;

if (!defined('ABSPATH')) {
	exit;
}

use WPS\DB;

class Settings_General_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			self::$instantiated = new DB\Settings_General();

		}

		return self::$instantiated;

	}

}
