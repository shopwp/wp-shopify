<?php

namespace WPS;

use WPS\Utils;
use WPS\Messages;

if (!defined('ABSPATH')) {
	exit;
}


class HTTP {

	private $DB_Settings_Connection;

	public function __construct($DB_Settings_Connection) {
		$this->DB_Settings_Connection	= $DB_Settings_Connection;
	}


	/*

	Used exclusively for throttling API requests

	*/
	public function throttle_request() {
		sleep(8);
	}


	/*

	Grab the total call amount from the header response

	*/
	public function get_shopify_api_call_amount($response) {

		$call_limit = wp_remote_retrieve_header($response, WPS_SHOPIFY_HEADER_API_CALL_LIMIT);

		if ($call_limit === '') {
			return false;
		}

		return $call_limit;

	}


	/*

	Responsible for building the request URL

	*/
	public function get_request_url($endpoint, $params = false) {

		$connection = $this->DB_Settings_Connection->get();

		if (is_object($connection) && isset($connection->domain)) {
			return "https://" . $connection->domain . $endpoint . $params;
		}

	}


	/*

	Gets a response status code

	$response : Guzzle response

	*/
	public function get_status_code($response) {
		return (int) wp_remote_retrieve_response_code($response);
	}


	/*

	Returns a custom error message depending on the API response code

	*/
	public function get_error_message_from_status_code($response) {

		$response_code = $this->get_status_code($response);

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
				return Messages::get('shopify_api_generic');
				break;

		}

	}


	/*

	Callback to the on_headers Guzzle function

	*/
	public function check_rate_limit($response) {

		$call_total = $this->get_shopify_api_call_amount($response);

		if ($call_total === WPS_SHOPIFY_RATE_LIMIT || $call_total === false) {
			$this->throttle_request();
		}

	}


	/*

	Sets the body argument for wp_remote_request

	*/
	public function maybe_set_request_arg_body($request_args, $body) {

		if ($body) {
			$request_args['body'] = json_encode($body);
		}

		return $request_args;

	}


	/*

	Sets the timing argument for wp_remote_request

	*/
	public function maybe_set_request_arg_timing($request_args, $blocking) {

		$request_args['timeout'] = $blocking ? 0.01 : 30;

		return $request_args;

	}


	/*

	Sets the blocking argument for wp_remote_request

	*/
	public function maybe_set_request_arg_blocking($request_args, $blocking) {

		$request_args['blocking'] = $blocking ? false : true;

		return $request_args;

	}


	/*

	Sets the default headers used in each request despite the method

	*/
	public function default_request_headers($request_args, $auth_token) {

		return $request_args['headers'] = [
			'Authorization' 	=> 'Basic ' . $auth_token,
			'Content-Type' 		=> 'application/json'
		];

	}


	/*

	Sets the headers argument for wp_remote_request

	*/
	public function maybe_set_request_arg_headers($request_args) {

		$request_args['headers'] = $this->default_request_headers($request_args, $this->DB_Settings_Connection->get_auth_token() );

		return $request_args;
	}


	/*

	Sets the method argument for wp_remote_request

	*/
	public function maybe_set_request_arg_method($request_args, $method) {

		$request_args['method'] = $method;

		return $request_args;

	}


	public function build_request_args($method, $body, $blocking) {

		$request_args = [];
		$request_args = $this->maybe_set_request_arg_body($request_args, $body);
		$request_args = $this->maybe_set_request_arg_timing($request_args, $blocking);
		$request_args = $this->maybe_set_request_arg_blocking($request_args, $blocking);
		$request_args = $this->maybe_set_request_arg_headers($request_args);
		$request_args	= $this->maybe_set_request_arg_method($request_args, $method);

		return $request_args;

	}


	public function is_server_error($response) {

		if ( is_wp_error($response) ) {
			return true;
		}

	}

	public function is_client_error($response) {

		$first_num = Utils::first_num( $this->get_status_code($response) );

		if ( $first_num === 4) {
			return true;
		}

	}


	public function get_server_error_message($response) {
		return $this->get_error_message_from_status_code($response);
	}


	public function get_client_error_message($response) {

		$response_message = json_decode( wp_remote_retrieve_body($response) );

		if ( Utils::has($response_message, 'error') ) {
			return $response_message->error;
		}

		if ( Utils::has($response_message, 'errors') ) {

			$stuff = array_values( Utils::convert_object_to_array($response_message->errors) );

			return $stuff[0][0];

		}

	}


	/*

	Responsible for inserting data <type> into DB

	Returns response object on success or WP_Error on fail

	*/
	public function post($endpoint, $body = false, $blocking = false) {
		return $this->request('POST', $this->get_request_url($endpoint), $body, $blocking);
	}


	/*

	Responsible for deleting data <type> from DB

	Returns response object on success or WP_Error on fail

	*/
	public function delete($endpoint = false, $blocking = false) {
		return $this->request('DELETE', $this->get_request_url($endpoint), [], $blocking);
	}


	/*

	Responsible for getting data <type> from Shopify

	Returns response object on success or WP_Error on fail

	*/
	public function get($endpoint, $params = false, $blocking = false) {
		return $this->request('GET', $this->get_request_url($endpoint, $params), [], $blocking);
	}


	/*

	Lowest level request wrapper

	Param 1: $method
	Param 1: $url
	Param 1: $body
	Param 1: $blocking

	Returns response object on success or WP_Error on fail

	*/
	public function request($method, $url, $body = false, $blocking = false) {

		$request_args = $this->build_request_args($method, $body, $blocking);

		$url = apply_filters('wps_remote_request_url', $url, $request_args);

		do_action('wps_before_remote_request', $url, $request_args);

		if (!$url) {
			return false;
		}

		$response = wp_remote_request($url, $request_args);


		if ( $this->is_client_error($response) ) {
			return Utils::wp_error( __( $this->get_client_error_message($response), WPS_PLUGIN_TEXT_DOMAIN ) );
		}

		if ( $this->is_server_error($response) ) {
			return Utils::wp_error( __( $this->get_server_error_message($response), WPS_PLUGIN_TEXT_DOMAIN ) );
		}

		// Throttles API calls if needed to stay under limit
		$this->check_rate_limit($response);

		return json_decode( wp_remote_retrieve_body($response) );

	}


}
