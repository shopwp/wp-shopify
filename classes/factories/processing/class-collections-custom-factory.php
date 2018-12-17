<?php

namespace WPS\Factories\Processing;

use WPS\Processing;
use WPS\Factories;

if (!defined('ABSPATH')) {
	exit;
}

class Collections_Custom_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			self::$instantiated = new Processing\Collections_Custom(
				Factories\DB\Settings_Syncing_Factory::build(),
				Factories\DB\Collections_Factory::build()
			);

		}

		return self::$instantiated;

	}

}
