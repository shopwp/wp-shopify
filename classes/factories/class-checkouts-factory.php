<?php

namespace WPS\Factories;

use WPS\Checkouts;

use WPS\Factories\Messages_Factory;
use WPS\Factories\WS_Factory;

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('Checkouts_Factory')) {

  class Checkouts_Factory {

		protected static $instantiated = null;

    public static function build() {

			if (is_null(self::$instantiated)) {

				$Checkouts = new Checkouts(
					Messages_Factory::build(),
					WS_Factory::build()
				);

				self::$instantiated = $Checkouts;

			}

			return self::$instantiated;

		}

  }

}
