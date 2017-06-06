<button class="<?php echo apply_filters( 'wps_cart_btn_class', ''); ?> wps-btn-cart">

  <?php

  do_action('wps_cart_counter');
  do_action('wps_cart_icon');

  ?>

</button>
