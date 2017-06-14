/*

Admin Notice

*/
function addAdminNotice() {

  var $postBody = jQuery('#post');

  $postBody.after('<div class="wps-admin-notice postbox"><h1 class="wps-admin-heading">2-way syncing coming soon!</h1><p>WP Shopify currently only performs one-way syncing. We\'re planning to release two-way syncing soon. You can learn more about it <a href="https://staging.wpshop.io" target="_blank">here</a>.</p><p>In the meantime, please head over to <a href="https://www.shopify.com/login" target="_blank">Shopify</a> to make any necessary content updates. They\'ll automatically appear here.</p><p>If you need any help please contact support on Slack or send us an email at <a href="mailto:help@wpshop.io">help@wpshop.io</a></p></div>');

}

function initTooltips() {
  jQuery('.wps-help-tip').tooltipster({
    animation: 'fade',
    delay: 0,
    animationDuration: [100, 100],
    interactive: true,
    IEmin: 8,
    timer: 0
  });
}


/*

Init Admin

*/
function initAdmin() {
  addAdminNotice();
  initTooltips();
}

export {
  initAdmin
}
