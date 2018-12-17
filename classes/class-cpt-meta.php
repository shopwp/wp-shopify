<?php

namespace WPS;

if (!defined('ABSPATH')) {
	exit;
}

use WPS\Utils;
use WPS\Utils\Posts as Utils_Posts;


class CPT_Meta {

	public $DB_Products;
	public $DB_Tags;
	public $DB_Posts;
	public $DB_Collections_Smart;
	public $DB_Collections_Custom;

	public function __construct($DB_Products, $DB_Tags, $DB_Posts, $DB_Collections_Smart, $DB_Collections_Custom) {

		$this->DB_Products 							= $DB_Products;
		$this->DB_Tags 									= $DB_Tags;
		$this->DB_Posts									= $DB_Posts;
		$this->DB_Collections_Smart 		= $DB_Collections_Smart;
		$this->DB_Collections_Custom 		= $DB_Collections_Custom;
		$this->DB 											= $DB_Products; // alias

	}


	/*

	Wrapper function for updating post meta

	Codex: https://codex.wordpress.org/Function_Reference/update_post_meta

	Returns meta_id if the meta doesn't exist, otherwise returns true
	on success and false on failure.

	NOTE: If the meta_value passed to this function is the same as the
	value that is already in the database, this function returns false.

	In otherwords, this will fail (return false) if the value to update
	is the same as the existing value.

	Returns:

	WP_Error if SQL issues
	false if update error
	true otherwise

	*/
	public function update_post_meta_helper($post_id, $meta_key, $meta_value) {

		$response = $this->DB->sanitize_db_response(
			update_post_meta($post_id, $meta_key, $meta_value),
			'Failed to update post meta table with key value',
			'update_post_meta'
		);

		return $this->sanitize_update_post_meta_response($response, $post_id, $meta_key, $meta_value);

	}


	/*

	Responsible for adding product id to posts

	*/
	public function add_lookup_key_to_posts($params) {

		$final_posts = [];
		$posts = Utils::maybe_wrap_in_array($params['posts']);


		foreach ($posts as $post) {

			$item = $this->DB_Posts->get_from_post_name($params['table_name'], $post->post_name);

			if ( !empty($item) ) {
				$final_posts[$post->ID][$params['lookup_key']] = $item->{$params['lookup_key']};
				$final_posts[$post->ID]['post_id'] = $post->ID;
			}

		}

		return $final_posts;

	}


	/*

	By checking if the two values exist, we can determine if it was successfully
	updated or not. Equal means update was successful.

	*/
	public function post_meta_values_same($post_id, $meta_key, $new_meta_value) {
		return get_post_meta($post_id, $meta_key, true) === $new_meta_value;
	}


	/*

	Sanitizes an update post meta response

	*/
	public function sanitize_update_post_meta_response($response, $post_id, $meta_key, $new_meta_value) {

		// SQL errors occured
		if ( is_wp_error($response) ) {
			return $response;
		}

		// Check if a real error
		return $this->post_meta_values_same($post_id, $meta_key, $new_meta_value);

	}


	/*

	Sets the post id param

	*/
	public function set_post_id_param($post) {

		return [
			'post_id' => $post['post_id']
		];

	}


	/*

	Sets the lookup key param

	*/
	public function set_lookup_key_param($post, $lookup_key) {

		return [
			$lookup_key => $post[$lookup_key]
		];

	}


	/*

	Update post meta

	Returns:

	WP_Error if SQL issues
	false if update error
	true otherwise

	lookup_key === product_id, collection_id, etc

	$post must have a propery / array key of the same value as the lookup_key.

	In otherwords:

	[
		post_id 		=> 123,
		product_id 	=> 2394823904
	]

	*/
	public function update_posts_meta_relationship($post, $params) {

		$update_posts_meta_result = $this->update_post_meta_helper($post['post_id'], $params['lookup_key'], $post[$params['lookup_key']]);
		
		if ( Utils_Posts::is_modify_posts_error($update_posts_meta_result) ) {

			return Utils::wp_warning([
				'message_lookup' 	=> 'failed_to_set_lookup_key_post_meta_table',
				'message_aux'			=> $post[$params['lookup_key']] . ' to the WordPress post meta table. The post ID affected is: ' . $post['post_id'],
				'call_method' 		=> __METHOD__,
				'call_line' 			=> __LINE__
			]);

		}

		return $update_posts_meta_result;

	}


	/*

	Update products table

	*/
	public function update_posts_relationship($post, $db_class, $params) {

		return $this->{$db_class}->update_column_single(
			$this->set_post_id_param($post),
			$this->set_lookup_key_param($post, $params['lookup_key'])
		);

	}


}
