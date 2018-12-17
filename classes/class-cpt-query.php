<?php

namespace WPS;

use WPS\CPT;
use WPS\Utils;
use WPS\Utils\Posts as Utils_Posts;
use WPS\Utils\Data as Utils_Data;


if (!defined('ABSPATH')) {
	exit;
}


class CPT_Query {

	public $DB;
	public $DB_Settings_General;
	public $DB_Settings_Connection;
	public $CPT_Meta;


	/*

	Initialize the class and set its properties.

	*/
	public function __construct($DB_Settings_General, $DB_Settings_Connection, $CPT_Meta) {

		$this->DB 											= $DB_Settings_General;
		$this->DB_Settings_General 			= $DB_Settings_General;
		$this->DB_Settings_Connection 	= $DB_Settings_Connection;
		$this->CPT_Meta 								= $CPT_Meta;

	}


	/*

	Construct posts col names as string

	*/
	public function posts_col_names() {
		return "(post_author, post_date, post_date_gmt, post_content, post_title, post_excerpt, post_status, comment_status, ping_status, post_password, post_name, to_ping, pinged, post_modified, post_modified_gmt, post_content_filtered, post_parent, guid, menu_order, post_type, post_mime_type, comment_count)";
	}


	/*

	If $post is not a true WordPress $post object, then we know
	the items are NEW and coming straight from Shopify. We can
	return 0 for now as the post_id will be changed during
	a seperate step in the syncing process.

	*/
	public function return_post_id($post) {

		if (Utils::has($post, 'post_id')) {
			$post_id = $post->post_id;

		} else {
			$post_id = 0;
		}

		return intval($post_id);

	}


	public function return_author_id() {
		return CPT::return_author_id();
	}

	public function return_post_date() {
		return sanitize_text_field( date_i18n('Y-m-d H:i:s') );
	}

	public function return_post_date_gmt() {
		return sanitize_text_field( date_i18n('Y-m-d H:i:s') );
	}

	public function return_post_content($post) {
		return wp_kses_post($post->body_html);
	}

	public function return_post_title($post) {
		return sanitize_text_field($post->title);
	}

	public function return_post_excerpt() {
		return sanitize_text_field('');
	}

	public function return_post_status() {
		return sanitize_text_field('publish');
	}

	public function return_comment_status() {
		return sanitize_text_field('open');
	}

	public function return_ping_status() {
		return sanitize_text_field('open');
	}

	public function return_post_password() {
		return sanitize_text_field('');
	}

	public function return_post_name($post) {
		return sanitize_title($post->handle);
	}

	public function return_to_ping() {
		return sanitize_text_field('');
	}

	public function return_pinged() {
		return sanitize_text_field('');
	}

	public function return_post_modified() {
		return sanitize_text_field( date_i18n('Y-m-d H:i:s') );
	}

	public function return_post_modified_gmt() {
		return sanitize_text_field( date_i18n('Y-m-d H:i:s') );
	}

	public function return_post_content_filtered() {
		return sanitize_text_field('');
	}

	public function return_post_parent() {
		return intval(0);
	}

	public function return_guid($posts_page_url) {
		return esc_url_raw($posts_page_url);
	}

	public function return_menu_order() {
		return intval(0);
	}

	public function return_post_type($post_type) {
		return sanitize_text_field($post_type);
	}

	public function return_post_mime_type() {
		return sanitize_text_field('');
	}

	public function return_comment_count() {
		return intval(0);
	}


	public function build_query_from_values($carry, $post, $post_type) {

		if ( !empty($carry) ) {
			$carry .=',';
		}

		$carry .= "(" .
			"'" . esc_sql( $this->return_author_id() ) . "', " .
			"'" . esc_sql( $this->return_post_date() ) . "', " .
			"'" . esc_sql( $this->return_post_date_gmt() ) . "', " .
			"'" . esc_sql( $this->return_post_content($post) ) . "', " .
			"'" . esc_sql( $this->return_post_title($post) ) . "', " .
			"'" . esc_sql( $this->return_post_excerpt($post) ) . "', " .
			"'" . esc_sql( $this->return_post_status() ) . "', " .
			"'" . esc_sql( $this->return_comment_status() ) . "', " .
			"'" . esc_sql( $this->return_ping_status() ) . "', " .
			"'" . esc_sql( $this->return_post_password() ) . "', " .
			"'" . esc_sql( $this->return_post_name($post) ) . "', " .
			"'" . esc_sql( $this->return_to_ping() ) . "', " .
			"'" . esc_sql( $this->return_pinged() ) . "', " .
			"'" . esc_sql( $this->return_post_modified() ) . "', " .
			"'" . esc_sql( $this->return_post_modified_gmt() ) . "', " .
			"'" . esc_sql( $this->return_post_content_filtered() ) . "', " .
			"'" . esc_sql( $this->return_post_parent() ) . "', " .
			"'" . esc_sql( $this->return_guid( $this->construct_post_guid($post, $post_type) ) ) . "', " .
			"'" . esc_sql( $this->return_menu_order() ) . "', " .
			"'" . esc_sql( $this->return_post_type($post_type) ) . "', " .
			"'" . esc_sql( $this->return_post_mime_type() ) . "', " .
			"'" . esc_sql( $this->return_comment_count() ) . "'" .
		")";

		return $carry;

	}


	/*

	Construct posts col values as string

	$post_type = 'wps_products' or 'wps_collections'

	*/
	public function construct_posts_col_values($posts, $post_type) {

		if ( !is_array($posts) ) {
			$posts = [$posts];
		}

		return array_reduce($posts, function ($carry, $item) use ($post_type) {
		  return $this->build_query_from_values($carry, $item, $post_type);
		});


	}


	/*

	Construct post case columns query

	*/
	public function construct_post_case_columns_query($post_values_to_update) {

		// Columns we will be updating
		$columns = [
			'post_date' 				=> '`post_date` = CASE ',
			'post_date_gmt' 		=> '`post_date_gmt` = CASE ',
			'post_content' 			=> '`post_content` = CASE ',
			'post_title' 				=> '`post_title` = CASE ',
			'post_name' 				=> '`post_name` = CASE ',
			'post_modified' 		=> '`post_modified` = CASE ',
			'post_modified_gmt' => '`post_modified_gmt` = CASE ',
			'guid' 							=> '`guid` = CASE '
		];


		// Build up each columns CASE statement
		foreach($post_values_to_update as $id => $values) {

			$post_date = esc_sql( sanitize_text_field( date('Y-m-d H:i:s', strtotime($values['post_date'])) ) );
			$post_date_gmt = esc_sql( sanitize_text_field( date('Y-m-d H:i:s', strtotime($values['post_date_gmt'])) ) );
			$post_content = esc_sql( wp_kses_post($values['post_content']) );
			$post_title = esc_sql( sanitize_text_field($values['post_title']) );
			$post_name = esc_sql( sanitize_title($values['post_name']) );
			$post_modified = esc_sql( sanitize_text_field( date('Y-m-d H:i:s', strtotime($values['post_modified'])) ) );
			$post_modified_gmt = esc_sql( sanitize_text_field( date('Y-m-d H:i:s', strtotime($values['post_modified_gmt'])) ) );
			$post_guid = esc_sql( esc_url_raw($values['guid']) );

			$columns['post_date'] .= "WHEN `id`='" . esc_sql( intval($id) ) . "' THEN '" . $post_date . "' ";
			$columns['post_date_gmt'] .= "WHEN `id`='" . esc_sql( intval($id) ) . "' THEN '" . $post_date_gmt . "' ";
			$columns['post_content'] .= "WHEN `id`='" . esc_sql( intval($id) ) . "' THEN '" . $post_content . "' ";
			$columns['post_title'] .= "WHEN `id`='" . esc_sql( intval($id) ) . "' THEN '" . $post_title . "' ";
			$columns['post_name'] .= "WHEN `id`='" . esc_sql( intval($id) ) . "' THEN '" . $post_name . "' ";
			$columns['post_modified'] .= "WHEN `id`='" . esc_sql( intval($id) ) . "' THEN '" . $post_modified . "' ";
			$columns['post_modified_gmt'] .= "WHEN `id`='" . esc_sql( intval($id) ) . "' THEN '" . $post_modified_gmt . "' ";
			$columns['guid'] .= "WHEN `id`='" . esc_sql( intval($id) ) . "' THEN '" . $post_guid . "' ";

		}

		return $columns;

	}


	/*

	Sets the default case values

	Adds a default case, we use whatever value was already in the field

	*/
	public function set_default_case_values($columns) {

		foreach($columns as $column_name => $query_part) {
			$columns[$column_name] .= " ELSE `$column_name` END ";
		}

		return $columns;

	}


	/*

	Builds the WHERE clause of the query

	Since we keyed our update_values off the database keys, this is pretty easy

	*/
	public function set_where_clause($post_values_to_update) {
		return " WHERE `id`='" . implode("' OR `id`='", array_keys($post_values_to_update)) . "'";
	}


	/*

	Start of the query

	*/
	public function start_update_query() {

		global $wpdb;

		return "UPDATE " . $wpdb->prefix . WPS_TABLE_NAME_WP_POSTS . " SET ";

	}


	/*

	Join the statements with commas, then run the query

	*/
	public function join_query_with_commas($update_query, $columns, $where_clause) {

		$update_query .= implode(', ',$columns) . $where_clause;

		return $update_query;

	}


	/*

	Construct post GUID

	*/
	public function construct_post_guid($post, $type) {

		if ($type === WPS_COLLECTIONS_POST_TYPE_SLUG) {
			$slug = $this->DB_Settings_General->collections_slug();

		} else {
			$slug = $this->DB_Settings_General->products_slug();
		}

		return Utils::get_site_url() . '/' . $slug . '/' . $post->handle;

	}


	/*

	Only posts with maching handle / post_name

	*/
	public function only_posts_with_matching_handle($new_items, $existing_posts) {

		return array_filter($new_items, function($item, $index) use($existing_posts) {

			if (CPT::post_exists_by_handle($existing_posts, $item->handle)) {
				return true;
			}

		}, ARRAY_FILTER_USE_BOTH);

	}


	/*

	Find posts for update

	*/
	public function find_posts_to_update($new_items, $post_type) {

		if ( is_object($new_items) ) {
			$new_items = [$new_items];
		}

		$existing_posts = CPT::get_all_posts_compressed($post_type);
		$matching_items = $this->only_posts_with_matching_handle($new_items, $existing_posts);

		return $this->add_post_id_before_update($matching_items, $existing_posts);


	}


	/*

	Format posts for update

	*/
	public function format_posts_for_update($shopify_posts_to_update, $post_type) {

		$results = [];

		foreach ($shopify_posts_to_update as $shopify_post) {

			$results[$shopify_post->post_id] = [
				'post_date' 				=> $shopify_post->published_at,
				'post_date_gmt' 		=> $shopify_post->published_at,
				'post_content' 			=> $shopify_post->body_html,
				'post_title' 				=> $shopify_post->title,
				'post_name' 				=> $shopify_post->handle,
				'post_modified' 		=> $shopify_post->updated_at,
				'post_modified_gmt' => $shopify_post->updated_at,
				'guid' 							=> $this->construct_post_guid($shopify_post, $post_type)
			];

		}

		return $results;

	}


	/*

	Find posts for insert

	*/
	public function find_posts_to_insert($items_from_shopify, $existing_posts) {

		if ( is_object($items_from_shopify) ) {
			$items_from_shopify = [$items_from_shopify];
		}

		if (empty($existing_posts) || !$existing_posts) {
			return $items_from_shopify;
		}

		return array_filter($items_from_shopify, function($item, $index) use($existing_posts) {

			return !CPT::post_exists_by_handle($existing_posts, $item->handle);

		}, ARRAY_FILTER_USE_BOTH);

	}


	/*

	Add post id before updates

	*/
	public function add_post_id_before_update($shopify_items, $existing_posts) {

		return array_map( function($shopify_item) use($existing_posts) {

			foreach ($existing_posts as $existing_post) {

				if ($existing_post['post_name'] === $shopify_item->handle) {
					$shopify_item->post_id = $existing_post['ID'];
				}

			}

			return $shopify_item;

		}, $shopify_items);

	}


	/*

	Construct posts update query

	*/
	public function construct_posts_update_query($post_values_to_update) {

		// If we didn't find any posts to update , return false and don't perform the query.
		if (empty($post_values_to_update)) {
			return false;
		}

		global $wpdb;

		// Start of the query
		$update_query = $this->start_update_query();

		$columns = $this->construct_post_case_columns_query($post_values_to_update);
		$columns = $this->set_default_case_values($columns);
		$where = $this->set_where_clause($post_values_to_update);

		$update_query = $this->join_query_with_commas($update_query, $columns, $where);

		return $update_query;

	}


	/*

	Query for inseting posts

	*/
	public function posts_insert_query($posts_column_names, $posts_column_values_insert) {

		global $wpdb;

		return "INSERT INTO " . $wpdb->prefix . WPS_TABLE_NAME_WP_POSTS . $posts_column_names . " VALUES " . $posts_column_values_insert . ";";

	}


	/*

	Construct posts insert query

	*/
	public function construct_posts_insert_query($items, $post_type) {

		if ( empty($items) ) {
			return false;
		}

		// Should return an array of Shopify items
		$items_to_insert = $this->find_posts_to_insert($items, CPT::get_all_posts_compressed($post_type) );

		// If we didn't find any posts to insert, return false and don't perform the query.
		if ( empty($items_to_insert) ) {
			return false;
		}

		$posts_column_values_insert = $this->construct_posts_col_values($items_to_insert, $post_type);

		return $this->posts_insert_query( $this->posts_col_names(), $posts_column_values_insert);

	}


	/*

	Use the identity operator (===) to check for errors (e.g., false === $result),
	and whether any rows were affected (e.g., 0 === $result).

	*/
	public function query($query, $type) {

		if ($query) {

			global $wpdb;

			$insertion_result = $wpdb->query($query);

			return $this->DB_Settings_General->sanitize_db_response($insertion_result, 'Failed to execute query for ' . $type, 'query');

		}

	}


	/*

	Checks if inserting only

	*/
	public function inserting_only($type) {
		return !CPT::posts_exist($type);
	}


	/*

	Checks if updating only

	*/
	public function updating_only($post_type, $total_new_items) {
		return CPT::num_of_posts($post_type) === $total_new_items;
	}


	/*

	Wrapper for inserting Shopify items as posts

	*/
	public function insert_posts($items, $post_type) {

		$query = $this->construct_posts_insert_query($items, $post_type);

		if ($query) {
			return $this->DB->query($query);
		}

	}


	/*

	Wrapper for updating Shopify items as posts

	*/
	public function update_posts($posts_to_update, $post_type) {

		$query = $this->construct_posts_update_query( $this->format_posts_for_update($posts_to_update, $post_type) );

		if ($query) {
			return $this->DB->query($query);
		}

	}


	/*

	Process post relationships

	*/
	public function process_post_relationship($db_class, $post, $params) {

		$result = $this->CPT_Meta->update_posts_relationship($post, $db_class, $params);

		if ( Utils_Posts::is_modify_posts_error($result) ) {

			return Utils::wp_warning([
				'message_lookup' 	=> 'failed_to_set_post_id_custom_table',
				'message_aux'			=> $post['post_id'] . ' to the custom <b>' . $db_class . '</b> table. The ' . $params['lookup_key'] . ' affected is: ' . $post[$params['lookup_key']],
				'call_method' 		=> __METHOD__,
				'call_line' 			=> __LINE__
			]);

		}

		return $result;

	}


	/*

	Modifies post relationships

	*/
	public function modify_posts_relationship($post, $params) {

		return array_map(function($relationship) use($params, $post) {
			return $this->process_post_relationship($relationship, $post, $params);
		}, $params['relationships']);

	}


	/*

	General modify posts relationships

	*/
	public function modify_posts_relationships($params) {

		$results = array_map(function($post) use($params) {

			return [
				'custom'			=> $this->modify_posts_relationship($post, $params),
				'post_meta'		=> $this->CPT_Meta->update_posts_meta_relationship($post, $params)
			];

		}, $params['posts']);

		return Utils::flatten_array($results);

	}


	/*

	General modify posts

	*/
	public function modify_posts($params) {

		// Inserting only ...
		if ( $this->inserting_only($params['post_type']) ) {
			return $this->insert_posts($params['items'], $params['post_type']);
		}

		// Updating only ...
		if ( $this->updating_only($params['post_type'], $params['totals']) ) {

			return $this->update_posts(
				$this->find_posts_to_update($params['items'], $params['post_type']),
				$params['post_type']
			);

		}

		// Inserting and Updating ...
		$result_insert = $this->insert_posts($params['items'], $params['post_type']);

		$result_update = $this->update_posts(
			$this->find_posts_to_update($params['items'], $params['post_type']),
			$params['post_type']
		);

		if (is_wp_error($result_insert)) {
			return $result_insert;
		}

		if (is_wp_error($result_update)) {
			return $result_update;
		}

		return true;

	}


}
