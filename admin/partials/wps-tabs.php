<!--

Tab navs

-->

<?php


if('wp-shopify_page_wps-settings' == get_current_screen()->id ) {

  if (isset($_GET['tab']) && $_GET['tab']) {
    $tab = $_GET['tab'];
  } else {
    $tab = false;
  }

}

?>


<h2 class="nav-tab-wrapper">
  <a href="" class="nav-tab  <?php echo $tab === false ? 'nav-tab-active' : ''; ?>" data-tab="tab-connect">Connect</a>
  <a href="" class="nav-tab <?php echo $tab === 'settings' ? 'nav-tab-active' : ''; ?>" data-tab="tab-settings">Settings</a>
  <!-- <a href="" class="nav-tab <?php echo $tab === 'tools' ? 'nav-tab-active' : ''; ?>" data-tab="tab-tools">Tools</a> -->
  <a href="" class="nav-tab <?php echo $tab === 'updates' ? 'nav-tab-active' : ''; ?>" data-tab="tab-updates">License / Updates</a>
  <a href="" class="nav-tab <?php echo $tab === 'help' ? 'nav-tab-active' : ''; ?>" data-tab="tab-help">Help / Debug</a>
</h2>
