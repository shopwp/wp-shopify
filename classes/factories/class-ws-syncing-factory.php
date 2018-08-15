<?php

namespace WPS\Factories;

use WPS\WS\Syncing as WS_Syncing;
use WPS\Factories\DB_Settings_Syncing_Factory;

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('WS_Syncing_Factory')) {

  class WS_Syncing_Factory {

		protected static $instantiated = null;

    public static function build() {

			if (is_null(self::$instantiated)) {

				$Sync = new WS_Syncing(
					DB_Settings_Syncing_Factory::build()
				);

				self::$instantiated = $Sync;

			}

			return self::$instantiated;

		}

  }

}
