<?php

namespace WPS;

if (!defined('ABSPATH')) {
	exit;
}

use WPS\Utils;
use WPS\Vendor\Gerardojbaez\Money\Money as Money_Gerardo;
use WPS\Vendor\Gerardojbaez\Money\Currency as Currency_Gerardo;


if ( !class_exists('Money') ) {


	class Money {

		private $DB_Settings_General;
		private $DB_Shop;
		private $DB_Variants;

		public function __construct($DB_Settings_General, $DB_Shop, $DB_Variants) {
			$this->DB_Settings_General 			= $DB_Settings_General;
			$this->DB_Shop 									= $DB_Shop;
			$this->DB_Variants 							= $DB_Variants;
		}


		/*

		Array of money formatting options from Shopify.

		As of Sept. 2018, formats are listed here: https://help.shopify.com/en/manual/payments/currency-formatting#currency-formatting-options

		{{ amount }}																					1,134.65
		{{ amount_no_decimals }}															1,135
		{{ amount_with_comma_separator }}											1.134,65
		{{ amount_no_decimals_with_comma_separator }}					1.135
		{{ amount_with_apostrophe_separator }} 								1'134.65

		*/
		public function get_avail_money_formats() {

			return [
				'amount',
				'amount_no_decimals',
				'amount_with_comma_separator',
				'amount_no_decimals_with_comma_separator',
				'amount_with_apostrophe_separator'
			];

		}


		/*

	  Checks whether we want to show the 'money_with_currency_format' or 'money_format' column val
	  - Predicate Function (returns boolean)

	  */
	  public function showing_price_with_currency_code() {

	    $priceWithCurrency = $this->DB_Settings_General->get_column_single('price_with_currency');

	    if ( !empty($priceWithCurrency[0]->price_with_currency) ) {
	      return true;

	    } else {
	      return false;
	    }

	  }


		/*

		Gets the currently saved money format

		*/
		public function get_current_money_format() {

			if ($this->showing_price_with_currency_code()) {
				return $this->DB_Shop->get_money_with_currency_format();

			} else {
				return $this->DB_Shop->get_money_format();
			}

		}


		/*

		Replaces amount front delimiter

		*/
		public function replace_amount_front_delimiter($price_with_format) {
			return str_replace('{', ' ', $price_with_format);
		}


		/*

		Replaces amount back delimiter

		*/
		public function replace_amount_back_delimiter($price_with_format) {
			return str_replace('}', ' ', $price_with_format);
		}


		/*

		Replaces amount space delimiter

		*/
		public function replace_amount_space_delimiter($price_with_format) {
			return str_replace(' ', '', $price_with_format);
		}


		/*

		Replaces amount space delimiter

		*/
		public function replace_amount_double_space_delimiter($price_with_format) {
			return str_replace('  ', '', $price_with_format);
		}


		public function seperate_format_by_spaces($format) {
			return explode(' ', $format);
		}


		public function return_only_format_name($value) {

			if ( is_string($value) ) {
				return strpos( $value, 'amount') !== false;
			}

		}


		public function search_format_from_avail_list($amount_format_to_use, $avail_money_formats) {
			return array_search($amount_format_to_use, $avail_money_formats);
		}


		public function return_format_from_found_key($array_key_of_found_format, $avail_money_formats) {

			if ($array_key_of_found_format >= 0) {
				return $avail_money_formats[$array_key_of_found_format];

			} else {
				return false;
			}

		}


		public function find_format_from_explosion($format_after_explosion) {

			$match = array_values( array_filter($format_after_explosion, [__CLASS__, 'return_only_format_name']) );

			if ( empty($match) ) {
				return false;
			}

			return $match[0];

		}


		/*

		Extracts amount format

		$format : ${{amount_no_decimals}} USD

		*/
		public function extract_format_name($format) {

			// $amount_no_decimals}} USD
			$format_no_front_delimiter 			= $this->replace_amount_front_delimiter($format);

			// $amount_no_decimals USD
			$format_no_back_delimiter 			= $this->replace_amount_back_delimiter($format_no_front_delimiter);

			$exploded = $this->seperate_format_by_spaces($format_no_back_delimiter);

			return $this->find_format_from_explosion($exploded);

		}


		/*

	  Perform the actual formatting depending on the setting at Shopify
	  Since: 1.0.1

	  */
	  public function get_price($currency_code, $money_format, $price) {

	    if ($money_format === 'amount') {
	      $money = new Money_Gerardo($price);

	    } else if ($money_format === 'amount_no_decimals') {

	      $currency = new Currency_Gerardo($currency_code);
	      $currency->setPrecision(0);
	      $money = new Money_Gerardo(round($price, 2), $currency);

	    } else if ($money_format === 'amount_with_comma_separator') {

	      $currency = new Currency_Gerardo($currency_code);
	      $currency->setDecimalSeparator(',');
	      $money = new Money_Gerardo($price, $currency);

	    } else if ($money_format === 'amount_no_decimals_with_comma_separator') {

	      $currency = new Currency_Gerardo($currency_code);
	      $currency->setPrecision(0);
	      $currency->setDecimalSeparator(',');
	      $money = new Money_Gerardo(round($price, 2), $currency);

	    } else if ($money_format === 'amount_with_apostrophe_separator') {
	      $currency = new Currency_Gerardo($currency_code);
	      $currency->setThousandSeparator('\'');
	      $money = new Money_Gerardo($price, $currency);

	    } else {
	      $money = new Money_Gerardo($price);
	    }

	    return $money->amount();

	  }



		public function replace_format_with_real_price($money_format_current, $format_name, $price) {
			return str_replace($format_name, $price, $money_format_current);
		}




	  /*

	  Handles replacing delimiters with the correctly formatted money

	  */
	  public function get_final_price($money_format_current = '${{amount}}', $shop_currency = 'USD', $price) {

	    $format_name 									= $this->find_amount_format();
	    $price 												= $this->get_price($shop_currency, $format_name, $price);

	    $price_replaced 							= $this->replace_format_with_real_price($money_format_current, $format_name, $price);
	    $priceWithoutFrontDelimiter 	= $this->replace_amount_front_delimiter($price_replaced);
			$priceWithoutBackDelimiter 		= $this->replace_amount_back_delimiter($priceWithoutFrontDelimiter);
			$final_price_with_format 			= $this->replace_amount_double_space_delimiter($priceWithoutBackDelimiter);

	    return $final_price_with_format;

	  }


		/*

	  Find Variant By Price

	  */
	  public function find_variant_by_price($price, $variants) {

	    $foundVariant = array();

	    foreach ($variants as $key => $variant) {

	      if ($variant->price === $price) {
	        $foundVariant = $variant;
	      }

	    }

	    return $foundVariant;

	  }




















		public function get_currency_code() {

			$shop_currency = $this->DB_Shop->get_shop('currency');

			if (empty($shop_currency)) {
				$shop_currency = WPS_DEFAULT_CURRENCY; // default fallback incase user doesn't sync shop data

			} else {
				$shop_currency = $shop_currency[0]->currency;
			}

			return $shop_currency;

		}









		public function get_currency_symbol($currency_code) {

			$currency = new Currency_Gerardo($currency_code);

			return $currency->getSymbol();

		}



		/*

	  -- Main Format Money Function --

		In order to find the correct ID to cache, we need to perform a search
		if more than one variant exists (more than one price). If only one
		variant exists we know this product currently only has one price.

		Also need to check if $product is an array or not. The collection single
		template returns an object for $product.

	  */
	  public function format_price($price, $product_id) {

	    if ( get_transient('wps_product_price_id_' . $product_id . '_' . $price) ) {
	      return get_transient('wps_product_price_id_' . $product_id . '_' . $price);
	    }

			$currency_code = $this->get_currency_code();

			$format_name = $this->find_amount_format();

			$price_only = $this->get_price($currency_code, $format_name, $price);

			$symbol = $this->get_currency_symbol($currency_code);

			$price_markup = $this->get_symbol_markup($symbol) . $this->get_price_markup($price_only);

			// Only show currency code if the user sets it
			if ( $this->showing_price_with_currency_code() ) {
				$price_markup .= $this->get_code_markup($currency_code);
			}

			set_transient('wps_product_price_id_' . $product_id . '_' . $price, $price_markup);

			return $price_markup;

	  }


		public function get_symbol_markup($symbol) {
			return '<span class="wps-product-price-currency" itemprop="priceCurrency">' . $symbol . '</span>';
		}

		public function get_price_markup($price) {
			return '<span itemprop="price" class="wps-product-individual-price">' . $price .'</span>';
		}

		public function get_code_markup($currency_code) {
			return '<span itemprop="priceCurrency" class="wps-product-individual-price-format">' . $currency_code .'</span>';
		}


		/*

		Checks if the amount value exists within Shopify array

		*/
		public function find_amount_format() {

			$amount_format_to_use = $this->extract_format_name( $this->get_current_money_format() );

			$avail_money_formats = $this->get_avail_money_formats();

			$array_key_of_found_format = $this->search_format_from_avail_list($amount_format_to_use, $avail_money_formats);

			return $this->return_format_from_found_key($array_key_of_found_format, $avail_money_formats);

		}


		/*

		Responsible for checking if more than one price exists

		*/
		public function has_more_than_one_price($variants_amount) {

			if ($variants_amount > 1) {
				return true;
			}

			return false;

		}


	}

}
