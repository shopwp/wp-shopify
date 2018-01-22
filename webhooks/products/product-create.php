<?php

use WPS\DB\Products;
use WPS\Config;
use WPS\Webhooks;
use WPS\WS;

$Products = new Products(new Config());
$jsonData = file_get_contents('php://input');


if (Webhooks::webhook_verified($jsonData, WS::get_header_hmac())) {

  $WS = new WS(new Config());
  $WS->wps_ws_set_syncing_indicator(false, 1);

  $product = json_decode($jsonData);
  $Products->create_product($product);

  $WS->wps_ws_set_syncing_indicator(false, 0);


} else {
  error_log('WP Shopify Error - Unable to verify webhook response from product-create.php');
}
