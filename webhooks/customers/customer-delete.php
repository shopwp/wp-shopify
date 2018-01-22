<?php

use WPS\Webhooks;
use WPS\WS;
use WPS\DB\Customers;
use WPS\Utils;

$jsonData = file_get_contents('php://input');

if (Webhooks::webhook_verified($jsonData, WS::get_header_hmac())) {

  $Customers = new Customers();

  $customer = json_decode($jsonData);
  $Customers->delete_customer($customer);

} else {
  error_log('WP Shopify Error - Unable to verify webhook response from customer-delete.php');
}
