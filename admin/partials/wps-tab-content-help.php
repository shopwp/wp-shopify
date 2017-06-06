<!--

Tab Content: Help / Debug

-->
<div class="tab-content <?php echo $tab === 'help' ? 'tab-content-active' : ''; ?>" data-tab-content="tab-help">

  <div class="wps-admin-section">

    <h3 class="wps-admin-section-heading">Help</h3>

    <p>If you're reading this then you're currently using a beta version of WPS. With all beta's come the inevitable bugs. If you're running into problems don't hesitate to jump into our public Slack to <a href="https://join.slack.com/wpshopify/shared_invite/MTg5OTQxODEwOTM1LTE0OTU5ODY2MTktN2Y1ODk0YzBlNg">ask a question</a>. If Slack isn't your thing, feel free to send us an email outlining your problem to <a href="mailto:hello@wpshop.io">hello@wpshop.io</a></p>

    <p>Also make sure to review the documentation found here: <a href="<?php echo $this->config->plugin_env; ?>/docs"><?php echo $this->config->plugin_env; ?>/docs</a></p>

  </div>


  <!-- Test buttons -->
  <!-- <button type="button" name="button" class="button wps-btn wps-btn-uninstall">Uninstall ...</button>
  <button type="button" name="button" class="button wps-btn wps-btn-sync-data">Sync proudcts</button>
  <button type="button" name="button" class="button wps-btn wps-btn-wh-add">Add Webhooks</button>
  <button type="button" name="button" class="button wps-btn wps-btn-wh-get">Get list of Webhooks</button>
  <button type="button" name="button" class="button wps-btn wps-btn-wh-del">Delete Webhooks</button>
  <button type="button" name="button" class="button wps-btn" id="testing">Testing ...</button>
  <button type="button" name="button" class="button wps-btn wps-btn-collections-product">Get collections from product</button> -->

</div>
