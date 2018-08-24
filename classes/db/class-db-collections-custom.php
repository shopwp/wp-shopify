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
      $this->primary_key        				= 'id';
      $this->version            				= '1.0';
      $this->cache_group        				= 'wps_db_collections_custom';

    }


    /*

    Get Columns

    */
  	public function get_columns() {

      return [
				'id'       						=> '%d',
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
				'id'       						=> 0,
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
			$collection = $this->rename_primary_key($collection, 'collection_id');

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

			if ( !$table_name ) {
				$table_name = $this->table_name;
			}

      $collate = $this->collate();

      return "CREATE TABLE $table_name (
				id bigint(100) unsigned NOT NULL AUTO_INCREMENT,
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
        PRIMARY KEY  (id)
      ) ENGINE=InnoDB $collate";

  	}


  }

}
