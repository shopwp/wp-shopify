<?php

namespace WPS;

use WPS\Messages;
use WPS\Utils\Data as Utils_Data;
use WPS\Utils\Sorting as Utils_Sorting;

if (!defined('ABSPATH')) {
	exit;
}


class Utils {

	/*

	Checks for a valid backend nonce
	- Predicate Function (returns boolean)

	*/
	public static function valid_backend_nonce($nonce) {
		return wp_verify_nonce($nonce, WPS_BACKEND_NONCE_ACTION);
	}


	/*

	Checks for a valid frontend nonce
	- Predicate Function (returns boolean)

	*/
	public static function valid_frontend_nonce($nonce) {
		return wp_verify_nonce($nonce, WPS_FRONTEND_NONCE_ACTION);
	}


	/*

	Filter errors
	- Predicate Function (returns boolean)

	*/
	public static function filter_errors($item) {
		return is_wp_error($item);
	}


	/*

	Filter errors
	- Predicate Function (returns boolean)

	*/
	public static function filter_error_messages($error) {

		if (isset($error->errors) && isset($error->errors['error'])) {
			return $error->errors['error'][0];
		}

	}

	public static function filter_non_empty($item) {
		return !empty($item);
	}


	/*

	Helper for throwing WP_Errors

	*/
	public static function wp_error($params) {
		return new \WP_Error('error', Messages::error($params) );
	}


	/*

	Helper for throwing WP_Errors

	*/
	public static function wp_warning($params) {
		return new \WP_Error('warning', Messages::error($params) );
	}


	/*

	Loops through items and returns only those with values
	of WP_Error instances

	*/
	public static function return_only_errors($items) {
		return array_filter($items, [__CLASS__, 'filter_errors'], ARRAY_FILTER_USE_BOTH);
	}


	/*

	Loops through items and returns only those with values
	of WP_Error instances

	*/
	public static function return_only_error_messages($array_of_errors) {
		return array_values( array_map([__CLASS__, 'filter_error_messages'], $array_of_errors) );
	}


	/*

	Filter Errors With Messages

	*/
	public function filter_errors_with_messages($title, $error) {
		return $error->get_error_message();
	}


	/*

	Loops through items and returns only those with values
	of WP_Error instances

	*/
	public static function return_non_empty($items) {
		return array_filter($items, [__CLASS__, 'filter_non_empty'], ARRAY_FILTER_USE_BOTH);
	}


	/*

	Generate and return hash

	*/
	public static function hash_string($string) {
		return wp_hash($string);
	}


	/*

	Generate and return hash

	*/
	public static function hash($content, $serialize = false) {

		if ($serialize) {
			return md5( serialize($content) );
		}

		return md5($content);

	}


	/*

	Generate and return hash

	*/
	public static function hash_static_num($content) {
		return crc32( self::hash($content) );
	}


	/*

	Sort Product Images

	*/
	public static function sort_product_images($a, $b) {

		$a = self::convert_array_to_object($a);
		$b = self::convert_array_to_object($b);

		$a = (int) $a->position;
		$b = (int) $b->position;

		if ($a == $b) {
			return 0;
		}

		return ($a < $b) ? -1 : 1;

	}


	/*

	Sort Product Images By Position

	TODO: Need to check if this passes or fails

	*/
	public static function sort_product_images_by_position($images) {

		if ( is_array($images) ) {
			usort($images, array(__CLASS__, "sort_product_images"));
		}

		return $images;

	}


	/*

	Empty Connection
	- Predicate Function (returns boolean)

	*/
	public static function emptyConnection($connection) {

		if (!is_object($connection)) {
			return true;

		} else {

			if (property_exists($connection, 'api_key') && $connection->api_key) {
				return false;

			} else {
				return true;

			}

		}

	}



	/*

	Back From Shopify
	- Predicate Function (returns boolean)

	*/
	public static function backFromShopify() {

		if(isset($_GET["auth"]) && trim($_GET["auth"]) == 'true') {
			return true;

		} else {
			return false;
		}

	}


	/*

	Is Manually Sorted
	- Predicate Function (returns boolean)

	*/
	public static function is_manually_sorted($shortcodeArgs) {

		if (isset($shortcodeArgs['custom']) && isset($shortcodeArgs['custom']['titles']) && isset($shortcodeArgs['custom']['orderby']) && is_array($shortcodeArgs['custom']['titles']) && $shortcodeArgs['custom']['orderby'] === 'manual') {
			return true;

		} else {
			return false;
		}

	}


	/*

	Check if user is using the "collections" attribute. If they are, then we should
	default to the order they've established within Shopify.

	To do this, we need to first get the list of collects associated with the query.
	This could be thousands of products. From there, we sort the collects by position
	and then grab the posts based on the product IDs.

	*/
	public static function is_collections_sorted($shortcode_args) {

		if ( empty($shortcode_args['custom']['collections']) ) {
			return false;

		} else {
			return true;
		}

	}


	/*

	Construct proper path to wp-admin folder

	*/
	public static function manually_sort_posts_by_title($sortedArray, $unsortedArray) {

		$finalArray = array();

		foreach ($sortedArray as $key => $needle) {

			foreach ($unsortedArray as $key => $post) {

				if ($post->title === $needle) {
					$finalArray[] = $post;
				}

			}

		}

		return $finalArray;

	}





	/*

	Construct proper path to wp-admin folder

	*/
	public static function construct_admin_path_from_urls($homeURL, $adminURL) {

		if (strpos($homeURL, 'https://') !== false) {
			$homeProtocol = 'https';

		} else {
			$homeProtocol = 'http';
		}

		if (strpos($adminURL, 'https://') !== false) {
			$adminProtocol = 'https';

		} else {
			$adminProtocol = 'http';
		}

		$explodedHome = explode($homeProtocol, $homeURL);
		$explodedAdmin = explode($adminProtocol, $adminURL);

		$explodedHomeFiltered = array_values(array_filter($explodedHome))[0];
		$explodedAdminFiltered = array_values(array_filter($explodedAdmin))[0];

		$adminPath = explode($explodedHomeFiltered, $explodedAdminFiltered);

		return array_values(array_filter($adminPath))[0];

	}


	/*

	Returns the first item in an array

	*/
	public static function get_first_array_item($array) {

		reset($array);
		return current($array);

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


	public static function lessen_array_by($array, $criteria = []) {

		return array_map(function($obj) use($criteria) {
			return Utils::keep_only_props($obj, $criteria);
		}, $array);

	}


	public static function keep_only_props($obj, $props) {

		foreach ($obj as $key => $value) {

			if (!in_array($key, $props)) {
				unset($obj->$key);
			}

		}

		return $obj;

	}


	/*

	convert_to_comma_string

	*/
	public static function convert_to_comma_string($items) {

		if ( is_string($items) ) {
			return $items;
		}

		if ( is_array($items) ) {
			return implode(', ', $items);
		}

		return false;

	}


	/*

	Is multi dimensional array

	*/
	public static function is_multi_array($array) {

		rsort($array);

		return isset( $array[0] ) && is_array( $array[0] );

	}


	/*

	convert_to_comma_string

	*/
	public static function convert_to_comma_string_backticks($items) {
		return implode('`, `', $items);
	}







	public static function get_first_image_if_exists($product) {

		if ( self::has($product, 'images') && !empty($product->images) ) {
			return $product->images[0]->src;
		}

	}


	/*

	Get single shop info value

	*/
	public static function flatten_image_prop($items) {

		$items_copy = $items;
		$items_copy = Utils::convert_array_to_object($items_copy);

		if ( self::has($items_copy, 'image') && self::has($items_copy->image, 'src') ) {
			$items_copy->image = $items_copy->image->src;

		} else {
			$items_copy->image = self::get_first_image_if_exists($items_copy);

		}

		return $items_copy;

	}


	/*

	$items = Items currently living in database to compare against
	$diff = An array of IDs to be deleted from database

	Returns Array

	TODO: This could be slow if we need to loop through all products ... revist

	*/
	public static function filter_items_by_id($items, $diff, $keyToCheck = 'id') {

		$finalResuts = [];

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


	public static function gather_item_ids($current_items, $new_items, $num_dimensions, $key_to_check) {

		return [
			'current'	=> self::get_item_ids($current_items, $num_dimensions, $key_to_check),
			'new'			=> self::get_item_ids($new_items, $num_dimensions, $key_to_check)
		];

	}


	/*

	Find Items to Delete

	Returns Array

	*/
	public static function find_items_to_delete($current_items, $new_items, $num_dimensions = false, $key_to_check = 'id') {

		$ids_to_check = self::gather_item_ids($current_items, $new_items, $num_dimensions, $key_to_check);

		// Deletes ids in 'current' that arent in 'new'
		$difference = array_values( array_diff($ids_to_check['current'], $ids_to_check['new']) );

		return self::filter_items_by_id($current_items, $difference, $key_to_check);

	}


	/*

	@param $current_items = array of arrays
	@param $new_items = array of arrays

	Returns Array

	*/
	public static function find_items_to_add($current_items, $new_items, $num_dimensions = false, $key_to_check = 'id') {

		$ids_to_check = self::gather_item_ids($current_items, $new_items, $num_dimensions, $key_to_check);

		// Adds ids from 'new' that arent in 'current'
		$difference = array_values( array_diff($ids_to_check['new'], $ids_to_check['current']) );

		return self::filter_items_by_id($new_items, $difference, $key_to_check);

	}


	/*

	get_item_ids

	*/
	public static function get_item_ids($items, $one_dimension = false, $key_to_check = 'id') {

		$items = self::convert_to_assoc_array($items);

		$results = [];

		if ($one_dimension) {

			foreach ($items as $item) {

				if (isset($item[$key_to_check]) && $item[$key_to_check]) {
					$results[] = $item[$key_to_check];
				}

			}

		} else {

			foreach ($items as $sub_array) {

				foreach ($sub_array as $item) {

					if (isset($item[$key_to_check]) && $item[$key_to_check]) {
						$results[] = $item[$key_to_check];
					}

				}

			}

		}

		return $results;

	}


	/*

	convert_object_to_array

	*/
	public static function convert_object_to_array($maybe_object) {

		if ( is_array($maybe_object) ) {
			return $maybe_object;
		}

		// Unable to convert to Object from these. Return false.
		if (is_float($maybe_object) || is_int($maybe_object) || is_bool($maybe_object)) {

			return self::wp_error([
				'message_lookup' 	=> 'unable_to_convert_to_array',
				'call_method' 		=> __METHOD__,
				'call_line' 			=> __LINE__
			]);

		}

		return (array) $maybe_object;

	}


	/*

	Converts an array to object

	*/
	public static function convert_array_to_object($maybe_array) {

		if (is_object($maybe_array)) {
			return $maybe_array;
		}

		// Unable to convert to Object from these. Return false.
		if (is_float($maybe_array) || is_int($maybe_array) || is_bool($maybe_array)) {

			return self::wp_error([
				'message_lookup' 	=> 'unable_to_convert_to_object',
				'call_method' 		=> __METHOD__,
				'call_line' 			=> __LINE__
			]);

		}

		if (is_array($maybe_array)) {
			return json_decode( json_encode($maybe_array), false );
		}

	}


	/*

	Converts to an associative array

	*/
	public static function convert_to_assoc_array($items) {
		return json_decode( json_encode($items), true );
	}


	/*

	Maybe serialize data

	*/
	public static function serialize_data_for_db($data) {

		$dataSerialized = array();

		foreach ($data as $key => $value) {

			/*

			IMPORTANT -- Need to check for both Array and Objects
			otherwise the following error is thrown and data not saved:

			mysqli_real_escape_string() expects parameter 2 to be string, object given

			*/
			if (is_array($value) || is_object($value)) {
				$value = maybe_serialize($value);
			}

			$dataSerialized[$key] = $value;

		}

		return $dataSerialized;

	}


	/*

	Maybe serialize data

	*/
	public static function data_values_size_limit_reached($data, $table_name) {

		global $wpdb;

		foreach ($data as $key => $value) {

			$db_col_size = $wpdb->get_col_length( $table_name, $key );

			if ($db_col_size !== false && !is_wp_error($db_col_size) ) {
;
				if ( Utils_Data::size_in_bytes($value) > $db_col_size['length'] ) {

					return [
						'table_name'				=> $table_name,
						'value_attempted'		=> $value,
						'column_name'				=> $key,
						'max_size'					=> $db_col_size['length']
					];

				}

			}

		}

		return false;

	}


	/*

	Add product data to database

	*/
	public static function wps_get_domain_prefix($domain) {

		$prefix = explode(WPS_SHOPIFY_DOMAIN_SUFFIX, $domain);

		return $prefix[0];

	}


	/*

	Remove all spaces from string

	*/
	public static function mask($string) {
		$length = strlen($string);
		$stringNew = str_repeat('•', $length - 4) . $string[$length-4] . $string[$length-3] . $string[$length-2] . $string[$length-1];
		return $stringNew;
	}


	/*

	Remove all spaces from string

	*/
	public static function remove_spaces_from_string($string) {
		return str_replace(' ', '', $string);
	}













	public static function construct_flattened_object($items_flattened, $type) {

		$items_obj = new \stdClass;
		$items_obj->{$type} = $items_flattened;

		return $items_obj;

	}



	public static function flatten_array_into_object($items, $type) {

		// Need to check since $items comes directly from a request
		if (is_wp_error($items)) {
			return $items;
		}

		$items_flattened = [];

		foreach ($items as $item_wrap) {

			foreach ($item_wrap as $single_item) {
				$items_flattened[] = $single_item;
			}

		}

		return self::construct_flattened_object($items_flattened, $type);

	}












































	/*

	Map collections shortcode arguments

	Defines the available shortcode arguments by checking
	if they exist and applying them to the custom property.

	The returned value eventually gets passed to wps_clauses_mod

	*/
	public static function map_collections_args_to_query($shortcode_args) {

		$query = array(
			'post_type'         => WPS_COLLECTIONS_POST_TYPE_SLUG,
			'post_status'       => 'publish',
			'paged'             => 1
		);

		//
		// Order
		//
		if (isset($shortcode_args['order']) && $shortcode_args['order']) {
			$shortcode_args['custom']['order'] = $shortcode_args['order'];
		}

		//
		// Order by
		//
		if (isset($shortcode_args['orderby']) && $shortcode_args['orderby']) {
			$shortcode_args['custom']['orderby'] = $shortcode_args['orderby'];
		}

		//
		// IDs
		//
		if (isset($shortcode_args['ids']) && $shortcode_args['ids']) {
			$shortcode_args['custom']['ids'] = $shortcode_args['ids'];
		}

		//
		// Meta Slugs
		//
		if (isset($shortcode_args['slugs']) && $shortcode_args['slugs']) {
			$shortcode_args['custom']['slugs'] = $shortcode_args['slugs'];
		}

		//
		// Meta Title
		//
		if (isset($shortcode_args['titles']) && $shortcode_args['titles']) {
			$shortcode_args['custom']['titles'] = $shortcode_args['titles'];
		}

		//
		// Descriptions
		//
		if (isset($shortcode_args['desc']) && $shortcode_args['desc']) {
			$shortcode_args['custom']['desc'] = $shortcode_args['desc'];
		}

		//
		// Limit
		//
		if (isset($shortcode_args['limit']) && $shortcode_args['limit']) {
			$shortcode_args['custom']['limit'] = $shortcode_args['limit'];
		}

		//
		// Items per row
		//
		if (isset($shortcode_args['items-per-row']) && $shortcode_args['items-per-row']) {
			$shortcode_args['custom']['items-per-row'] = $shortcode_args['items-per-row'];
		}

		//
		// Pagination
		//
		if (isset($shortcode_args['pagination'])) {
			$shortcode_args['custom']['pagination'] = false;
		}

		//
		// Breadcrumbs
		//
		if (isset($shortcode_args['breadcrumbs']) && $shortcode_args['breadcrumbs']) {
			$shortcode_args['custom']['breadcrumbs'] = $shortcode_args['breadcrumbs'];
		}

		//
		// Keep permalinks
		//
		if (isset($shortcode_args['keep-permalinks']) && $shortcode_args['keep-permalinks']) {
			$shortcode_args['custom']['keep-permalinks'] = $shortcode_args['keep-permalinks'];
		}

		return $shortcode_args;

	}





	/*

	Formats collections shortcode args
	Returns SQL query

	*/
	public static function wps_format_collections_shortcode_args($shortcodeArgs) {

		if ( isset($shortcodeArgs) && $shortcodeArgs ) {

			foreach ($shortcodeArgs as $key => $arg) {

				if (strpos($arg, ',') !== false) {
					$shortcodeArgs[$key] = self::comma_list_to_array( trim($arg) );

				} else {
					$shortcodeArgs[$key] = trim($arg);

				}

			}

			$collectionsQuery = self::map_collections_args_to_query($shortcodeArgs);
			return $collectionsQuery;

		} else {
			return array();

		}


	}


	/*

	Turns comma seperated list into array

	*/
	public static function comma_list_to_array($string) {
		return array_map('trim', explode(',', $string));
	}



	public static function is_empty($array_or_object) {
		return count($array_or_object) <= 0;
	}



	/*

	Removes duplicates

	*/
	public static function wps_remove_duplicates($collectionIDs) {

		$dups = array();

		foreach ( array_count_values($collectionIDs) as $collection => $ID ) {

			if ($ID > 1) {
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

	Checks if needle exists in associative array

	*/
	public static function in_assoc($needle, $array) {

		$key = array_keys($array);
		$value = array_values($array);

		if (in_array($needle,$key)) {
			return true;

		} elseif (in_array($needle,$value)) {
			return true;

		} else {
			return false;

		}

	}


	/*

	Responsible for checking whether a variant is available for
	purchase.  must be an (object)

	$variant is expected to have the following properties:

	$variant->inventory_management
	$variant->inventory_quantity
	$variant->inventory_policy

	UNDER TEST

	*/
	public static function is_available_to_buy($variant) {

		// Sanity checks
		if ( empty($variant) && !is_array($variant) && !is_object($variant) ) {
			return false;
		}

		if ( is_array($variant) ) {
			$variant = self::convert_array_to_object($variant);
		}

		if ( !property_exists($variant, 'inventory_management') ) {
			return false;
		}


		// User has set Shopify to track the product's inventory
		if ($variant->inventory_management === 'shopify') {

			// If the product's inventory is 0 or less than 0
			if ($variant->inventory_quantity <= 0) {

				// If 'Allow customers to purchase this product when it's out of stock' is unchecked
				if ($variant->inventory_policy === 'deny') {

					return false;

				} else {
					return true;
				}

			} else {
				return true;
			}

		// User has set product to "do not track inventory" (always able to purchase)
		} else {
			return true;

		}

	}


	/*

	Product Inventory

	Checks whether a product's variant(s) are in stock or not

	*/
	public static function only_available_variants( $variants = [] ) {

		if ( empty($variants) ) {
			return [];
		}

		return array_values( array_filter($variants, [__CLASS__, 'is_available_to_buy']) );

	}


	public static function has_available_variants($variants) {

		$variants_copy = $variants;

		return !empty( self::only_available_variants($variants_copy) );

	}


	/*

	Construct Option Selections

	*/
	public static function construct_option_selections($selectedOptions) {

		$newSelectedOptions = $selectedOptions;
		$indexx = 1;

		foreach ($newSelectedOptions as $key => $optionVal) {

			// stripcslashes is import incase user has quotes within variant name
			$newSelectedOptions['option' . $indexx] = stripcslashes($optionVal);
			$indexx++;

			unset($newSelectedOptions[$key]);

		}

		return $newSelectedOptions;

	}



















	public static function has_option_values_set($variant) {

		$variant_copy = (array) $variant;

		if ( isset($variant_copy['option_values']) && !empty($variant_copy['option_values']) ) {
			return true;

		} else {
			return false;
		}

	}

	public static function clean_option_values($option_values) {

		$clean_option_values = [];

		foreach ($option_values as $key => $option_value) {
			$clean_option_values['option' . ($key + 1)] = $option_value->value;
		}

		return $clean_option_values;

	}


	/*

	Responsible for checking whether a property contains the word "option"

	UNDER TEST

	*/
	public static function has_option_property($key, $property) {
		return strpos($property, 'option') !== false;
	}


	public static function only_option_properties($variant) {
		return array_filter( (array) $variant, [__CLASS__, 'has_option_property'], ARRAY_FILTER_USE_BOTH);
	}

	public static function get_options_values($option_values) {
		return maybe_unserialize($option_values);
	}

	public static function build_numbered_options_from_option_values($option) {

		$option_values = self::get_options_values($option['option_values']);

		$clean_option_values = self::clean_option_values($option_values);

		return $clean_option_values;

	}


	public static function maybe_build_numbered_options_from_option_values($option) {

		if ( self::has_option_values_set($option) ) {
			return self::build_numbered_options_from_option_values($option);
		}

		return $option;

	}


	public static function normalize_option_values($variants) {

		$options = self::filter_variants_to_options_values($variants);

		$options_built = array_map( [__CLASS__, 'maybe_build_numbered_options_from_option_values'], $options );

		return $options_built;

	}


	/*

	Filter Variants To Options Values

	*/
	public static function filter_variants_to_options_values($variants) {

		if ( is_object($variants) ) {
			$variants = self::convert_object_to_array($variants);
		}

		return array_map( [__CLASS__, 'only_option_properties'], $variants );

	}


	/*

	Generic function to sort by a specific key / value

	*/
	public static function shift_arrays_up($array) {

		$newArray = [];

		foreach ($array as $index => $countArray) {

			foreach ($countArray as $name => $count) {
				$newArray[$name] = $count;
			}

		}

		return $newArray;

	}


	/*

	Generic function to sort by a specific key / value

	*/
	public static function get_current_page($postVariables) {

		if (!isset($postVariables['currentPage']) || !$postVariables['currentPage']) {
			$currentPage = 1;

		} else {
			$currentPage = $postVariables['currentPage'];
		}

		return $currentPage;

	}


	/*

	Gets the add to cart button width

	*/
	public static function get_add_to_cart_button_width($product) {

		if (count($product->options) === 1) {

			if (count($product->variants) > 1) {
				$col = 2;

			} else {
				$col = 1;
			}

		} else if (count($product->options) === 2) {
			$col = 1;

		} else if (count($product->options) === 3) {
			$col = 1;

		} else {
			$col = 1;
		}

		return $col;

	}


	/*

	Gets the product options button width (different from the add to cart width)

	UNDER TEST

	*/
	public static function get_options_button_width($options) {

		// This means the add to cart button will be to the right of the only option
		if (count($options) === 1) {
			return 2; // 50%

		} else {
			return count($options); // Either 100%, 50%, or 33%
		}

	}


	/*

	Responsible for connecting legacy option props to variants

	*/
	public static function connect_legacy_option_to_variant($variant, $options_and_values) {

		if (self::has($variant, 'option1')) {
			$options_and_values['option1'][] = $variant->option1;
		}

		if (self::has($variant, 'option2')) {
			$options_and_values['option2'][] = $variant->option2;
		}

		if (self::has($variant, 'option3')) {
			$options_and_values['option3'][] = $variant->option3;
		}

		return $options_and_values;

	}


	public static function connect_option_to_variant_from_option_values($variant, $options_and_values) {

		$option_values = self::get_options_values($variant->option_values);

		foreach ($option_values as $key => $option_value) {
			$options_and_values['option' . ($key + 1)][] = $option_value->value;
		}

		return $options_and_values;


	}




	public static function connect_options_to_variants($variants) {

		$options_and_values = [];

		foreach ($variants as $variant) {

			if  (self::has_option_values_set($variant) ) {
				$options_and_values = self::connect_option_to_variant_from_option_values($variant, $options_and_values);

			} else {
				$options_and_values = self::connect_legacy_option_to_variant($variant, $options_and_values);
			}

		}

		return self::remove_duplicate_variant_names($options_and_values);

	}





	public static function remove_duplicate_variant_names($options_and_values) {

		return array_map( function($options_and_value) {
			return array_unique($options_and_value, SORT_REGULAR);
		}, $options_and_values );

	}




	public static function get_sorted_options($product) {

		$variants_with_options = self::connect_options_to_variants($product->variants);

		$sorted_options = Utils_Sorting::sort_by($product->options, 'position');

		foreach ($sorted_options as $sorted_option) {
			$position = $sorted_option->position;
			$sorted_option->values = $variants_with_options['option' . $position];
		}

		return $sorted_options;

	}







































	/*

	Ensures scripts don't timeout

	*/
	public static function prevent_timeouts() {

		if ( !function_exists('ini_get') || !ini_get('safe_mode') ) {
			@set_time_limit(0);
		}

	}


	/*

	Check is an Object has a property

	*/
	public static function has($item, $property) {

		if ( is_array($item) ) {
			$item = self::convert_array_to_object($item);
		}

		return is_object($item) && property_exists($item, $property) ? true : false;
	}


	/*

	Checks if item is NOT an empty array

	*/
	public static function array_not_empty($maybe_array) {

		if (is_array($maybe_array) && !empty($maybe_array)) {
			return true;

		} else {
			return false;
		}

	}


	/*

	Checks if item is an empty array

	*/
	public static function array_is_empty($maybe_array) {

		if (is_array($maybe_array) && empty($maybe_array)) {
			return true;

		} else {
			return false;
		}

	}


	/*

	Checks if item is an empty array

	*/
	public static function object_is_empty($object) {

		$object_copy = $object;
		$object_copy = (array) $object_copy;

		if ( count( array_filter($object_copy) ) == 0 ) {
			return true;

		} else {
			return false;
		}

	}


	/*

	If the product or collection has the Online Sales channel enabled ...

	If published_at is null, we know the user turned off the Online Store sales channel.
	TODO: Shopify may implement better sales channel checking in the future API. We should
	then check for Buy Button visibility as-well.

	*/
	public static function is_data_published($item) {

		if (property_exists($item, 'published_at') && $item->published_at !== null) {
			return true;

		} else {
			return false;
		}

	}


	/*

	Wraps something with an array

	*/
	public static function maybe_wrap_in_array($something) {

		if ( !is_array($something) ) {
			$something = [$something];
		}

		return $something;

	}


	/*

	Runs for every insertion and update to to DB

	*/
	public static function convert_needed_values_to_datetime($data_array) {

		$data_array = self::convert_object_to_array($data_array);

		foreach ($data_array as $key => $value) {

			switch ($key) {

				case 'created_at':
					$data_array[$key] = self::convert_string_to_datetime($value);
					break;

				case 'updated_at':
					$data_array[$key] = self::convert_string_to_datetime($value);
					break;

				case 'published_at':
					$data_array[$key] = self::convert_string_to_datetime($value);
					break;

				case 'closed_at':
					$data_array[$key] = self::convert_string_to_datetime($value);
					break;

				case 'cancelled_at':
					$data_array[$key] = self::convert_string_to_datetime($value);
					break;

				case 'processed_at':
					$data_array[$key] = self::convert_string_to_datetime($value);
					break;

				case 'expires':
					$data_array[$key] = self::convert_string_to_datetime($value);
					break;

				default:
					break;
			}

		}

		return $data_array;

	}


	/*

	Converts a string to datetime

	*/
	public static function convert_string_to_datetime($date_string) {

		if (is_string($date_string)) {
			return date("Y-m-d H:i:s", strtotime($date_string));

		} else {
			return $date_string;
		}

	}


	/*

	Converts a url to protocol relative

	*/
	public static function convert_to_relative_url($url) {

		if (strpos($url, '://') === false) {
			return $url;

		} else {
			return '//' . explode("://", $url)[1];
		}

	}


	/*

	Converts a url to HTTPS

	*/
	public static function convert_to_https_url($url) {

		if (strpos($url, '://') === false) {
			return $url;

		} else {
			return 'https://' . explode("://", $url)[1];
		}

	}


	/*

	Removes object properties specified by keys

	*/
	public static function unset_by($object, $keys = []) {

		foreach ($keys as $key) {
			unset($object->{$key});
		}

		return $object;

	}


	/*

	Removes object properties specified by keys

	$item: Represents an object

	*/
	public static function unset_all_except($item, $exception) {

		if (!self::has($item, $exception)) {
			return $item;
		}

		foreach($item as $key => $value) {

			if ($key !== $exception) {
				unset($item->{$key});
			}

		}

		return $item;

	}


	/*

	Filters out any data specified by $criteria

	$items: Represents an array of objects
	$criteria: Represents an array of strings to check object keys by

	*/
	public static function filter_data_by($items, $criteria = []) {

		if (!$criteria) {
			return $items;
		}

		if ( is_object($items) ) {
			$items = self::convert_object_to_array($items);
		}

		return array_map(function($item) use ($criteria) {
			return self::unset_by($item, $criteria);
		}, $items);

	}


	/*

	Filters out all data NOT specified by $exception

	$items: Represents an array of objects
	$exception: Represents a string to check object keys by

	*/
	public static function filter_data_except($items, $exception = false) {

		if (!$exception) {
			return $items;
		}

		if ( is_object($items) ) {
			$items = self::convert_object_to_array($items);
		}

		return array_map(function($item) use ($exception) {
			return self::unset_all_except($item, $exception);
		}, $items);

	}


	/*

	Calculates row difference

	*/
	public static function different_row_amount($columns_new, $columns_current) {
		return count($columns_new) > count($columns_current);
	}


	public static function flatten_array($array) {

		$result = [];

		if ( !is_array($array) ) {
			$array = func_get_args();
		}

		foreach ($array as $key => $value) {

			if (is_array($value)) {
				$result = array_merge($result, self::flatten_array($value));

			} else {
				$result = array_merge($result, array($key => $value));

			}

		}

		return $result;

	}


	public static function convert_array_to_in_string($array) {
		return "('" . implode("', '", $array) . "')";
	}


	public static function first_num($num) {

		$num_split = str_split($num);

		return (int) $num_split[0];

	}


	public static function get_last_index($array_size) {
		return $array_size - 1;
	}



	public static function find_product_id($product_object) {

		if ( self::has($product_object, 'product_id') ) {
			return $product_object->product_id;
		}

		if ( self::has($product_object, 'id') ) {
			return $product_object->id;
		}

		return false;

	}


	public static function is_pro_active() {

		if ( is_plugin_active(WPS_PRO_SUBDIRECTORY_NAME) ) {
			return true;

		} else {
			return false;
		}

	}


	public static function is_free_active() {

		if ( is_plugin_active(WPS_FREE_SUBDIRECTORY_NAME) ) {
			return true;

		} else {
			return false;
		}

	}

	public static function get_subdir_and_file() {

		if ( Utils::is_free_active() ) {
			return WPS_FREE_SUBDIRECTORY_NAME;

		} else {
			return WPS_PRO_SUBDIRECTORY_NAME;
		}

	}

	public static function is_network_wide() {

		// Makes sure the plugin is defined before trying to use it
		if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
			require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
		}

		return is_plugin_active_for_network( self::get_subdir_and_file() );

	}


	/*

	Responsible for getting the site URL

	*/
	public static function get_site_url($blog_id = false) {

    if ( is_multisite() ) {

			if ($blog_id) {
				$blog_details = get_blog_details($blog_id);

			} else {
				$blog_details = get_blog_details( get_current_blog_id() );
			}

			if ( !empty($blog_details) ) {
				return $blog_details->siteurl;
			}

    } else {

      return get_home_url();

    }

  }

}
