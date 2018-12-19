<?php

namespace WPS\Utils;

use WPS\Utils;

if (!defined('ABSPATH')) {
	exit;
}


class Data {

	/*

	Finds the size in bytes of a given piece of data / variable

	*/
	public static function size_in_bytes($data) {

    $serialized_data = serialize($data);

    if (function_exists('mb_strlen')) {
      $size = mb_strlen($serialized_data, '8bit');

    } else {
      $size = strlen($serialized_data);
    }

    return $size;

	}


	/*

	Convert to readable size format

	*/
	public static function to_readable_size($size) {

		$base = log($size) / log(1024);
	  $suffix = array("", "KB", "MB", "GB", "TB");
	  $f_base = floor($base);

		return round(pow(1024, $base - floor($base)), 1) . $suffix[$f_base];

	}


	/*

	Converts an array to a comma seperated string without spaces

	*/
	public static function array_to_comma_string($maybe_array) {
    return Utils::remove_spaces_from_string( Utils::convert_to_comma_string($maybe_array) );
	}


	/*

	Retrieves an array value based on a provided index, minus one

	*/
	public static function current_index_value_less_one($items, $index) {

		$current_index_less_one = $index - 1;

		// Send current index if empty
		if ( !isset($items[$current_index_less_one]) ) {
			return $index;
		}

		return $items[$current_index_less_one];

	}


	/*

	Chunks an array

	*/
	public static function chunk_data($data, $items_per_chunk) {
		return array_chunk($data, $items_per_chunk);
	}


	/*

	Used within a Reduce to count the total number of items

	*/
	public static function add($carry, $item) {

		$carry += $item;

		return $carry;

	}


	/*

	Adds ints from an array like:

	[1, 2, 3, 4]

	*/
	public static function add_totals($array_of_ints) {
		return array_reduce($array_of_ints, [__CLASS__, 'add']);
	}


	/*

	Only returns wp_errors

	*/
	public static function only_wp_errors($item) {
		return is_wp_error($item);
	}


	/*

	Only returns wp_errors

	*/
	public static function return_only_wp_errors($items) {
		return array_filter($items, [__CLASS__, 'only_wp_errors']);
	}


	/*

	Coerce into a given type

	*/
	public static function coerce($value, $type) {

		$new_value = $value;

		if ( settype($new_value, $type) ) {
			return $new_value;
		}

		return false;

	}


	public static function contains_comma($string) {
    return strpos($string, ',') !== false;
  }

}
