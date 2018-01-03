<!--

Tab navs

-->

<?php

// TODO: Clean up
if ('wp-shopify_page_wps-settings' == get_current_screen()->id ) {

  if (isset($_GET['tab']) && $_GET['tab']) {
    $tab = $_GET['tab'];

  } else {
    $tab = false;
  }

}

// $tab = 'updates';

?>


<h2 class="nav-tab-wrapper">

  <a href="#!" class="nav-tab  <?php echo $tab === false ? 'nav-tab-active' : ''; ?>" data-tab="tab-connect">
    <?php esc_html_e('Connect', 'wp-shopify'); ?>
  </a>

  <a href="#!" class="nav-tab <?php echo $tab === 'settings' ? 'nav-tab-active' : ''; ?>" data-tab="tab-settings">
    <?php esc_html_e('Settings', 'wp-shopify'); ?>
  </a>

  <a href="#!" class="nav-tab <?php echo $tab === 'tools' ? 'nav-tab-active' : ''; ?>" data-tab="tab-tools">
    <?php esc_html_e('Tools', 'wp-shopify'); ?>
  </a>

  <a href="#!" class="nav-tab <?php echo $tab === 'updates' ? 'nav-tab-active' : ''; ?>" data-tab="tab-updates">
    <?php esc_html_e('License / Updates', 'wp-shopify'); ?>
  </a>

  <a href="#!" class="nav-tab <?php echo $tab === 'help' ? 'nav-tab-active' : ''; ?>" data-tab="tab-help">
    <?php esc_html_e('Help / Debug', 'wp-shopify'); ?>
  </a>

</h2>
