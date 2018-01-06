<?php

use WPS\Webhooks;
use WPS\WS;
use WPS\DB\Orders;

$jsonData = file_get_contents('php://input');
$Orders = new Orders();

if (Webhooks::webhook_verified($jsonData, WS::get_header_hmac())) {

  error_log('---- Webhook verified order-updated -----');
  $orderData = json_decode($jsonData);
  $Orders->update_orders($orderData);

} else {
  error_log('WP Shopify Error - Unable to verify webhook response from order-updated.php');
}
