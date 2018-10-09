<?php

namespace WPS\Factories;

use WPS\WS\Tools;

use WPS\Factories\DB_Settings_Syncing_Factory;

if (!defined('ABSPATH')) {
	exit;
}

class WS_Tools_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			$WS_Tools = new Tools(
				DB_Settings_Syncing_Factory::build()
			);

			self::$instantiated = $WS_Tools;

		}

		return self::$instantiated;

	}

}
