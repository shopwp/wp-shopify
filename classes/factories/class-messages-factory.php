<?php

namespace WPS\Factories;

use WPS\Messages;

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('Messages_Factory')) {

  class Messages_Factory {

		protected static $instantiated = null;

    public static function build() {

			if (is_null(self::$instantiated)) {

				$Messages = new Messages();

				self::$instantiated = $Messages;

			}

      return self::$instantiated;

    }

  }

}
