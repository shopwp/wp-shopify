<?php

use WPS\DB\Shop;
use WPS\Webhooks;
use WPS\WS;

$jsonData = file_get_contents('php://input');

if (Webhooks::webhook_verified($jsonData, WS::get_header_hmac())) {

  error_log('---- Webhook verified shop-update -----');
  $shopData = json_decode($jsonData);

  $Shop = new Shop();
  $Shop->update_shop($shopData);

} else {
  error_log('WP Shopify Error - Unable to verify webhook response from shop-update.php');
}
