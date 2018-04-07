<?php

/*

@description   Represents the next page link within the pagination

@version       1.0.0
@since         1.0.49
@path          templates/partials/pagination/page-next.php

@docs          https://wpshop.io/docs/templates/pagination/page-next

*/

if ( !defined('ABSPATH') ) {
	exit;
}

?>

<a itemprop="url" href="<?php echo $data->page_href ?>" class="wps-products-page-next" itemprop="item"><?php echo $data->page_next_text; ?></a>
