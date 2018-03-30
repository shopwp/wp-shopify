<?php

namespace WPS\DB;

use WPS\Utils;
use WPS\DB\Products;
use WPS\Progress_Bar;
use WPS\Config;


// If this file is called directly, abort.
if (!defined('ABSPATH')) {
	exit;
}


/*

Database class for Tags

*/
if (!class_exists('Tags')) {

  class Tags extends \WPS\DB {

    public $table_name;
  	public $version;
  	public $primary_key;


    /*

    Construct

    */
  	public function __construct() {

      global $wpdb;
      $this->table_name         = $wpdb->prefix . 'wps_tags';
      $this->primary_key        = 'tag_id';
      $this->version            = '1.0';
      $this->cache_group        = 'wps_db_tags';

    }


    /*

    Get Columns

    */
  	public function get_columns() {
      return array(
        'tag_id'                    => '%d',
        'product_id'                => '%d',
        'post_id'                   => '%d',
        'tag'                       => '%s'
      );
    }


    /*

    Get Column Defaults

    */
  	public function get_column_defaults() {
      return array(
        'tag_id'                    => 0,
        'product_id'                => 0,
        'post_id'                   => 0,
        'tag'                       => '',
      );
    }


    /*

  	Construct Tag Model

  	*/
    public function construct_tag_model($tag, $product = 0, $cpt_id = 0, $tag_id = 0) {

      $product_id = null;

      if (is_object($product)) {

        if (isset($product->id)) {
          $product_id = $product->id;
        } else {
          $product_id = $product->product_id;
        }

      }

      return array(
        'tag_id' => $tag_id,
        'product_id' => $product_id,
        'post_id' => $cpt_id,
        'tag' => $tag
      );

    }


    /*

    $tags parameter represents an array of arrays modeled from the above 'construct_tag_model'

    */
    public function construct_only_tag_names($tags) {

      return array_map(function($tagObj) {
        return $tagObj->tag;
      }, $tags);

    }


    /*

    Get single shop info value

    */
  	public function insert_tags($product, $cpt_id) {

      $results = [];

      if (isset($product->tags) && $product->tags) {

        $tags = Utils::wps_comma_list_to_array($product->tags);

        foreach ($tags as $key => $tag) {

          if (!Utils::isStillSyncing()) {
            wp_die();
            break;
          }

          $tagData = $this->construct_tag_model($tag, $product, $cpt_id);
          $results[] = $this->insert($tagData, 'tag');

        }

      }

      return $results;

    }


    /*

    update_option

    */
  	public function update_tags($product, $cpt_id) {

      $results = array();
      $tagsFromShopifyyNew = array();
      $tagsFromShopify = Utils::wps_comma_list_to_array($product->tags);

      $newProductID = Utils::wps_find_product_id($product);

      /*

      In order to handle image creation / deletions, we need to compare what's
      currently in the database with what gets sent back via the
      product/update webhook.

      */
      $currentTagsArray = $this->get_rows('product_id', $newProductID);

      $currentTagsArray = Utils::wps_convert_object_to_array($currentTagsArray);


      foreach ($tagsFromShopify as $key => $newTag) {
        $tagsFromShopifyyNew[] = $this->construct_tag_model($newTag);
      }

      $tagsToAdd = Utils::wps_find_items_to_add($currentTagsArray, $tagsFromShopifyyNew, true, 'tag');
      $tagsToDelete = Utils::wps_find_items_to_delete($currentTagsArray, $tagsFromShopifyyNew, true, 'tag');

      /*

      Insert

      */
      if (count($tagsToAdd) > 0) {

        foreach ($tagsToAdd as $key => $newTag) {

          $tag = $this->construct_tag_model($newTag['tag'], $product, $cpt_id);
          $results['created'] = $this->insert($tag, 'tag');

        }

      }


      /*

      Delete

      */
      if (count($tagsToDelete) > 0) {

        foreach ($tagsToDelete as $key => $oldTag) {
          $results['deleted'] = $this->delete($oldTag['tag_id']);
        }

      }


      return $results;

    }


    /*

    Get Product Tags

    */
    public function get_product_tags($postID = null) {

      global $wpdb;

      if ($postID === null) {
        $postID = get_the_ID();
      }

      if (get_transient('wps_product_single_tags_' . $postID)) {
        $results = get_transient('wps_product_single_tags_' . $postID);

      } else {

        $DB_Products = new Products();
        $table_products = $DB_Products->get_table_name();

        $query = "SELECT tags.* FROM $table_products as products INNER JOIN $this->table_name as tags ON products.product_id = tags.product_id WHERE products.post_id = %d";

        $results = $wpdb->get_results( $wpdb->prepare($query, $postID) );

        set_transient('wps_product_single_tags_' . $postID, $results);

      }

      return $results;

    }













    /*

    Creates a table query string

    */
    public function create_table_query() {

      global $wpdb;

      $collate = '';

      if ( $wpdb->has_cap('collation') ) {
        $collate = $wpdb->get_charset_collate();
      }

      return "CREATE TABLE `{$this->table_name}` (
        `tag_id` bigint(100) unsigned NOT NULL AUTO_INCREMENT,
        `product_id` bigint(100) DEFAULT NULL,
        `post_id` bigint(100) DEFAULT NULL,
        `tag` varchar(255) DEFAULT NULL,
        PRIMARY KEY  (`{$this->primary_key}`)
      ) ENGINE=InnoDB $collate";

  	}


    /*

    Creates database table

    */
  	public function create_table() {

      require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

      if ( !$this->table_exists($this->table_name) ) {
        dbDelta( $this->create_table_query() );
      }

    }

  }

}
