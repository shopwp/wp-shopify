<?php

namespace WPS\Factories;

use WPS\CPT_Model;
use WPS\Factories;

if (!defined('ABSPATH')) {
	exit;
}

class CPT_Model_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			self::$instantiated = new CPT_Model(
				Factories\DB\Settings_General_Factory::build()
			);

		}

		return self::$instantiated;

	}

}
