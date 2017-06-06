<?php

namespace WPS\DB;

use WPS\Utils;
use WPS\DB\Products;

class Tags extends \WPS\DB {

  public $table_name;
	public $version;
	public $primary_key;

  /*

  Construct

  */
	public function __construct() {

    global $wpdb;
    $this->table_name  = $wpdb->prefix . 'wps_tags';
    $this->primary_key = 'tag_id';
    $this->version     = '1.0';

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



  public function construct_tag_model($tag, $product = 0, $cpt_id = 0) {

    $product_id = null;

    if (is_object($product)) {
      if (isset($product->id)) {
        $product_id = $product->id;
      } else {
        $product_id = $product->product_id;
      }
    }

    return array(
      'tag_id' => 0,
      'product_id' => $product_id,
      'post_id' => $cpt_id,
      'tag' => $tag
    );

  }



  /*

  Get single shop info value

  */
	public function insert_tags($product, $cpt_id) {

    $tags = Utils::wps_comma_list_to_array($product->tags);
    $results = array();

    foreach ($tags as $key => $tag) {

      $tagData = $this->construct_tag_model($tag, $product, $cpt_id);
      error_log('TAG PRODUCT $tagData');
      error_log(print_r($tagData, true));

      $results[] = $this->insert($tagData, 'tag');

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

    /*

    In order to handle image creation / deletions, we need to compare what's
    currently in the database with what gets sent back via the
    product/update webhook.

    */
    $currentTagsArray = $this->get_rows('product_id', $product->id);

    // error_log('$tagsFromShopify');
    // error_log(print_r($tagsFromShopify, true));
    //
    // error_log('$currentTagsArray');
    // error_log(print_r($currentTagsArray, true));

    $currentTagsArray = Utils::wps_convert_object_to_array($currentTagsArray);


    foreach ($tagsFromShopify as $key => $newTag) {
      $tagsFromShopifyyNew[] = $this->construct_tag_model($newTag);
    }


    $tagsToAdd = Utils::wps_find_items_to_add($currentTagsArray, $tagsFromShopifyyNew, true, 'tag');

    error_log('$tagsToAdd TAG UPDATE STUFF');
    error_log(print_r($tagsToAdd, true));


    $tagsToDelete = Utils::wps_find_items_to_delete($currentTagsArray, $tagsFromShopifyyNew, true, 'tag');

    error_log('$tagsToDelete TAG UPDATE STUFF');
    error_log(print_r($tagsToDelete, true));


    /*

    Insert

    */
    if (count($tagsToAdd) > 0) {

      foreach ($tagsToAdd as $key => $newTag) {

        $alrightHereWeGo = $this->construct_tag_model($newTag['tag'], $product, $cpt_id);
        $results['created'] = $this->insert($alrightHereWeGo, 'tag');

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


    /*

    Update

    */
    // foreach ($tagsFromShopify as $key => $tag) {
    //   $results['updated'] = $this->update($tag->id, $tag);
    // }



    return $results;



  }


  /*

  update_option

  */
	public function delete_tags($tag) {

  }


  /*

  Get Product Variants

  */
  public function get_product_tags($postID = null) {

  }


  /*

  Creates database table

  */
	public function create_table() {

    global $wpdb;

		$collate = '';

		if ( $wpdb->has_cap('collation') ) {
			$collate = $wpdb->get_charset_collate();
		}

    $query = "CREATE TABLE `{$this->table_name}` (
      `tag_id` bigint(100) unsigned NOT NULL AUTO_INCREMENT,
      `product_id` bigint(100) DEFAULT NULL,
      `post_id` bigint(100) DEFAULT NULL,
      `tag` varchar(255) DEFAULT NULL,
      PRIMARY KEY (`{$this->primary_key}`)
    ) ENGINE=InnoDB DEFAULT CHARSET={$collate};";

    //
    // Create the table if it doesnt exist. Where the magic happens.
    //
    if (!$this->table_exists($this->table_name)) {
      dbDelta($query);
    }

  }

}
