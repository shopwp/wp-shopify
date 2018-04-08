<?php

/*

@description   Notice component used to show related add to cart errors

@version       1.0.0
@since         1.0.49
@path          templates/partials/products/add-to-cart/notice-inline.php

@docs          https://wpshop.io/docs/templates/partials/products/add-to-cart/notice-inline

*/

if ( !defined('ABSPATH') ) {
	exit;
}

?>

<aside class="wps-notice-inline wps-product-notice wps-notice-inline-sm <?= apply_filters('wps_notice_inline_class', ''); ?>"></aside>
