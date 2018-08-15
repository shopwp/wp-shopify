<?php

namespace WPS\Factories;

use WPS\Async_Processing_Tags;
use WPS\Factories\DB_Settings_Syncing_Factory;
use WPS\Factories\DB_Tags_Factory;
use WPS\Factories\WS_Factory;


if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('Async_Processing_Tags_Factory')) {

  class Async_Processing_Tags_Factory {

		protected static $instantiated = null;

    public static function build() {

			if (is_null(self::$instantiated)) {

				$Async_Processing_Tags = new Async_Processing_Tags(
					DB_Settings_Syncing_Factory::build(),
					DB_Tags_Factory::build(),
					WS_Factory::build()
				);

				self::$instantiated = $Async_Processing_Tags;

			}

			return self::$instantiated;

    }

  }

}
