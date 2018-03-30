<header class="wps-product-header">

  <h1
    itemprop="name"
    class="entry-title wps-product-heading">
      <?php esc_html_e($data->product['details']['title'], 'wp-shopify'); ?>
  </h1>

  <?php

  do_action('wps_product_single_header_price_before', $data->product);
  do_action('wps_products_price', $data->product);
  do_action('wps_product_single_header_price_after', $data->product);

  ?>

</header>
