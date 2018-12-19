<?php

namespace WPS\Factories\Layout;

use WPS\Layout;

if (!defined('ABSPATH')) {
	exit;
}

class Data_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			self::$instantiated = new Layout\Data();

		}

		return self::$instantiated;

	}

}
