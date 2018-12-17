<?php

namespace WPS;


if (!defined('ABSPATH')) {
	exit;
}


class Options {


	/*

	Get Option

	*/
	public static function get($name, $default = false) {
		return get_option($name, $default);
	}


	/*

	Set Option

	$time = 0 = does not expire

	*/
	public static function update($name, $value) {

		if ( !update_option($name, $value) ) {

			if ( is_array($value) ) {
				$attempted_valued = 'Array';

			} else if ( is_object($value) ) {
				$attempted_valued = 'Object';

			} else {
				$attempted_valued = $value;
			}

			return Utils::wp_error([
				'message_lookup' 	=> 'Failed to update option: ' . $name . ' with value: ' . $attempted_valued,
				'call_method' 		=> __METHOD__,
				'call_line' 			=> __LINE__
			]);

		}

		return true;

	}


	/*

	Delete Option

	*/
	public static function delete($name) {
		return delete_option($name);
	}


}
