<?php

namespace WPS\Factories\Processing;

use WPS\Processing;
use WPS\Factories;

if (!defined('ABSPATH')) {
	exit;
}

class Shop_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			self::$instantiated = new Processing\Shop(
				Factories\DB\Settings_Syncing_Factory::build(),
				Factories\DB\Shop_Factory::build()
			);

		}

		return self::$instantiated;

	}

}
