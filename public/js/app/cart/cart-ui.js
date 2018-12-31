import to from 'await-to-js';
import size from 'lodash/size';
import castArray from 'lodash/castArray';
import last from 'lodash/last';
import first from 'lodash/first';
import forEach from 'lodash/forEach';
import filter from 'lodash/filter';
import isEmpty from 'lodash/isEmpty';
import uniqBy from 'lodash/uniqBy';
import has from 'lodash/has';


import { formatAsMoney, turnAnimationFlagOn, formatTotalAmount, elementExists, trimHTMLFromMoneyFormat } from '../utils/utils-common';
import { createLineItemsFromVariants, createLineItemsMarkup, cartTermsState } from '../ws/ws-cart';
import { getMoneyFormat, getShop } from '../ws/ws-shop';
import { enable, disable } from '../utils/utils-ux';
import { bounceIn, slideInLeft, slideInRight, slideOutRight, pulse } from '../utils/utils-animations';
import { isCheckoutEmpty, lineItemExists } from '../utils/utils-cart';


function disableCartIcon() {

  var $cartIcon = jQuery('.wps-btn-cart');

  disable($cartIcon);
  $cartIcon.addClass('wps-is-disabled wps-is-loading');

}


function enableCartIcon() {

  var $cartIcon = jQuery('.wps-btn-cart');

  enable($cartIcon);
  $cartIcon.removeClass('wps-is-disabled wps-is-loading');

}


/*

Disables cart

*/
function disableCartItem($cartItem) {

  disable($cartItem);
  disableCartIcon();

  $cartItem.addClass('wps-is-disabled wps-is-loading');

}


/*

Enables cart

*/
function enableCartItem($cartItem) {

  var $cartIcon = jQuery('.wps-btn-cart'); // cart icon

  enable($cartItem);
  enableCartIcon();

  $cartItem.removeClass('wps-is-disabled wps-is-loading');

}


/*

Checks the state of the has cart terms flag. Updated within plugin settings.

Under test

*/
function hasCartTerms() {

  var cartTerms = WP_Shopify.hasCartTerms;

  if (cartTerms == 1) {
    return true;
  }

  if (cartTerms == 0) {
    return false;
  }

}


/*

Gets the current value of the terms checkbox

Under test

*/
function cartTermsAccepted() {
  return jQuery('#wps-terms-checkbox').prop('checked');
}


/*

Checkout conditions met

Under test

*/
function checkoutConditionsMet() {

  if ( hasCartTerms() ) {

    if ( cartTermsAccepted() ) {
      return true;
    }

    return false;

  }

  return true;

}


/*

Enable checkout button

Under test

*/
function enableCheckoutButton() {
  enable( jQuery('.wps-btn-checkout').removeClass('wps-is-disabled wps-is-loading') );
}


/*

Disable checkout button

Under test

*/
function disableCheckoutButton() {
  disable( jQuery('.wps-btn-checkout').addClass('wps-is-disabled') );
}


/*

Update product variant price

*/
function updateSubtotal(formattedSubtotal) {

  var $subtotal = jQuery('.wps-cart .wps-pricing');

  $subtotal.text(formattedSubtotal);

  pulse(jQuery('.wps-cart .wps-cart-info__pricing'));

}


function updateTotalCartPricing(checkout) {
  updateSubtotal( formatAsMoney(checkout.subtotalPrice) );
}


/*

Creates our Empty Cart Notice

*/
function createEmptyCartNotice() {
  return jQuery('<aside class="wps-cart-empty-notice"><h2>Your cart is empty</h2></aside>');
}


/*

Creates our Empty Cart Notice

*/
function removeVariantSelections() {
  jQuery('.wps-btn-dropdown[data-selected="true"]').attr('data-selected', false);
  jQuery('.wps-btn-dropdown[data-selected="true"]').data('selected', false);
}


/*

Adds each line item quantity together

*/
function addLineItemTotals(total, item) {
  return total + item.quantity;
}


/*

Gets the checkout grand total by adding each line item quantity

*/
function getCartGrandTotal(checkout) {
  return checkout.lineItems.reduce(addLineItemTotals, 0);
}


function addCheckoutQuantityToCounter($counter, totalItems) {
  return $counter.html(totalItems);
}

function showCheckoutCounter($counter) {

  jQuery('.wps-btn-cart-fixed').removeClass('wps-is-cart-empty');

  $counter.removeClass('wps-is-hidden');
  
}

function setCheckoutCounterSize($checkoutCounter, checkout) {

  if (getCartGrandTotal(checkout) >= 10) {
    $checkoutCounter.addClass('wps-cart-counter-lg');

  } else {
    $checkoutCounter.removeClass('wps-cart-counter-lg');

  }

}

/*

Update Cart Icon Amount

*/
function updateCartCounter(client, checkout) {

  var $checkoutCounter = jQuery('.wps-cart-counter');

  addCheckoutQuantityToCounter( $checkoutCounter, getCartGrandTotal(checkout) );
  showCheckoutCounter($checkoutCounter);

  setCheckoutCounterSize($checkoutCounter, checkout);

  if ($checkoutCounter.length) {
    bounceIn($checkoutCounter);
  }

}


/*

Find Line Item By Variant ID

*/
function findLineItemByVariantID(checkout, variant) {
  return checkout.lineItems.filter( lineItem => lineItem.variant.id === variant.id )[0];
}


/*

Param: Can be product or variant ID

*/
function findExistingLineItem(id) {

  return jQuery('.wps-cart-item-container')
    .find('.wps-quantity-decrement[data-variant-id="' + id + '"]')
    .closest('.wps-cart-item');

}


function removeLineItemByVariantID(variantID) {
  return findExistingLineItem(variantID).remove();
}


/*

Updates the single line item HTML
Returns: String of updated HTML

*/
function updateSingleProductCartDOM(lineItem, variant) {

  // Remove item if quantity equals 0
  if (typeof lineItem === "undefined") {

    return removeLineItemByVariantID(variant.id);

  } else {

    var $foundLineItem = findExistingLineItem(lineItem.variant.id);
    var lineItemHTML = '';

    if (!elementExists($foundLineItem)) {
      $foundLineItem = getLineItemTemplate();
    }

    lineItemHTML = renderLineItemImage(lineItem, $foundLineItem);
    lineItemHTML = renderLineItemTitle(lineItem, lineItemHTML);
    lineItemHTML = renderLineItemVariantTitle(lineItem, lineItemHTML);
    lineItemHTML = renderCartQuantities(lineItem, lineItemHTML);
    lineItemHTML = formatLineItemMoney(lineItem, lineItemHTML);

    return lineItemHTML;

  }

}


/*

Render Cart Quantities

*/
function renderCartQuantities(lineItem, lineItemHTML, $lineItemTemplate = false) {

  if ( !lineItemExists(lineItem) ) {
    return false;
  }

  if (!$lineItemTemplate) {
    $lineItemTemplate = jQuery(lineItemHTML);
  }

  var $decrement = $lineItemTemplate.find('.wps-quantity-decrement');
  var $increment = $lineItemTemplate.find('.wps-quantity-increment');

  $decrement.attr('data-variant-id', lineItem.variant.id);
  $decrement.attr('data-product-id', lineItem.variant.product.id);

  $increment.attr('data-variant-id', lineItem.variant.id);
  $increment.attr('data-product-id', lineItem.variant.product.id);

  $lineItemTemplate.find('.wps-cart-item__quantity')
    .attr('value', lineItem.quantity)
    .attr('data-wps-previous-amount', lineItem.quantity)
    .data('wps-previous-amount', lineItem.quantity);

  return $lineItemTemplate.prop('outerHTML');

}


/*

Format Line Item Money
Calls Server

*/
function formatLineItemMoney(lineItem, lineItemHTML) {

  if ( !lineItemExists(lineItem) ) {
    return false;
  }

  var formatedPrice = formatAsMoney(lineItem.variant.price);

  return renderCartItemQuantity( lineItem.quantity, renderCartItemPrice(formatedPrice, lineItemHTML) );

}



function renderCartItemQuantity(quantity, lineItemHTML) {

  var $lineItem = jQuery(lineItemHTML);
  var $price = $lineItem.find('.wps-cart-item__price');

  $price.prepend('<span class="wps-cart-item__quantity">x' + quantity + '</span>');

  return $lineItem.prop('outerHTML');

}


/*

Render Cart Item Price

Returns the HTML of the price

*/
function renderCartItemPrice(price, lineItemHTML) {

  var $lineItem = jQuery(lineItemHTML);
  var $price = $lineItem.find('.wps-cart-item__price');

  $price.text(price);

  return $lineItem.prop('outerHTML');

}


/*

Contains Default Vairant Title

*/
function containsDefaultVariantTitle(lineItem) {

  if ( !lineItemExists(lineItem) ) {
    return false;
  }

  return lineItem.variant.title.indexOf('Default Title') !== -1;

}


/*

Get Line Item Link

*/
function getLineItemLink(lineItem) {
  return '/' + WP_Shopify.productsSlug;
}




function getWordPressURLCustomAttribute(wordpressURLsParsed, lineItem) {

  return filter(wordpressURLsParsed, function(product) {
    return product.productID === lineItem.variant.product.id;
  });

}


function getStoredWordPressURLs() {
  return localStorage.getItem('wps-wordpress-urls');
}

function setStoredWordPressURLs(items, existing = false) {

  if (!existing) {
    return localStorage.setItem('wps-wordpress-urls', JSON.stringify([items]) );

  } else {

    var existingNewItems = JSON.parse(existing);

    existingNewItems.push(items);

    existingNewItems = uniqBy(existingNewItems, item => item.productID );

    return localStorage.setItem('wps-wordpress-urls', JSON.stringify(existingNewItems));

  }

}

function buildWordPressURLsObj(foundLineItem, wordpressProductURL) {

  if (isEmpty(foundLineItem)) {
    return {};
  }

  return {
    'productID': foundLineItem[0].variant.product.id,
    'url': wordpressProductURL
  }

}

function getLineItemWordPressURL(lineItem) {

  var wordpressURLs = getStoredWordPressURLs();
  var wordpressURLsParsed = JSON.parse(wordpressURLs);
  var foundTheStoredStuff = getWordPressURLCustomAttribute(wordpressURLsParsed, lineItem);

  if ( isEmpty(foundTheStoredStuff) ) {
    return '#!';

  } else {
    return foundTheStoredStuff[0].url;
  }

}


/*

Render Line Item Image

*/
function renderLineItemImage(lineItem, lineItemHTML) {

  var wordpressURL = getLineItemWordPressURL(lineItem);
  var $lineItem = jQuery(lineItemHTML);
  var $image = $lineItem.find('.wps-cart-item__img');

  getLineItemLink(lineItem);

  $image.css('background-image', 'url(\'' + getLineItemImage(lineItem) + '\')');

  if (WP_Shopify.settings.itemsLinkToShopify) {
    $lineItem.find('.wps-cart-item-img-link').attr('href', WP_Shopify.shop.primaryDomain.url + '/products/' +  lineItem.title.replace(/\s+/g, '-').toLowerCase());

  } else {

    if (wordpressURL) {
      $lineItem.find('.wps-cart-item-img-link').attr('href', wordpressURL);
    }

  }

  return $lineItem.prop('outerHTML');

}


/*

Render Line Item Title

*/
function renderLineItemTitle(lineItem, lineItemHTML) {

  var $lineItem = jQuery(lineItemHTML);
  var $title = $lineItem.find('.wps-cart-item__title');

  // We can just use what we set on the image since title is always rendered afterwards.
  var wordpressURL = $lineItem.find('.wps-cart-item-img-link').attr('href');

  $title.text(lineItem.title);

  if (wordpressURL) {
    $title.attr('href', wordpressURL);
  }

  return $lineItem.prop('outerHTML');

}


/*

Render Line Item Variant Title

*/
function renderLineItemVariantTitle(lineItem, lineItemHTML) {

  if ( !lineItemExists(lineItem) ) {
    return false;
  }

  var $lineItem = jQuery(lineItemHTML);
  var $variantTitle = $lineItem.find('.wps-cart-item__variant-title');

  if ( !containsDefaultVariantTitle(lineItem) ) {
    $variantTitle.text(lineItem.variant.title);

  } else {
    $variantTitle.text('');

  }

  return $lineItem.prop('outerHTML');

}


/*

Get Line Item Template

*/
function getLineItemTemplate() {
  return jQuery('#wps-cart-item-template').html();
}


/*

Get Line Item Image

*/
function getLineItemImage(lineItem) {

  if ( !lineItemExists(lineItem) || !lineItem.variant.image ) {
    return WP_Shopify.pluginsDirURL + 'public/imgs/placeholder.png';

  } else {
    return lineItem.variant.image.src;
  }

}


function renderLineItemUniqueIDs(lineItem, lineItemHTML) {

  if ( !lineItemExists(lineItem) || !lineItemHTML ) {
    return false;
  }

  return addLineItemAttributes( jQuery(lineItemHTML), lineItem)[0].outerHTML;

}




function addLineItemAttributes($lineItem, lineItemData) {

  $lineItem.attr({
    "data-wps-line-item-id": lineItemData.id,
    "data-wps-line-item-variant-id": lineItemData.variant.id,
    "data-wps-line-item-product-id": lineItemData.variant.product.id
  });

  return $lineItem;

}







/*

Responsible for building the Line Item markup

*/
function createLineItemMarkup(lineItem) {

  var lineItemHTML = '';

  lineItemHTML = renderLineItemImage(lineItem, getLineItemTemplate());
  lineItemHTML = renderLineItemTitle(lineItem, lineItemHTML);
  lineItemHTML = renderLineItemVariantTitle(lineItem, lineItemHTML);
  lineItemHTML = renderCartQuantities(lineItem, lineItemHTML);
  lineItemHTML = renderLineItemUniqueIDs(lineItem, lineItemHTML);
  lineItemHTML = formatLineItemMoney(lineItem, lineItemHTML);

  return lineItemHTML;

}


/*

Get Cart Item Container

*/
function getCartItemContainer() {
  return jQuery('.wps-cart-item-container');
}


/*

Render Empty Cart Message

*/
function renderEmptyCartMessage() {

  var $cartItemContainer = getCartItemContainer();

  $cartItemContainer.empty();
  $cartItemContainer.append( createEmptyCartNotice() );

}


/*

Append Cart Items
Param: jQuery Object of item HTML

Returns: The DOM element that was added

*/
function emptyAndAppendCartItems($cartLineItems, checkout) {

  var $cartItemContainer = getCartItemContainer();

  $cartItemContainer.empty();
  $cartItemContainer.append( $cartLineItems );

  return $cartLineItems;

}


/*

Append Cart Items
Param: jQuery Object of item HTML

Returns: The DOM element that was added

*/
function appendSingleCartItem(cartLineItemHTML, client, checkout) {

  var $newlineItemToAdd = jQuery(cartLineItemHTML);

  getCartItemContainer()
    .append($newlineItemToAdd);

  return $newlineItemToAdd;

}


/*

Cart Has Items in DOM

*/
function cartHasItemsDOM() {
  return jQuery('.wps-cart-item-container .wps-cart-item').length;
}


/*

Updates HTML of single line item

Returns: The DOM element that was added

*/
function replaceSingleCartItem($existingItem, itemNewHTML) {

  var $newItemToInsert = jQuery(itemNewHTML);

  $existingItem.replaceWith( $newItemToInsert );

  return $newItemToInsert;

}


/*

Has Existing Cart items

*/
function hasExistingCartItem(variant) {

  var $existingItem = findExistingLineItem(variant.id);

  if ($existingItem.length) {
    return $existingItem;

  } else {
    return false;

  }

}


/*

Render Single Cart Item

Used only when adding a product to the cart

*/
function renderSingleCartItem(client, checkout, variant) {

  // Filters the checkout items for the one we want ...
  var lineItem = findLineItemByVariantID(checkout, variant);

  // Grabs the line item markup
  var lineItemMarkup = updateSingleProductCartDOM(lineItem, variant);

  /*

  Now that we have the updated HTML, we need to add it to the DOM.
  We also need to make sure we return the recently added item.

  */
  if ( cartHasItemsDOM() ) {

    var $existingItem = hasExistingCartItem(variant);

    if ($existingItem) {
      return replaceSingleCartItem($existingItem, lineItemMarkup);

    } else {
      return appendSingleCartItem(lineItemMarkup, client, checkout);
    }

  } else {
    return emptyAndAppendCartItems( jQuery(lineItemMarkup), checkout );

  }

}


/*

Render Cart Items

We have nested promises here ...

*/
function renderCartItems(checkout) {
  return emptyAndAppendCartItems( createLineItemsMarkup(checkout), checkout );
}


/*

Checks if the cart is currently open or not.

*/
function cartIsOpen() {

  var isOpen = jQuery('.wps-cart').hasClass('wps-cart-is-open');
  return isOpen ? true : false;

}



function hideCart() {
  jQuery('.wps-cart').removeClass('wps-is-visible wps-animated wps-slideInRight wps-bounceInOutRight');
}


function showFixedCartIcon() {
  slideInRight( jQuery('.wps-btn-cart-fixed') );
}


/*

Close Cart

*/
function closeCart() {

  if (cartIsOpen()) {
    slideOutRight( jQuery('.wps-cart') );
  }

}


/*

Toggle Cart

*/
function openCart() {

  if ( !cartIsOpen() ) {
    slideInLeft( jQuery('.wps-cart') );
  }

}


/*

Empty Cart Total

*/
function emptyCartTotal(checkout) {

  jQuery('.wps-cart .wps-pricing')
    .text( formatTotalAmount( checkout.subtotalPrice,  trimHTMLFromMoneyFormat( getMoneyFormat( getShop() ) ) ) );

}


/*

Empty Cart UI

*/
function emptyCartUI(checkout) {

  jQuery('.wps-cart-counter').addClass('wps-is-hidden');
  jQuery('.wps-btn-cart').addClass('wps-is-cart-empty').removeClass('wps-is-disabled wps-is-loading');
  renderEmptyCartMessage();
  emptyCartTotal(checkout);
  disableCheckoutButton();
  enableCartIcon();

}




function getLineItemFromVariant(lineItems, variant) {
  return filter(lineItems, lineItem => variant.id === lineItem.variant.id );
}

function getLineItemFromVariantID(lineItems, variantID) {

  if (!lineItems) {
    return [];
  }

  return filter(lineItems, lineItem =>  variantID === lineItem.variant.id );

}



function getMostRecentlyAddedLineitem(checkout, variant) {

  var foundMatchingLineItem = getLineItemFromVariant(checkout.lineItems, variant);

  if ( !isEmpty(foundMatchingLineItem) ) {
    return first(foundMatchingLineItem);
  }

  return first(checkout.lineItems);

}



function addLineItemIDs($lastLineItem, variant, checkout) {

  addLineItemAttributes( $lastLineItem, getMostRecentlyAddedLineitem(checkout, variant));

}



function setCartTermsCheckboxState() {
  jQuery('#wps-terms-checkbox').prop('checked', cartTermsState());
}


function hasCartElements() {

  var emptyNotice = jQuery('.wps-cart-item-container').find('.wps-cart-empty-notice').length;

  if (emptyNotice === 0) {
    return true;
  }

  return false;

}


function renderCartState() {

  setCartTermsCheckboxState();

  if ( hasCartTerms() ) {

    if ( cartTermsState() && hasCartElements() ) {
      enableCheckoutButton();

    } else {
      disableCheckoutButton();
    }

  }

}


function getCheckoutSubmit() {
  return jQuery('#wps-btn-checkout');
}

function getTermsCheckbox() {
  return jQuery('#wps-terms-checkbox');
}


export {
  updateTotalCartPricing,
  updateCartCounter,
  renderCartItems,
  openCart,
  closeCart,
  cartIsOpen,
  renderSingleCartItem,
  emptyCartUI,
  createLineItemMarkup,
  removeVariantSelections,
  enableCartItem,
  disableCartItem,
  enableCheckoutButton,
  disableCheckoutButton,
  hideCart,
  enableCartIcon,
  addLineItemIDs,
  getLineItemFromVariantID,
  getStoredWordPressURLs,
  setStoredWordPressURLs,
  buildWordPressURLsObj,
  checkoutConditionsMet,
  renderCartState,
  getCheckoutSubmit,
  getTermsCheckbox,
  hasCartElements,
  hasCartTerms,
  cartTermsAccepted,
  showFixedCartIcon
}
