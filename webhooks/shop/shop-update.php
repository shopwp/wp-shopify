<?php

use WPS\DB\Shop;

$DB_Shop = new Shop();
$shop = json_decode( file_get_contents('php://input') );

$DB_Shop->update_shop($shop);
