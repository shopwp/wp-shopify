<?php

require plugin_dir_path( __FILE__ ) . '../vendor/autoload.php';

use WPS\Config;
use WPS\Messages;
use WPS\WS;

use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Pool;
use GuzzleHttp\Promise;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;


/*

Tests that the correct error message is sent back to the client when a specific error occurs.

Current erors tested:
400, 401, 402, 403, 404, 406, 422, 429, 500, 501, 503, 504

*/
class Test_Shopify_API_Error_Messages extends WP_UnitTestCase {

	protected static $Messages;
  protected static $WS;

  static function setUpBeforeClass() {

    // Assemble
    self::$WS = new WS( new Config() );
    self::$Messages = new Messages();

  }


  /*

  Mock: 400 Error

  */
  function test_shopify_error_message_400() {

    // Act
    try {
      $mockResponse = self::$WS->mock_shopify_error(400);

    } catch (\Exception $mockErrorResponse) {

      $errorMessage = self::$WS->wps_get_error_message($mockErrorResponse);

      // Assert
      $this->assertEquals(self::$Messages->message_shopify_api_400, $errorMessage);

    }

  }


  /*

  Mock: 401 Error

  */
  function test_shopify_error_message_401() {

    // Act
    try {
      $mockResponse = self::$WS->mock_shopify_error(401);

    } catch (\Exception $mockErrorResponse) {

      $errorMessage = self::$WS->wps_get_error_message($mockErrorResponse);

      // Assert
      $this->assertEquals(self::$Messages->message_shopify_api_401, $errorMessage);

    }

  }


  /*

  Mock: 402 Error

  */
  function test_shopify_error_message_402() {

    // Act
    try {
      $mockResponse = self::$WS->mock_shopify_error(402);

    } catch (\Exception $mockErrorResponse) {

      $errorMessage = self::$WS->wps_get_error_message($mockErrorResponse);

      // Assert
      $this->assertEquals(self::$Messages->message_shopify_api_402, $errorMessage);

    }

  }


  /*

  Mock: 403 Error

  */
  function test_shopify_error_message_403() {

    // Act
    try {
      $mockResponse = self::$WS->mock_shopify_error(403);

    } catch (\Exception $mockErrorResponse) {

      $errorMessage = self::$WS->wps_get_error_message($mockErrorResponse);

      // Assert
      $this->assertEquals(self::$Messages->message_shopify_api_403, $errorMessage);

    }

  }


  /*

  Mock: 404 Error

  */
  function test_shopify_error_message_404() {

    // Act
    try {
      $mockResponse = self::$WS->mock_shopify_error(404);

    } catch (\Exception $mockErrorResponse) {

      $errorMessage = self::$WS->wps_get_error_message($mockErrorResponse);

      // Assert
      $this->assertEquals(self::$Messages->message_shopify_api_404, $errorMessage);

    }

  }


  /*

  Mock: 406 Error

  */
  function test_shopify_error_message_406() {

    // Act
    try {
      $mockResponse = self::$WS->mock_shopify_error(406);

    } catch (\Exception $mockErrorResponse) {

      $errorMessage = self::$WS->wps_get_error_message($mockErrorResponse);

      // Assert
      $this->assertEquals(self::$Messages->message_shopify_api_406, $errorMessage);

    }

  }


  /*

  Mock: 422 Error

  */
  function test_shopify_error_message_422() {

    // Act
    try {
      $mockResponse = self::$WS->mock_shopify_error(422);

    } catch (\Exception $mockErrorResponse) {

      $errorMessage = self::$WS->wps_get_error_message($mockErrorResponse);

      // Assert
      $this->assertEquals(self::$Messages->message_shopify_api_422, $errorMessage);

    }

  }


  /*

  Mock: 429 Error

  */
  function test_shopify_error_message_429() {

    // Act
    try {
      $mockResponse = self::$WS->mock_shopify_error(429);

    } catch (\Exception $mockErrorResponse) {

      $errorMessage = self::$WS->wps_get_error_message($mockErrorResponse);

      // Assert
      $this->assertEquals(self::$Messages->message_shopify_api_429, $errorMessage);

    }

  }


  /*

  Mock: 500 Error

  */
  function test_shopify_error_message_500() {

    // Act
    try {
      $mockResponse = self::$WS->mock_shopify_error(500);

    } catch (\Exception $mockErrorResponse) {

      $errorMessage = self::$WS->wps_get_error_message($mockErrorResponse);

      // Assert
      $this->assertEquals(self::$Messages->message_shopify_api_500, $errorMessage);

    }

  }


  /*

  Mock: 501 Error

  */
  function test_shopify_error_message_501() {

    // Act
    try {
      $mockResponse = self::$WS->mock_shopify_error(501);

    } catch (\Exception $mockErrorResponse) {

      $errorMessage = self::$WS->wps_get_error_message($mockErrorResponse);

      // Assert
      $this->assertEquals(self::$Messages->message_shopify_api_501, $errorMessage);

    }

  }


  /*

  Mock: 503 Error

  */
  function test_shopify_error_message_503() {

    // Act
    try {
      $mockResponse = self::$WS->mock_shopify_error(503);

    } catch (\Exception $mockErrorResponse) {

      $errorMessage = self::$WS->wps_get_error_message($mockErrorResponse);

      // Assert
      $this->assertEquals(self::$Messages->message_shopify_api_503, $errorMessage);

    }

  }


  /*

  Mock: 504 Error

  */
  function test_shopify_error_message_504() {

    // Act
    try {
      $mockResponse = self::$WS->mock_shopify_error(504);

    } catch (\Exception $mockErrorResponse) {

      $errorMessage = self::$WS->wps_get_error_message($mockErrorResponse);

      // Assert
      $this->assertEquals(self::$Messages->message_shopify_api_504, $errorMessage);

    }

  }


}
