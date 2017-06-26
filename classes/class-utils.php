<?php

namespace WPS;
require plugin_dir_path( __FILE__ ) . '../vendor/autoload.php';

use WPS\DB;
use WPS\Config;
use WPS\DB\Products;
use WPS\DB\Collects;
use WPS\DB\Collections_Custom;
use WPS\DB\Collections_Smart;
use WPS\DB\Settings_General;
use WPS\DB\Tags;
use WPS\DB\Variants;
use WPS\DB\Options;
use Gerardojbaez\Money\Money;


/*

Class Utils

*/
class Utils {

  protected static $instantiated = null;

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

  Construct proper path to wp-admin folder

  */
  public static function wps_construct_admin_path_from_urls($homeURL, $adminURL) {

    $explodedHome = explode('/', $homeURL);
    $explodedAdmin = explode('/', $adminURL);

    array_filter($explodedHome);
    array_filter($explodedAdmin);

    $diff = array_diff($explodedAdmin, $explodedHome);
    $diff = array_values($diff);

    $newPath = implode("/", $diff);
    $newPath = '/' . $newPath . '/';

    return $newPath;

  }




  /*

  wps_find_post_id_from_new_product

  */
  public static function wps_find_post_id_from_new_product($product) {

    /*

    Find the WP Post ID of the product being updated

    */
    $DB_Products = new Products();
    $existingProducts = $DB_Products->get_all_rows();
    $found_post_id = null;

    foreach ($existingProducts as $key => $existingProduct) {

      /*

      Loose equality check to ensure correct ID is found.

      TODO: might
      want to revist how the ID's are stored. Force all to ints.

      */
      if ($existingProduct->product_id == $product->id) {
        $found_post_id = $existingProduct->post_id;
        break;
      }
    }

    return $found_post_id;

  }


  /*

  wps_find_post_id_from_new_collection

  */
  public static function wps_find_post_id_from_new_collection($collection) {

    /*

    Find the WP Post ID of the collection being updated

    */
    $DB = new DB();
    $existingCollections = $DB->get_collections();
    $found_post_id = null;

    foreach ($existingCollections as $key => $existingCollection) {

      /*

      Loose equality check to ensure correct ID is found.

      TODO: might
      want to revist how the ID's are stored. Force all to ints.

      */
      if ($existingCollection->collection_id == $collection->id) {
        $found_post_id = $existingCollection->post_id;
        break;
      }
    }

    return $found_post_id;

  }


  /*

  extract_ids_from_object

  */
  public static function extract_ids_from_object($items) {

    $item_ids = array();

    foreach ($items as $key => $item) {
      $item_ids[] = $item->id;
    }

    return $item_ids;

  }


  /*

  convert_to_comma_string

  */
  public static function convert_to_comma_string($items) {
    return implode(', ', $items);
  }


  /*

  Get single shop info value

  */
  public static function flatten_collections_image_prop($customCollections) {

    $newCustomCollections = $customCollections;

    /*

    If multiple collections are passed ... AKA an Array

    */
    if (is_array($newCustomCollections)) {

      foreach ($newCustomCollections as $key => $newCustomCollection) {
        if (isset($newCustomCollection->image)) {
          $newCustomCollection->image = $newCustomCollection->image->src;
        }
      }

    }


    /*

    If a single collection is passed ... AKA an Object

    */
    if (is_object($newCustomCollections)) {

      if (isset($newCustomCollections->image)) {

        // TODO: Revist why we need to check for src property
        if (isset($newCustomCollections->image->src)) {
          $newCustomCollections->image = $newCustomCollections->image->src;

        } else {
          $newCustomCollections->image = $newCustomCollections->image;
        }

      }

    }

    return $newCustomCollections;

  }


  /*

  $items = Items currently living in database to compare against
  $diff = An array of IDs to be deleted from database

  */
  public static function wps_filter_items_by_id($items, $diff, $keyToCheck = 'id') {

    $finalResuts = array();

    foreach ($items as $key => $value) {

      foreach ($diff as $key => $diffID) {

        if (is_object($value)) {

          if ($diffID === $value->$keyToCheck) {
            $finalResuts[] = $value;
          }

        } else {

          if ($diffID === $value[$keyToCheck]) {
            $finalResuts[] = $value;
          }

        }

      }

    }

    return $finalResuts;

  }



  public static function wps_find_items_to_delete($currentItemsArray, $newItemsArray, $numDimensions = false, $keyToCheck = 'id') {

    $arrayOfIDsFromCurrent = self::wps_get_item_ids($currentItemsArray, $numDimensions, $keyToCheck);
    $arrayOfIDsFromShopify = self::wps_get_item_ids($newItemsArray, $numDimensions, $keyToCheck);

    if ($numDimensions) {

    }

    $diff = array_diff($arrayOfIDsFromCurrent, $arrayOfIDsFromShopify);
    $diff = array_values($diff);


    return self::wps_filter_items_by_id($currentItemsArray, $diff, $keyToCheck);

  }




  /*

  @param $currentItemsArray = array of arrays
  @param $newItemsArray = array of arrays

  */
  public static function wps_find_items_to_add($currentItemsArray, $newItemsArray, $numDimensions = false, $keyToCheck = 'id') {

    $arrayOfIDsFromCurrent = self::wps_get_item_ids($currentItemsArray, $numDimensions, $keyToCheck);
    $arrayOfIDsFromShopify = self::wps_get_item_ids($newItemsArray, $numDimensions, $keyToCheck);

    $diff = array_diff($arrayOfIDsFromShopify, $arrayOfIDsFromCurrent);
    $diff = array_values($diff);

    return self::wps_filter_items_by_id($newItemsArray, $diff, $keyToCheck);

  }


  /*

  wps_get_item_ids

  */
  public static function wps_get_item_ids($arr, $oneDimension = false, $keyToCheck = 'id') {

    // Converting to associative array
    $arr  = json_encode($arr);
    $arr  = json_decode($arr, true);

    $results = array();

    if ($oneDimension) {

      foreach ($arr as $key => $value) {

        if (isset($value[$keyToCheck]) && $value[$keyToCheck]) {
          $results[] = $value[$keyToCheck];
        }
      }

    } else {

      foreach ($arr as $key => $subarray) {

        foreach ($subarray as $key => $value) {
          if (isset($value[$keyToCheck]) && $value[$keyToCheck]) {
            $results[] = $value[$keyToCheck];
          }
        }
      }

    }

    return $results;

  }


  /*

  wps_convert_object_to_array

  */
  public static function wps_convert_object_to_array($obj) {

    $array = array();

    foreach ($obj as $key => $value) {
      $array[] = (array) $value;
    }

    return $array;

  }


  /*

  Maybe serialize data

  */
  public static function wps_serialize_data_for_db($data) {

    $dataSerialized = array();

    foreach ($data as $key => $value) {

      if (is_array($value)) {
        $value = maybe_serialize($value);
      }

      $dataSerialized[$key] = $value;

    }

    return $dataSerialized;

  }


  /*

  Add product data to database

  */
  public static function wps_get_domain_prefix($domain) {

    $prefix = explode(".myshopify.com", $domain);

    return $prefix[0];

  }


  /*

	Remove all spaces from string

	*/
	public static function wps_mask_value($string) {
    $length = strlen($string);
    $stringNew = str_repeat('•', $length - 4) . $string[$length-4] . $string[$length-3] . $string[$length-2] . $string[$length-1];
		return $stringNew;
	}


  /*

	Remove all spaces from string

	*/
	public static function wps_remove_spaces_from_string($string) {
		return str_replace(' ', '', $string);
	}












  public static function construct_in_clause($shortcodeAttrs, $type) {

    $tags = '';

    if (is_array($shortcodeAttrs[$type])) {

      foreach ($shortcodeAttrs[$type] as $key => $tag) {
        $tags .= '"' . $tag . '", ';
      }

      $tags = substr($tags, 0, -2);

    } else {
      $tags .= '"' . $shortcodeAttrs[$type] . '"';

    }

    return $tags;

  }





  public static function construct_slug_clauses($shortcode_query, $slugs, $table_name) {

    global $wpdb;

    if (is_array($table_name)) {

      $counter = 0;

      foreach ($table_name as $key => $table) {

        if ($counter === 0) {
          $shortcode_query['where'] .= ' AND ' . $table . '.handle IN (' . $slugs . ')';

        } else {
          $shortcode_query['where'] .= ' OR ' . $table . '.handle IN (' . $slugs . ')';
        }

        $counter++;

      }

    } else {
      $shortcode_query['where'] .= ' AND ' . $table_name . '.handle IN (' . $slugs . ')';
    }

    return $shortcode_query;

  }




  public static function construct_title_clauses($shortcode_query, $titles, $table_name) {

    global $wpdb;

    if (is_array($table_name)) {

      $counter = 0;

      foreach ($table_name as $key => $table) {

        if ($counter === 0) {
          $shortcode_query['where'] .= ' AND ' . $table . '.title IN (' . $titles . ')';

        } else {
          $shortcode_query['where'] .= ' OR ' . $table . '.title IN (' . $titles . ')';
        }

        $counter++;

      }

    } else {
      $shortcode_query['where'] .= ' AND ' . $table_name . '.title IN (' . $titles . ')';
    }



    return $shortcode_query;

  }





  public static function construct_tag_clauses($shortcode_query, $tags, $table_name) {

    global $wpdb;
    $DB_Tags = new Tags();

    $tags_table_name = $DB_Tags->get_table_name();

    $shortcode_query['where'] .= ' AND tags.tag IN (' . $tags . ')';
    $shortcode_query['join'] .= ' INNER JOIN ' . $tags_table_name . ' tags ON ' . $table_name . '.product_id = tags.product_id';

    return $shortcode_query;

  }



  public static function construct_variants_clauses($shortcode_query, $variants, $table_name) {

    global $wpdb;
    $DB_Variants = new Variants();

    $variants_table_name = $DB_Variants->get_table_name();

    $shortcode_query['where'] .= ' AND variantss.title IN (' . $variants . ')';
    $shortcode_query['join'] .= ' INNER JOIN ' . $variants_table_name . ' variantss ON ' . $table_name . '.product_id = variantss.product_id';

    return $shortcode_query;

  }


  public static function construct_options_clauses($shortcode_query, $options, $table_name) {

    global $wpdb;
    $DB_Options = new Options();

    $options_table_name = $DB_Options->get_table_name();

    $shortcode_query['where'] .= ' AND options.name IN (' . $options . ')';
    $shortcode_query['join'] .= ' INNER JOIN ' . $options_table_name . ' options ON ' . $table_name . '.product_id = options.product_id';

    return $shortcode_query;

  }



  public static function construct_vendors_clauses($shortcode_query, $vendors, $table_name) {

    global $wpdb;

    $shortcode_query['where'] .= ' AND ' . $table_name . '.vendor IN (' . $vendors . ')';

    return $shortcode_query;

  }




  public static function construct_types_clauses($shortcode_query, $types, $table_name) {

    global $wpdb;



    $shortcode_query['where'] .= ' AND ' . $table_name . '.product_type IN (' . $types . ')';



    return $shortcode_query;

  }


  public static function construct_desc_clauses($shortcode_query, $desc, $table_name) {

    global $wpdb;

    $shortcode_query['where'] .= ' AND ' . $table_name . '.body_html LIKE "%' . $desc . '%"';

    return $shortcode_query;

  }


  public static function construct_collection_slugs_clauses($shortcode_query, $slugs) {

    global $wpdb;

    $DB_Collects = new Collects();
    $collects_table_name = $DB_Collects->get_table_name();

    $DB_Products = new Products();
    $products_table_name = $DB_Products->get_table_name();

    $DB_Collections_Smart = new Collections_Smart();
    $collections_smart_table_name = $DB_Collections_Smart->get_table_name();

    $DB_Collections_Custom = new Collections_Custom();
    $collections_custom_table_name = $DB_Collections_Custom->get_table_name();

    $shortcode_query['where'] .= ' AND collection_id in (
    SELECT
    smart.collection_id
    FROM ' . $collections_smart_table_name . ' smart
    WHERE smart.handle IN (' . $slugs . ')

    UNION

    SELECT
    custom.collection_id
    FROM ' . $collections_custom_table_name  . ' custom
    WHERE custom.handle IN (' . $slugs . ')
    )';

    $shortcode_query['join'] .= ' INNER JOIN ' . $collects_table_name . ' collects ON collects.product_id = products.product_id';
    $shortcode_query['fields'] .= ', collection_id';

    return $shortcode_query;

  }





  public static function construct_collections_clauses($shortcode_query, $slugs) {

    global $wpdb;

    $DB_Collects = new Collects();
    $collects_table_name = $DB_Collects->get_table_name();

    $DB_Products = new Products();
    $products_table_name = $DB_Products->get_table_name();

    $DB_Collections_Smart = new Collections_Smart();
    $collections_smart_table_name = $DB_Collections_Smart->get_table_name();

    $DB_Collections_Custom = new Collections_Custom();
    $collections_custom_table_name = $DB_Collections_Custom->get_table_name();

    $shortcode_query['where'] .= ' AND collection_id in (
    SELECT
    smart.collection_id
    FROM ' . $collections_smart_table_name . ' smart
    WHERE smart.title IN (' . $slugs . ')

    UNION

    SELECT
    custom.collection_id
    FROM ' . $collections_custom_table_name  . ' custom
    WHERE custom.title IN (' . $slugs . ')
    )';

    $shortcode_query['join'] .= ' INNER JOIN ' . $collects_table_name . ' collects ON collects.product_id = products.product_id';

    $shortcode_query['fields'] .= ', collection_id';

    return $shortcode_query;

  }






  public static function construct_limit_clauses($shortcode_query, $limit) {

    global $wpdb;

    $shortcode_query['limits'] .= 'LIMIT 0, ' . $limit;

    return $shortcode_query;

  }



  public static function construct_order_clauses($shortcode_query, $order) {

    global $wpdb;

    if (isset($shortcode_query['orderby']) && $shortcode_query['orderby']) {
      $shortcode_query['orderby'] .= ' ' . $order;

    } else {
      // TODO: Throw passive error
    }

    return $shortcode_query;

  }


  public static function construct_orderby_clauses($shortcode_query, $orderby, $table_name) {

    global $wpdb;

    $shortcode_query['orderby'] .= $table_name . '.' . $orderby . ' ';

    return $shortcode_query;

  }





  /*


  construct_clauses_from_products_shortcode


  */
  public static function construct_clauses_from_products_shortcode($shortcodeAttrs, $query) {

    global $wpdb;
    $sql = '';

    $DB_Products = new Products();
    $shortcode_query = $DB_Products->get_default_query();


    /*

    Here we have to loop through all the shortcode attributes that
    were passed in and check if they exist

    */

    if (array_key_exists('slugs', $shortcodeAttrs)) {
      $slugs = self::construct_in_clause($shortcodeAttrs, 'slugs');
      $shortcode_query = self::construct_slug_clauses($shortcode_query, $slugs, 'products');
    }

    if (array_key_exists('titles', $shortcodeAttrs)) {
      $titles = self::construct_in_clause($shortcodeAttrs, 'titles');
      $shortcode_query = self::construct_title_clauses($shortcode_query, $titles, 'products');
    }

    if (array_key_exists('tags', $shortcodeAttrs)) {
      $tags = self::construct_in_clause($shortcodeAttrs, 'tags');
      $shortcode_query = self::construct_tag_clauses($shortcode_query, $tags, 'products');
    }

    if (array_key_exists('vendors', $shortcodeAttrs)) {
      $vendors = self::construct_in_clause($shortcodeAttrs, 'vendors');
      $shortcode_query = self::construct_vendors_clauses($shortcode_query, $vendors, 'products');
    }

    if (array_key_exists('types', $shortcodeAttrs)) {
      $types = self::construct_in_clause($shortcodeAttrs, 'types');
      $shortcode_query = self::construct_types_clauses($shortcode_query, $types, 'products');
    }

    if (array_key_exists('desc', $shortcodeAttrs)) {
      $shortcode_query = self::construct_desc_clauses($shortcode_query, $shortcodeAttrs['desc'], 'products');
    }

    if (array_key_exists('collection_slugs', $shortcodeAttrs)) {
      $collection_slugs = self::construct_in_clause($shortcodeAttrs, 'collection_slugs');
      $shortcode_query = self::construct_collection_slugs_clauses($shortcode_query, $collection_slugs);
    }

    if (array_key_exists('collections', $shortcodeAttrs)) {
      $collections = self::construct_in_clause($shortcodeAttrs, 'collections');
      $shortcode_query = self::construct_collections_clauses($shortcode_query, $collections);
    }

    if (array_key_exists('variants', $shortcodeAttrs)) {
      $variants = self::construct_in_clause($shortcodeAttrs, 'variants');
      $shortcode_query = self::construct_variants_clauses($shortcode_query, $variants, 'products');
    }

    if (array_key_exists('options', $shortcodeAttrs)) {
      $options = self::construct_in_clause($shortcodeAttrs, 'options');
      $shortcode_query = self::construct_options_clauses($shortcode_query, $options, 'products');
    }

    if (array_key_exists('orderby', $shortcodeAttrs)) {

      // Default to products table
      $table_name_orderby = 'products';

      // Check if price is passed in, use variants table instead
      if ($shortcodeAttrs['orderby'] === 'price') {
        $table_name_orderby = 'variants';
      }

      $shortcode_query = self::construct_orderby_clauses($shortcode_query, $shortcodeAttrs['orderby'], $table_name_orderby);

      // Order depends on order by
      if (array_key_exists('order', $shortcodeAttrs)) {
        $shortcode_query = self::construct_order_clauses($shortcode_query, $shortcodeAttrs['order']);
      }

    }

    if (array_key_exists('limit', $shortcodeAttrs)) {
      $shortcode_query = self::construct_limit_clauses($shortcode_query, $shortcodeAttrs['limit']);
    }

    return $shortcode_query;


  }













  /*


  construct_clauses_from_collections_custom_shortcode


  */
  public static function construct_clauses_from_collections_shortcode($shortcodeAttrs, $query) {

    global $wpdb;
    $sql = '';

    $DB = new DB();

    $shortcode_query = $DB->get_default_collections_query();


    if (array_key_exists('slugs', $shortcodeAttrs)) {
      $slugs = self::construct_in_clause($shortcodeAttrs, 'slugs');
      $shortcode_query = self::construct_slug_clauses($shortcode_query, $slugs, 'collections');
    }

    if (array_key_exists('titles', $shortcodeAttrs)) {
      $titles = self::construct_in_clause($shortcodeAttrs, 'titles');
      $shortcode_query = self::construct_title_clauses($shortcode_query, $titles, 'collections');
    }

    if (array_key_exists('desc', $shortcodeAttrs)) {
      $shortcode_query = self::construct_desc_clauses($shortcode_query, $shortcodeAttrs['desc'], 'collections');
    }

    if (array_key_exists('orderby', $shortcodeAttrs)) {
      $shortcode_query = self::construct_orderby_clauses($shortcode_query, $shortcodeAttrs['orderby'], 'collections');

      if (array_key_exists('order', $shortcodeAttrs)) {
        $shortcode_query = self::construct_order_clauses($shortcode_query, $shortcodeAttrs['order']);
      }

    }

    if (array_key_exists('limit', $shortcodeAttrs)) {
      $shortcode_query = self::construct_limit_clauses($shortcode_query, $shortcodeAttrs['limit']);
    }

    return $shortcode_query;

  }



  public static function construct_join_from_products_shortcode($shortcodeAttrs) {

    $sql = '';

    /*

    Here we have to loop through all the shortcode attributes that
    were passed in and check if they exist

    */

    if (array_key_exists('order', $shortcodeAttrs)) {

    }

    if (array_key_exists('orderby', $shortcodeAttrs)) {

    }

    if (array_key_exists('slugs', $shortcodeAttrs)) {

    }

    if (array_key_exists('titles', $shortcodeAttrs)) {

    }

    if (array_key_exists('desc', $shortcodeAttrs)) {

    }

    if (array_key_exists('tags', $shortcodeAttrs)) {

    }

    if (array_key_exists('vendors', $shortcodeAttrs)) {

    }

    if (array_key_exists('variants', $shortcodeAttrs)) {

    }

    if (array_key_exists('types', $shortcodeAttrs)) {

    }

    if (array_key_exists('options', $shortcodeAttrs)) {

    }

    if (array_key_exists('available', $shortcodeAttrs)) {

    }

    if (array_key_exists('collections', $shortcodeAttrs)) {

    }

    if (array_key_exists('limit', $shortcodeAttrs)) {

    }

    return $sql;

  }





















  /*

  Map products shortcode arguments

  Defines the available shortcode arguments by checking
  if they exist and applying them to the custom property.

  The returned value eventually gets passed to wps_clauses_mod

  */
  public static function wps_map_products_args_to_query($shortcodeArgs) {

    $shortcode_args = array(
      'post_type'         => 'wps_products',
      'post_status'       => 'publish',
      'paged'             => 1
    );


    //
    // Order
    //
    if (isset($shortcodeArgs['order']) && $shortcodeArgs['order']) {
      $shortcode_args['custom']['order'] = $shortcodeArgs['order'];
    }

    //
    // Order by
    //
    if (isset($shortcodeArgs['orderby']) && $shortcodeArgs['orderby']) {
      $shortcode_args['custom']['orderby'] = $shortcodeArgs['orderby'];
    }

    //
    // IDs
    //
    if (isset($shortcodeArgs['ids']) && $shortcodeArgs['ids']) {
      $shortcode_args['custom']['ids'] = $shortcodeArgs['ids'];
    }

    //
    // Meta Slugs
    //
    if (isset($shortcodeArgs['slugs']) && $shortcodeArgs['slugs']) {
      $shortcode_args['custom']['slugs'] = $shortcodeArgs['slugs'];
    }

    //
    // Meta Title
    //
    if (isset($shortcodeArgs['titles']) && $shortcodeArgs['titles']) {
      $shortcode_args['custom']['titles'] = $shortcodeArgs['titles'];
    }

    //
    // Descriptions
    //
    if (isset($shortcodeArgs['desc']) && $shortcodeArgs['desc']) {
      $shortcode_args['custom']['desc'] = $shortcodeArgs['desc'];
    }

    //
    // Tags
    //
    if (isset($shortcodeArgs['tags']) && $shortcodeArgs['tags']) {
      $shortcode_args['custom']['tags'] = $shortcodeArgs['tags'];
    }

    //
    // Vendors
    //
    if (isset($shortcodeArgs['vendors']) && $shortcodeArgs['vendors']) {
      $shortcode_args['custom']['vendors'] = $shortcodeArgs['vendors'];
    }

    //
    // Variants
    //
    if (isset($shortcodeArgs['variants']) && $shortcodeArgs['variants']) {
      $shortcode_args['custom']['variants'] = $shortcodeArgs['variants'];
    }

    //
    // Type
    //
    if (isset($shortcodeArgs['types']) && $shortcodeArgs['types']) {
      $shortcode_args['custom']['types'] = $shortcodeArgs['types'];
    }

    //
    // Options
    //
    if (isset($shortcodeArgs['options']) && $shortcodeArgs['options']) {
      $shortcode_args['custom']['options'] = $shortcodeArgs['options'];
    }

    //
    // Available
    //
    if (isset($shortcodeArgs['available']) && $shortcodeArgs['available']) {
      $shortcode_args['custom']['available'] = $shortcodeArgs['available'];
    }

    //
    // Collections
    //
    if (isset($shortcodeArgs['collections']) && $shortcodeArgs['collections']) {
      $shortcode_args['custom']['collections'] = $shortcodeArgs['collections'];
    }

    //
    // Collection Slugs
    //
    if (isset($shortcodeArgs['collection_slugs']) && $shortcodeArgs['collection_slugs']) {
      $shortcode_args['custom']['collection_slugs'] = $shortcodeArgs['collection_slugs'];
    }

    //
    // Limit
    //
    if (isset($shortcodeArgs['limit']) && $shortcodeArgs['limit']) {
      $shortcode_args['custom']['limit'] = $shortcodeArgs['limit'];
    }

    //
    // Pagination
    //
    if (isset($shortcodeArgs['page']) && $shortcodeArgs['page']) {
      $shortcode_args['paged'] = $shortcodeArgs['page'];
    }

    return $shortcode_args;

  }


  /*

  Map collections shortcode arguments

  Defines the available shortcode arguments by checking
  if they exist and applying them to the custom property.

  The returned value eventually gets passed to wps_clauses_mod

  */
  public static function wps_map_collections_args_to_query($shortcodeArgs) {

    $query = array(
      'post_type'         => 'wps_collections',
      'post_status'       => 'publish',
      'paged'             => 1
    );


    //
    // Order
    //
    if (isset($shortcodeArgs['order']) && $shortcodeArgs['order']) {
      $shortcode_args['custom']['order'] = $shortcodeArgs['order'];
    }

    //
    // Order by
    //
    if (isset($shortcodeArgs['orderby']) && $shortcodeArgs['orderby']) {
      $shortcode_args['custom']['orderby'] = $shortcodeArgs['orderby'];
    }

    //
    // IDs
    //
    if (isset($shortcodeArgs['ids']) && $shortcodeArgs['ids']) {
      $shortcode_args['custom']['ids'] = $shortcodeArgs['ids'];
    }

    //
    // Meta Slugs
    //
    if (isset($shortcodeArgs['slugs']) && $shortcodeArgs['slugs']) {
      $shortcode_args['custom']['slugs'] = $shortcodeArgs['slugs'];
    }

    //
    // Meta Title
    //
    if (isset($shortcodeArgs['titles']) && $shortcodeArgs['titles']) {
      $shortcode_args['custom']['titles'] = $shortcodeArgs['titles'];
    }

    //
    // Descriptions
    //
    if (isset($shortcodeArgs['desc']) && $shortcodeArgs['desc']) {
      $shortcode_args['custom']['desc'] = $shortcodeArgs['desc'];
    }

    //
    // Limit
    //
    if (isset($shortcodeArgs['limit']) && $shortcodeArgs['limit']) {
      $shortcode_args['custom']['limit'] = $shortcodeArgs['limit'];
    }

    return $shortcode_args;


  }



  /*

  Formats products shortcode args
  Returns SQL query

  TODO: Combine with wps_format_collections_shortcode_args

  */
  public static function wps_format_products_shortcode_args($shortcodeArgs) {

    if ( isset($shortcodeArgs) && $shortcodeArgs ) {

      foreach ($shortcodeArgs as $key => $arg) {

        if (strpos($arg, ',') !== false) {

          $shortcodeArgs[$key] = Utils::wps_comma_list_to_array( trim($arg) );

        } else {
          $shortcodeArgs[$key] = trim($arg);

        }

      }

      $productsQuery = Utils::wps_map_products_args_to_query($shortcodeArgs);

      return $productsQuery;

    } else {
      return array();

    }

  }


  /*

  Formats collections shortcode args
  Returns SQL query

  TODO: Combine with wps_format_products_shortcode_args

  */
	public static function wps_format_collections_shortcode_args($shortcodeArgs) {

    if ( isset($shortcodeArgs) && $shortcodeArgs ) {

      foreach ($shortcodeArgs as $key => $arg) {

        if (strpos($arg, ',') !== false) {
          $shortcodeArgs[$key] = Utils::wps_comma_list_to_array( trim($arg) );

        } else {
          $shortcodeArgs[$key] = trim($arg);

        }

      }

      $collectionsQuery = Utils::wps_map_collections_args_to_query($shortcodeArgs);

      return $collectionsQuery;

    } else {
      return array();

    }


	}


	/*

	Turns comma seperated list into array

	*/
	public static function wps_comma_list_to_array($string) {
    return array_map('trim', explode(',', $string));
	}


  /*

	Turns comma seperated list into array

	*/
	public static function wps_remove_duplicates($collectionIDs) {

    $dups = array();

    foreach( array_count_values($collectionIDs) as $collection => $ID ) {
      if($ID > 1) {
        $dups[] = $collection;
      }
	  }

    return $dups;

  }


  /*

  Delete product data from database

  */
  public static function wps_delete_product_data($postID, $type, $dataToDelete) {
  	foreach ($dataToDelete as $key => $value) {
  		delete_post_meta($postID, $type, $value);
  	}
  }


  /*

  Add product data to database

  */
  public static function wps_add_product_data($postID, $type, $dataToAdd) {
    foreach ($dataToAdd as $key => $value) {
      add_post_meta($postID, $type, $value);
    }
  }


  /*

  Return product collections

  */
  public static function wps_return_product_collections($collects) {

    $collectionIDs = array();

    foreach ($collects as $key => $value) {
      array_push($collectionIDs, $collects[$key]->collection_id);
    }

    return $collectionIDs;

  }


  /*

  Find existing products

  */
  public static function wps_find_existing_products() {

    $existingProducts = array();

    $posts = get_posts(array(
      'posts_per_page'   => -1,
      'post_type'        => 'wps_products',
      'post_status'      => 'publish'
    ));

    foreach($posts as $post) {
			$existingProducts[$post->ID] = $post->post_name;
		}

    return $existingProducts;

  }
















  /*

  Get collection ID by Handle

  */
  public static function wps_get_collection_id_by_handle($handle) {

    $args = array(
      'post_type' => 'wps_collections',
      'post_status' => 'publish',
      'posts_per_page' -1,
      'meta_query' => array(
        array(
          'key'    => 'wps_collection_handle',
          'value'  => $handle
        )
      )
    );

    $collection = get_posts($args);


    if(isset($collection) && $collection) {
      $collectionID = get_post_meta( $collection[0]->ID, 'wps_collection_id', true );
      return $collectionID;

    } else {
      return false;
    }

  }


  /*

  Format Money

  */
  public static function wps_format_money($price) {

    $money = new Money($price);
    $money->format();

    return $money;

  }





  /*





  Implement




  */
  public function wps_construct_products_args() {


    /*

    Check what was passed in and contruct our arguments for WP_Query

    */
    if( isset($wps_shortcode_atts['collections']) && $wps_shortcode_atts['collections']) {

      // Removing all spaces
      // $collections = Utils::wps_remove_spaces_from_string($wps_shortcode_atts['collections']);

      // If user passed in collection as handle, find ID version
      if(!ctype_digit($wps_shortcode_atts['collections'])) {
        $collections = Utils::wps_get_collection_id_by_handle($wps_shortcode_atts['collections']);
      } else {
        $collections = $wps_shortcode_atts['collections'];
      }



      // $collectionIDs = Utils::wps_comma_list_to_array($collections);

      $args = array(
        'post_type' => 'wps_products',
        'post_status' => 'publish',
        'posts_per_page' => $wps_shortcode_atts['limit'] ? $wps_shortcode_atts['limit'] : -1,
        'paged' => $paged,
        'meta_query' => array(
          array(
            'key'    => 'wps_product_collections',
            'value'  => $collections
          )
        )
      );

    } else {

      if( isset($wps_shortcode_atts['products']) && $wps_shortcode_atts['products'] ) {
        $products = Utils::wps_remove_spaces_from_string($wps_shortcode_atts['products']);
        $productIDs = Utils::wps_comma_list_to_array($products);

        $args = array(
          'post__in' => $productIDs,
          'post_type' => 'wps_products',
          'post_status' => 'publish',
          'paged' => $paged,
          'posts_per_page' => $wps_shortcode_atts['limit']
        );

      } else {

        $args = array(
          'post_type' => 'wps_products',
          'post_status' => 'publish',
          'paged' => $paged,
          'posts_per_page' => $wps_shortcode_atts['limit']
        );

      }
    }


  }







  public function wps_get_pagenum_link($args, $page) {

    $Config = new Config();
    $generalSettings = $Config->wps_get_settings_general();
    $link = '';
    $homeURL = get_home_url();
    $post_type = $args['query']->query['post_type'];

    if ($post_type === 'wps_products') {
      $slug = $generalSettings->url_products;
    } else {
      $slug = $generalSettings->url_collections;
    }

    $link = $homeURL . '/' . $slug . '/page/' . $page;

    return $link;

  }









  /*

  @author Pieter Goosen
  @license GPLv2
  @link http://www.gnu.org/licenses/gpl-2.0.html

  @param array $args An array of key => value arguments. Defaults below
  - mixed query variable                   'query'                 => $GLOBALS['wp_query'],
  - string Previous page text              'previous_page_text'    => __( '&laquo;' ),
  - string Next page text                  'next_page_text'        => __( '&raquo;' ),
  - string First page link text            'first_page_text'       => __( 'First' ),
  - string Last page link text             'last_page_text'        => __( 'Last' ),
  - string Older posts text                'next_link_text'        => __( 'Older Entries' ),
  - string Newer posts text                'previous_link_text'    => __( 'Newer Entries' ),
  - bool Whether to use links              'show_posts_links'      => false,
  - int Amount of numbered links to show   'range'                 => 5,

  @return string $paginated_text

  */
  function wps_get_paginated_numbers( $args = [] ) {

    $Config = new Config();
    $DB_Settings_General = new Settings_General();

    // Exit if not enough posts to show pagination
    if ($args['query']->found_posts <= $DB_Settings_General->num_posts) {
      return;
    }

    // Set defaults to use
    $defaults = [
      'query'                 => $GLOBALS['wp_query'],
      'previous_page_text'    => __( apply_filters('wps_products_pagination_prev_page_text', '&laquo;') ),
      'next_page_text'        => __( apply_filters('wps_products_pagination_next_page_text', '&raquo;') ),
      'first_page_text'       => __( apply_filters('wps_products_pagination_first_page_text', 'First') ),
      'last_page_text'        => __( apply_filters('wps_products_pagination_last_page_text', 'Last') ),
      'next_link_text'        => __( apply_filters('wps_products_pagination_next_link_text', 'Next') ),
      'previous_link_text'    => __( apply_filters('wps_products_pagination_prev_link_text', 'Prev') ),
      'show_posts_links'      => apply_filters('wps_products_pagination_show_as_prev_next', false),
      'range'                 => apply_filters('wps_products_pagination_range', 5),
    ];


    // Merge default arguments with user set arguments
    $args = wp_parse_args( $args, $defaults );


    // if ($amountOfProducts <= $settingsNumProducts) {}


    /*

    Get current page if query is paginated and more than one page exists
    The first page is set to 1

    Static front pages is included

    @see WP_Query pagination parameter 'paged'
    @link http://codex.wordpress.org/Class_Reference/WP_Query#Pagination_Parameters

    */
    if ( get_query_var('paged') ) {
      $current_page = get_query_var('paged');

    } elseif ( get_query_var('page') ) {
      $current_page = get_query_var('page');

    } else{
      $current_page = 1;
    }


    $max_pages = ceil(($args['query']->found_posts / $DB_Settings_General->num_posts));

    /*

    If $args['show_posts_links'] is set to false, numbered paginated links are returned
    If $args['show_posts_links'] is set to true, pagination links are returned

    */
    if ($args['show_posts_links'] === false) {

      // Don't display links if only one page exists
      if ($max_pages === 1) {
        $paginated_text = '';

      } else {

        /*

        For multi-paged queries, we need to set the variable ranges which will be used to check
        the current page against and according to that set the correct output for the paginated numbers

        */
        $mid_range      = (int) floor( $args['range'] / 2 );
        $start_range    = range( 1 , $mid_range );
        $end_range      = range( ( $max_pages - $mid_range +1 ) , $max_pages );
        $exclude        = array_merge( $start_range, $end_range );

        /*

        The amount of pages must now be checked against $args['range']. If the total amount of pages
        is less than $args['range'], the numbered links must be returned as is

        If the total amount of pages is more than $args['range'], then we need to calculate the offset
        to just return the amount of page numbers specified in $args['range']. This defaults to 5, so at any
        given instance, there will be 5 page numbers displayed

        */
        $check_range = ( $args['range'] > $max_pages ) ? true : false;

        if ($check_range === true) {
          $range_numbers = range(1, $max_pages);

        } elseif ($check_range === false) {

          if (!in_array($current_page, $exclude)) {
            $range_numbers = range( ( $current_page - $mid_range ), ( $current_page + $mid_range ) );

          } elseif (in_array( $current_page, $start_range ) && ( $current_page - $mid_range ) <= 0 ) {
            $range_numbers = range(1, $args['range']);

          } elseif(in_array( $current_page, $end_range ) && ( $current_page + $mid_range ) >= $max_pages ) {

            $range_numbers = range( ( $max_pages - $args['range'] +1 ), $max_pages );

          }

        }


        /*

        The page numbers are set into an array through this foreach loop. The current page, or active page
        gets the class 'current' assigned to it. All the other pages get the class 'inactive' assigned to it

        */
        foreach ($range_numbers as $v) {

          if ($v == $current_page) {
            $page_numbers[] = '<span class="wps-products-page-current">' . $v . '</span>';

          } else {
            $page_numbers[] = '<a href="' . self::wps_get_pagenum_link( $args, $v ) . '" class="wps-products-page-inactive">' . $v . '</a>';

          }

        }

        /*

        All the texts are set here and when they should be displayed which will link back to:
         - $previous_page The previous page from the current active page
         - $next_page The next page from the current active page
         - $first_page Links back to page number 1
         - $last_page Links to the last page

        */
        $previous_page = ( $current_page !== 1 ) ? '<a href="' . self::wps_get_pagenum_link($args, $current_page - 1) . '" class="wps-products-page-previous">' . $args['previous_page_text'] . '</a>' : '';

        $next_page = ( $current_page !== $max_pages ) ? '<a href="' . self::wps_get_pagenum_link($args, $current_page + 1) . '" class="wps-products-page-next">' . $args['next_page_text'] . '</a>' : '';

        $first_page = ( !in_array( 1, $range_numbers ) ) ? '<a href="' . self::wps_get_pagenum_link($args, 1) . '" class="wps-products-page-first">' . $args['first_page_text'] . '</a>' : '';

        $last_page = ( !in_array( $max_pages, $range_numbers ) ) ? '<a href="' . self::wps_get_pagenum_link($args, $max_pages) . '" class="wps-products-page-last">' . $args['last_page_text'] . '</a>' : '';

        /*

        Text to display before the page numbers
        This is set to the following structure:
          - Page X of Y

        */

        $page_text = '<div class="wps-products-page-counter">' . sprintf( __( 'Page %s of %s' ), $current_page, $max_pages ) . '</div>';

        // Turn the array of page numbers into a string
        $numbers_string = implode( ' ', $page_numbers );

        $paginated_text = apply_filters('wps_products_pagination_start', '<div class="wps-products-pagination">');
        $paginated_text .= $page_text . $first_page . $previous_page . $numbers_string . $next_page . $last_page;
        $paginated_text .= apply_filters('wps_products_pagination_end', '</div>');

      }

    } elseif ($args['show_posts_links'] === true) {

      /*

      If $args['show_posts_links'] is set to true, only links to the previous and next pages are displayed
      The $max_pages parameter is already set by the function to accommodate custom queries

      */
      $paginated_text = apply_filters('wps_products_pagination_start', '<div class="wps-products-pagination">');
      $paginated_text .= previous_posts_link( '<div class="wps-pagination-products-prev-link">' . $args['previous_link_text'] . '</div>' );
      $paginated_text .= next_posts_link( '<div class="wps-pagination-products-next-link">' . $args['next_link_text'] . '</div>', $max_pages );
      $paginated_text .= apply_filters('wps_products_pagination_end', '</div>');

    }

    // Finally return the output text from the function
    return $paginated_text;

  }



  /*

  Returns a string to be used within posts_clauses. E.g., "LIMIT 0, 10"

  */
  public static function construct_pagination_limits($query) {

    global $post;
    $wps_related_products = $query->get('wps_related_products');


    if (empty($wps_related_products)) {


      $currentPage = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
      $Config = new Config();
      $generalSettings = $Config->wps_get_settings_general();

      if(isset($generalSettings->num_posts) && $generalSettings->num_posts) {

        $posts_per_page = $generalSettings->num_posts;

        $minNumProducts = ($currentPage - 1) * $posts_per_page;
        $maxNumProducts = $posts_per_page;


      } else {

        $posts_per_page = get_option('posts_per_page');

        $minNumProducts = ($currentPage - 1) * $posts_per_page;
        $maxNumProducts = $posts_per_page;

      }

      $limit = 'LIMIT ' . $minNumProducts . ', ' . $maxNumProducts;


    } else {

      $wps_related_products_count = $query->get('wps_related_products_count');
      $limit = 'LIMIT 0, ' . $wps_related_products_count;

    }

    return $limit;

  }


}
