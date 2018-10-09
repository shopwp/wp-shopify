<?php

namespace WPS\Factories;

use WPS\CPT_Model;
use WPS\Factories\DB_Settings_General_Factory;

if (!defined('ABSPATH')) {
	exit;
}

class CPT_Model_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			$CPT_Model = new CPT_Model(
				DB_Settings_General_Factory::build()
			);

			self::$instantiated = $CPT_Model;

		}

		return self::$instantiated;

	}

}
