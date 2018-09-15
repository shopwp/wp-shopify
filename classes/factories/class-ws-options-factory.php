<?php

namespace WPS\Factories;

use WPS\WS\Options as WS_Options;

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('WS_Options_Factory')) {

  class WS_Options_Factory {

		protected static $instantiated = null;

    public static function build() {

			if (is_null(self::$instantiated)) {

				$WS_Options = new WS_Options();

				self::$instantiated = $WS_Options;

			}

			return self::$instantiated;

    }

  }

}
