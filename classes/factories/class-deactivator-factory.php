<?php

namespace WPS\Factories;

use WPS\Deactivator;

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('Deactivator_Factory')) {

  class Deactivator_Factory {

		protected static $instantiated = null;

    public static function build() {

			if (is_null(self::$instantiated)) {

				$Deactivator = new Deactivator();

				self::$instantiated = $Deactivator;

			}

			return self::$instantiated;


    }


  }

}
