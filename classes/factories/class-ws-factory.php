<?php

namespace WPS\Factories;

if (!defined('ABSPATH')) {
	exit;
}

use WPS\WS;

class WS_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			$WS = new WS();

			self::$instantiated = $WS;

		}

		return self::$instantiated;

	}

}
