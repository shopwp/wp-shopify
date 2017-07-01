<li class="wps-col wps-col-3 wps-collection-single-product">
  <a href="/products/<?php echo $product->handle; ?>" class="wps-collections-product-link">
    <img src="<?php echo $product->image; ?>" alt="<?php echo $product->title; ?>" class="wps-products-img" />
    <h2 class="wps-collections-product-title wps-products-title"><?php echo $product->title; ?></h2>
    <h3 class="wps-products-price">
      <?php echo WPS\Utils::wps_format_money($product->variants[0]->price, $product->variants[0]); ?>
    </h3>
  </a>
</li>
