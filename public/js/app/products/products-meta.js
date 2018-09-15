import to from 'await-to-js';
import { setCurrentlySelectedVariants } from '../ws/ws-products';
import { getClient } from '../utils/utils-client';
import { isGraphqlError, logNotice } from '../utils/utils-notices';

import filter from 'lodash/filter';


/*

Updates product title (product is the actual DOM element)

*/
function updateProductTitle(productTitle, title) {
  jQuery(productTitle).text(title);
}


/*

Updates product image

*/
function updateVariantImage(productImage, image) {
  jQuery(productImage).attr('src', image.src);
}


/*

Update product variant title

*/
function updateVariantTitle(productVariantTitle, variant) {
  jQuery(productVariantTitle).text(variant.title);
}


/*

Update product variant price

*/
function updateVariantPrice(productVariantPrice, variant) {
  jQuery(productVariantPrice).text('$' + variant.price);
}


/*

Show Hidden Product Variants

*/
function showHiddenProductVariants() {
  jQuery('.wps-modal .wps-product-style').removeClass('wps-is-hidden');
}


function addProductIdsToMeta($element, productID) {
  $element.attr('data-product-storefront-id', productID);
}


/*

Resets variant selection of all products

*/
function resetVariantSelectors() {

  jQuery('.wps-btn-dropdown[data-selected=true]').each(function (index, element) {

    var $dropdown = jQuery(element);
    var $dropdownLink = $dropdown.find('.wps-modal-trigger');

    $dropdown.attr('data-selected', false);
    $dropdown.data('selected', false);

    // Resets the dropdown text to the Option name (Size, Color, Material, etc)
    $dropdownLink.html($dropdownLink.attr("data-option"));

  });

  setCurrentlySelectedVariants({});
  showHiddenProductVariants();

}


/*

Gets the amount of dropdowns currently selected
Returns: Int

*/
function getCurrentlySelectedOptions() {

  var options = [];
  var $options = jQuery('.wps-btn-dropdown[data-selected="true"]');

  $options.each((index, value) => {
    options.push( jQuery(value).data('selected-val') );
  });

  return options;

}


/*

Gets the amount of dropdowns currently selected
Returns: Int

*/
function getCurrentlySelectedOptionsAmount() {
  return jQuery('.wps-btn-dropdown[data-selected="true"]').length;
}


/*

Reset Options Selection

*/
function resetOptionsSelection() {

  jQuery('.wps-product-meta').data('product-selected-options', '');
  jQuery('.wps-product-meta').attr('data-product-selected-options', '');

}


/*

Get Deselected Dropdowns

*/
function getDeselectedDropdowns($trigger) {

  var $deselectedDropdowns = $trigger.closest('.wps-product-actions-group').find('.wps-btn-dropdown[data-selected="false"]');

  return filter( $deselectedDropdowns, function($option) {
    return jQuery($option).data('selected') === false;
  });

}


export {
  resetVariantSelectors,
  updateProductTitle,
  updateVariantImage,
  updateVariantTitle,
  updateVariantPrice,
  resetOptionsSelection,
  showHiddenProductVariants,
  getDeselectedDropdowns,
  getCurrentlySelectedOptionsAmount,
  getCurrentlySelectedOptions
}
