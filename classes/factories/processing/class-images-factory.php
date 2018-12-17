<?php

namespace WPS\Factories\Processing;

use WPS\Processing;
use WPS\Factories;

if (!defined('ABSPATH')) {
	exit;
}

class Images_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			self::$instantiated = $Async_Processing_Images = new Processing\Images(
				Factories\DB\Settings_Syncing_Factory::build(),
				Factories\DB\Images_Factory::build()
			);

		}

		return self::$instantiated;

	}

}
