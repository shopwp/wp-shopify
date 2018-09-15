<?php

namespace WPS\Factories;

use WPS\WS\Tags as WS_Tags;

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('WS_Tags_Factory')) {

  class WS_Tags_Factory {

		protected static $instantiated = null;

    public static function build() {

			if (is_null(self::$instantiated)) {

				$WS_Tags = new WS_Tags();

				self::$instantiated = $WS_Tags;

			}

			return self::$instantiated;

    }

  }

}
