<?php

namespace WPS\WS;

use WPS\Utils;
use GuzzleHttp\Promise;

if (!defined('ABSPATH')) {
	exit;
}


if (!class_exists('Images')) {


  class Images extends \WPS\WS {

		protected $DB_Images;
		protected $DB_Settings_General;
		protected $Messages;
		protected $Guzzle;


		public function __construct($DB_Images, $DB_Settings_General, $Messages, $Guzzle) {

			$this->DB_Images    					= $DB_Images;
			$this->DB_Settings_General    = $DB_Settings_General;
			$this->Messages         			= $Messages;
			$this->Guzzle         				= $Guzzle;

		}


		/*

	  delete_images

	  */
	  public function delete_images() {

			$syncStates = $this->DB_Settings_General->selective_sync_status();

			if ($syncStates['all']) {

				if (!$this->DB_Images->delete()) {
					return new \WP_Error('error', $this->Messages->message_delete_product_images_error . ' (delete_images)');

				} else {
					return true;
				}

			} else {

				if ($syncStates['products']) {

					if (!$this->DB_Images->delete()) {
						return new \WP_Error('error', $this->Messages->message_delete_product_images_error . ' (delete_images 2)');

					} else {
						return true;
					}

				} else {
					return true;
				}

			}

	  }


		/*

		Insert alt text

		TODO: Refactor / move to new async background job?

		*/
		public function insert_alt_text() {

			if (!Utils::valid_backend_nonce($_POST['nonce'])) {
				$this->send_error($this->Messages->message_nonce_invalid . ' (insert_alt_text)');
			}

			$allImages = $this->DB_Images->get_all_rows();

			$Guzzle = $this->Guzzle;

			$requests = function () use ($Guzzle, $allImages) {

				foreach ($allImages as $image) {

					usleep(200000);

					if (is_object($image)) {
						$imageID = $image->id;

					} else if (is_array($image)) {
						$imageID = $image['id'];
					}

					$url = "/admin/metafields.json";
					$urlParams = "?metafield[owner_id]=" . $imageID . "&metafield[owner_resource]=product_image&limit=250";

					yield $this->Guzzle->requestAsync(
						'GET',
						$this->get_request_url($url, $urlParams),
						$this->get_request_options()
					)->then(function ($response) use ($image, $imageID) {

					});

				}

			};

			$promise = Promise\each_limit(
		    $requests(),
		    3,
		    function($resp) {

				},
				function($test) {

				}
			);

			$promise->wait();

		}


		/*

		Inserting Product Images

		*/
		public function insert_product_image($product = false) {
			return $this->DB_Images->insert_image($product);
		}



		public function hooks() {

			add_action('wp_ajax_insert_alt_text', [$this, 'insert_alt_text']);
			add_action('wp_ajax_nopriv_insert_alt_text', [$this, 'insert_alt_text']);

			add_action('wp_ajax_insert_product_image', [$this, 'insert_product_image']);
			add_action('wp_ajax_nopriv_insert_product_image', [$this, 'insert_product_image']);

		}


		/*

		Init

		*/
		public function init() {
			$this->hooks();
		}


  }

}
