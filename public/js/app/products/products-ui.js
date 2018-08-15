/*

Show Products meta UI elements

*/
function showProductsMetaUI() {

  jQuery('.wps-product-meta')
    .addClass('wps-fadeIn')
    .removeClass('wps-is-disabled wps-is-loading');

}

function getOpenProductDropdowns() {
  return jQuery('.wps-btn-dropdown[data-open="true"]');
}

function hideDropdowns($openDropdowns) {
  $openDropdowns.attr('data-open', false);
}

function hideAllOpenProductDropdowns() {
  hideDropdowns( getOpenProductDropdowns() );
}

export {
  showProductsMetaUI,
  hideAllOpenProductDropdowns
}
