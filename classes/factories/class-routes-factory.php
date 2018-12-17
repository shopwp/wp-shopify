<?php

namespace WPS\Factories;

use WPS\Factories;
use WPS\Routes;

if (!defined('ABSPATH')) {
	exit;
}

class Routes_Factory {

	protected static $instantiated = null;

	public static function build() {

		if ( is_null(self::$instantiated) ) {
			self::$instantiated = new Routes();
		}

		return self::$instantiated;

	}

}
