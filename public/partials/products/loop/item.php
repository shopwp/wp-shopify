<?php

do_action('wps_products_item_before', $product, $settings);
do_action('wps_products_img_before', $product);
do_action('wps_products_img', $product);
do_action('wps_products_title_before', $product);
do_action('wps_products_title', $product);
do_action('wps_products_price_before', $product);
do_action('wps_products_price', $product);
do_action('wps_products_price_after', $product);
do_action('wps_products_item_after', $product);
