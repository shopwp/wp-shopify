<section class="wps-collections-products <?php echo apply_filters('wps_collection_single_products_class', ''); ?>">

  <?php do_action('wps_collection_single_products_before', $collection, $products); ?>

  <ul class="wps-row wps-row-left wps-collections-products">

    <?php foreach ($products as $key => $product) { ?>

      <?php do_action('wps_collection_single_product', $product); ?>

    <?php } ?>

  </ul>

</section>
