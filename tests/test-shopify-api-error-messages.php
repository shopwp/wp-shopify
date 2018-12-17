<?php

use WPS\Factories\HTTP_Factory;
use WPS\Messages;
use WPS\Utils\HTTP as Utils_HTTP;

/*

Tests that the correct error message is sent back to the client when a specific error occurs.

Current erors tested:
400, 401, 402, 403, 404, 406, 422, 429, 500, 501, 503, 504

*/
class Test_Shopify_API_Error_Messages extends WP_UnitTestCase {

	protected static $HTTP;

  static function wpSetUpBeforeClass() {
    // Assemble
    self::$HTTP = HTTP_Factory::build();

  }


	/*

	Mock: Shopify error wrapper

	*/
	function mock_shopify_error($status) {

		return [
			'headers' => [
			  'date' 						=> 'Thu, 30 Sep 2010 15:16:36 GMT',
			  'server' 					=> 'Apache',
			  'x-powered-by' 		=> 'PHP/5.3.3',
			  'x-server' 				=> '10.90.6.243',
			  'expires' 				=> 'Thu, 30 Sep 2010 03:16:36 GMT',
			  'cache-control' 	=> [
					'no-store, no-cache, must-revalidate',
					'post-check=0, pre-check=0'
				],
			  'vary' 						=> 'Accept-Encoding',
			  'content-length' 	=> 1641,
			  'connection' 			=> 'close',
			  'content-type' 		=> 'application/php',
			],
			'body' 							=> '<html>This is a website!</html>',
			'response' => [
				'code'      			=> $status,
				'message'   			=> 'OK',
			],
			'cookies' => []
		];

	}


  /*

  Mock: 400 Error

  */
  function test_shopify_error_message_400() {

    // Act
    $mock_error_response = $this->mock_shopify_error(400);
		$error_message = Utils_HTTP::get_error_message_from_status_code($mock_error_response);

		$this->assertEquals( Messages::get('shopify_api_400'), $error_message );

  }


  /*

  Mock: 401 Error

  */
  function test_shopify_error_message_401() {

    $mock_error_response = $this->mock_shopify_error(401);
		$error_message = Utils_HTTP::get_error_message_from_status_code($mock_error_response);

		// Assert
		$this->assertEquals( Messages::get('shopify_api_401'), $error_message );

  }


  /*

  Mock: 402 Error

  */
  function test_shopify_error_message_402() {

    // Act
    $mock_error_response = $this->mock_shopify_error(402);
		$error_message = Utils_HTTP::get_error_message_from_status_code($mock_error_response);

		// Assert
		$this->assertEquals( Messages::get('shopify_api_402'), $error_message );

  }


  /*

  Mock: 403 Error

  */
  function test_shopify_error_message_403() {

    // Act
    $mock_error_response = $this->mock_shopify_error(403);
		$error_message = Utils_HTTP::get_error_message_from_status_code($mock_error_response);

		// Assert
		$this->assertEquals( Messages::get('shopify_api_403'), $error_message );

  }


  /*

  Mock: 404 Error

  */
  function test_shopify_error_message_404() {

    $mock_error_response = $this->mock_shopify_error(404);
		$error_message = Utils_HTTP::get_error_message_from_status_code($mock_error_response);

		// Assert
		$this->assertEquals( Messages::get('shopify_api_404'), $error_message );

  }


  /*

  Mock: 406 Error

  */
  function test_shopify_error_message_406() {

    $mock_error_response = $this->mock_shopify_error(406);
		$error_message = Utils_HTTP::get_error_message_from_status_code($mock_error_response);

		// Assert
		$this->assertEquals( Messages::get('shopify_api_406'), $error_message );

  }


  /*

  Mock: 422 Error

  */
  function test_shopify_error_message_422() {

    $mock_error_response = $this->mock_shopify_error(422);
		$error_message = Utils_HTTP::get_error_message_from_status_code($mock_error_response);

		// Assert
		$this->assertEquals( Messages::get('shopify_api_422'), $error_message );

  }


  /*

  Mock: 429 Error

  */
  function test_shopify_error_message_429() {

    $mock_error_response = $this->mock_shopify_error(429);
		$error_message = Utils_HTTP::get_error_message_from_status_code($mock_error_response);

		// Assert
		$this->assertEquals( Messages::get('shopify_api_429'), $error_message );

  }


  /*

  Mock: 500 Error

  */
  function test_shopify_error_message_500() {

    $mock_error_response = $this->mock_shopify_error(500);
		$error_message = Utils_HTTP::get_error_message_from_status_code($mock_error_response);

		// Assert
		$this->assertEquals( Messages::get('shopify_api_500'), $error_message );

  }


  /*

  Mock: 501 Error

  */
  function test_shopify_error_message_501() {

    $mock_error_response = $this->mock_shopify_error(501);
		$error_message = Utils_HTTP::get_error_message_from_status_code($mock_error_response);

		// Assert
		$this->assertEquals( Messages::get('shopify_api_501'), $error_message );

  }


  /*

  Mock: 503 Error

  */
  function test_shopify_error_message_503() {

    $mock_error_response = $this->mock_shopify_error(503);
		$error_message = Utils_HTTP::get_error_message_from_status_code($mock_error_response);

		// Assert
		$this->assertEquals( Messages::get('shopify_api_503'), $error_message );

  }


  /*

  Mock: 504 Error

  */
  function test_shopify_error_message_504() {

    $mock_error_response = $this->mock_shopify_error(504);
		$error_message = Utils_HTTP::get_error_message_from_status_code($mock_error_response);

		// Assert
		$this->assertEquals( Messages::get('shopify_api_504'), $error_message );

  }


}
