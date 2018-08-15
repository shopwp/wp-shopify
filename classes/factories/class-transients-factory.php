<?php

namespace WPS\Factories;

use WPS\Transients;

use WPS\Factories\Messages_Factory;

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('Transients_Factory')) {

  class Transients_Factory {

		protected static $instantiated = null;

    public static function build() {

			if (is_null(self::$instantiated)) {

				$Transients = new Transients(
					Messages_Factory::build()
				);

				self::$instantiated = $Transients;

			}

			return self::$instantiated;

		}

  }

}
