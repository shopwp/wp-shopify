<?php

/*

@description   Opening tag for related products heading

@version       1.0.1
@since         1.0.49
@path          templates/partials/products/related/heading-start.php

@docs          https://wpshop.io/docs/templates/products/related/heading-start

*/

if ( !defined('ABSPATH') ) {
	exit;
}

?>

<?php if ( apply_filters('wps_related_products_heading_show', true) ) { ?>

	<h1 class="wps-related-products-heading <?= apply_filters('wps_related_products_heading_class', ''); ?>">
		<?= $data->heading; ?>
	</h1>

<?php } ?>
