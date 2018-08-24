<?php

namespace WPS\DB;

use WPS\Utils;
use WPS\CPT;

if (!defined('ABSPATH')) {
	exit;
}


if (!class_exists('Collections_Smart')) {

  class Collections_Smart extends \WPS\DB {

    public $table_name;
  	public $version;
  	public $primary_key;


  	public function __construct() {

      global $wpdb;

      $this->table_name         			= WPS_TABLE_NAME_COLLECTIONS_SMART;
      $this->primary_key        			= 'id';
      $this->version            			= '1.0';
      $this->cache_group        			= 'wps_db_collections_smart';

    }


  	public function get_columns() {

      return [
				'id'       						=> '%d',
        'collection_id'       => '%d',
        'post_id'             => '%d',
        'title'               => '%s',
        'handle'              => '%s',
        'body_html'           => '%s',
        'image'               => '%s',
        'rules'               => '%s',
        'disjunctive'         => '%s',
        'sort_order'          => '%s',
        'published_at'        => '%s',
        'updated_at'          => '%s'
      ];

    }


  	public function get_column_defaults() {

			return [
				'id'       						=> 0,
        'collection_id'       => 0,
        'post_id'             => 0,
        'title'               => '',
        'handle'              => '',
        'body_html'           => '',
        'image'               => '',
        'rules'               => '',
        'disjunctive'         => '',
        'sort_order'          => '',
        'published_at'        => date_i18n( 'Y-m-d H:i:s' ),
        'updated_at'          => date_i18n( 'Y-m-d H:i:s' )
      ];

    }


		public function collection_was_deleted($collection) {

			if (Utils::has($collection, 'published_at') && $collection->published_at !== null) {
				return false;

			} else {
				return true;
			}

		}


    /*

    Only responsible for inserting a smart collection into the wps_collections_smart table

    */
    public function insert_smart_collection($smart_collection) {

			$smart_collection = Utils::flatten_collections_image_prop($smart_collection);
			$smart_collection = $this->rename_primary_key($smart_collection, 'collection_id');

			return $this->insert($smart_collection, 'smart_collection');

    }


    /*

    @param Object

    */
    public function update_smart_collection($collection) {
      return $this->update_collection($collection);
    }


    /*

    delete_smart_collection
    @param Object

    */
    public function delete_smart_collection($collection) {
      return $this->delete($collection->id);
    }


    /*

  	Default Smart Collections Query

  	*/
  	public function get_default_query() {

  		global $wpdb;

  		return array(
  			'where' => '',
  			'groupby' => '',
  			'join' => ' INNER JOIN ' . WPS_TABLE_NAME_COLLECTIONS_SMART . ' smart ON ' . $wpdb->posts . '.ID = smart.post_id',
  			'orderby' => '',
  			'distinct' => '',
  			'fields' => 'smart .*',
  			'limits' => ''
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
        rules longtext DEFAULT NULL,
        disjunctive varchar(100) DEFAULT NULL,
        sort_order varchar(100) DEFAULT NULL,
        published_at datetime,
        updated_at datetime,
        PRIMARY KEY  (id)
      ) ENGINE=InnoDB $collate";

  	}


  }

}
