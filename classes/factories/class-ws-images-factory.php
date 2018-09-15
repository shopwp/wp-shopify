<?php

namespace WPS\Factories;

if (!defined('ABSPATH')) {
	exit;
}

use WPS\WS\Images as WS_Images;


if (!class_exists('WS_Images_Factory')) {

  class WS_Images_Factory {

		protected static $instantiated = null;

    public static function build() {

			if (is_null(self::$instantiated)) {

				$WS_Images = new WS_Images();

				self::$instantiated = $WS_Images;

			}

			return self::$instantiated;

    }

  }

}
