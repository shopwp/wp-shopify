<?php

if (count($product['options']) === 1) {

  if (count($product['variants']) > 1) {
    $col = 2;

  } else {
    $col = 1;
  }

} else if (count($product['options']) === 2) {
  $col = 1;

} else if (count($product['options']) === 3) {
  $col = 1;
}

?>
<div class="wps-btn-wrapper wps-col wps-col-<?php echo $col; ?>">
  <a href="#" class="wps-btn wps-col-1 wps-btn-secondary wps-add-to-cart">Add to cart</a>
</div>
