<?php

namespace WPS\Factories\Processing;

use WPS\Processing;
use WPS\Factories;

if (!defined('ABSPATH')) {
	exit;
}

class Posts_Relationships_Collections_Factory {

	protected static $instantiated = null;

  public static function build() {

		if (is_null(self::$instantiated)) {

			self::$instantiated = new Processing\Posts_Relationships_Collections(
				Factories\DB\Settings_Syncing_Factory::build(),
				Factories\CPT_Meta_Factory::build(),
				Factories\CPT_Query_Factory::build(),
				Factories\DB\Collections_Factory::build()
			);

		}

		return self::$instantiated;


  }

}
