<?php

namespace WPS\Factories\DB;

use WPS\DB;

if (!defined('ABSPATH')) {
	exit;
}

class Collections_Smart_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {
			self::$instantiated = new DB\Collections_Smart();
		}

		return self::$instantiated;

	}

}
