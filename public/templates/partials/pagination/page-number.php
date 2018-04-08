<?php

/*

@description   Represents a normal page number withinin the pagination

@version       1.0.0
@since         1.0.49
@path          templates/partials/pagination/page-number.php

@docs          https://wpshop.io/docs/templates/pagination/page-number

*/

if ( !defined('ABSPATH') ) {
	exit;
}

?>

<a itemprop="url" href="<?= $data->page_href; ?>" class="wps-products-page-inactive" itemprop="item"><?= $data->page_number; ?></a>
