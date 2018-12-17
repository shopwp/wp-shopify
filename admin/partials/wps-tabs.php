<h2 class="nav-tab-wrapper">

  <a href="#" class="nav-tab  <?= $active_tab === 'tab-connect' ? 'nav-tab-active' : ''; ?>" data-tab="tab-connect">
    <?php esc_html_e('Connect', WPS_PLUGIN_TEXT_DOMAIN); ?>
  </a>

  <a href="#" class="nav-tab <?= $active_tab === 'tab-settings' ? 'nav-tab-active' : ''; ?>" data-tab="tab-settings">
    <?php esc_html_e('Settings', WPS_PLUGIN_TEXT_DOMAIN); ?>
  </a>

  <a href="#" class="nav-tab <?= $active_tab === 'tab-tools' ? 'nav-tab-active' : ''; ?>" data-tab="tab-tools">
    <?php esc_html_e('Tools', WPS_PLUGIN_TEXT_DOMAIN); ?>
  </a>

  <a href="#" class="nav-tab <?= $active_tab === 'tab-license' ? 'nav-tab-active' : ''; ?>" data-tab="tab-license">
    <?php esc_html_e('License', WPS_PLUGIN_TEXT_DOMAIN); ?>
  </a>

  <a href="#" class="nav-tab <?= $active_tab === 'tab-help' ? 'nav-tab-active' : ''; ?>" data-tab="tab-help">
    <?php esc_html_e('Debug', WPS_PLUGIN_TEXT_DOMAIN); ?>
  </a>

  <a href="#" class="nav-tab <?= $active_tab === 'tab-misc' ? 'nav-tab-active' : ''; ?>" data-tab="tab-misc">
    <?php esc_html_e('Misc', WPS_PLUGIN_TEXT_DOMAIN); ?>
  </a>

</h2>
