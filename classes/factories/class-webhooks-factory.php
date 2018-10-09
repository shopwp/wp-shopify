<?php

namespace WPS\Factories;

use WPS\Webhooks;

use WPS\Factories\DB_Settings_Connection_Factory;
use WPS\Factories\DB_Settings_General_Factory;

if (!defined('ABSPATH')) {
	exit;
}

class Webhooks_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			$Webhooks = new Webhooks(
				DB_Settings_Connection_Factory::build(),
				DB_Settings_General_Factory::build()
			);

			self::$instantiated = $Webhooks;

		}

		return self::$instantiated;

	}

}
