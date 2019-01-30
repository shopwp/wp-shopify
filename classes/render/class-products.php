<?php

namespace WPS\Render;

if (!defined('ABSPATH')) {
	exit;
}


/*

Render: Products

*/
class Products {

	public $Templates;
	public $Template_Data;

	public function __construct($Templates, $Template_Data) {
		$this->Templates 			= $Templates;
		$this->Template_Data 	= $Template_Data;
	}


	/*

	Products: Title

	*/
	public function title($params) {
		return $params;
	}


	/*

	Products: Description

	*/
	public function description($params) {}


	/*

	Products: Buy Button

	*/
	public function buy_button($params) {}


	/*

	Products: Pricing

	*/
	public function pricing($params) {}


	/*

	Products: Add to cart

	*/
	public function add_to_cart($params) {

		return $this->Templates->load([
			'path' => 'partials/products/add-to-cart/button-add-to',
			'name' => 'cart',
			'data' => $params
		]);

	}


	/*

	Products: Quanity

	*/
	public function quantity($params) {

		return $this->Templates->load([
			'path' => 'partials/products/add-to-cart/quantity',
			'data' => $params
		]);

	}


	/*

	Products: Options

	*/
	public function options($params) {

		return $this->Templates->load([
			'path' => 'partials/products/add-to-cart/options',
			'data' => $params
		]);

	}

}





// Render\Products()








// WPS\Render\product()
// WPS\Render\products()
//
// WPS\Render\Products\single()
// WPS\Render\Products\many()
//
// WPS\Render\Products\single_title()
// WPS\Render\Products\single_description()
// WPS\Render\Products\single_buy_button()



// Products\single();
// Products\many();
//
// Products\single_title();
// Products\single_description();
// Products\single_pricing();
// Products\single_controls();
// Products\single_add_to_cart();
// Products\single_options();
// Products\single_quantity();
// Products\single_gallery();
//
//
//
//
//
//
//
// Products\many();
// Products\single();
//
//
// Products\single([
// 	'single'
// ]);
//
//
// Products\title([
// 	'single' 	=> true
// 	'ids'			=> [123, 02348]
// ]);
// Products\description();
// Products\pricing();
// Products\controls();
// Products\add_to_cart();
// Products\options();
// Products\quantity();
// Products\gallery();
