<?php

use WPS\Utils;

?>
<header class="wps-product-header">
  <h1 class="entry-title wps-product-heading"><?php echo $product['details']['title']; ?></h1>
  <p class="wps-product-price"><?php echo Utils::wps_format_money($product['variants'][0]['price']); ?></p>
</header>
