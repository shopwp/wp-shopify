<header class="wps-product-header">

  <h1
    itemprop="name"
    class="entry-title wps-product-heading">
      <?php echo $product['details']['title']; ?>
  </h1>

  <?php

  do_action('wps_product_single_header_price_before', $product);
  do_action('wps_product_single_header_price', $product);
  do_action('wps_product_single_header_price_after', $product);

  ?>

</header>
