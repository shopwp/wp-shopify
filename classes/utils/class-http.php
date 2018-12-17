<?php

namespace WPS\Utils;

use WPS\Utils;
use WPS\Messages;


if (!defined('ABSPATH')) {
	exit;
}


class HTTP {

	/*

	Gets a response status code

	*/
	public static function get_status_code($response) {
		return (int) wp_remote_retrieve_response_code($response);
	}


	/*

	Checks if a client error

	*/
	public static function is_client_error($response) {

		if ( Utils::first_num( self::get_status_code($response) ) === 4) {
			return true;
		}

	}












	/*

	Returns a custom error message depending on the API response code

	*/
	public static function get_error_message_from_status_code($response, $url = false) {

		$response_code = self::get_status_code($response);

		switch ($response_code) {

			case 400:
				return Messages::get('shopify_api_400');
				break;

			case 401:
				return Messages::get('shopify_api_401');
				break;

			case 402:
				return Messages::get('shopify_api_402');
				break;

			case 403:
				return Messages::get('shopify_api_403');
				break;

			case 404:
				return Messages::get('shopify_api_404');
				break;

			case 406:
				return Messages::get('shopify_api_406');
				break;

			case 413:
				return Messages::get('max_post_body_size');
				break;

			case 422:
				return Messages::get('shopify_api_422');
				break;

			case 429:
				return Messages::get('shopify_api_429');
				break;

			case 500:
				return Messages::get('shopify_api_500');
				break;

			case 501:
				return Messages::get('shopify_api_501');
				break;

			case 503:
				return Messages::get('shopify_api_503');
				break;

			case 504:
				return Messages::get('shopify_api_504');
				break;

			default:

				$errors = $response->get_error_messages();

				if ( empty($errors) ) {
					return Messages::get('shopify_api_generic');
					break;

				} else {
					return $errors[0];
					break;
				}

		}

	}


	public static function get_request_url($response) {
		return $response['http_response']->get_response_object()->url;
	}





	public static function error_code($response) {
		return $response['http_response']->get_response_object()->status_code;
	}

	public static function error_url($response) {
		return $response['http_response']->get_response_object()->url;
	}



	public static function massage_error_message($response, $message) {

		switch ($message) {

			case 'expected String to be a id':

				return '<p class="wps-syncing-error-message"><b>400 Error:</b> The request endpoint was malformed.</p>';

				break;

			default:
				break;

		}

		return $message;

	}



	/*

	Get client error message

	*/
	public static function get_client_error_message($response) {

		$response_message = json_decode( wp_remote_retrieve_body($response) );

		if ( empty($response_message) ) {

			if ( empty($response['response']['message']) ) {
				return $response['http_response']->get_response_object()->status_code . ' ' . $response['http_response']->get_response_object()->url;

			} else {
				return self::get_error_message_from_status_code($response, self::get_request_url($response));
			}

		}

		if ( Utils::has($response_message, 'error') ) {
			return $response_message->error;
		}

		if ( Utils::has($response_message, 'errors') ) {

			$errors = array_values( Utils::convert_object_to_array($response_message->errors) );

			if ( Utils::is_multi_array($errors) ) {
				return $errors[0][0];

			} else {

				return self::massage_error_message($response, $errors[0]);

			}

		}

		if ( Utils::has($response_message, 'message') ) {
			return $response_message->message;
		}

	}


}
