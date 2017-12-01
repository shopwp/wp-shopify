<?php

// TODO: Add actions / filters to all webhooks
use WPS\DB\Settings_Connection;
use WPS\Cart;
use WPS\Transients;

$Connection = new Settings_Connection();

$order = json_decode( file_get_contents('php://input') );

$cartID = Cart::wps_get_cart_id_from_order($order);

Transients::delete('wps_cart_' . $cartID);
