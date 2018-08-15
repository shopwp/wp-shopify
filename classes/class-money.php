<?php

namespace WPS;

if (!defined('ABSPATH')) {
	exit;
}

use WPS\Utils;
use Gerardojbaez\Money\Money as Money_Gerardo; // TODO: Get rid of these hidden deps
use Gerardojbaez\Money\Currency as Currency_Gerardo;

require plugin_dir_path( __FILE__ ) . '../vendor/autoload.php';


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
		TODO: Can we pull this in dynamically from the API?

		*/
		public function get_avail_money_formats() {

			return [
				'amount',
				'amount_no_decimals',
				'amount_with_comma_separator',
				'amount_no_decimals_with_comma_separator',
				'amount_with_space_separator',
				'amount_no_decimals_with_space_separator',
				'amount_with_apostrophe_separator'
			];

		}


		/*

	  Checks whether we want to show the 'money_with_currency_format' or 'money_format' column val
	  - Predicate Function (returns boolean)

	  */
	  public function is_using_money_with_currency_format() {

	    $priceWithCurrency = $this->DB_Settings_General->get_column_single('price_with_currency');

	    if (Utils::array_not_empty($priceWithCurrency) && isset($priceWithCurrency[0]->price_with_currency)) {
	      return true;

	    } else {
	      return false;
	    }

	  }


		/*

		Extracts amount format

		*/
		public function extract_amount_format() {

			// Need to check what field to use
			if ($this->is_using_money_with_currency_format()) {
				$settingsMoneyFormat = $this->DB_Shop->get_money_with_currency_format();

			} else {
				$settingsMoneyFormat = $this->DB_Shop->get_money_format();
			}

			$formatNoFrontDelimiter = explode("{{", $settingsMoneyFormat);

			if (count($formatNoFrontDelimiter) >= 2) {

				$formatNoBackDelimiter = explode("}}", $formatNoFrontDelimiter[1]);
				$finalFormatNoSpaces = str_replace(' ', '', $formatNoBackDelimiter[0]);

				return $finalFormatNoSpaces;

			} else {
				return false;

			}

		}


		/*

	  Perform the actual formatting depending on the setting at Shopify
	  Since: 1.0.1

	  */
	  public function construct_format_money($shop_currency, $moneyFormat, $price) {

	    if ($moneyFormat === 'amount') {
	      $money = new Money_Gerardo($price);

	    } else if ($moneyFormat === 'amount_no_decimals') {

	      $currency = new Currency_Gerardo($shop_currency);
	      $currency->setPrecision(0);
	      $money = new Money_Gerardo(round($price, 2), $currency);

	    } else if ($moneyFormat === 'amount_with_comma_separator') {

	      $currency = new Currency_Gerardo($shop_currency);
	      $currency->setDecimalSeparator(',');
	      $money = new Money_Gerardo($price, $currency);

	    } else if ($moneyFormat === 'amount_no_decimals_with_comma_separator') {

	      $currency = new Currency_Gerardo($shop_currency);
	      $currency->setPrecision(0);
	      $currency->setDecimalSeparator(',');
	      $money = new Money_Gerardo(round($price, 2), $currency);

	    } else if ($moneyFormat === 'amount_with_space_separator') {
	      $currency = new Currency_Gerardo($shop_currency);
	      $currency->setThousandSeparator(' ');
	      $money = new Money_Gerardo($price, $currency);

	    } else if ($moneyFormat === 'amount_no_decimals_with_space_separator') {
	      $currency = new Currency_Gerardo($shop_currency);
	      $currency->setPrecision(0);
	      $currency->setThousandSeparator(' ');
	      $money = new Money_Gerardo(round($price, 2), $currency);

	    } else if ($moneyFormat === 'amount_with_apostrophe_separator') {
	      $currency = new Currency_Gerardo($shop_currency);
	      $currency->setThousandSeparator('\'');
	      $money = new Money_Gerardo($price, $currency);

	    } else {
	      $money = new Money_Gerardo($price);
	    }

	    return $money;

	  }


	  /*

	  Handles replacing delimiters with the correctly formatted money

	  */
	  public function replace_delimiters_with_formatted_money($money_format_current = '${{amount}}', $shop_currency = 'USD', $price) {

	    $moneyFormat = $this->wps_find_amount_format();
	    $money = $this->construct_format_money($shop_currency, $moneyFormat, $price);

	    $priceReplaced = strtr($money_format_current, array ($moneyFormat => $money->amount()));
	    $priceWithoutFrontDelimiter = str_replace('{{', '', $priceReplaced);
	    $priceWithoutBackDelimiter = str_replace('}}', '', $priceWithoutFrontDelimiter);

	    return $priceWithoutBackDelimiter;

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


		/*

	  -- Main Format Money Function --

		In order to find the correct ID to cache, we need to perform a search
		if more than one variant exists (more than one price). If only one
		variant exists we know this product currently only has one price.

		Also need to check if $product is an array or not. The collection single
		template returns an object for $product.

	  */
	  public function format_money($price, $product) {

	    if (is_array($product)) {

	      if (isset($product['variants']) && count($product['variants']) > 1) {
	        $matchedVariant = $this->find_variant_by_price($price, $product['variants']);
	        $productID = $matchedVariant->id;

	      } else {
	        $productID = $product['variants'][0]['id'];
	      }

	    } else {

	      if (!isset($product->post_id) && isset($product->option1)) {
	        $variants = array($product);

	      } else {
	        $variants = $this->DB_Variants->get_variants_from_post_id($product->post_id);
	      }

	      if (count($variants) > 1) {

	        $variants = Utils::convert_object_to_array($variants);
	        $matchedVariant = $this->find_variant_by_price($price, $variants);
	        $productID = $matchedVariant->id;

	      } else {

					$productID = $product->product_id;

	      }

	    }


	    if (get_transient('wps_product_price_id_' . $productID)) {
	      return get_transient('wps_product_price_id_' . $productID);

	    } else {

	      $shop_currency = $this->DB_Shop->get_shop('currency');

				if (empty($shop_currency)) {
					$shop_currency = WPS_DEFAULT_CURRENCY; // default fallback incase user doesn't sync shop data

				} else {
					$shop_currency = $shop_currency[0]->currency;
				}


	      if ($this->is_using_money_with_currency_format()) {
	        $money_format_current = $this->DB_Shop->get_money_with_currency_format();

	      } else {
	        $money_format_current = $this->DB_Shop->get_money_format();

	      }

	      $finalPrice = $this->replace_delimiters_with_formatted_money($money_format_current, $shop_currency, $price);
	      $currency_symbols = explode('{{amount}}', $money_format_current);

	      if (empty($currency_symbols[0])) {
	        $symbol = WPS_DEFAULT_CURRENCY_SYMBOL; // default fallback incase user doesn't sync shop data

	      } else {
	        $symbol = $currency_symbols[0];
	      }


	      $priceFormatted = explode($symbol, $finalPrice);


				if (isset($currency_symbols[1]) && !empty($currency_symbols[1])) {
					$currency_acronym = trim($currency_symbols[1]);

				} else {
					$currency_acronym = false;
				}


				$price_markup = '<span class="wps-product-price-currency" itemprop="priceCurrency">' . $symbol . '</span>' . '<span itemprop="price" class="wps-product-individual-price">' . $price .'</span>';


				if ($currency_acronym) {
					$price_markup .= '<span itemprop="priceCurrency" class="wps-product-individual-price-format">' . trim($currency_symbols[1]) .'</span>';
				}


	      set_transient('wps_product_price_id_' . $productID, $price_markup);

	      return $price_markup;

	    }

	  }


		/*

		Checks if the amount value exists within Shopify array

		*/
		public function wps_find_amount_format() {

			$amountFormatCurrent = $this->extract_amount_format();
			$availMoneyFormats = $this->get_avail_money_formats();
			$key = array_search($amountFormatCurrent, $availMoneyFormats);

			if ($key >= 0) {
				return $availMoneyFormats[$key];

			} else {
				return false;
			}

		}


	}

}
