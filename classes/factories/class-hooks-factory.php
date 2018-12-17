<?php

namespace WPS\Factories;

use WPS\Hooks;
use WPS\Factories;

if (!defined('ABSPATH')) {
	exit;
}

class Hooks_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			$Hooks = new Hooks(
				Factories\Utils_Factory::build(),
				Factories\DB\Settings_General_Factory::build(),
				Factories\DB\Shop_Factory::build(),
				Factories\Templates_Factory::build(),
				Factories\Processing\Database_Factory::build(),
				Factories\Pagination_Factory::build(),
				Factories\DB\Settings_Syncing_Factory::build(),
				Factories\DB\Settings_License_Factory::build(),
				Factories\Activator_Factory::build()
			);

			self::$instantiated = $Hooks;

		}

		return self::$instantiated;

	}

}
