<?php

namespace WPS\Factories;

use WPS\Transients;

if (!defined('ABSPATH')) {
	exit;
}

class Transients_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			$Transients = new Transients();

			self::$instantiated = $Transients;

		}

		return self::$instantiated;

	}

}
