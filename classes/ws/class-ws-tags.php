<?php

namespace WPS\WS;

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('Tags')) {

  class Tags extends \WPS\WS {

		private $DB_Tags;
		private $Settings_General;

  	public function __construct($DB_Tags, $Settings_General) {
			$this->DB_Tags 					= $DB_Tags;
			$this->Settings_General = $Settings_General;
    }


		/*

	  Delete Tags

	  */
	  public function delete_tags() {

			$syncStates = $this->Settings_General->selective_sync_status();

			if ($syncStates['all']) {

				if ( !$this->DB_Tags->delete() ) {
					return new \WP_Error('error', $this->Messages->message_delete_product_tags_error . ' (delete_tags)');

				} else {
					return true;
				}

			} else {

				if ($syncStates['products']) {

					if ( !$this->DB_Tags->delete() ) {
						return new \WP_Error('error', $this->Messages->message_delete_product_tags_error . ' (delete_tags 2)');

					} else {
						return true;
					}

				} else {
					return true;
				}

			}

	  }


		/*

		Inserting Product Tags

		*/
		public function insert_product_tags($product = false) {
			return $this->DB_Tags->insert_tags($product);
		}


		/*

		Hooks

		*/
		public function hooks() {

			add_action('wp_ajax_insert_product_tags', [$this, 'insert_product_tags']);
			add_action('wp_ajax_nopriv_insert_product_tags', [$this, 'insert_product_tags']);

		}


		/*

		Init

		*/
		public function init() {
			$this->hooks();
		}


  }

}
