<?php

namespace WPS;

use WPS\Utils;

use GuzzleHttp\Promise\Promise;
use GuzzleHttp\Exception;
use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Pool;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;


if (!defined('ABSPATH')) {
	exit;
}


if (!class_exists('WS')) {

	class WS {

		protected $Guzzle;
		protected $Messages;
		protected $DB_Settings_Connection;
		protected $DB_Settings_General;
		protected $DB_Settings_Syncing;


		public function __construct($Guzzle, $Messages, $DB_Settings_Connection, $DB_Settings_General, $DB_Settings_Syncing) {

			$this->Guzzle 									= $Guzzle;
			$this->Messages 								= $Messages;
			$this->DB_Settings_Connection 	= $DB_Settings_Connection;
			$this->DB_Settings_General 			= $DB_Settings_General;
			$this->DB_Settings_Syncing			= $DB_Settings_Syncing;

		}


		/*

		Responsible for inserting data <type> into DB

		Returns Guzzle response object on success or WP_Error on fail

		*/
		public function post($endpoint, $headers = false, $body = false) {
			return $this->wps_request('POST', $this->get_request_url($endpoint), $this->get_request_options($headers, $body) );
		}


		/*

		Responsible for deleting data <type> from DB

		Returns Guzzle response object on success or WP_Error on fail

		*/
		public function delete($endpoint = false) {
			return $this->wps_request('DELETE', $this->get_request_url($endpoint), $this->get_request_options() );
		}


		/*

		Responsible for getting data <type> from Shopify

		Returns Guzzle response object on success or WP_Error on fail

		*/
		public function get($endpoint, $params = false, $async = false) {
			return $this->wps_request('GET', $this->get_request_url($endpoint, $params), $this->get_request_options(), $async);
		}




		public function get_contents($response) {
			return json_decode($response->getBody()->getContents());
		}



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


	  /*

	  Grab the total call amount from the header response

	  */
	  public function get_shopify_api_call_amount($response) {

	    $headerValue = $response->getHeader('HTTP_X_SHOPIFY_SHOP_API_CALL_LIMIT');

	    if (is_array($headerValue) && !empty($headerValue)) {
	      return $response->getHeader('HTTP_X_SHOPIFY_SHOP_API_CALL_LIMIT')[0];

	    } else {
	      return false;
	    }

	  }


	  /*

	  Used exclusively for throttling API requests

	  */
	  public function throttle_requests() {
	    sleep(8);
	  }


		/*

		Gets a Guzzle status code

		$response : Guzzle response

		*/
		public function get_status_code($response) {

			if (method_exists($response, 'getResponse') && method_exists($response->getResponse(), 'getStatusCode')) {
				return $response->getResponse()->getStatusCode();

			} else {
				return 500; // Generic error response
			}

		}


		/*

		Returns a custom error message depending on the API response code

		*/
		public function get_error_message($response) {

			$responseCode = $this->get_status_code($response);

			switch ($responseCode) {

				case 400:
					return $this->Messages->message_shopify_api_400;
					break;

				case 401:
					return $this->Messages->message_shopify_api_401;
					break;

				case 402:
					return $this->Messages->message_shopify_api_402;
					break;

				case 403:
					return $this->Messages->message_shopify_api_403;
					break;

				case 404:
					return $this->Messages->message_shopify_api_404;
					break;

				case 406:
					return $this->Messages->message_shopify_api_406;
					break;

				case 422:
					return $this->Messages->message_shopify_api_422;
					break;

				case 429:
					return $this->Messages->message_shopify_api_429;
					break;

				case 500:
					return $this->Messages->message_shopify_api_500;
					break;

				case 501:
					return $this->Messages->message_shopify_api_501;
					break;

				case 503:
					return $this->Messages->message_shopify_api_503;
					break;

				case 504:
					return $this->Messages->message_shopify_api_504;
					break;

				default:
					return $this->Messages->message_shopify_api_generic;
					break;

			}

		}


	  /*

	  Callback to the on_headers Guzzle function

	  */
	  public function check_rate_limit($response) {

			$callTotal = $this->get_shopify_api_call_amount($response);

	    if ($callTotal === WPS_SHOPIFY_RATE_LIMIT || $callTotal === false) {
	      $this->throttle_requests();
	    }

	  }


		/*

		Returns request headers + options for Guzzle

		Param: $newHeaders array
		Param: $newOptions array

		get_request_options(['Content-Type' => 'text/json'], ['query' => $stuff])

		*/
		public function get_request_options($newHeaders = false, $newOptions = false, $shopify = true) {

			$finalOptions = [];
			$defaultHeaders = [];

			$connection = $this->DB_Settings_Connection->get();

			if (is_object($connection) && isset($connection->api_key)) {
				$authToken = base64_encode($connection->api_key . ':' . $connection->password);

			} else {
				$authToken = '';
			}


			if ($shopify) {

				$finalOptions = [
					'http_errors' => true,
					'headers' => [
						'Authorization' => 'Basic ' . $authToken
					],
					'on_headers' => function($response) {
						$this->check_rate_limit($response);
					}
				];

			}

			if (is_array($newHeaders) && !empty($newHeaders)) {

				if (isset($finalOptions['headers']) && $finalOptions['headers']) {

					$defaultHeaders = $finalOptions['headers'];
					$finalOptions['headers'] = array_merge($defaultHeaders, $newHeaders);

				}

			}

			if (is_array($newOptions) && !empty($newOptions)) {
				$finalOptions = array_merge($finalOptions, $newOptions);
			}

			return $finalOptions;

		}


		/*

		Returns request url

		*/
		public function get_request_url($endpoint, $params = false) {

			$connection = $this->DB_Settings_Connection->get();

			if (is_object($connection) && isset($connection->domain)) {
				return "https://" . $connection->domain . $endpoint . $params;
			}

		}


		/*

		Lowest level request wrapper

		Returns Guzzle response object on success or WP_Error on fail

		*/
		public function wps_request($method, $url, $options = false, $async = false, $throttle = true) {

			if ($throttle) {
				usleep(180000);
			}

			if (empty($options)) {
				$options = [];
			}


			if ($async) {

				try {

					$promise = $this->Guzzle->requestAsync(
						$method,
						$url,
						$options
					);

					return $promise;

				} catch (\Exception $error) {

					return new \WP_Error('error', __($this->get_error_message($error), WPS_PLUGIN_TEXT_DOMAIN));

				}

			} else {


				try {

					$response = $this->Guzzle->request(
						$method,
						$url,
						$options
					);


					return $this->get_contents($response);

				} catch (\Exception $error) {

					 $wp_error = new \WP_Error('error', __($this->get_error_message($error), WPS_PLUGIN_TEXT_DOMAIN));

					 return $wp_error;

				}

			}


		}




		public function construct_sync_by_collections_count_url($type) {

			$urls = [];
			$collection_ids = maybe_unserialize($this->DB_Settings_General->sync_by_collections());

			foreach ($collection_ids as $collection_id) {
				$urls[] = '/admin/' . $type . '/count.json?collection_id=' . $collection_id;
			}

			return $urls;

		}



		public function get_counts_from_urls($urls) {

			$products_count = [];

			foreach ($urls as $url) {

				$count = $this->get($url);

				if (!empty($count)) {

					if (Utils::has($count, 'count')) {
						$products_count[] = $count->count;
					}

				}

			}

			return array_sum($products_count);

		}



		public function construct_sync_by_collections_api_urls($collection_ids, $currentPage) {

			$urls = [];

			foreach ($collection_ids as $collection_id) {
				$urls[] = "?collection_id=" . $collection_id . "&limit=250&page=" . $currentPage;
			}

			return $urls;

		}


		public function construct_flattened_object($items_flattened, $type) {

			$items_obj = new \stdClass;
			$items_obj->{$type} = $items_flattened;

			return $items_obj;

		}


		public function flatten_data_from_sync_by_collections($items, $type) {

			// Need to check since $items comes directly from a request
			if (is_wp_error($items)) {
				return $items;
			}

			$items_flattened = [];

			foreach ($items as $item_wrap) {

				foreach ($item_wrap as $single_item) {
					$items_flattened[] = $single_item;
				}

			}

			return $this->construct_flattened_object($items_flattened, $type);

		}



		public function has_rejected_promises($promise_results) {

			foreach ($promise_results as $promise_result) {

				if (isset($promise_result['state']) && $promise_result['state'] === 'rejected') {
					return $this->get_error_message($promise_result);
				}

			}

			return false;

		}


		/*

		Checks for a valid (open) connection to the web server based on a URL. Useful to check
		whether the syncing will even work before starting ...

		WP Shopify addon

		@param string $url
		@return boolean

		*/
		public function has_valid_server_connection() {

			$url = $_SERVER['HTTP_REFERER'];

	    $url_parts = @parse_url($url);

			if (!$url_parts) return false;
	    if (!isset($url_parts['host'])) return false; //can't process relative URLs
	    if (!isset($url_parts['path'])) $url_parts['path'] = '/';

	    $sock = fsockopen($url_parts['host'], (isset($url_parts['port']) ? (int)$url_parts['port'] : 80), $errno, $errstr, 30);

			if (!$sock) {
				return new \WP_Error('error', $this->Messages->message_invalid_server_connection, WPS_PLUGIN_TEXT_DOMAIN);

			} else {
				return true;
			}

		}






		/*

		Ends a progress bar instance

		*/
		public function expire_sync() {

			if ($this->DB_Settings_Syncing->is_syncing()) {
				$this->DB_Settings_Syncing->reset_all_syncing_totals();
				$this->DB_Settings_Syncing->reset_syncing_cache();
			}

		}



		public function save_notice($WP_Error) {

			$this->DB_Settings_Syncing->save_notice(
				$WP_Error->get_error_message(),
				$WP_Error->get_error_code()
			);

		}


		/*

		Saves error and stops the syncing process

		*/
		public function save_notice_and_stop_sync($WP_Error) {

			$this->save_notice($WP_Error);
			$this->expire_sync();

		}





	}

}
