<?php

namespace WPS\API\Processing;

if (!defined('ABSPATH')) {
	exit;
}


class Posts_Products extends \WPS\API {

	public $Processing_Posts_Products;


	public function __construct($Processing_Posts_Products, $Processing_Posts_Relationships) {
		$this->Processing_Posts_Products 								= $Processing_Posts_Products;
		$this->Processing_Posts_Relationships 	= $Processing_Posts_Relationships;
	}


	/*

	Responsible for firing off a background process for product posts

	*/
	public function process_posts_products($request) {
		$this->Processing_Posts_Products->process($request);
	}


	/*

	Responsible for firing off a background process for product posts relationships

	*/
	public function process_posts_products_relationships($request) {
		$this->Processing_Posts_Relationships->process($request);
	}


	/*

	Register route: /process/posts_products

	*/
  public function register_route_process_posts_products() {

		return register_rest_route( WPS_SHOPIFY_API_NAMESPACE, '/process/posts_products', [
			[
				'methods'         => \WP_REST_Server::CREATABLE,
				'callback'        => [$this, 'process_posts_products']
			]
		]);

	}


	/*

	Register route: /process/posts_products

	*/
  public function register_route_process_posts_products_relationships() {

		return register_rest_route( WPS_SHOPIFY_API_NAMESPACE, '/process/posts_products_relationships', [
			[
				'methods'         => \WP_REST_Server::CREATABLE,
				'callback'        => [$this, 'process_posts_products_relationships']
			]
		]);

	}


	/*

	Hooks

	*/
	public function hooks() {
		add_action('rest_api_init', [$this, 'register_route_process_posts_products']);
		add_action('rest_api_init', [$this, 'register_route_process_posts_products_relationships']);
	}


  /*

  Init

  */
  public function init() {
		$this->hooks();
  }


}
