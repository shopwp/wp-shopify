<?php

namespace WPS\Factories;

use WPS\Pagination;
use WPS\Factories;

if (!defined('ABSPATH')) {
	exit;
}

class Pagination_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			self::$instantiated = new Pagination(
				Factories\DB\Settings_General_Factory::build(),
				Factories\Templates_Factory::build()
			);

		}

		return self::$instantiated;

	}

}
