<?php

// TODO: Implement security checks: Shopify HMAC and Domain from headers
use WPS\DB\Products;
use WPS\Config;

$Products = new Products(new Config());

$product = json_decode( file_get_contents('php://input') );

$Products->update_product($product);
