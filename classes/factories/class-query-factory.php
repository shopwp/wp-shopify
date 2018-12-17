<?php

namespace WPS\Factories;

use WPS\Query;
use WPS\Factories;

if (!defined('ABSPATH')) {
	exit;
}

class Query_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			$Query = new Query(
				Factories\Template_loader_Factory::build(),
				Factories\DB\Collections_Factory::build(),
				Factories\DB\Settings_General_Factory::build(),
				Factories\DB\Images_Factory::build(),
				Factories\Pagination_Factory::build(),
				Factories\DB\Products_Factory::build()
			);

			self::$instantiated = $Query;

		}

		return self::$instantiated;

	}

}
