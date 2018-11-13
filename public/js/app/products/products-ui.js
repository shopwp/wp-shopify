import { elementExists } from '../utils/utils-common';
import { setIntialPricing } from '../ws/ws-products';

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











function cacheInitialSingleProductPricing() {

}


function getProductMetaPricingDOM() {
  return jQuery('.wps-product-meta').parent().find('> .wps-product-pricing');
}

function cacheInitialProductsPricing() {

  var $pricing = getProductMetaPricingDOM();

  jQuery.each($pricing, function(index, element) {

    var handle = jQuery(element).parent().find('.wps-product-meta').data('product-handle');

    setIntialPricing(handle, element.outerHTML);

  });

}


function cacheInitialProductPricing() {

  cacheInitialSingleProductPricing();
  cacheInitialProductsPricing();

}



export {
  showProductsMetaUI,
  hideAllOpenProductDropdowns,
  cacheInitialProductPricing
}
