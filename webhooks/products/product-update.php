<?php

// TODO: Implement security checks: Shopify HMAC and Domain from headers
use WPS\DB\Products;
use WPS\Config;
use WPS\Webhooks;
use WPS\WS;

$Products = new Products(new Config());
$jsonData = file_get_contents('php://input');
$product = json_decode($jsonData);
$Products->update_product($product);





// if (Webhooks::webhook_verified($jsonData, WS::get_header_hmac())) {
//
//   $product = json_decode($jsonData);
//   error_log('---- Webhook verified -----');
//
//   $Products->update_product($product);
//
// } else {
//   error_log('WP Shopify Error - Unable to verify webhook response from product-update.php');
// }
