<?php

/*

@description   Opening tag for product single page content (right column next to image gallery)

@version       1.0.0
@since         1.0.49
@path          templates/partials/products/single/info-start.php

@docs          https://wpshop.io/docs/templates/products/single/info-start

*/

if ( !defined('ABSPATH') ) {
	exit;
}

?>

<article <?php post_class('wps-col wps-col-2 wps-product-info'); ?>>
