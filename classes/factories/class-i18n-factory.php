<?php

namespace WPS\Factories;

use WPS\I18N;

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('I18N_Factory')) {

  class I18N_Factory {

		protected static $instantiated = null;

    public static function build() {

			if (is_null(self::$instantiated)) {

				$I18N = new I18N();

				self::$instantiated = $I18N;

			}

      return self::$instantiated;

    }

  }

}
