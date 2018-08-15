<?php

namespace WPS\Factories;

use WPS\WS\Tags as WS_Tags;

use WPS\Factories\DB_Tags_Factory;
use WPS\Factories\DB_Settings_General_Factory;

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('WS_Tags_Factory')) {

  class WS_Tags_Factory {

		protected static $instantiated = null;

    public static function build() {

			if (is_null(self::$instantiated)) {

				$WS_Tags = new WS_Tags(
					DB_Tags_Factory::build(),
					DB_Settings_General_Factory::build()
				);

				self::$instantiated = $WS_Tags;

			}

			return self::$instantiated;

    }

  }

}
