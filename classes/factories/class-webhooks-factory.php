<?php

namespace WPS\Factories;

use WPS\Webhooks;

use WPS\Factories\Messages_Factory;
use WPS\Factories\DB_Settings_Connection_Factory;
use WPS\Factories\DB_Settings_General_Factory;
use WPS\Factories\WS_Factory;

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('Webhooks_Factory')) {

  class Webhooks_Factory {

		protected static $instantiated = null;

    public static function build() {

			if (is_null(self::$instantiated)) {

				$Webhooks = new Webhooks(
					Messages_Factory::build(),
					DB_Settings_Connection_Factory::build(),
					DB_Settings_General_Factory::build(),
					WS_Factory::build()
				);

				self::$instantiated = $Webhooks;

			}

			return self::$instantiated;

		}

  }

}
