<?php

namespace WPS\WS;

if (!defined('ABSPATH')) {
	exit;
}


if (!class_exists('Options')) {


  class Options extends \WPS\WS {

		public function __construct($DB_Options, $DB_Settings_General, $Messages) {

			$this->DB_Options         				= $DB_Options;
			$this->DB_Settings_General        = $DB_Settings_General;
      $this->Messages         					= $Messages;

    }


		/*

		Delete Options

		*/
		public function delete_options() {

			$syncStates = $this->DB_Settings_General->selective_sync_status();

			if ($syncStates['all']) {

				if (!$this->DB_Options->delete()) {
					return new \WP_Error('error', $this->Messages->message_delete_product_options_error . ' (delete_options)');

				} else {
					return true;
				}

			} else {

				if ($syncStates['products']) {

					if (!$this->DB_Options->delete()) {
						return new \WP_Error('error', $this->Messages->message_delete_product_options_error . ' (delete_options 2)');

					} else {
						return true;
					}

				} else {
					return true;
				}

			}

		}


		/*

		Inserting Product Options

		*/
		public function insert_product_options($product = false) {
			return $this->DB_Options->insert_option($product);
		}


		/*

		Hooks

		*/
		public function hooks() {
			add_action('wp_ajax_insert_product_options', [$this, 'insert_product_options']);
			add_action('wp_ajax_nopriv_insert_product_options', [$this, 'insert_product_options']);
		}


		/*

		Init

		*/
		public function init() {
			$this->hooks();
		}


  }

}
