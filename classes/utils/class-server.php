<?php

namespace WPS\Utils;

use WPS\Utils\Data;

if (!defined('ABSPATH')) {
	exit;
}


class Server {

	public static function server_type() {

    $server_software = $_SERVER['SERVER_SOFTWARE'];
		$web_server_name = explode('/', $server_software)[0];

    return strtolower($web_server_name);

	}

	public static function is_nginx() {
		return self::server_type() === 'nginx';
	}

	public static function is_apache() {
		return self::server_type() === 'apache';
	}

	public static function get_php_post_max_size_bytes() {
		return wp_max_upload_size();
	}

	public static function exceeds_max_post_body_size($data) {
		return Data::size_in_bytes($data) > self::get_php_post_max_size_bytes();
	}

	public static function get_current_locale() {

		$myArray = explode(',', $_SERVER["HTTP_ACCEPT_LANGUAGE"]);

		// If empty, default to US
		if ( empty($myArray) ) {
			return 'en-US';
		}

		// Sends back the first locale. There should always be one locale
		return $myArray[0];

	}

}
