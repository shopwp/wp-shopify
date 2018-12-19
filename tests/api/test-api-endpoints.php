<?php

use WPS\Factories\API_Factory;

/*

Tests Utils functions

*/
class Test_API_Endpoints extends WP_UnitTestCase {

	protected static $API;
	protected static $server;

	/*

	Setup ...

	*/
  static function wpSetUpBeforeClass() {

		global $wp_rest_server;

		self::$API = API_Factory::build();

		self::$server = $wp_rest_server = new \WP_REST_Server;

		do_action( 'rest_api_init' );

  }

	function test_it_should_have_valid_api_namespace() {
		$this->assertEquals('wpshopify/v1', WPS_SHOPIFY_API_NAMESPACE );
  }


	function test_it_should_find_api_routes() {

		$the_route = WPS_SHOPIFY_API_NAMESPACE;
		$routes = self::$server->get_routes();

		$routes_com = [];

		foreach( $routes as $route => $route_config ) {

			if ( strpos($route, $the_route) ) {
				array_push($routes_com, $route);
			}

		}

		$this->assertEquals('/wpshopify/v1', array_shift($routes_com) );
		$this->assertCount(79, $routes_com );

  }


}
