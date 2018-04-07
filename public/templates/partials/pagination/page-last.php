<?php

/*

@description   Represents the last page link within the pagination

@version       1.0.0
@since         1.0.49
@path          templates/partials/pagination/page-last.php

@docs          https://wpshop.io/docs/templates/pagination/page-last

*/

if ( !defined('ABSPATH') ) {
	exit;
}

?>

<a itemprop="url" href="<?php echo $data->page_href; ?>" class="wps-products-page-last" itemprop="item"><?php echo $data->page_last_text; ?></a>
