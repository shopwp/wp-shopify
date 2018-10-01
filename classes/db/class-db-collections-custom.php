<?php

namespace WPS\DB;

use WPS\Utils;
use WPS\CPT;


if (!defined('ABSPATH')) {
	exit;
}


if (!class_exists('Collections_Custom')) {

  class Collections_Custom extends \WPS\DB {

		public $table_name_suffix;
    public $table_name;
  	public $version;
  	public $primary_key;
		public $lookup_key;
		public $cache_group;
		public $type;


  	public function __construct() {

      $this->table_name_suffix    	= WPS_TABLE_NAME_COLLECTIONS_CUSTOM;
			$this->table_name         		= $this->get_table_name();
			$this->version            		= '1.0';
      $this->primary_key        		= 'id';
      $this->lookup_key        			= WPS_COLLECTIONS_LOOKUP_KEY;
      $this->cache_group        		= 'wps_db_collections_custom';
			$this->type        						= 'collection';

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

    Default Custom Collections Query

    */
    public function get_default_query() {

      global $wpdb;

      return array(
        'where' => '',
        'groupby' => '',
        'join' => ' INNER JOIN ' . $this->table_name . ' custom ON ' . $wpdb->posts . '.ID = custom.post_id',
        'orderby' => '',
        'distinct' => '',
        'fields' => 'custom .*',
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
