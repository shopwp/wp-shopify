<?php

/*

@description   Represents the 'current' page number in the pagination

@version       1.0.0
@since         1.0.49
@path          templates/partials/pagination/page-number-current.php

@docs          https://wpshop.io/docs/templates/pagination/page-number-current

*/

if ( !defined('ABSPATH') ) {
	exit;
}

?>

<span itemprop="identifier" class="wps-products-page-current"><?php echo $data->page_number; ?></span>
