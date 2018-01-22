<?php

use WPS\Webhooks;
use WPS\WS;

$jsonData = file_get_contents('php://input');


if (Webhooks::webhook_verified($jsonData, WS::get_header_hmac())) {

  $orderData = json_decode($jsonData);

} else {
  error_log('WP Shopify Error - Unable to verify webhook response from order-partially-fulfilled.php');
}
