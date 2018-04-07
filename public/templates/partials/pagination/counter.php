<?php

/*

@description   Range that displays above page numbers. This is set to the following structure: Page X of Y.

@version       1.0.0
@since         1.0.49
@path          templates/partials/pagination/counter.php

@docs          https://wpshop.io/docs/templates/pagination/counter

*/

if ( !defined('ABSPATH') ) {
	exit;
}

?>

<div itemprop="description" class="wps-products-page-counter"><?php echo sprintf( __( 'Page %s of %s' ), $data->page_number, $data->max_pages ); ?></div>
