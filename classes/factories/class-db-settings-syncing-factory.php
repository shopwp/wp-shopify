<?php

namespace WPS\Factories;

use WPS\DB\Settings_Syncing as DB_Settings_Syncing;

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('DB_Settings_Syncing_Factory')) {

  class DB_Settings_Syncing_Factory {

		protected static $instantiated = null;

    public static function build() {

			if (is_null(self::$instantiated)) {

      	$DB_Settings_Syncing = new DB_Settings_Syncing();

				self::$instantiated = $DB_Settings_Syncing;

			}

      return self::$instantiated;

    }

  }

}
