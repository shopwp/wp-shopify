<?php

namespace WPS;

use WPS\Utils;

if (!defined('ABSPATH')) {
	exit;
}


class API {

	public $DB_Settings_Syncing;


	public function __construct($DB_Settings_Syncing) {
		$this->DB_Settings_Syncing = $DB_Settings_Syncing;
	}


	public function send_success($data = true) {
		return $data;
	}


	public function is_handle_response_error($response) {

		$response = Utils::convert_object_to_array($response);

		if ( isset($response['type']) && $response['type'] === 'error' ) {
			return true;
		}

		return false;

	}


	public function send_error($message) {

		if ( is_wp_error($message) ) {
			$message = $message->get_error_message();
		}

		return [
			'type' => 'error',
			'message' => $message
		];

	}


	public function send_warning($message) {

		return [
			'type' => 'warning',
			'message' => $message
		];

	}


	public function get_nonce($request) {
		return $request->get_param('nonce');
	}

	public function is_valid_nonce($nonce) {
		return Utils::valid_backend_nonce( $nonce );
	}





	public function handle_warnings($message) {

		if ( $this->DB_Settings_Syncing->is_syncing() ) {
			$this->DB_Settings_Syncing->save_warning( Messages::get($message) );
		}

		return $this->send_warning( Messages::get($message) );

	}


	/*

	Will throw a warning if the response prop does not exist or is empty

	*/
	public function has_warning($params) {
		return !Utils::has($params['response'], $params['access_prop']) || empty($params['response']->{$params['access_prop']});
	}


	/*

	Returns an array of shape:

	[
		'type' 			=> 'error',
		'message' 	=> $message
	]

	*/
	public function handle_errors($WP_Error) {

		// if ( !$this->DB_Settings_Syncing->is_syncing() ) {
		//
		// }

		$this->DB_Settings_Syncing->save_notice_and_expire_sync($WP_Error);

		return $this->send_error( $WP_Error->get_error_message() );

	}


	public function run_processes($params) {

		foreach ($params['process_fns'] as $class) {

			if ($params['access_prop']) {

				$params['response'] = Utils::convert_array_to_object($params['response']);

				call_user_func_array( [$class, 'process'], [$params['response']->{$params['access_prop']}, $params]);

			} else {
				call_user_func_array( [$class, 'process'], [ $params['response'], $params ]);
			}

		}

	}


	public function handle_response_defaults() {

		return [
			'response' 					=> false,
			'response_multi'		=> false,
			'access_prop' 			=> false,
			'return_key' 				=> false,
			'warning_message' 	=> false,
			'process_fns'				=> false
		];

	}

	public function has_multi_response($params) {
		return !empty($params['response_multi']);
	}

	public function has_response($params) {
		return !empty($params['response']);
	}

	public function needs_processing($params) {
		return !empty($params['process_fns']);
	}

	public function has_return_key($params) {
		return !empty($params['return_key']);
	}

	public function has_access_prop($params) {
		return !empty($params['access_prop']);
	}

	public function has_warning_message($params) {
		return !empty($params['warning_message']);
	}

	public function handle_response_params($params) {
		return wp_parse_args($params, $this->handle_response_defaults() );
	}

	public function return_keyed_response($params) {

		$params['response'] = Utils::convert_array_to_object($params['response']);

		if ( !empty($params['access_prop']) ) {
			$key = $params['response']->{$params['access_prop']};

		} else {
			$key = false;
		}

		return [
			$params['return_key'] => $key
		];

	}


	public function return_from_prop($params) {

		$params['response'] = Utils::convert_array_to_object($params['response']);

		return $params['response']->{$params['access_prop']};
	}



	public function handle_response_logic($params) {

		if ( !$this->has_response($params) ) {
			return $params;
		}

		if ( is_wp_error( $params['response'] ) ) {
			return $this->handle_errors( $params['response'] );
		}

		if ($params['response'] === false) {
			return $params['response'];
		}

		if ( $this->has_access_prop($params) && $this->has_warning($params) && $this->has_warning_message($params) ) {
			$this->handle_warnings( $params['warning_message'] ); // no need to return if just a warning
		}

		if ( $this->needs_processing($params) ) {
			$this->run_processes($params);
		}

		if ( $this->has_return_key($params) ) {
			return $this->return_keyed_response($params);
		}

		if ( $this->has_access_prop($params) ) {
			return $this->return_from_prop($params);
		}

		return $params['response'];

	}


	public function handle_multi_response($responses) {
		return array_map([$this, 'handle_response_logic'], $responses);
	}


	/*

	Params:

	'response' 				=> $response,
	'access_prop' 		=> 'count',
	'return_key' 			=> 'smart_collections',
	'warning_message'	=> 'this_is_a_message'

	*/
	public function handle_response($params) {

		/*

		If all we're doing is returning data immedtiately, this allows us
		to use handle_response like this: return handle_response($stuff)
		Which prevents having to pass an array with a response prop.

		*/
		if ( !is_array($params) ) {
			return $params;
		}

		$params = $this->handle_response_params($params);


		if ( $this->has_multi_response($params) ) {
			return $this->handle_multi_response($params);
		}

		return $this->handle_response_logic($params);

	}


}
