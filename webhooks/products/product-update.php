<?php

// TODO: Implement security checks: Shopify HMAC and Domain from headers
use WPS\DB\Settings_Connection;
use WPS\DB\Products;
use WPS\Config;

$Products = new Products(new Config());
$Connection = new Settings_Connection();

$product = json_decode( file_get_contents('php://input') );

$Connection->turn_on_need_cache_flush();
$Products->update_product($product);
