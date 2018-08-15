<?php

namespace WPS\WS;

use WPS\Utils;

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('Variants')) {


  class Variants extends \WPS\WS {

		protected $DB_Settings_General;
		protected $DB_Products;
		protected $DB_Variants;
		protected $Messages;

		public function __construct($DB_Products, $DB_Settings_General, $Messages, $DB_Variants, $DB_Settings_Connection) {

			$this->DB_Settings_General 				= $DB_Settings_General;
			$this->DB_Products 								= $DB_Products;
			$this->DB_Variants 								= $DB_Variants;
			$this->Messages 									= $Messages;

			$this->DB_Settings_Connection			=	$DB_Settings_Connection;

		}


		/*

	  delete_variants

	  */
	  public function delete_variants() {

			$syncStates = $this->DB_Settings_General->selective_sync_status();

			if ($syncStates['all']) {

				if (!$this->DB_Variants->delete()) {
					return new \WP_Error('error', $this->Messages->message_delete_product_variants_error . ' (delete_variants)');

				} else {
					return true;
				}

			} else {

				if ($syncStates['products']) {

					if (!$this->DB_Variants->delete()) {
						return new \WP_Error('error', $this->Messages->message_delete_product_variants_error . ' (delete_variants 2)');

					} else {
						return true;
					}

				} else {
					return true;
				}

			}

	  }


		/*

		Inserting Product Variants

		*/
		public function insert_product_variants($product = false) {
			return $this->DB_Variants->insert_variants_from_product($product);
		}


		/*

		Find Variant ID from Options

		*/
		public function get_variant_id_from_product_options() {

			if (isset($_POST['selectedOptions']) && is_array($_POST['selectedOptions'])) {

				$selectedOptions = $_POST['selectedOptions'];

				// TODO: combine below two lines with get_variants
				$productData = $this->DB_Products->get_product_from_post_id($_POST['productID']);
				$variantData = $this->DB_Variants->get_variants_from_post_id($_POST['productID']);

				// $productVariants = maybe_unserialize( unserialize( $productData['variants'] ));

				// TODO: Move to Utils
				function array_filter_key($ar, $callback = 'empty') {
					$ar = (array)$ar;
					return array_intersect_key($ar, array_flip(array_filter(array_keys($ar), $callback)));
				}

				// $productWithVariantsProperty = $productData
				$refinedVariants = [];
				$refinedVariantsOptions = [];


				foreach ($variantData as $key => $variant) {

					$refinedVariantsOptions = array_filter_key($variant, function($key) {
						return strpos($key, 'option') === 0;
					});


					$refinedVariants[] = [
						'id' 									=> $variant->id,
						'sku'									=> $variant->sku,
						'inventory_quantity'	=> $variant->inventory_quantity,
						'price'								=> $variant->price,
						'compare_at_price'		=> $variant->compare_at_price,
						'image_id'						=> $variant->image_id,
						'options' 						=> $refinedVariantsOptions
					];

				}


				$constructedOptions = Utils::construct_option_selections($selectedOptions);

				// TODO -- Breakout into own function
				$found = false;

				foreach ($refinedVariants as $key => $variant) {

					$cleanVariants = array_filter($variant['options']);

					if ( $cleanVariants === $constructedOptions ) {

						$variantObj = $this->DB_Variants->get_by('id', $variant['id']);
						$productData->variants = $variantData;

						if (Utils::product_inventory($productData, [(array) $variantObj])) {

							$found = true;
							$this->send_success($variant);

						} else {
							$this->send_error($this->Messages->message_products_out_of_stock . ' (get_variant_id_from_product_options 3)');
						}

					}

				}

				if (!$found) {
					$this->send_error($this->Messages->message_products_options_unavailable . ' (get_variant_id_from_product_options 4)');
				}

			} else {
				$this->send_error($this->Messages->message_products_options_not_found . ' (get_variant_id_from_product_options 5)');

			}

		}


		/*

		Hooks

		*/
		public function hooks() {

			add_action('wp_ajax_get_variants', [$this, 'get_variants']);
			add_action('wp_ajax_nopriv_get_variants', [$this, 'get_variants']);

			add_action('wp_ajax_insert_product_variants', [$this, 'insert_product_variants']);
			add_action('wp_ajax_nopriv_insert_product_variants', [$this, 'insert_product_variants']);

			add_action('wp_ajax_get_variant_id_from_product_options', [$this, 'get_variant_id_from_product_options']);
			add_action('wp_ajax_nopriv_get_variant_id_from_product_options', [$this, 'get_variant_id_from_product_options']);

		}


		/*

		Init

		*/
		public function init() {
			$this->hooks();
		}


  }

}
