<?php

use WPS\Webhooks;
use WPS\WS;
use WPS\DB\Orders;

$jsonData = file_get_contents('php://input');

if (Webhooks::webhook_verified($jsonData, WS::get_header_hmac())) {

  $Orders = new Orders();
  $orderData = json_decode($jsonData);

  $Orders->update_orders($orderData);

} else {
  error_log('WP Shopify Error - Unable to verify webhook response from order-updated.php');
}
