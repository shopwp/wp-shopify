<?php

namespace WPS;

if (!defined('ABSPATH')) {
	exit;
}


class WS {

	public function __construct() {}


	/*

	Always returns a JSON object to the client in this format:

	{
		success: false,
		data: {
			type: 'error',
			message: <message>
		}
	}

	*/
	public function send_error($message = '') {

		wp_send_json_error([
			'type' => 'error',
			'message' => $message
		]);

		wp_die();

	}


	/*

	Always returns a JSON object to the client in this format:

	{
		success: true,
		data: {
			type: 'warning',
			message: <message>
		}
	}

	*/
	public function send_warning($message = '') {

		wp_send_json_success([
			'type' => 'warning',
			'message' => $message
		]);

		wp_die();

	}


	/*

	Always returns a JSON object to the client in this format:

	{
		success: true,
		data: $data
	}

	*/
	public function send_success($data = false) {

		wp_send_json_success($data);
		wp_die();

	}


}
