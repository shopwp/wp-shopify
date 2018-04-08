<?php

/*

@description   Represents the previous page link withinin the pagination

@version       1.0.0
@since         1.0.49
@path          templates/partials/pagination/page-previous.php

@docs          https://wpshop.io/docs/templates/pagination/page-previous

*/

if ( !defined('ABSPATH') ) {
	exit;
}

?>

<a itemprop="url" href="<?= $data->page_href; ?>" class="wps-products-page-previous" itemprop="item"><?= $data->page_previous_text; ?></a>
