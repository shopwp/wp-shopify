<?php

/*

@description   Represents the first page link within the pagination

@version       1.0.0
@since         1.0.49
@path          templates/partials/pagination/page-first.php

@docs          https://wpshop.io/docs/templates/pagination/page-first

*/

if ( !defined('ABSPATH') ) {
	exit;
}

?>

<a itemprop="url" href="<?php echo $data->page_href; ?>" class="wps-products-page-first" itemprop="item"><?php echo $data->first_page_text; ?></a>
