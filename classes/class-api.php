<?php

namespace WPS;

if (!defined('ABSPATH')) {
	exit;
}


class API {

	public function __construct() {

	}


	public function error($route, $message, $status_code) {

		return new \WP_Error(
			$route,
			__($message, WPS_PLUGIN_TEXT_DOMAIN),
			['status' => $status_code]
		);

	}


}
