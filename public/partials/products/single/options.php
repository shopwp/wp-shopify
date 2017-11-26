<?php

use WPS\Utils;

if (count($product['options']) === 1) {
  $col = 2;

} else {
  $col = count($product['options']);
}

$uid = 0;
$optionNumber = 1;
$options = Utils::wps_sort_by($product['options'], 'position');

?>

<?php foreach ($options as $key => $option) { ?>

  <div
    class="wps-btn-dropdown wps-col wps-col-<?php echo $col; ?>"
    data-selected="false"
    data-open="false"
    data-selected-val=""
    data-option="<?php echo $optionNumber; ?>">

    <a
      href="#!"
      class="wps-btn wps-icon wps-icon-dropdown wps-modal-trigger"
      data-option="<?php echo $option['name']; ?>"
      data-option-id="<?php echo $option['id']; ?>">
        <?php echo $option['name']; ?>
    </a>

    <ul class="wps-modal">

      <?php foreach (unserialize($option['values']) as $key => $variant) { ?>

        <li
          itemprop="category"
          class="wps-product-style wps-modal-close-trigger"
          data-option-id="<?php echo $uid; ?>"
          data-variant-title="<?php echo $variant; ?>"
          data-option-position="<?php echo $option['position']; ?>">
            <?php echo $variant; ?>
        </li>

        <?php $uid++; ?>

      <?php } ?>

    </ul>

  </div>


<?php

$optionNumber++;

}


?>
