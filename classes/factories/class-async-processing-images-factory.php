<?php

namespace WPS\Factories;

use WPS\Async_Processing_Images;

use WPS\Factories\DB_Settings_Syncing_Factory;
use WPS\Factories\DB_Images_Factory;


if (!defined('ABSPATH')) {
	exit;
}

class Async_Processing_Images_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			$Async_Processing_Images = new Async_Processing_Images(
				DB_Settings_Syncing_Factory::build(),
				DB_Images_Factory::build()
			);

			self::$instantiated = $Async_Processing_Images;

		}

		return self::$instantiated;

	}

}
