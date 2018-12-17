<?php

namespace WPS\Factories;

use WPS\Backend;
use WPS\Factories;

if (!defined('ABSPATH')) {
	exit;
}

class Backend_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			$Backend = new Backend(
				Factories\DB\Settings_General_Factory::build(),
				Factories\DB\Settings_Connection_Factory::build()
			);

			self::$instantiated = $Backend;

		}

		return self::$instantiated;

	}

}
