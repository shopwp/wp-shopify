<?php

namespace WPS\DB;

use WPS\Config;
use WPS\WS;
use WPS\Utils;
use WPS\DB\Products;
use WPS\DB\Settings_Connection;
use WPS\DB\Settings_General;
use WPS\Progress_Bar;

use GuzzleHttp\Promise;


// If this file is called directly, abort.
if (!defined('ABSPATH')) {
	exit;
}


/*

Database class for Images

*/
if (!class_exists('Images')) {

  class Images extends \WPS\DB {

    public $table_name;
  	public $version;
  	public $primary_key;

    /*

    Construct

    */
  	public function __construct() {

      global $wpdb;
      $this->table_name         = $wpdb->prefix . 'wps_images';
      $this->primary_key        = 'id';
      $this->version            = '1.0';
      $this->cache_group        = 'wps_db_images';

    }


    /*

    Get Columns

    */
  	public function get_columns() {
      return array(
        'id'                   => '%d',
        'product_id'           => '%d',
        'variant_ids'          => '%s',
        'src'                  => '%s',
        'alt'                  => '%s',
        'position'             => '%d',
        'created_at'           => '%s',
        'updated_at'           => '%s'
      );
    }


    /*

    Get Column Defaults

    */
  	public function get_column_defaults() {
      return array(
        'id'                   => 0,
        'product_id'           => '',
        'variant_ids'          => '',
        'src'                  => '',
        'alt'                  => '',
        'position'             => '',
        'created_at'           => date_i18n( 'Y-m-d H:i:s' ),
        'updated_at'           => date_i18n( 'Y-m-d H:i:s' )
      );
    }


    /*

    Get alt text from response

    */
    public function get_alt_text_from_response($imageAltResponse) {

      if (!is_object($imageAltResponse)) {
        return esc_html__('Shop Product', 'wp-shopify'); // Default alt text if none exists

      } else {

        $data = json_decode( $imageAltResponse->getBody()->getContents() );

        if (!is_object($data)) {
          return esc_html__('Shop Product', 'wp-shopify'); // Default alt text if nothing exists
        }

        if (property_exists($data, 'metafields')) {

          if (is_array($data->metafields) && !empty($data->metafields)) {
            return $data->metafields[0]->value;

          } else {
            return esc_html__('Shop Product', 'wp-shopify'); // Default alt text if none exists
          }

        } else {

          return new \WP_Error('error', $data->errors);

        }

      }

    }


    /*

    Get single shop info value
    TODO: Create a map function for insert_product instead of nested loops

    */
  	public function insert_images($products) {

      $DB_Settings_Connection = new Settings_Connection();
      $DB_Settings_General = new Settings_General();
      $WS = new WS(new Config());
      $progress = new Progress_Bar(new Config());
      $results = [];
      $count = 1;

      foreach ($products as $key => $product) {

        if (isset($product->images) && $product->images) {

          foreach ($product->images as $key => $image) {

            if (!Utils::isStillSyncing()) {
              wp_die();
              break 2;
            }

            /*

            If use title as alt isn't checked, go get the real alt text, otherwise
            use the title for alt.

            */
            if (!$DB_Settings_General->title_as_alt()) {

              // Calls API asynchronously and returns a Promise
              $response = $WS->wps_ws_get_image_alt($image);
              $altText = $this->get_alt_text_from_response($response);

              if (is_wp_error($altText)) {

                // $results[] = false;
                $results = false;
                break 2;

              } else {

                // $results[] = $altText;
                $image->alt = $altText;

              }

            } else {
              $image->alt = $product->title;
            }

            $results[] = $this->insert($image, 'image');

            $progress->increment_current_amount('products');
            $count++;

          }

        }

      }

      return $results;

    }




















    /*

    Insert Images

    */
    public function insert_image($product) {

      $DB_Settings_Connection = new Settings_Connection();
      $DB_Settings_General = new Settings_General();
      $WS = new WS(new Config());

      $results = [];
      $count = 1;

      if (isset($product->images) && $product->images) {

        foreach ($product->images as $key => $image) {

          if (!Utils::isStillSyncing()) {
            wp_die();
            break;
          }

          /*

          If use title as alt isn't checked, go get the real alt text, otherwise
          use the title for alt.

          */
          if (!$DB_Settings_General->title_as_alt()) {

            // Calls API asynchronously and returns a Promise
            $response = $WS->wps_ws_get_image_alt($image);

            $altText = $this->get_alt_text_from_response($response);

            if (is_wp_error($altText)) {

              // $results[] = false;
              $results = false;
              break;

            } else {

              // $results[] = $altText;
              $image->alt = $altText;

            }

          } else {
            $image->alt = $product->title;
          }

          $results[] = $this->insert($image, 'image');

          $count++;

        }

      }

      return $results;

    }


    /*

    update_variant

    */
  	public function update_image($product) {

      $WS = new WS(new Config());
      $results = array();
      $imagesFromShopify = $product->images;

      /*

      In order to handle image creation / deletions, we need to compare what's
      currently in the database with what gets sent back via the
      product/update webhook.

      */

      $newProductID = Utils::wps_find_product_id($product);
      $currentImagesArray = $this->get_rows('product_id', $newProductID);

      $imagesToAdd = Utils::wps_find_items_to_add($currentImagesArray, $imagesFromShopify, true);
      $imagesToDelete = Utils::wps_find_items_to_delete($currentImagesArray, $imagesFromShopify, true);

      $imagesToAdd = Utils::wps_convert_object_to_array($imagesToAdd);
      $imagesToDelete = Utils::wps_convert_object_to_array($imagesToDelete);

      /*

      Insert

      */
      if (count($imagesToAdd) > 0) {

        foreach ($imagesToAdd as $key => $newImage) {

          // TODO: Should we type check or type cast?
          if (is_object($newImage)) {
            $imageResponse = $WS->wps_ws_get_image_alt($newImage);
            $newImage->alt = $this->get_alt_text_from_response($imageResponse);

          } else if (is_array($newImage)) {
            $imageResponse = $WS->wps_ws_get_image_alt($newImage);
            $newImage['alt'] = $this->get_alt_text_from_response($imageResponse);
          }

          $results['created'] = $this->insert($newImage, 'image');

        }

      }


      /*

      Delete

      */
      if (count($imagesToDelete) > 0) {

        foreach ($imagesToDelete as $key => $oldImage) {
          $results['deleted'] = $this->delete($oldImage['id']);
        }

      }


      /*

      Update

      */
      foreach ($imagesFromShopify as $key => $image) {

        $imageResponse = $WS->wps_ws_get_image_alt($image);
        $image->alt = $this->get_alt_text_from_response($imageResponse);

        $results['updated'] = $this->update($image->id, $image);

      }

      return $results;

    }


    /*

    Get Single Product Images
    Without: Images, variants

    */
    public function get_product_images($postID = null) {

      global $wpdb;

      if ($postID === null) {
        $postID = get_the_ID();
      }

      if (get_transient('wps_product_single_images_' . $postID)) {
        $results = get_transient('wps_product_single_images_' . $postID);

      } else {

        $DB_Products = new Products();
        $table_images = $this->table_name;
        $table_products = $DB_Products->get_table_name();

        $query = "SELECT images.* FROM $table_products AS products INNER JOIN $table_images AS images ON images.product_id = products.product_id WHERE products.post_id = %d";

        $results = $wpdb->get_results($wpdb->prepare($query, $postID));

        set_transient('wps_product_single_images_' . $postID, $results);

      }

      return $results;

    }



    public static function get_variants_from_image($image) {

      if (is_array($image) && isset($image['variant_ids'])) {

        $variantIDs = maybe_unserialize($image['variant_ids']);

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

      $Config = new Config();

      if (empty($image['alt'])) {
        $alt = $product['details']['title'];

      } else {
        $alt = $image['alt'];
      }

      if (empty($image['src'])) {
        $src = $Config->plugin_url . 'public/imgs/placeholder.png';

      } else {
        $src = $image['src'];
      }

      return array(
        'src' => $src,
        'alt' => $alt
      );

    }


    /*

    Gets Image details (alt and src) by product object
    Param: $product Object

    */
    public static function get_image_details_from_product($product) {

      $Config = new Config();
			$data = new \stdClass;

      // If an object is passed ...
      if (is_object($product)) {

        if (empty($product->feat_image)) {

          $alt = $product->title;
          $src = $Config->plugin_url . 'public/imgs/placeholder.png';

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

      $Config = new Config();
			$data = new \stdClass;


      if (empty($collection->image)) {
        $src = $Config->plugin_url . 'public/imgs/placeholder.png';

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

    Creates a table query string

    */
    public function create_table_query() {

      global $wpdb;

      $collate = '';

      if ( $wpdb->has_cap('collation') ) {
        $collate = $wpdb->get_charset_collate();
      }

      return "CREATE TABLE `{$this->table_name}` (
        `id` bigint(100) unsigned NOT NULL AUTO_INCREMENT,
        `product_id` bigint(100) DEFAULT NULL,
        `variant_ids` longtext DEFAULT NULL,
        `src` longtext DEFAULT NULL,
        `alt` longtext DEFAULT NULL,
        `position` int(20) DEFAULT NULL,
        `created_at` datetime,
        `updated_at` datetime,
        PRIMARY KEY  (`{$this->primary_key}`)
      ) ENGINE=InnoDB $collate";

    }


    /*

    Creates database table

    */
  	public function create_table() {

      require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

      if (!$this->table_exists($this->table_name)) {
        dbDelta( $this->create_table_query() );
      }

    }

  }

}
