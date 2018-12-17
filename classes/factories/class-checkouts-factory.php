<?php

namespace WPS\Factories;

use WPS\Checkouts;

if (!defined('ABSPATH')) {
	exit;
}

class Checkouts_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			self::$instantiated = new Checkouts();

		}

		return self::$instantiated;

	}

}
