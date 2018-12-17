<?php

namespace WPS\Factories\API\Processing;

if ( !defined('ABSPATH') ) {
	exit;
}

use WPS\Factories;
use WPS\API;

class Collections_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			self::$instantiated = new API\Processing\Collections(
				Factories\Processing\Collections_Smart_Factory::build(),
				Factories\Processing\Collections_Custom_Factory::build(),
				Factories\Processing\Posts_Factory::build()
			);

		}

		return self::$instantiated;

	}

}
