<?php

use WPS\DB\Products;
use WPS\Config;
use WPS\Webhooks;
use WPS\WS;

$Products = new Products(new Config());
$jsonData = file_get_contents('php://input');


if (Webhooks::webhook_verified($jsonData, WS::get_header_hmac())) {

  $product = json_decode($jsonData);
  $Products->delete_product($product);

} else {
  error_log('WP Shopify Error - Unable to verify webhook response from product-delete.php');
}
