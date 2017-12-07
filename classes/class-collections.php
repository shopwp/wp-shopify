<?php

namespace WPS;

use WPS\Messages;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
	exit;
}

/*

Class Collections

*/
class Collections {

  protected static $instantiated = null;
  private $Config;
  private $messages;

	/*

	Initialize the class and set its properties.

	*/
	public function __construct($Config) {
		$this->config = $Config;
    $this->connection = $this->config->wps_get_settings_connection();
    $this->messages = new Messages();
	}

  /*

	Creates a new class if one hasn't already been created.
	Ensures only one instance is used.

	*/
	public static function instance() {

		if (is_null(self::$instantiated)) {
			self::$instantiated = new self();
		}

		return self::$instantiated;

	}


  /*

  Used to check the type of collection
  - Predicate Function (returns boolean)

  */
  public function wps_is_smart_collection($collection) {

    if (property_exists($collection, "rules") && isset($collection->rules)) {
      return true;

    } else {
      return false;

    }

  }


  /*

	Returns an array of all product data based on ID
	TODO: Move to Util? Look through all functions to determine where they should go.

	*/
	public static function wps_get_collection_data($id = false) {

		if(isset($id) && $id) {
			$collectionId = $id;

		} else {
			$collectionId = get_the_ID();

		}

    //
    // Removing nested arrays created by update_post_meta()
    //
    if($collectionId) {
      $meta = get_post_meta($collectionId);

      foreach ($meta as $meta_key => $meta_val) {
        $meta[$meta_key] = array_shift($meta_val);
      }

      foreach ($meta as $meta_key => $meta_val) {
        if(is_serialized($meta_val)) {
          $meta[$meta_key] = unserialize($meta_val);
        }
      }

      return $meta;

    } else {
      return false;

    }

	}


  /*

  Inserting collections into database

  */
  public function wps_insert_collections() {

    Utils::valid_backend_nonce($_POST['nonce']) ?: wp_send_json_error($this->messages->message_nonce_invalid . ' (Error code: #1066a)');

    $results = [];
    $results['added'] = [];
    $results['updated'] = [];

    $existingCollections = [];
    $newCollections = $_POST['collections'];

    $args = array(
     'posts_per_page'   => -1,
     'post_type'        => 'wps_collections',
     'post_status'      => 'publish'
    );

    $posts = get_posts($args);

    foreach($posts as $post) {
      $existingCollections[$post->ID] = $post->ID;
    }

    foreach($newCollections as $key => $collection) {

     if(!in_array($collection['collectionId'], $existingCollections)) {

       $newCollectionModel = array(
         'post_title'    => array_key_exists('collectionTitle', $collection) ? $collection['collectionTitle'] : '',
         'post_content'  => array_key_exists('collectionDescription', $collection) ? $collection['collectionDescription'] : '',
         'post_status'   => 'publish',
         'post_type'     => 'wps_collections',
         'post_name'		 => array_key_exists('collectionHandle', $collection) ? $collection['collectionHandle'] : '',
         'meta_input'		 => array(
           "wps_collection_id" => array_key_exists('collectionId', $collection) ? $collection['collectionId'] : ''
         )
       );

       // Insert the post into the database
       $postID = wp_insert_post($newCollectionModel);

       $results['added'][] = $collection['collectionTitle'];

     } else {

       // TODO: do something here to notify user of duplicates
       // TODO: only update post if content has changed

       $existingPostId = array_search($collection['collectionId'], $existingCollections);

       $existingProductModel = array(
         'ID'                           => $existingPostId,
         'post_title'                   => array_key_exists('collectionTitle', $collection) ? $collection['collectionTitle'] : '',
         'post_content'                 => array_key_exists('collectionDescription', $collection) ? $collection['collectionDescription'] : '',
         'post_name'		                => array_key_exists('collectionHandle', $collection) ? $collection['collectionHandle'] : '',
         'meta_input'		                => array(
           "wps_collection_id"          => array_key_exists('collectionId', $collection) ? $collection['collectionId'] : ''
         )
       );

       // Update the post into the database
       $postID = wp_update_post($existingProductModel);

       $results['updated'][] = $collection['collectionTitle'];

     }

    }

    wp_send_json_success($results);

  }

}
