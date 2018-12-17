<?php

namespace WPS\Factories\API\Processing;

if ( !defined('ABSPATH') ) {
	exit;
}

use WPS\Factories;
use WPS\API;

class Webhooks_Deletions_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			self::$instantiated = new API\Processing\Webhooks_Deletions(
				Factories\Processing\Webhooks_Deletions_Factory::build()
			);

		}

		return self::$instantiated;

	}

}
