<h3
  itemprop="offers"
  itemscope
  itemtype="https://schema.org/Offer"
  class="wps-products-price wps-products-price-multi <?php echo apply_filters( 'wps_products_price_class', '' ); ?>">

  <?php echo apply_filters('wps_products_price_multi', $data->price, $data->priceFirst, $data->priceLast, $data->product); ?>

</h3>
