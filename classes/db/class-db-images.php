<?php

namespace WPS\DB;

use WPS\Utils;


if (!defined('ABSPATH')) {
	exit;
}


if (!class_exists('Images')) {

  class Images extends \WPS\DB {

    public $table_name;
  	public $version;
  	public $primary_key;


  	public function __construct() {

      global $wpdb;

      $this->table_name         			= WPS_TABLE_NAME_IMAGES;
      $this->primary_key        			= 'id';
      $this->version            			= '1.0';
      $this->cache_group        			= 'wps_db_images';

    }


  	public function get_columns() {

			return [
        'id'                   => '%d',
				'image_id'             => '%d',
        'product_id'           => '%d',
        'variant_ids'          => '%s',
        'src'                  => '%s',
        'alt'                  => '%s',
        'position'             => '%d',
        'created_at'           => '%s',
        'updated_at'           => '%s'
      ];

    }


  	public function get_column_defaults() {

      return [
        'id'                   => 0,
				'image_id'             => 0,
        'product_id'           => '',
        'variant_ids'          => '',
        'src'                  => '',
        'alt'                  => '',
        'position'             => '',
        'created_at'           => date_i18n( 'Y-m-d H:i:s' ),
        'updated_at'           => date_i18n( 'Y-m-d H:i:s' )
      ];

    }


    /*

    Insert images
    TODO: Create a map function for insert_product instead of nested loops

    */
  	public function insert_images($products) {

      $results = [];
			$products = Utils::wrap_in_array($products);

      foreach ($products as $key => $product) {

        if (isset($product->images) && $product->images) {

          foreach ($product->images as $key => $image) {
						$results[] = $this->insert( $this->rename_primary_key($image, 'image_id'), 'image');

          }

        }

      }

      return $results;

    }


    /*

    Insert Images

    */
    public function insert_image($product) {

      $results = [];
			$product = Utils::convert_array_to_object($product);

      if (isset($product->images) && $product->images) {

        foreach ($product->images as $key => $image) {

					$result = $this->insert( $this->rename_primary_key($image, 'image_id'), 'image');

					if (is_wp_error($result)) {
						return $result;
					}

					$results[] = $result;

        }

      }

      return $results;

    }


    /*

    Update Image

		In order to handle image creation / deletions, we need to compare what's
		currently in the database with what gets sent back via the
		product/update webhook.

    */
  	public function update_images_from_product($product) {

      $results = [];
      $imagesFromShopify = $product->images;

      $currentImagesArray = $this->get_rows('product_id', $product->id);

      $imagesToAdd = Utils::wps_find_items_to_add($currentImagesArray, $imagesFromShopify, true);
      $imagesToDelete = Utils::wps_find_items_to_delete($currentImagesArray, $imagesFromShopify, true);

      $imagesToAdd = Utils::convert_object_to_array($imagesToAdd);
      $imagesToDelete = Utils::convert_object_to_array($imagesToDelete);


      /*

      Insert

      */
      if (count($imagesToAdd) > 0) {

        foreach ($imagesToAdd as $key => $new_image) {
          $results['created'] = $this->insert( $this->rename_primary_key($new_image, 'image_id'), 'image');
        }

      }


      /*

      Delete

      */
      if (count($imagesToDelete) > 0) {

        foreach ($imagesToDelete as $key => $oldImage) {

					$oldImage = Utils::convert_object_to_array($oldImage);

					if (isset($oldImage['image_id'])) {
						$results['deleted'] = $this->delete($oldImage['image_id']);
					}

        }

      }


      /*

      Update

      */
      foreach ($imagesFromShopify as $key => $image) {
        $results['updated'] = $this->update($image->image_id, $image);
      }

      return $results;

    }












    public static function get_variants_from_image($image) {

			if (is_array($image)) {
				$image = Utils::convert_array_to_object($image);
			}

      if (Utils::has($image, 'variant_ids')) {

        $variantIDs = maybe_unserialize($image->variant_ids);

        if (!empty($variantIDs)) {
          $variantIDs = implode(', ', $variantIDs);

        } else {
          $variantIDs = '';
        }

      } else {
        $variantIDs = '';
      }

      return $variantIDs;

    }


    /*

    TODO: Rethink ... redundant
    Currently used within imgs.partials/products/single/imgs.php

    */
    public static function get_image_details_from_image($image, $product) {

			$result = new \stdClass;

      if (empty($image->alt)) {
        $alt = $product->details->title;

      } else {
        $alt = $image->alt;
      }

      if (empty($image->src)) {
        $src = WPS_PLUGIN_URL . 'public/imgs/placeholder.png';

      } else {
        $src = $image->src;
      }

			$result->src = $src;
			$result->alt = $alt;

      return $result;

    }


    /*

    Gets Image details (alt and src) by product object
    Param: $product Object

    */
    public static function get_image_details_from_product($product) {

			$data = new \stdClass;

      // If an object is passed ...
      if (is_object($product)) {

        if (empty($product->feat_image)) {

          $alt = $product->title;
          $src = WPS_PLUGIN_URL . 'public/imgs/placeholder.png';

        } else {
          $src = $product->feat_image[0]->src;

          if (empty($product->feat_image[0]->alt)) {
            $alt = $product->title;

          } else {
            $alt = $product->feat_image[0]->alt;
          }

        }

				$data->src = $src;
				$data->alt = $alt;

      } else {

				$data->src = '';
				$data->alt = '';

      }

			return $data;

    }


    /*

    Gets Image details (alt and src) by product object
    Param: $product Object

    */
    public static function get_image_details_from_collection($collection) {

			$data = new \stdClass;

      if (empty($collection->image)) {
        $src = WPS_PLUGIN_URL . 'public/imgs/placeholder.png';

      } else {
        $src = $collection->image;

      }

			if (isset($collection->title)) {
      	$alt = $collection->title;

      } else {
        $alt = '';

      }

			$data->src = $src;
			$data->alt = $alt;

      return $data;

    }


		/*

		Get Single Product Images
		Without: Images, variants

		*/
		public function get_images_from_post_id($postID = null) {

			global $wpdb;

			if ($postID === null) {
				$postID = get_the_ID();
			}

			if (get_transient('wps_product_single_images_' . $postID)) {
				$results = get_transient('wps_product_single_images_' . $postID);

			} else {

				$table_products = WPS_TABLE_NAME_PRODUCTS;

				$query = "SELECT images.* FROM " . $table_products . " AS products INNER JOIN " . $this->table_name . " AS images ON images.product_id = products.product_id WHERE products.post_id = %d";

				$results = $wpdb->get_results($wpdb->prepare($query, $postID));

				set_transient('wps_product_single_images_' . $postID, $results);

			}

			return $results;

		}


		/*

		Delete Images from product ID

		*/
		public function delete_images_from_product_id($product_id) {
			return $this->delete_rows('product_id', $product_id);
		}


		/*

		Position is a string so we need a more relaxed
		equality check

		*/
		public function get_featured_image_by_position($image) {
			return $image->position == 1;
		}


		/*

		Get feat image by id

		*/
		public function get_feat_image_by_post_id($post_id) {
			return array_values( array_filter( $this->get_images_from_post_id($post_id), [$this, "get_featured_image_by_position"] ) );
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
				image_id bigint(100) unsigned NOT NULL DEFAULT 0,
        product_id bigint(100) DEFAULT NULL,
        variant_ids longtext DEFAULT NULL,
        src longtext DEFAULT NULL,
        alt longtext DEFAULT NULL,
        position int(20) DEFAULT NULL,
        created_at datetime,
        updated_at datetime,
        PRIMARY KEY  (id)
      ) ENGINE=InnoDB $collate";

    }


  }

}
