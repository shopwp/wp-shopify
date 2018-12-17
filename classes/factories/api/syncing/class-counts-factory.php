<?php

namespace WPS\Factories\API\Syncing;

if ( !defined('ABSPATH') ) {
	exit;
}

use WPS\API;
use WPS\Factories;

class Counts_Factory {

	protected static $instantiated = null;

	public static function build() {

		if ( is_null(self::$instantiated) ) {

			self::$instantiated = new API\Syncing\Counts(
				Factories\DB\Settings_Syncing_Factory::build()
			);

		}

		return self::$instantiated;

	}

}
