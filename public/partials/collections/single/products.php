<section>

  <?php do_action('wps_collections_single_products_before', $collection, $products); ?>

  <ul class="wps-l-row wps-l-row-left wps-collections-products">

    <?php foreach ($products as $key => $product) { ?>
      <li class="wps-col-3 wps-collections-product">
        <a href="/products/<?php echo $product->handle; ?>" class="wps-collections-product-link">
          <img src="<?php echo $product->image; ?>" alt="<?php echo $product->title; ?>" class="wps-collections-product-img" />
          <h2 class="wps-collections-product-title wps-products-title"><?php echo $product->title; ?></h2>
          <h3 class="wps-products-price"><?php echo $product->variants[0]->price; ?></h3>
        </a>
      </li>
    <?php } ?>

  </ul>

</section>
