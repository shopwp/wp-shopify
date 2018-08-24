<?php

namespace WPS\WS;

if (!defined('ABSPATH')) {
	exit;
}

use WPS\Utils;
use WPS\Transients;
use WPS\Vendor\GuzzleHttp\Promise as GuzzlePromise;

if (!class_exists('Collections')) {

  class Collections extends \WPS\WS {

		protected $WS_Collections_Custom;
		protected $WS_Collections_Smart;
		protected $DB_Settings_General;
		protected $Messages;
		protected $DB_Settings_Connection;


  	public function __construct($WS_Collections_Smart, $WS_Collections_Custom, $DB_Settings_General, $Messages, $DB_Settings_Connection) {

			$this->WS_Collections_Smart 		= $WS_Collections_Smart;
			$this->WS_Collections_Custom 		= $WS_Collections_Custom;
			$this->DB_Settings_General 			= $DB_Settings_General;
			$this->Messages 								= $Messages;
			$this->DB_Settings_Connection 	= $DB_Settings_Connection;

    }


		/*

		Gets all collections

		*/
		public function get_all_collections() {

			if (!Utils::valid_backend_nonce($_POST['nonce'])) {
				$this->send_error($this->Messages->message_nonce_invalid  . ' (get_all_collections)');
			}

			if (!$this->DB_Settings_Connection->has_connection()) {
				$this->send_error($this->Messages->message_connection_not_found . ' (get_all_collections)');
			}

			$collections_cache = Transients::get('wps_sync_by_collections');


			if ( !empty($collections_cache) ) {
				$this->send_success(maybe_unserialize($collections_cache));
			}


			$promises = [];

			$promises[] = $this->WS_Collections_Smart->get_smart_collections_by_page(1, true);
			$promises[] = $this->WS_Collections_Custom->get_custom_collections_by_page(1, true);

			$data = [];

			GuzzlePromise\settle($promises)->then(

				function ($results) use($data) {

					$rejected_promise_message = $this->has_rejected_promises($results);

					if ($rejected_promise_message) {
						$this->send_error($rejected_promise_message);
					}


					foreach ($results as $result) {

						if (isset($result['value'])) {
							$data[] = (array) json_decode($result['value']->getBody()->getContents());
						}

					}


					$collections_list = call_user_func_array('array_merge', [&$data]);
					$collections_merged = [];

					foreach ($collections_list as $collection) {

						if (isset($collection['smart_collections'])) {
							$collections_merged[] = $collection['smart_collections'];
						}

						if (isset($collection['custom_collections'])) {
							$collections_merged[] = $collection['custom_collections'];
						}

					}


					if (!empty($collections_merged)) {

						$collections_merged_final = call_user_func_array('array_merge', $collections_merged);

						$collections_merged_final_reduced = array_map( function($collection) {

							$new_collection_obj = new \stdClass();
							$new_collection_obj->id = $collection->id;
							$new_collection_obj->title = $collection->title;

							return $new_collection_obj;

						}, $collections_merged_final);

						$collections_serialized = maybe_serialize($collections_merged_final_reduced);

						Transients::set('wps_sync_by_collections', $collections_serialized);

						$this->send_success($collections_merged_final);

					} else {
						$this->send_success();
					}

				},

				function (RequestException $error) {

					$error_message = $this->get_error_message($error);

  		})->wait();



		}


		/*

		Hooks

		*/
		public function hooks() {

			add_action('wp_ajax_get_all_collections', [$this, 'get_all_collections']);
			add_action('wp_ajax_nopriv_get_all_collections', [$this, 'get_all_collections']);

		}


		/*

		Init

		*/
		public function init() {
			$this->hooks();
		}


  }

}
