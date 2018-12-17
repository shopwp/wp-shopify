<?php

namespace WPS;

use WPS\Messages;

if (!defined('ABSPATH')) {
	exit;
}


class Checkouts {

	/*

	Initialize the class and set its properties.

	*/
	public function __construct() {

	}


	/*

	Only returns note attribute with name 'cartID'

	*/
	private function filter_note_attributes_by_checkout_id($attribute) {
		return $attribute->name === 'cartID';
	}


	/*

	Filters order note attributes by car ID

	*/
	private function get_checkout_id_from_note_attributes($order) {
		return array_filter($order->note_attributes, [__CLASS__, 'filter_note_attributes_by_checkout_id']);
	}


	/*

	Get cart id from order

	*/
	public function get_checkout_id_from_order($order) {

		$checkout_id = self::get_checkout_id_from_note_attributes($order);

		if (is_array($checkout_id) && isset($checkout_id[0]->value)) {
			return $checkout_id[0]->value;

		} else {
			return false;

		}

	}


	/*

	Hooks

	*/
	public function hooks() {


	}


	/*

	Init

	*/
	public function init() {
		$this->hooks();
	}


}
