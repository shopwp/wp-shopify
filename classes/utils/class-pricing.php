<?php

namespace WPS\Utils;

if (!defined('ABSPATH')) {
	exit;
}

class Pricing {

	public static function has_zero_price($price) {

		if ( !is_string($price) && !is_integer($price) && !is_double($price) ) {
			return true;
		}

		if ($price === '0.00' || $price === '0.0' || $price === '0') {
			return true;
		}

		if ($price === 0.00 || $price === 0.0 || $price === 0) {
			return true;
		}

		return false;

	}

}
