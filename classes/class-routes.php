<?php

namespace WPS;


if (!defined('ABSPATH')) {
	exit;
}


class Routes {

	public function __construct() {

	}

	public function flush_routes() {
		flush_rewrite_rules();
	}

}
