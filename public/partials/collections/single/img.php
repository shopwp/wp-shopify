<?php if (!empty($collection->image)) { ?>
  <img
    itemprop="image"
    src="<?php echo $collection->image; ?>"
    alt="<?php echo $collection->title . ' collection '; ?>"
    class="wps-collection-img <?php echo apply_filters('wps_collections_single_img_class', ''); ?>">
<?php } ?>
