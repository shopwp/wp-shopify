<?php

namespace WPS\Factories;

use WPS\Utils;

if (!defined('ABSPATH')) {
	exit;
}

class Utils_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			$Utils = new Utils();

			self::$instantiated = $Utils;

		}

		return self::$instantiated;

	}

}
