<?php

namespace WPS\Factories;

use WPS\Async_Processing_Variants;

use WPS\Factories\DB_Settings_Syncing_Factory;
use WPS\Factories\DB_Variants_Factory;


if (!defined('ABSPATH')) {
	exit;
}

class Async_Processing_Variants_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			$Async_Processing_Variants = new Async_Processing_Variants(
				DB_Settings_Syncing_Factory::build(),
				DB_Variants_Factory::build()
			);

			self::$instantiated = $Async_Processing_Variants;

		}

		return self::$instantiated;

	}

}
