<?php

namespace WPS;

use WPS\Utils;
use WPS\DB\Images;

if (!defined('ABSPATH')) {
	exit;
}


if (!class_exists('Templates')) {

	class Templates {

		public $Template_Loader;
		private $DB_Settings_General;
		private $Money;
		private $DB_Variants;
		private $DB_Products;
		private $DB_Images;
		private $DB_Tags;
		private $DB_Options;
		private $DB_Collections;


    public function __construct($Template_Loader, $DB_Settings_General, $Money, $DB_Variants, $DB_Products, $DB_Images, $DB_Tags, $DB_Options, $DB_Collections) {

			$this->Template_Loader 				= $Template_Loader;
			$this->DB_Settings_General		= $DB_Settings_General;
			$this->Money									= $Money;
			$this->DB_Variants						= $DB_Variants;
			$this->DB_Products						= $DB_Products;
			$this->DB_Images							= $DB_Images;
			$this->DB_Tags								= $DB_Tags;
			$this->DB_Options							= $DB_Options;
			$this->DB_Collections					= $DB_Collections;

    }


		/*

		Template: partials/products/loop/loop-start

		*/
		public function wps_products_loop_start($query) {

			$data = [
				'query' => $query
			];

			return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/products/loop/start' );

		}


		/*

		Template: partials/products/loop/loop-end

		*/
		public function wps_products_loop_end($query) {

			$data = [
				'query' => $query
			];

			return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/products/loop/end' );

		}


		/*

		Template: partials/products/loop/item-start

		*/
		public function wps_products_item_start($product, $args, $customArgs) {


			// Related products will always override
			if (isset($args->wps_related_products_items_per_row) && $args->wps_related_products_items_per_row !== false ) {
				$items_per_row = $args->wps_related_products_items_per_row;

			} else {
				$items_per_row = apply_filters('wps_products_items_per_row', 3);
			}


			// Shortcode will always override wps_related_products_items_per_row filter
			if (isset($customArgs['items-per-row']) && $customArgs['items-per-row'] !== false ) {
				$items_per_row = $customArgs['items-per-row'];

			} else {
				$items_per_row = apply_filters('wps_products_items_per_row', 3);
			}


			$data = [
				'product' 			=> $product,
				'args' 					=> $args,
				'custom_args' 	=> $customArgs,
				'items_per_row' => $items_per_row
			];

			return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/products/loop/item', 'start' );

		}


		/*

		Template: partials/products/loop/item-end

		*/
		public function wps_products_item_end($product) {

			$data = [
				'product' => $product
			];

			return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/products/loop/item', 'end' );

		}


		/*

		Template: partials/products/loop/item

		*/
		public function wps_products_item($product, $args, $settings) {

			$data = [
				'product' 					=> 	$product,
				'product_details'		=>	$this->get_product_data($product->post_id),
				'args'							=>	$args,
				'settings'					=>	$settings
			];

			return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/products/loop/item' );

		}


		/*

		Template: partials/products/loop/item-link-start

		*/
		public function wps_products_item_link_start($product, $settings) {

			$data = [
				'product' 		=> 	$product,
				'settings'		=>	$settings
			];

			return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/products/loop/item-link', 'start' );

		}


		/*

		Template: partials/products/loop/item-link-end

		*/
		public function wps_products_item_link_end($product) {

			$data = [
				'product' => $product
			];

			return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/products/loop/item-link', 'end' );

		}


		/*

		Template: partials/products/loop/item-img

		*/
		public function wps_products_img($product) {

			$image = Images::get_image_details_from_product($product);

			$data = [
				'product' => $product,
				'image'	=> $image
			];

			return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/products/loop/item', 'img' );

		}


		/*

		Template: partials/products/loop/item-title

		*/
		public function wps_products_title($product) {

			$data = [
				'product' => $product
			];

			return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/products/loop/item', 'title' );

		}


		/*

		Template: partials/products/loop/item-add-to-cart

		*/
		public function wps_products_add_to_cart($product) {

			$data = [
				'product' => $product
			];

			return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/products/loop/item-add-to', 'cart' );

		}


		/*

		Responsible for getting data for a multple prices

		*/
		public function gather_multi_price_template_data($price_markup, $price_first, $price_last, $product) {

			return [
				'price'				=> $price_markup,
				'price_first' => $price_first,
				'price_last' 	=> $price_last,
				'product' 		=> $product
			];

		}


		/*

		Responsible for getting data for a single price

		*/
		public function gather_single_price_data($price, $product) {

			return [
				'price'		=> $price,
				'product' => $product
			];

		}


		/*

		Responsible for getting markup for multiple prices

		*/
		public function get_multi_price_markup($price_first, $price_last) {
			return apply_filters('wps_products_price_multi_from', '<small class="wps-product-from-price">' . esc_html__('From: ', WPS_PLUGIN_TEXT_DOMAIN) . '</small>') . apply_filters('wps_products_price_multi_first', $price_first) . apply_filters('wps_products_price_multi_separator', ' <span class="wps-product-from-price-separator">-</span> ') . apply_filters('wps_products_price_multi_last', $price_last);
		}


		/*

		Template: partials/products/loop/item-price

		*/
		public function wps_products_price($product) {

			$product = Utils::convert_array_to_object($product);

			$post_id 								= $this->DB_Products->get_post_id_from_product($product);
			$variants_in_stock 			= $this->DB_Variants->get_all_variants_from_post_id($post_id);
			$variants_amount 				= $this->DB_Variants->get_variants_amount($variants_in_stock);
			$variants_sorted 				= $this->DB_Variants->sort_variants_by_price($variants_in_stock);

			$first_price 						= $this->DB_Variants->get_first_variant_price($variants_sorted);
			$last_price 						= $this->DB_Variants->get_last_variant_price($variants_sorted, Utils::get_last_index($variants_amount) );

			$first_price_formatted 	= $this->Money->format_price($first_price, $product->product_id);
			$last_price_formatted 	= $this->Money->format_price($last_price, $product->product_id);


			if ( $this->Money->has_more_than_one_price($variants_amount) ) {

				if ( $this->DB_Variants->check_if_all_variant_prices_match($last_price, $first_price) ) {

					$data = $this->gather_single_price_data($first_price_formatted, $product);

					return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/products/add-to-cart/price', 'one' );

				} else {

					$price_markup = $this->get_multi_price_markup($first_price_formatted, $last_price_formatted);

					$data = $this->gather_multi_price_template_data($price_markup, $first_price_formatted, $last_price_formatted, $product);

					return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/products/add-to-cart/price', 'multi' );

				}

			} else {

				$data = $this->gather_single_price_data($first_price_formatted, $product);

				return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/products/add-to-cart/price', 'one' );

			}


		}


		/*

		Template: partials/products/loop/header

		*/
		public function wps_products_header($query) {

			$data = [
				'query' => $query
			];

			if ( !is_single() ) {
				return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/products/loop/header' );
			}

		}


		/*

		Template: partials/products/add-to-cart/meta-start

		*/
		public function wps_products_meta_start($product) {

			$product->details->url = get_permalink($product->post_id);

			$data = [
				'product' 					=> $product,
				'filtered_options'	=> Utils::normalize_option_values($product->variants)
			];

			return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/products/add-to-cart/meta', 'start' );

		}


		/*

		Template: partials/products/add-to-cart/meta-end

		*/
		public function wps_products_meta_end($product) {

			$data = [
				'product' => $product
			];

			return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/products/add-to-cart/meta', 'end' );

		}


		/*

		Template: partials/products/add-to-cart/quantity

		*/
		public function wps_products_quantity($product) {

			$data = [
				'product' => $product
			];

			return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/products/add-to-cart/quantity' );

		}


		/*

		Template: partials/products/action-groups/action-groups-start

		*/
		public function wps_products_actions_group_start($product) {

			$data = [
				'product' => $product
			];

			return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/products/action-groups/start' );

		}











		/*

		Template: partials/products/add-to-cart/options

		*/
		public function wps_products_options($product) {

			// Only show product options if more than one variant exists, otherwise just shoe add to cart button
			if (count($product->variants) > 1) {

				$data = [
					'product' 									=> $product,
					'button_width'							=> Utils::get_options_button_width($product->options),
					'sorted_options'						=> Utils::get_sorted_options($product),
					'option_number'							=> 1,
					'variant_number'						=> 0
				];

				return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/products/add-to-cart/options' );

			}

		}


		/*

		Template: partials/products/add-to-cart/button-add-to-cart

		*/
		public function wps_products_button_add_to_cart($product) {

			$button_width = Utils::get_add_to_cart_button_width($product);

			$data = [
				'product' 			=> $product,
				'button_width'	=> $button_width
			];

			return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/products/add-to-cart/button-add-to', 'cart' );

		}


		/*

		Template: partials/products/action-groups/action-groups-end

		*/
		public function wps_products_actions_group_end($product) {

			$data = [
				'product' => $product
			];

			return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/products/action-groups/end' );

		}


		/*

		Template: partials/products/add-to-cart/notice-inline

		*/
		public function wps_products_notice_inline($product) {

			$data = [
				'product' => $product
			];

			return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/notices/add-to', 'cart' );

		}


		/*

		Template: partials/products/loop/no-results

		*/
		public function wps_products_no_results($args) {

			$data = [
				'args' => $args
			];

			return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/notices/no', 'results' );

		}


		/*

		Template: partials/pagination/start

		*/
		public function wps_products_pagination_start() {

			$data = [];

			ob_start();
			$this->Template_Loader->set_template_data($data)->get_template_part( 'partials/pagination/start' );
			$output = ob_get_clean();
			return $output;

		}


		/*

		Template: partials/products/related/start

		*/
		public function wps_products_related_start() {

			$data = [];

			return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/products/related/start' );

		}


		/*

		Template: partials/products/related/end

		*/
		public function wps_products_related_end() {

			$data = [];

			return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/products/related/end' );

		}


		/*

		Template: partials/products/related/heading-start

		*/
		public function wps_products_related_heading() {

			$data = [];

			return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/products/related/heading' );

		}


		/*

		Template: partials/pagination/end

		*/
		public function wps_products_pagination_end() {

			$data = [];

			ob_start();
			$this->Template_Loader->set_template_data($data)->get_template_part( 'partials/pagination/end' );
			$output = ob_get_clean();
			return $output;

		}


		/*

		Single Template for related products

		*/
		public function wps_related_products() {

			if (apply_filters('wps_products_related_show', true)) {

				if (!is_single()) {
					return;

				} else {

					$data = [];

					return $this->Template_Loader->set_template_data($data)->get_template_part( 'products', 'related' );

				}

			} else {
				return;

			}

		}


		/*

		Template: partials/collections/loop/loop-start

		*/
		public function wps_collections_loop_start() {

			$data = [];

			return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/collections/loop/start' );

		}


		/*

		Template: partials/collections/loop/loop-end

		*/
		public function wps_collections_loop_end() {

			$data = [];

			return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/collections/loop/end' );

		}


		/*

		Template: partials/collections/loop/item-start

		*/
		public function wps_collections_item_start($collection, $args, $customArgs) {

			// Shortcode will always override wps_related_products_items_per_row filter
			if (isset($customArgs['items-per-row']) && $customArgs['items-per-row'] !== false ) {
				$items_per_row = $customArgs['items-per-row'];

			} else {
				$items_per_row = apply_filters('wps_collections_items_per_row', 3);
			}

			$data = [
				'collection' 		=> $collection,
				'args' 					=> $args,
				'custom_args' 	=> $customArgs,
				'items_per_row' => $items_per_row
			];

			return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/collections/loop/item', 'start' );

		}


		/*

		Template: partials/collections/loop/item-end

		*/
		public function wps_collections_item_end($collection) {

			$data = [
				'collection' 	=> $collection
			];

			return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/collections/loop/item', 'end' );

		}


		/*

		Template: partials/collections/loop/item

		*/
		public function wps_collections_item($collection) {

			$data = [
				'collection' 	=> $collection
			];

			return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/collections/loop/item' );

		}


		/*

		Template: partials/collections/loop/item-link-start

		*/
		public function wps_collections_item_before($collection) {

			$data = [
				'collection' 	=> $collection,
				'settings'		=> $this->DB_Settings_General->get()
			];

			return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/collections/loop/item-link', 'start' );

		}


		/*

		Template: partials/collections/loop/item-link-end

		*/
		public function wps_collections_item_after($collection) {

			$data = [
				'collection' 	=> $collection
			];

			return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/collections/loop/item-link', 'end' );

		}


		/*

		Template: partials/collections/loop/item-img

		*/
		public function wps_collections_img($collection) {

			$data = [
				'collection' 	=> $collection,
				'image'				=> Images::get_image_details_from_collection($collection)
			];

			return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/collections/loop/item', 'img' );

		}


		/*

		Template: partials/collections/loop/item-title

		*/
		public function wps_collections_title($collection) {

			$data = [
				'collection' 	=> $collection
			];

			return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/collections/loop/item', 'title' );

		}


		/*

		Template: partials/collections/loop/no-results

		*/
		public function wps_collections_no_results($args) {

			$data = [
				'args' => $args
			];

			return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/notices/no', 'results' );

		}


		/*

		Template: partials/collections/loop/header

		*/
		public function wps_collections_header($collections) {

			$data = [
				'collections' 	=> $collections
			];

			return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/collections/loop/header' );

		}


		/*

		Template: partials/products/action-groups/action-groups-start

		*/
		public function wps_product_single_actions_group_start($product) {

			$data = [
				'product' 	=> $product
			];

			return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/products/action-groups/start' );

		}


		/*

		Template: partials/products/single/content

		*/
		public function wps_product_single_content($product) {

			if (is_object($product) && property_exists($product, 'details') && !empty($product->details->body_html) ) {

				$data = [
					'product' 	=> $product
				];

				return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/products/single/content' );

			} else {

				$data = [
					'type' 	=> 'product'
				];

				return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/notices/no', 'description' );

			}

		}


		/*

		Template: partials/products/single/header

		*/
		public function wps_product_single_header($product) {

			$data = [
				'product' 	=> $product
			];

			return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/products/single/header' );

		}


		/*

		Template: partials/products/single/header

		*/
		public function wps_product_single_heading($product) {

			$data = [
				'product' 	=> $product
			];

			return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/products/single/heading' );

		}


		/*

		Template: partials/products/single/imgs

		*/
		public function wps_product_single_imgs($product) {

			$product->images = Utils::sort_product_images_by_position($product->images);

			$data = [
				'product' 					=> $product,
				'settings' 					=> $this->DB_Settings_General->get(),
				'images'						=> $product->images,
				'index'							=> 0,
				'amount_of_thumbs'	=> count($product->images)
			];

			return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/products/single/imgs' );

		}


		/*

		Template: partials/products/single/imgs

		*/
		public function wps_product_single_img($data, $image) {

			$data->image_type_class = 'wps-product-gallery-img-thumb';
			$data->image_details = Images::get_image_details_from_image($image, $data->product);

			if ($data->amount_of_thumbs === 1) {
				$data->amount_of_thumbs = 3;
			}

			if ($data->amount_of_thumbs > 8) {
				$data->amount_of_thumbs = 6;
			}


			$data->variant_ids = Images::get_variants_from_image($image);

			return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/products/single/img' );

		}


		/*

		Template: partials/products/single/imgs-feat

		*/
		public function wps_product_single_imgs_feat_placeholder($data) {

			$data->image_type_class = 'wps-product-gallery-img-feat';
			$data->settings->plugin_url = WPS_PLUGIN_URL;

			return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/products/single/imgs-feat', 'placeholder' );

		}


		/*

		Template: partials/products/single/imgs-feat

		*/
		public function wps_product_single_imgs_feat($data, $image) {

			$image_details = Images::get_image_details_from_image($image, $data->product);
			$data->image_details = $image_details;
			$data->image_type_class = 'wps-product-gallery-img-feat';

			$data->variant_ids = Images::get_variants_from_image($image);

			return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/products/single/imgs-feat' );

		}


		/*

		Template: partials/products/single/info-start

		*/
		public function wps_product_single_info_start($product) {

			$data = [
				'product' 	=> $product
			];

			return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/products/single/info', 'start' );

		}


		/*

		Template: partials/products/single/info-end

		*/
		public function wps_product_single_info_end($product) {

			$data = [
				'product' 	=> $product
			];

			return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/products/single/info', 'end' );

		}


		/*

		Template: partials/products/single/gallery-start

		*/
		public function wps_product_single_gallery_start($product) {

			$data = [
				'product' 	=> $product
			];

			return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/products/single/gallery', 'start' );

		}


		/*

		Template: partials/products/single/gallery-end

		*/
		public function wps_product_single_gallery_end($product) {

			$data = [
				'product' 	=> $product
			];

			return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/products/single/gallery', 'end' );

		}


		/*

		Template: partials/products/single/start

		*/
		public function wps_product_single_start($product) {

			$data = [
				'product' 	=> $product
			];

			return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/products/single/start' );

		}


		/*

		Template: partials/products/single/end

		*/
		public function wps_product_single_end($product) {

			$data = [
				'product' 	=> $product
			];

			return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/products/single/end' );

		}


		/*

		Template: partials/notices/out-of-stock

		*/
		public function wps_products_notice_out_of_stock($product) {

			$data = [
				'product' 	=> $product
			];

			return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/notices/out-of', 'stock' );

		}


		/*

		Template: partials/collections/single/start

		*/
		public function wps_collection_single_start($collection) {

			$data = [
				'collection' 	=> $collection
			];

			return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/collections/single/start' );

		}


		/*

		Template: partials/collections/single/header

		*/
		public function wps_collection_single_header($collection) {

			$data = [
				'collection' 	=> $collection
			];

			return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/collections/single/header' );

		}


		/*

		Template: partials/collections/single/img

		*/
		public function wps_collection_single_img($collection) {

			$data = [
				'collection' 	=> $collection,
				'image'				=> Images::get_image_details_from_collection($collection)
			];

			return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/collections/single/img' );

		}


		/*

		Template: partials/collections/single/content

		*/
		public function wps_collection_single_content($collection) {

			if (is_object($collection) && property_exists($collection, 'body_html') && !empty($collection->body_html) ) {

				$data = [
					'collection' 	=> $collection
				];

				return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/collections/single/content' );

			} else {

				$data = [
					'type' 	=> 'collection'
				];

				return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/notices/no', 'description' );

			}

		}


		/*

		Template: partials/collections/single/products

		*/
		public function wps_collection_single_products($collection, $products) {

			$data = [
				'products'		=> $products,
				'collection' 	=> $collection
			];

			return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/collections/single/products' );

		}


		/*

		Template: partials/collections/single/products

		*/
		public function wps_collection_single_products_heading() {

			$data = [];

			return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/collections/single/products', 'heading' );

		}


		/*

		Template: partials/collections/single/end

		*/
		public function wps_collection_single_end($collection) {

			$data = [
				'collection' 	=> $collection
			];

			return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/collections/single/end' );

		}


		/*

		Template: partials/collections/single/heading

		*/
		public function wps_collection_single_heading($collection) {

			$data = [
				'collection' 	=> $collection
			];

			return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/collections/single/heading' );

		}


		/*

		Template: partials/collections/single/product

		*/
		public function wps_collection_single_product($product) {

			$data = [
				'product'		=> $product,
				'settings'	=> $this->DB_Settings_General->get_all_rows()[0]
			];

			return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/collections/single/product' );

		}


		/*

		Template: partials/collections/single/products-list

		*/
		public function wps_collection_single_products_list($collection, $products) {

			if (!is_array($products) || empty($products)) {
				return $this->Template_Loader->get_template_part( 'partials/notices/no', 'results' );
			}

			$data = [
				'products'			=> $products,
				'collection'		=> $collection
			];

			return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/collections/single/products', 'list' );

		}


		/*

		Template: partials/pagination/breadcrumbs

		*/
		public function wps_breadcrumbs($shortcodeData) {

			if ( apply_filters('wps_breadcrumbs_show', $this->DB_Settings_General->show_breadcrumbs()) ) {

				$data = [];

				if ( empty($shortcodeData->shortcodeArgs) || empty($shortcodeData->shortcodeArgs['custom'])|| empty($shortcodeData->shortcodeArgs['custom']['breadcrumbs']) ) {

					return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/pagination/breadcrumbs' );

				} else {

					if (isset($shortcodeData->shortcodeArgs['custom']['breadcrumbs']) && $shortcodeData->shortcodeArgs['custom']['breadcrumbs'] === 'true') {
						return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/pagination/breadcrumbs' );
					}

				}

			}

		}


		/*

		Template: partials/products/single/thumbs-start

		*/
		public function wps_product_single_thumbs_start() {

			$data = [];

			return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/products/single/thumbs', 'start' );

		}


		/*

		Template: partials/products/single/thumbs-end

		*/
		public function wps_product_single_thumbs_end() {

			$data = [];

			return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/products/single/thumbs', 'end' );

		}


		/*

		Template: partials/cart/cart-counter

		*/
		public function wps_cart_counter() {

			$data = [];

			return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/cart/cart', 'counter' );

		}


		/*

		Template: partials/cart/cart-icon

		*/
		public function wps_cart_icon() {

			$data = [];

			return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/cart/cart', 'icon' );

		}


		/*

		Template - cart-button-checkout

		*/
		public function wps_cart_checkout_btn() {

			$data = [
				'checkout_base_url' => WPS_CHECKOUT_BASE_URL
			];

			return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/cart/cart-button', 'checkout' );

		}


		/*

		Template - partials/cart/button
		Shortcode [wps_cart]

		TODO: Think about using shortcode_atts for [wps_products] and [wps_collections] as well.

		*/
		public function wps_cart_shortcode($atts) {

			$shortcode_output = '';

			// Need to cast string to proper boolean
			if (is_array($atts) && isset($atts['counter']) && $atts['counter'] === 'false') {
				$atts['counter'] = false;
			}

			$atts = shortcode_atts([
				'counter' => true
			], $atts, 'wps_cart');

			ob_start();
			$this->Template_Loader->set_template_data($atts)->get_template_part( 'partials/cart/cart-icon', 'wrapper' );
			$cart = ob_get_contents();
			ob_end_clean();

			$shortcode_output .= $cart;

			return $shortcode_output;

		}


		public function is_cart_loaded($cart_loaded_db_response) {

			if (Utils::array_not_empty($cart_loaded_db_response) && isset($cart_loaded_db_response[0]->cart_loaded)) {
				$cart_loaded = $cart_loaded_db_response[0]->cart_loaded;

			} else {
				$cart_loaded = false;
			}

			return $cart_loaded;

		}


		/*

		Template - partials/cart/cart

		This is slow. We should think of a better way to do this.

		*/
		public function wps_cart() {

			$data = [];

			if ( $this->is_cart_loaded( $this->DB_Settings_General->get_column_single('cart_loaded') ) ) {

				ob_start();
				$this->Template_Loader->set_template_data($data)->get_template_part( 'partials/cart/cart' );
				$content = ob_get_contents();
				ob_end_clean();
				echo $content;

			}

		}


		/*

		Template - partials/notices/notice

		*/
		public function wps_notice() {

			$data = [];

			if ( $this->is_cart_loaded( $this->DB_Settings_General->get_column_single('cart_loaded') ) ) {
				return $this->Template_Loader->set_template_data($data)->get_template_part( 'partials/notices/not', 'found' );
			}

		}


		/*

		Template - products-all
		Shortcode [wps_products]

		There's a few things going on here.

		1. 'wps_format_products_shortcode_args' formats the provided shortcode args
				by taking the comma seperated list of values in each attribute and constructing
				an array. It also uses the attribute name as the array key. For example"

				array(
					'title' => array(
						'Sale', 'Featured'
					)
				)''

		2. Next, it passes the array of args to 'wps_map_products_args_to_query'
			 which is the main function that constructs our custom SQL query. This is where
			 the "custom" property is set that we eventually check for within 'wps_clauses_mod'.

		3. At this point in the execution we load our template by pulling in our
			 products-all.php. This template then calls our custom action 'wps_products_display'

		4. 'wps_products_display' then calls 'wps_clauses_mod' when it invokes WP_Query.

		5. Because 'wps_clauses_mod' will get fired for both products and collections, we then
			 need to fork where the execution goes by calling one of two functions depending
			 on what we're dealing with. They are:

			 construct_clauses_from_products_shortcode
			 construct_clauses_from_collections_shortcode

			 ================================================================
			 wps_products_shortcode ->
			 wps_format_products_shortcode_args ->
			 wps_map_products_args_to_query ->
			 wps_products_display -> wps_clauses_mod (via WP_Query)
					either a. construct_clauses_from_products_shortcode
					either b. construct_clauses_from_collections_shortcode
			 ================================================================

		*/
		public function wps_products_shortcode($atts) {

			$shortcode_output = '';
			$shortcodeArgs = Utils::wps_format_products_shortcode_args($atts);

			$data = [
				'shortcodeArgs' => $shortcodeArgs,
				'is_shortcode' 	=> true
			];

			ob_start();
			$this->Template_Loader->set_template_data($data)->get_template_part( 'products', 'all' );
			$products = ob_get_contents();
			ob_end_clean();

			$shortcode_output .= $products;

			return $shortcode_output;

		}


		/*

		Template - collections-all
		Shortcode [wps_collections]

		*/
		public function wps_collections_shortcode($atts) {

			$shortcode_output = '';
			$shortcodeArgs = Utils::wps_format_collections_shortcode_args($atts);

			$data = [
				'shortcodeArgs' => $shortcodeArgs,
				'is_shortcode' 	=> true
			];

			ob_start();
			$this->Template_Loader->set_template_data($data)->get_template_part( 'collections', 'all' );
			$collections = ob_get_contents();
			ob_end_clean();

		 $shortcode_output .= $collections;

		 return $shortcode_output;

		}



		/*

		Main Template - products-single

		*/
		public function wps_single_template($template) {

			if (is_single()) {

				global $post;

				if ($post->post_type === WPS_PRODUCTS_POST_TYPE_SLUG) {
					$template = $this->Template_Loader->get_template_part( 'products', 'single', false ); // passing false will return string and not load template

				} else if ($post->post_type === WPS_COLLECTIONS_POST_TYPE_SLUG) {
					$template = $this->Template_Loader->get_template_part( 'collections', 'single', false );

				}

				return $template;

			}

		}


		/*

		Main Template products-all

		*/
		public function wps_all_template($template) {

			if ( is_post_type_archive(WPS_PRODUCTS_POST_TYPE_SLUG) ) {

				// Passing false will return string and not template contents
				$template = $this->Template_Loader->get_template_part( 'products', 'all', false );

			} else if (is_post_type_archive(WPS_COLLECTIONS_POST_TYPE_SLUG)) {
				$template = $this->Template_Loader->get_template_part( 'collections', 'all', false );

			}

			return $template;

		}



		public function get_shortcode_data($data) {

			if (empty($data)) {

			  $data = new \stdClass;
			  $data->shortcodeArgs = [];
			  $data->is_shortcode = false;

			} else {

			  $data->shortcodeArgs = !empty($data->shortcodeArgs) ? $data->shortcodeArgs : [];
				$data->is_shortcode = isset($data->is_shortcode) && $data->is_shortcode ? $data->is_shortcode : false;

			}

			return $data;

		}


		/*

		Show / Hide Header

		*/
		public function show_header($shortcodeData = false) {

			if (empty($shortcodeData) || empty($shortcodeData->is_shortcode)) {
				get_header('wps');
			}

		}


		/*

		Show / Hide Footer

		*/
		public function show_footer($shortcodeData = false) {

			if (empty($shortcodeData) || empty($shortcodeData->is_shortcode)) {
				get_footer('wps');
			}

		}


    /*

    Get Single Product

    */
    public function get_product_data($postID = false) {

			// Should grab the correct post ID on product single pages
			if ($postID === false) {
        $postID = get_the_ID();
      }

			$product_data_cache = Transients::get('wps_product_data_' . $postID);

			if ( !empty($product_data_cache) ) {
				return $product_data_cache;
			}
			

			$results = new \stdClass;
			$results->details = $this->DB_Products->get_product_from_post_id($postID);
      $results->images = $this->DB_Images->get_images_from_post_id($postID);
      $results->tags = $this->DB_Tags->get_tags_from_post_id($postID);
      $results->variants = $this->DB_Variants->get_in_stock_variants_from_post_id($postID);
      $results->options = $this->DB_Options->get_options_from_post_id($postID);
      $results->details->tags = $this->DB_Tags->construct_only_tag_names($results->tags);

			if (Utils::has($results->details, 'product_id')) {
				$results->product_id = $results->details->product_id;
				$results->collections = $this->DB_Collections->get_collections_by_product_id($results->details->product_id);
			}

			if (Utils::has($results->details, 'post_id')) {
				$results->post_id = $results->details->post_id;
			}

			Transients::set('wps_product_data_' . $postID, $results);

      return $results;

    }


    /*

    Get Collection Products

    */
    public function get_collection_products_data($post_id) {

			$collection = $this->DB_Collections->get_collection($post_id);
			$products = [];

			if ( is_object($collection[0]) && property_exists($collection[0], 'collection_id')) {

			  $products = $this->DB_Products->get_products_by_collection_id($collection[0]->collection_id);

			  /*

			  Get the variants / feat image and add them to the products

			  */
			  foreach ($products as $key => $product) {
			    $product->variants = $this->DB_Variants->get_in_stock_variants_from_post_id($product->post_id);
			    $product->feat_image = $this->DB_Images->get_feat_image_by_post_id($product->post_id);
			  }

			}

			return $products;

    }


		/*

		Hooks

		*/
		public function hooks() {

			/*

			Shortcodes

			*/
			add_shortcode('wps_products', [$this, 'wps_products_shortcode']);
			add_shortcode('wps_collections', [$this, 'wps_collections_shortcode']);
			add_shortcode('wps_cart', [$this, 'wps_cart_shortcode']);

			/*

			Cart & Breadcrumbs

			*/
			add_action('wps_breadcrumbs', [$this, 'wps_breadcrumbs']);
			add_action('wp_footer', [$this, 'wps_notice']);
			add_action('wp_footer', [$this, 'wps_cart']);
			add_action('wps_cart_icon', [$this, 'wps_cart_icon']);
			add_action('wps_cart_counter', [$this, 'wps_cart_counter']);
			add_action('wps_cart_checkout_btn', [$this, 'wps_cart_checkout_btn']);

			/*

			Main Templates

			*/
			add_filter('single_template', [$this, 'wps_single_template']);
			add_filter('archive_template', [$this, 'wps_all_template']);

			/*

			Collections

			*/
			add_action('wps_collections_header', [$this, 'wps_collections_header']);
			add_action('wps_collections_loop_start', [$this, 'wps_collections_loop_start']);
			add_action('wps_collections_loop_end', [$this, 'wps_collections_loop_end']);
			add_action('wps_collections_item_start', [$this, 'wps_collections_item_start'], 10, 3);
			add_action('wps_collections_item_end', [$this, 'wps_collections_item_end']);
			add_action('wps_collections_item', [$this, 'wps_collections_item']);
			add_action('wps_collections_item_before', [$this, 'wps_collections_item_before']);
			add_action('wps_collections_item_after', [$this, 'wps_collections_item_after']);
			add_action('wps_collections_img', [$this, 'wps_collections_img']);
			add_action('wps_collections_title', [$this, 'wps_collections_title']);
			add_action('wps_collections_no_results', [$this, 'wps_collections_no_results']);
			add_action('wps_collection_single_start', [$this, 'wps_collection_single_start']);
			add_action('wps_collection_single_header', [$this, 'wps_collection_single_header']);
			add_action('wps_collection_single_img', [$this, 'wps_collection_single_img']);
			add_action('wps_collection_single_content', [$this, 'wps_collection_single_content']);
			add_action('wps_collection_single_products', [$this, 'wps_collection_single_products'],  10, 3);
			add_action('wps_collection_single_products_list', [$this, 'wps_collection_single_products_list'], 10, 3);
			add_action('wps_collection_single_products_heading', [$this, 'wps_collection_single_products_heading']);
			add_action('wps_collection_single_end', [$this, 'wps_collection_single_end']);
			add_action('wps_collection_single_product', [$this, 'wps_collection_single_product']);
			add_action('wps_collection_single_heading', [$this, 'wps_collection_single_heading'], 10);

			/*

			Products

			*/
			add_action('wps_products_header', [$this, 'wps_products_header']);
			add_action('wps_products_loop_start', [$this, 'wps_products_loop_start']);
			add_action('wps_products_loop_end', [$this, 'wps_products_loop_end']);
			add_action('wps_products_item_start', [$this, 'wps_products_item_start'], 10, 3);
			add_action('wps_products_item_end', [$this, 'wps_products_item_end']);
			add_action('wps_products_item', [$this, 'wps_products_item'], 10, 3);
			add_action('wps_products_item_link_start', [$this, 'wps_products_item_link_start'], 10, 2);
			add_action('wps_products_item_link_end', [$this, 'wps_products_item_link_end']);
			add_action('wps_products_img', [$this, 'wps_products_img']);
			add_action('wps_products_title', [$this, 'wps_products_title']);
			add_action('wps_products_price', [$this, 'wps_products_price']);
			add_action('wps_products_no_results', [$this, 'wps_products_no_results']);
			add_action('wps_products_add_to_cart', [$this, 'wps_products_add_to_cart']);
			add_action('wps_products_meta_start', [$this, 'wps_products_meta_start']);
			add_action('wps_products_quantity', [$this, 'wps_products_quantity']);
			add_action('wps_products_options', [$this, 'wps_products_options']);
			add_action('wps_products_button_add_to_cart', [$this, 'wps_products_button_add_to_cart']);
			add_action('wps_products_actions_group_start', [$this, 'wps_products_actions_group_start']);
			add_action('wps_products_actions_group_end', [$this, 'wps_products_actions_group_end']);
			add_action('wps_products_notice_inline', [$this, 'wps_products_notice_inline']);
			add_action('wps_products_meta_end', [$this, 'wps_products_meta_end']);
			add_action('wps_products_related_start', [$this, 'wps_products_related_start']);
			add_action('wps_products_related_end', [$this, 'wps_products_related_end']);
			add_action('wps_products_related_heading', [$this, 'wps_products_related_heading']);
			add_action('wps_products_notice_out_of_stock', [$this, 'wps_products_notice_out_of_stock']);
			add_filter('wps_products_pagination_start', [$this, 'wps_products_pagination_start']);
			add_filter('wps_products_pagination_end', [$this, 'wps_products_pagination_end']);
			add_action('wps_product_single_after', [$this, 'wps_related_products']);
			add_action('wps_product_single_actions_group_start', [$this, 'wps_product_single_actions_group_start']);
			add_action('wps_product_single_content', [$this, 'wps_product_single_content']);
			add_action('wps_product_single_header', [$this, 'wps_product_single_header']);
			add_action('wps_product_single_heading', [$this, 'wps_product_single_heading']);
			add_action('wps_product_single_img', [$this, 'wps_product_single_img'], 10, 2);
			add_action('wps_product_single_imgs', [$this, 'wps_product_single_imgs']);
			add_action('wps_product_single_imgs_feat_placeholder', [$this, 'wps_product_single_imgs_feat_placeholder']);
			add_action('wps_product_single_imgs_feat', [$this, 'wps_product_single_imgs_feat'], 10, 2);
			add_action('wps_product_single_info_start', [$this, 'wps_product_single_info_start']);
			add_action('wps_product_single_info_end', [$this, 'wps_product_single_info_end']);
			add_action('wps_product_single_gallery_start', [$this, 'wps_product_single_gallery_start']);
			add_action('wps_product_single_gallery_end', [$this, 'wps_product_single_gallery_end']);
			add_action('wps_product_single_start', [$this, 'wps_product_single_start']);
			add_action('wps_product_single_end', [$this, 'wps_product_single_end']);
			add_action('wps_product_single_thumbs_start', [$this, 'wps_product_single_thumbs_start']);
			add_action('wps_product_single_thumbs_end', [$this, 'wps_product_single_thumbs_end']);

			/*

			Notices

			*/
			add_action('wp_ajax_wps_notice', [$this, 'wps_notice']);
			add_action('wp_ajax_nopriv_wps_notice', [$this, 'wps_notice']);

		}


		/*

		Init

		*/
		public function init() {
			$this->hooks();
		}

	}

}
