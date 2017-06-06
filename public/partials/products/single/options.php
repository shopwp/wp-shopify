<?php

if (count($product['options']) === 1) {
  $col = 2;

} else {
  $col = count($product['options']);
}

?>

<?php foreach ($product['options'] as $key => $option) { ?>
  <div class="wps-btn-dropdown wps-col-<?php echo $col; ?>"
       data-selected="false"
       data-selected-val="">

    <a href="#" class="wps-btn wps-icon wps-icon-dropdown wps-modal-trigger"><?php echo $option['name']; ?></a>

    <ul class="wps-modal">
      <?php foreach (unserialize($option['values']) as $key => $variant) { ?>
        <li class="wps-product-style wps-modal-close-trigger" data-id="<?php ?>"><?php echo $variant; ?></li>
      <?php } ?>
    </ul>
  </div>
<?php } ?>
