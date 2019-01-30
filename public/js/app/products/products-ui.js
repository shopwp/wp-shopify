import { elementExists } from '../utils/utils-common';
import { setIntialPricing } from '../ws/ws-products';

import { convertAndFormatPrice } from '../pricing/pricing-currency';


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


function isMultiPriceWrapper($element) {
  return $element.closest('[data-wps-is-multi-price]').length;
}

function makeReadyPriceWrapper($priceElementWrapper) {
  $priceElementWrapper.closest('[data-wps-is-price-wrapper]').addClass('wps-is-ready');
}


function replacePriceMarkup($priceWrapper, priceHTML) {
  $priceWrapper.html(priceHTML);
}


/*

$priceElement is an element with [data-wps-is-price-wrapper]. Should contain any DOM updates

*/
function changePriceMarkup($priceElementWrapper, amount) {

  var priceHTML = convertAndFormatPrice(amount);

  replacePriceMarkup($priceElementWrapper, priceHTML);
  makeReadyPriceWrapper($priceElementWrapper);

}

function getAllPriceElements() {
  return jQuery('[itemprop="price"]');
}


function changeAllPricingToLocal() {

  var $elements = getAllPriceElements();

  jQuery.each($elements, function(index, element) {

    var $element = jQuery(element);

    changePriceMarkup( $element.parent(), $element.text() );

  });

}


function cacheInitialProductPricing() {

  cacheInitialSingleProductPricing();
  cacheInitialProductsPricing();

}



export {
  showProductsMetaUI,
  hideAllOpenProductDropdowns,
  cacheInitialProductPricing,
  changeAllPricingToLocal,
  changePriceMarkup
}
