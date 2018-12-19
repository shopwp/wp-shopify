<?php

namespace WPS\API\Items;

use WPS\Messages;
use WPS\Utils;

if (!defined('ABSPATH')) {
	exit;
}


class Variants extends \WPS\API {

	public function __construct($DB_Products, $DB_Variants) {

		$this->DB_Products = $DB_Products;
		$this->DB_Variants = $DB_Variants;

	}


	/*

	Find Variant ID from Options

	*/
	public function get_variant_id_from_product_options($request) {

		$selected_options = $request->get_param('selectedOptions');
		$product_id = $request->get_param('productID');

		if ( isset($selected_options) ) {

			$product_data = $this->DB_Products->get_product_from_post_id($product_id);
			$variant_data = $this->DB_Variants->get_in_stock_variants_from_post_id($product_id);

			// $productVariants = maybe_unserialize( unserialize( $product_data['variants'] ));

			// TODO: Move to Utils
			function array_filter_key($ar, $callback = 'empty') {
				$ar = (array)$ar;
				return array_intersect_key($ar, array_flip(array_filter(array_keys($ar), $callback)));
			}

			// $productWithVariantsProperty = $product_data
			$refinedVariants = [];
			$refinedVariantsOptions = [];


			foreach ($variant_data as $key => $variant) {

				$refinedVariantsOptions = array_filter_key($variant, function($key) {
					return strpos($key, 'option') === 0;
				});


				$refinedVariants[] = [
					'variant_id' 					=> $variant->variant_id,
					'sku'									=> $variant->sku,
					'inventory_quantity'	=> $variant->inventory_quantity,
					'price'								=> $variant->price,
					'compare_at_price'		=> $variant->compare_at_price,
					'image_id'						=> $variant->image_id,
					'options' 						=> $refinedVariantsOptions
				];

			}


			$constructedOptions = Utils::construct_option_selections($selected_options);

			// TODO -- Breakout into own function
			$found = false;


			foreach ($refinedVariants as $key => $variant) {



				$clean_variants = array_filter($variant['options']);

				if (Utils::has_option_values_set($clean_variants)) {
					$option_values = Utils::get_options_values($clean_variants['option_values']);
					$clean_variants = Utils::clean_option_values($option_values);
				}


				if ( $clean_variants === $constructedOptions ) {

					$variant_obj = $this->DB_Variants->get_row_by('variant_id', $variant['variant_id']);
					$product_data->variants = $variant_data;

					if (Utils::product_inventory($product_data, [ (array) $variant_obj ] )) {

						$found = true;
						return $variant;

					} else {
						$this->send_error( Messages::get('products_out_of_stock') . ' (get_variant_id_from_product_options 3)' );
					}

				}

			}

			if (!$found) {
				$this->send_error( Messages::get('products_options_unavailable') . ' (get_variant_id_from_product_options 4)' );
			}

		} else {
			$this->send_error( Messages::get('products_options_not_found') . ' (get_variant_id_from_product_options 5)' );

		}

	}


	/*

	Register route: cart_icon_color

	*/
  public function register_route_variants() {

		return register_rest_route( WPS_SHOPIFY_API_NAMESPACE, '/variants', [
			[
				'methods'         => 'POST',
				'callback'        => [$this, 'get_variant_id_from_product_options']
			]
		]);

	}


	/*

	Hooks

	*/
	public function hooks() {

		add_action('rest_api_init', [$this, 'register_route_variants']);


	}


  /*

  Init

  */
  public function init() {
		$this->hooks();
  }


}
