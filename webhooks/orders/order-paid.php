<?php

// TODO: Add actions / filters to all webhooks
use WPS\DB\Settings_Connection;
use WPS\Cart;
use WPS\Transients;
use WPS\Webhooks;
use WPS\WS;

$jsonData = file_get_contents('php://input');


if (Webhooks::webhook_verified($jsonData, WS::get_header_hmac())) {

  $order = json_decode($jsonData);
  $cartID = Cart::wps_get_cart_id_from_order($order);

  Transients::delete('wps_cart_' . $cartID);

  do_action('wps_webhook_checkouts_order_paid', $order);

} else {
  error_log('WP Shopify Error - Unable to verify response from order-paid webhook');
}
