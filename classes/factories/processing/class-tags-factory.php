<?php

namespace WPS\Factories\Processing;

use WPS\Processing;
use WPS\Factories;

if (!defined('ABSPATH')) {
	exit;
}

class Tags_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			self::$instantiated = new Processing\Tags(
				Factories\DB\Settings_Syncing_Factory::build(),
				Factories\DB\Tags_Factory::build()
			);

		}

		return self::$instantiated;

	}

}
