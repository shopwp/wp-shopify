<?php

namespace WPS\API\Items;

use WPS\Factories;
use WPS\Messages;
use WPS\Utils;
use WPS\CPT;

if (!defined('ABSPATH')) {
	exit;
}


class Posts extends \WPS\API {

	public function __construct($DB_Posts, $Processing_Posts_Relationships_Products, $Processing_Posts_Relationships_Collections, $DB_Products) {

		$this->DB_Posts 																		= $DB_Posts;
		$this->DB_Products																	= $DB_Products;
		$this->Processing_Posts_Relationships_Products 			= $Processing_Posts_Relationships_Products;
		$this->Processing_Posts_Relationships_Collections 	= $Processing_Posts_Relationships_Collections;

	}


	/*

	Delete the synced Shopify data

	*/
	public function delete_posts($request) {

		$ids 				= $request->get_param('ids');
		$post_type 	= $request->get_param('post_type');

		// Removes only from an array of ids
		if ( !empty($ids) ) {
			return $this->DB_Posts->delete_posts(['ids' => $ids]);
		}

		// Removes only type
		if ( !empty($post_type) ) {
			return $this->DB_Posts->delete_posts(['post_type' => $post_type]);
		}

		// Removes all
		return $this->DB_Posts->delete_posts();

	}


	public function connect_products_posts_relationships($request) {

		return $this->handle_response([
			'response' 				=> CPT::get_all_posts_truncated(WPS_PRODUCTS_POST_TYPE_SLUG, ['ID', 'post_name', 'post_type']),
			'meta'						=> [
				'table_name'			=> $this->DB_Products->get_table_name(),
				'lookup_key'			=> WPS_PRODUCTS_LOOKUP_KEY,
				'post_type'				=> WPS_PRODUCTS_POST_TYPE_SLUG,
				'relationships'		=> ['DB_Products', 'DB_Tags'],
			],
			'process_fns'			=> [
				$this->Processing_Posts_Relationships_Products
			]
		]);

	}


	public function connect_collections_posts_relationships($request) {

		return $this->handle_response([
			'response' 				=> CPT::get_all_posts_truncated(WPS_COLLECTIONS_POST_TYPE_SLUG, ['ID', 'post_name', 'post_type']),
			'meta'						=> [
				'lookup_key'			=> WPS_COLLECTIONS_LOOKUP_KEY,
				'post_type'				=> WPS_COLLECTIONS_POST_TYPE_SLUG,
				'relationships'		=> ['DB_Collections_Smart', 'DB_Collections_Custom'],
			],
			'process_fns'			=> [
				$this->Processing_Posts_Relationships_Collections
			]
		]);

	}


	/*

	Register route: cart_icon_color

	*/
  public function register_route_products_posts() {

		return register_rest_route( WPS_SHOPIFY_API_NAMESPACE, '/posts/products', [
			[
				'methods'         => \WP_REST_Server::CREATABLE,
				'callback'        => [$this, 'connect_products_posts_relationships']
			]
		]);

	}


	/*

	Register route: cart_icon_color

	*/
  public function register_route_collections_posts() {

		return register_rest_route( WPS_SHOPIFY_API_NAMESPACE, '/posts/collections', [
			[
				'methods'         => \WP_REST_Server::CREATABLE,
				'callback'        => [$this, 'connect_collections_posts_relationships']
			]
		]);

	}


	/*

	Register route: cart_icon_color

	*/
  public function register_route_posts() {

		return register_rest_route( WPS_SHOPIFY_API_NAMESPACE, '/posts', [
			[
				'methods'         => \WP_REST_Server::CREATABLE,
				'callback'        => [$this, 'delete_posts']
			]
		]);

	}


	/*

	Hooks

	*/
	public function hooks() {

		add_action('rest_api_init', [$this, 'register_route_posts']);
		add_action('rest_api_init', [$this, 'register_route_products_posts']);
		add_action('rest_api_init', [$this, 'register_route_collections_posts']);

	}


  /*

  Init

  */
  public function init() {
		$this->hooks();
  }


}
