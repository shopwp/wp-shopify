<?php

namespace WPS;

use WPS\DB\Variants;

/*

Class Products
TODO: Move the methods in this class somewhere more contextual. "Products_General" becomes
confusing when we also have the main "Products" DB class.

*/
class Products_General {

  protected static $instantiated = null;
  private $Config;

	/*

	Initialize the class and set its properties.

	*/
	public function __construct($Config) {
		$this->config = $Config;
	}


  /*

  Creates a new class if one hasn't already been created.
  Ensures only one instance is used.

  */
  public static function instance($config) {

    if (is_null(self::$instantiated)) {
      self::$instantiated = new self($config);
    }

    return self::$instantiated;

  }


  /*

	Create image structure

	*/
	public function wps_create_image_model($images) {

		$model = array();

		foreach($images as $key => $image) {
			$model[] = $image['src'];
		}

		return $model;

	}


  /*

	Create product structure
  TODO: Combine with other post structure

	*/
  public function wps_create_product_structure($postData, $postID = '') {

    $images = $this->wps_create_image_model($postData['images']);

    $structure = array(
      'post_title'   						=> esc_html__($postData['title']),
      'post_content' 						=> esc_html__($postData['body_html']),
      'post_status'   					=> 'publish',
      'post_type'     					=> 'wps_products',
      'post_name'								=> $postData['handle'],
      'meta_input'							=> array(
        "wps_product_title" 		=> esc_html__($postData['title']),
        "wps_product_id" 				=> $postData['id'],
        "wps_product_handle" 		=> $postData['handle'],
        "wps_product_images" 		=> $images,
        "wps_product_tags" 			=> $postData['tags'],
        "wps_product_vendor" 		=> $postData['vendor'],
        "wps_product_variants"	=> $postData['variants'],
        "wps_product_type" 			=> $postData['product_type'],
        "wps_product_options" 	=> $postData['options']
      )
    );


    /*

    If a postID was passed, we'll attached it to our
    data structure for updating instead of creating

    */
    if(!empty($postID)) {

      // Setting the ID to perform our find
      $structure['ID'] = $postID;

      // Updating the slugs
      $newSlug = sanitize_title($structure['post_title']);
      $structure['post_name'] = $newSlug;
      $structure['meta_input']['wps_product_handle'] = $newSlug;

    } else {

    }

    return $structure;

  }


  /*

	Update Product

	*/
	public function wps_update_product($newProductData, $postID) {
		wp_insert_post( $this->wps_create_product_structure($newProductData, $postID) );
	}


	/*

	Create Product

	*/
	public function wps_create_product($newProductData) {
		wp_insert_post( $this->wps_create_product_structure($newProductData) );
	}


  /*

  Returns a list of images for a given product based on ID
  TODO: Move to Util? Look through all functions to determine where they should go.

  */
  public static function wps_get_product_images($id = '') {

  	if(isset($id) && $id) {
  		$productId = $id;

  	} else {
  		$productId = get_the_ID();
  	}


    $productData = get_post_meta($productId);


    if (isset($productData['wps_product_images']) && $productData['wps_product_images']) {
      $images = maybe_unserialize( unserialize( $productData['wps_product_images'][0] ));

    } else {
      $images = $productData['wps_collection_image'];
    }

  	return $images;

  }


  /*

  Returns a list of products for a given collection ID

  */
  public static function wps_get_products_by_collection($id = '') {

  	if(isset($id) && $id) {
  		$collectionId = $id;

  	} else {
  		$collectionId = get_the_ID();

  	}

  	return $collectionId;

  	$args = array(
  		'post_type' => 'wps_collections',
  		'meta_key' => 'wps_collection_products'
  	);

  	$collections = get_posts($args);

  	// $productData = get_post_meta($collectionId);

  }


  /*

  WPS API Method: Get Variants

  */
  public function get_variants($product_id) {

    $DB_Variants = new Variants();
    return $DB_Variants->get_product_variants($product_id);

  }


  /*

	Returns a list of variants for a given product based on ID
	TODO: Move to Util? Look through all functions to determine where they should go.
	TODO: Basically same function as above except for the key we're looking for. Combine into one machine.

	*/
	public static function wps_get_product_variants($id = '') {

		if(isset($id) && $id) {
			$productId = $id;

		} else {
			$productId = get_the_ID();
		}

		$productData = get_post_meta($productId);

    if (isset($productData['wps_product_variants']) && $productData['wps_product_variants']) {
      $variants = maybe_unserialize( unserialize( $productData['wps_product_variants'][0] ));

    } else {
      $variants = array();

    }

		return $variants;

	}


  /*

	Returns a list of options for a given product based on ID
	TODO: Move to Util? Look through all functions to determine where they should go.
	TODO: Basically same function as above except for the key we're looking for. Combine into one machine.

	*/
	public static function wps_get_product_options($id = '') {

		if(isset($id) && $id) {
			$productId = $id;

		} else {
			$productId = get_the_ID();
		}

		$productData = get_post_meta($productId);

    if (isset($productData['wps_product_options']) && $productData['wps_product_options']) {
      $options = maybe_unserialize( unserialize( $productData['wps_product_options'][0] ));

    } else {
      $options = array();

    }

		return $options;

	}


  /*

	Returns an array of all product data based on ID
	TODO: Move to Util? Look through all functions to determine where they should go.

	*/
	public static function wps_get_product_data($id = false) {

		if (isset($id) && $id) {
			$productId = $id;

		} else {
			$productId = get_the_ID();

		}


    /*

    Removing nested arrays created by update_post_meta()

    */
    if($productId) {
      $meta = get_post_meta($productId);

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

	Delete Product

	*/
	public function wps_delete_product($postID) {

		if (wp_delete_post( $postID, true)) {
			return true;

		} else {
			return false;
		}

	}


  /*

	Update Product Collections

	*/
	public function wps_update_product_collections($productID, $postID) {

    $Utils = new Utils();
		$WS = new WS($this->config);

    $shopifyCollects = $WS->wps_ws_get_collects_from_product($productID);
		$currentCollectionIDs = get_post_meta($postID, 'wps_product_collections');
		$latestCollectIDs = $Utils->wps_return_product_collections($shopifyCollects->collects);

		$currentCollectionIDs = array_unique($currentCollectionIDs);
		$collectionIDsToBeAdded = array_diff($latestCollectIDs, $currentCollectionIDs);
		$collectionIDsToBeDeleted = array_diff($currentCollectionIDs, $latestCollectIDs);

		// Adds any IDs from Shopify not in the current list of IDs
		$Utils->wps_add_product_data($postID, 'wps_product_collections', $collectionIDsToBeAdded);

		// Deletes any current IDs not in the latest IDs from Shopify
		$Utils->wps_delete_product_data($postID, 'wps_product_collections', $collectionIDsToBeDeleted);

	}


	/*

	Get product by id

	*/
	public function wps_get_product_by_id($id) {
		global $wpdb;
		return $wpdb->get_results( "select * from $wpdb->postmeta where meta_value = $id" );
	}


}
