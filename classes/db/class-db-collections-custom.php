<?php

namespace WPS\DB;

use WPS\Utils;
use WPS\CPT;


if (!defined('ABSPATH')) {
	exit;
}


if (!class_exists('Collections_Custom')) {

  class Collections_Custom extends \WPS\DB\Collections {

    public $table_name;
  	public $version;
  	public $primary_key;


  	public function __construct() {

      global $wpdb;

      $this->table_name         				= WPS_TABLE_NAME_COLLECTIONS_CUSTOM;
      $this->primary_key        				= 'collection_id';
      $this->version            				= '1.0';
      $this->cache_group        				= 'wps_db_collections_custom';

    }


    /*

    Get Columns

    */
  	public function get_columns() {

      return [
        'collection_id'       => '%d',
        'post_id'             => '%d',
        'title'               => '%s',
        'handle'              => '%s',
        'body_html'           => '%s',
        'image'               => '%s',
        'metafield'           => '%s',
        'published'           => '%s',
        'published_scope'     => '%s',
        'sort_order'          => '%s',
        'published_at'        => '%s',
        'updated_at'          => '%s'
      ];

    }


    /*

    Get Column Defaults

    */
  	public function get_column_defaults() {

      return [
        'collection_id'       => 0,
        'post_id'             => 0,
        'title'               => '',
        'handle'              => '',
        'body_html'           => '',
        'image'               => '',
        'metafield'           => '',
        'published'           => '',
        'published_scope'     => '',
        'sort_order'          => '',
        'published_at'        => date_i18n( 'Y-m-d H:i:s' ),
        'updated_at'          => date_i18n( 'Y-m-d H:i:s' )
      ];

    }



    /*

    Inserts Single Collection + associated Collects
    @param object

    */
  	public function insert_custom_collection($collection) {

			$collection = Utils::flatten_collections_image_prop($collection);
			$collection = $this->rename_primary_key($collection);

      return $this->insert($collection, 'custom_collection');

    }


    /*

    update_custom_collection
    @param Object

    */
    public function update_custom_collection($collection) {
      return $this->update_collection($collection);
    }


    /*

    delete_custom_collection
    @param Object

    */
    public function delete_custom_collection($collection) {
      return $this->delete($collection->id);
    }


    /*

    Rename primary key

    */
    public function rename_primary_key($collection) {

      $collectionCopy = $collection;
      $collectionCopy->collection_id = $collectionCopy->id;
      unset($collectionCopy->id);

      return $collectionCopy;

    }


    /*

    Default Custom Collections Query

    */
    public function get_default_query() {

      global $wpdb;

      return array(
        'where' => '',
        'groupby' => '',
        'join' => ' INNER JOIN ' . WPS_TABLE_NAME_COLLECTIONS_CUSTOM . ' custom ON ' . $wpdb->posts . '.ID = custom.post_id',
        'orderby' => '',
        'distinct' => '',
        'fields' => 'custom .*',
        'limits' => ''
      );

    }


		/*

		Assigns a post id to the product data

		*/
		public function assign_post_id_to_custom_collection($post_id, $collection_id) {

			global $wpdb;

			return $wpdb->update(
				$this->table_name,
				['post_id' => $post_id],
				['collection_id' => $collection_id],
				['%d'],
				['%d']
			);

		}


    /*

    Creates a table query string

    */
    public function create_table_query($table_name = false) {

      global $wpdb;

			if (!$table_name) {
				$table_name = $this->table_name;
			}

      $collate = '';

      if ($wpdb->has_cap('collation')) {
        $collate = $wpdb->get_charset_collate();
      }

      return "CREATE TABLE $table_name (
        collection_id bigint(100) unsigned NOT NULL DEFAULT 0,
        post_id bigint(100) unsigned DEFAULT NULL,
        title varchar(255) DEFAULT NULL,
        handle varchar(255) DEFAULT NULL,
        body_html longtext DEFAULT NULL,
        image longtext DEFAULT NULL,
        metafield longtext DEFAULT NULL,
        published varchar(50) DEFAULT NULL,
        published_scope varchar(100) DEFAULT NULL,
        sort_order varchar(100) DEFAULT NULL,
        published_at datetime,
        updated_at datetime,
        PRIMARY KEY  (collection_id)
      ) ENGINE=InnoDB $collate";

  	}


		/*

		Migrate insert into query

		*/
		public function migration_insert_into_query() {

			return $this->query('INSERT INTO ' . $this->table_name . WPS_TABLE_MIGRATION_SUFFIX . '(`collection_id`, `post_id`, `title`, `handle`, `body_html`, `image`, `metafield`, `published`, `published_scope`, `sort_order`, `published_at`, `updated_at`) SELECT `collection_id`, `post_id`, `title`, `handle`, `body_html`, `image`, `metafield`, `published`, `published_scope`, `sort_order`, `published_at`, `updated_at` FROM ' . $this->table_name);

		}


    /*

    Creates database table

    */
  	public function create_table() {

      require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

      if (!$this->table_exists($this->table_name)) {
        dbDelta( $this->create_table_query($this->table_name) );
				set_transient('wp_shopify_table_exists_' . $this->table_name, 1);
      }

    }


  }

}
