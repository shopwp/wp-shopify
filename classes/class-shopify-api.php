<?php

namespace WPS;

if (!defined('ABSPATH')) {
	exit;
}


class Shopify_API extends \WPS\HTTP {


	public function __construct($DB_Settings_Connection) {
		parent::__construct($DB_Settings_Connection);
	}

	/*

	Endpoints

	*/
	public function endpoint_products() {
		return "/admin/products.json";
	}

	public function endpoint_product_listings() {
		return "/admin/product_listings.json";
	}

	public function endpoint_product_listings_count() {
		return "/admin/product_listings/count.json";
	}

	public function endpoint_product_listings_product_ids() {
		return "/admin/product_listings/product_ids.json";
	}

	public function endpoint_webhooks() {
		return "/admin/webhooks.json";
	}

	public function endpoint_webhooks_single($webhook_id) {
		return "/admin/webhooks/" . $webhook_id . ".json";
	}

	public function endpoint_shop() {
		return "/admin/shop.json";
	}

	public function endpoint_storefront_access_tokens() {
		return "/admin/storefront_access_tokens.json";
	}

	public function endpoint_orders() {
		return "/admin/orders.json";
	}

	public function endpoint_orders_count() {
		return "/admin/orders/count.json";
	}

	public function endpoint_customers() {
		return "/admin/customers.json";
	}

	public function endpoint_customers_count() {
		return "/admin/customers/count.json";
	}

	public function endpoint_collects() {
		return "/admin/collects.json";
	}

	public function endpoint_collects_count() {
		return "/admin/collects/count.json";
	}

	public function endpoint_smart_collections() {
		return "/admin/smart_collections.json";
	}

	public function endpoint_smart_collections_count() {
		return "/admin/smart_collections/count.json";
	}

	public function endpoint_custom_collections() {
		return "/admin/custom_collections.json";
	}

	public function endpoint_custom_collections_count() {
		return "/admin/custom_collections/count.json";
	}


	/*

	Params

	*/
	public function param_limit($limit) {
		return "limit=" . $limit;
	}

	public function param_page($page) {
		return "page=" . $page;
	}

	public function param_product_id($product_id) {
		return "product_id=" . $product_id;
	}

	public function param_collection_id($collection_id) {
		return "collection_id=" . $collection_id;
	}

	public function param_status($status) {
		return "status=" . $status;
	}

	public function param_ids($ids) {
		return "ids=" . $ids;
	}


	/*

	Gets products per page

	*/
	public function get_products_per_page($product_ids, $limit) {

		return $this->get(
			$this->endpoint_products(),
			'?' . $this->param_ids($product_ids) .
			'&' . $this->param_limit($limit)
		);

	}


	/*

	Gets products per page

	*/
	public function get_collects_per_page($limit, $current_page) {

		return $this->get(
			$this->endpoint_collects(),
			'?' . $this->param_limit($limit) .
			'&' . $this->param_page($current_page)
		);

	}


	/*

	Gets products listings per page

	*/
	public function get_products_listing_product_ids_per_page($current_page) {

		return $this->get(
			$this->endpoint_product_listings_product_ids(),
			'?' . $this->param_limit(WPS_MAX_IDS_PER_REQUEST) .
			'&' . $this->param_page($current_page)
		);

	}


	/*

	Gets products listings per page

	*/
	public function get_products_listing_product_ids_by_collection_id_per_page($collection_id, $current_page) {

		return $this->get(
			$this->endpoint_product_listings_product_ids(),
			'?' . $this->param_collection_id($collection_id) .
			'&' . $this->param_limit(WPS_MAX_IDS_PER_REQUEST) .
			'&' . $this->param_page($current_page)
		);

	}


	/*

	Gets products from collection id per page

	*/
	public function get_products_from_collection_per_page($collection_id, $limit, $current_page) {

		return $this->get(
			$this->endpoint_products(),
			'?' . $this->param_collection_id($collection_id) .
			'&' . $this->param_limit($limit) .
			'&' . $this->param_page($current_page)
		);

	}


	/*

	Gets collects from collection id per page

	*/
	public function get_collects_from_collection_per_page($collection_id, $limit, $current_page) {

		return $this->get(
			$this->endpoint_collects(),
			'?' . $this->param_collection_id($collection_id) .
			'&' . $this->param_limit($limit) .
			'&' . $this->param_page($current_page)
		);

	}


	/*

	Gets products listing count

	*/
	public function	get_product_listings_count() {
		return $this->get( $this->endpoint_product_listings_count() );
	}


	/*

	Gets products listing count by collection id

	*/
	public function get_product_listings_count_by_collection_id($collection_id) {

		return $this->get(
			$this->endpoint_product_listings_count(),
			'?' . $this->param_collection_id($collection_id)
		);

	}


	/*

	Gets webhooks

	*/
	public function get_webhooks() {
		return $this->get( $this->endpoint_webhooks() );
	}


	/*

	Registers a single webhook

	*/
	public function register_webhook($webhook_body) {
		return $this->post( $this->endpoint_webhooks(), $webhook_body);
	}


	/*

	Deletes a single webhook by webhook id

	*/
	public function delete_webhook($webhook_id) {
		return $this->delete( $this->endpoint_webhooks_single($webhook_id) );
	}


	/*

	Gets shop

	*/
	public function get_shop() {
		return $this->get( $this->endpoint_shop() );
	}


	/*

	Gets storefront access tokens

	*/
	public function get_storefront_access_tokens() {
		return $this->get( $this->endpoint_storefront_access_tokens() );
	}


	/*

	Gets orders per page

	*/
	public function get_orders_per_page($limit, $current_page, $status) {

		return $this->get(
			$this->endpoint_orders(),
			'?' . $this->param_limit($limit) .
			'&' . $this->param_page($current_page) .
			'&' . $this->param_status($status)
		);

	}


	/*

	Gets orders count

	*/
	public function get_orders_count($status) {

		return $this->get(
			$this->endpoint_orders_count(),
			'?' . $this->param_status($status)
		);

	}


	/*

	Gets customers per page

	*/
	public function get_customers_per_page($limit, $current_page, $status) {

		return $this->get(
			$this->endpoint_customers(),
			'?' . $this->param_limit($limit) .
			'&' . $this->param_page($current_page) .
			'&' . $this->param_status($status)
		);

	}


	/*

	Gets customers count

	*/
	public function get_customers_count() {
		return $this->get( $this->endpoint_customers_count() );
	}


	/*

	Gets collects count

	*/
	public function get_collects_count() {
		return $this->get( $this->endpoint_collects_count() );
	}


	/*

	Gets collects by product id

	*/
	public function get_collects_by_product_id($product_id) {

		return $this->get(
			$this->endpoint_collects(),
			'?' . $this->param_product_id($product_id)
		);

	}


	/*

	Gets collects from collection id

	*/
	public function get_collects_from_collection_id($collection_id) {

		return $this->get(
			$this->endpoint_collects(),
			'?' . $this->param_collection_id($collection_id)
		);

	}


	/*

	Gets collects count from collection id

	*/
	public function get_collects_count_by_collection_id($collection_id) {

		return $this->get(
			$this->endpoint_collects_count(),
			'?' . $this->param_collection_id($collection_id)
		);

	}


	/*

	Gets smart collections count

	*/
	public function get_smart_collections_count() {
		return $this->get( $this->endpoint_smart_collections_count() );
	}


	/*

	Gets smart collections per page

	*/
	public function get_smart_collections_per_page($limit, $current_page) {

		return $this->get(
			$this->endpoint_smart_collections(),
			'?' . $this->param_limit($limit) .
			'&' . $this->param_page($current_page)
		);

	}


	/*

	Gets custom collections per page

	*/
	public function get_custom_collections_per_page($limit, $current_page) {

		return $this->get(
			$this->endpoint_custom_collections(),
			'?' . $this->param_limit($limit) .
			'&' . $this->param_page($current_page)
		);

	}


	/*

	Gets custom collections count

	*/
	public function get_custom_collections_count() {
		return $this->get( $this->endpoint_custom_collections_count() );
	}


}
