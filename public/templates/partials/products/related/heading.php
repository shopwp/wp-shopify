<?php

/*

@description   Opening tag for related products heading

@version       1.0.0
@since         1.0.49
@path          templates/partials/products/related/heading-start.php

@docs          https://wpshop.io/docs/templates/products/related/heading-start

*/

if ( !defined('ABSPATH') ) {
	exit;
}

?>

<h1 class="wps-related-products-heading <?= apply_filters('wps_related_products_heading_class', ''); ?>">
	<?= apply_filters('wps_products_related_heading_text', 'Related Products'); ?>
</h1>
