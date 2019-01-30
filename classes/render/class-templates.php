<?php

namespace WPS\Render;

if (!defined('ABSPATH')) {
	exit;
}

class Templates {

	public $Template_Loader;

	public function __construct($Template_Loader) {
		$this->Template_Loader = $Template_Loader;
	}


	/*

	2.0 Default template params

	*/
	public function default_render_params() {

		return [
			'data' => false,
			'path' => false,
			'name' => false
		];

	}


	/*

	2.0 Load a template

	*/
	public function load($params) {

		$params = wp_parse_args( $params, $this->default_render_params() );

		return $this->Template_Loader->set_template_data( $params['data'] )->get_template_part( $params['path'], $params['name'] );

	}


}
