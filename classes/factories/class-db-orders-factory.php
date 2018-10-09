<?php

namespace WPS\Factories;

use WPS\DB\Orders as DB_Orders;

if (!defined('ABSPATH')) {
	exit;
}

class DB_Orders_Factory {

	protected static $instantiated = null;

	public static function build() {

		if (is_null(self::$instantiated)) {

			$DB_Orders = new DB_Orders();

			self::$instantiated = $DB_Orders;

		}

		return self::$instantiated;

	}

}
