<?php

use WPS\DB\Shop;

$Shop = new Shop();
$shop = json_decode( file_get_contents('php://input') );

$Shop->update_shop($shop);
