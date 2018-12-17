<?php

namespace WPS\Factories\API\Tools;

if ( !defined('ABSPATH') ) {
	exit;
}

use WPS\API;
use WPS\Factories;

class Clear_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			self::$instantiated = new API\Tools\Clear(
				Factories\Processing\Database_Factory::build()
			);

		}

		return self::$instantiated;

	}

}
