<?php

namespace WPS\Factories;

use WPS\WS\Tags;

if (!defined('ABSPATH')) {
	exit;
}

class WS_Tags_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			$WS_Tags = new Tags();

			self::$instantiated = $WS_Tags;

		}

		return self::$instantiated;

	}

}
