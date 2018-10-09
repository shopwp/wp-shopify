<?php

namespace WPS\Factories;

use WPS\Pagination;

use WPS\Factories\DB_Settings_General_Factory;
use WPS\Factories\Templates_Factory;

if (!defined('ABSPATH')) {
	exit;
}

class Pagination_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			$Pagination = new Pagination(
				DB_Settings_General_Factory::build(),
				Templates_Factory::build()
			);

			self::$instantiated = $Pagination;

		}

		return self::$instantiated;

	}

}
