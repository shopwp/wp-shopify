import { enable } from '../utils/utils-ux';
import { slideInDown, slideInTop } from '../utils/utils-animations';
import isError from 'lodash/isError';
import isObject from 'lodash/isObject';
import isArray from 'lodash/isArray';
import isEmpty from 'lodash/isEmpty';
import isString from 'lodash/isString';
import has from 'lodash/has';
import forEach from 'lodash/forEach';


/*

Returns a standarized format for client-side errors

*/
function getErrorContents(xhr, err, action_name) {

  return {
    statusCode: xhr.status,
    message: err,
    action_name: action_name
  }

}


function logNotice(functionOrMessage, error, type) {

  if (type === 'error') {
    console.error('WP Shopify error (failed in ' + functionOrMessage + '): ', error);
  }

  if (type === 'warn') {
    console.error('WP Shopify warning: ', functionOrMessage);
  }

}


function showGlobalNotice(noticeObject, type) {
  showCartNotice(noticeObject, type); // Shows error message in cart
  showNoticeAllProducts(noticeObject, type); // Shows error message on all products
}


/*

Show error on slideout cart

*/
function showCartNotice(noticeObject, type) {

  noticeObject = getNotice(noticeObject, type);

  jQuery('.wps-cart-item-container')
    .empty()
    .html('<aside class="wps-notice-inline wps-notice-' + noticeObject.type + '"><p>' + noticeObject.message + '</p></aside>')
    .removeClass('wps-is-disabled wps-is-loading');

}


/*

Show error on all products

*/
function showNoticeAllProducts(noticeObject, type) {

  noticeObject = getNotice(noticeObject, type);

  enable( jQuery('.wps-btn-cart') );

  jQuery('.wps-product-meta')
    .html('<p class="wps-notice-inline wps-notice-' + noticeObject.type + '">' + noticeObject.message + '</p>')
    .removeClass('wps-is-disabled wps-is-loading');

}


function showSingleCartNotice(noticeObject, $cartItem, type = false) {

  noticeObject = getNotice(noticeObject, type);

  addCartItemNotice(noticeObject.message, $cartItem, noticeObject.type);

}


/*

Show error on single product only

*/
function showSingleNotice(noticeObject, $product, type = false) {

  noticeObject = getNotice(noticeObject, type);

  showProductMetaNotice($product, noticeObject.message, noticeObject.type);

}


/*

hideProductMetaNotice

*/
function hideAllProductMetaNotices($element) {

  $element
    .closest('.wps-product-meta')
    .find('.wps-product-notice')
    .html('')
    .removeClass('wps-is-visible wps-notice-error wps-notice-warning wps-notice-info');

}


/*

hideProductMetaNotice

*/
function hideProductMetaNotice($element) {

  $element
    .closest('.wps-product-meta')
    .find('.wps-product-notice')
    .html('')
    .removeClass('wps-is-visible wps-notice-error wps-notice-warning wps-notice-info');
}


/*

showProductMetaNotice

*/
function showProductMetaNotice($element, noticeMessage, type) {

  // Hides all other error messages
  hideAllProductMetaNotices($element);

  slideInDown($element
    .closest('.wps-product-meta')
    .find('.wps-product-notice')
    .html(noticeMessage)
    .addClass('wps-notice-' + type))


}


function clearCartItemNotice($cartItemRow) {

  $cartItemRow.find('.wps-cart-item__content')
    .next('.wps-notice-inline')
    .remove();

}

function clearAllCartNotices() {
  jQuery('.wps-cart .wps-notice-inline').remove();
}

/*

showProductMetaNotice

*/
function addCartItemNotice(noticeMessage, $cartItemRow, type) {

  // Hides all other error messages
  clearCartItemNotice($cartItemRow);

  var $noticeToAdd = jQuery('<div class="wps-notice-inline wps-notice-' + type + '">' + noticeMessage + '</div>');

  $cartItemRow
    .find('.wps-cart-item__content')
    .after($noticeToAdd);

  slideInTop($noticeToAdd);

}


function getWordPressErrorObject(noticeObject) {

  var wordpressNotice = {};

  if (has(noticeObject, 'data') && has(noticeObject.data, 'message')) {
    wordpressNotice.message = noticeObject.data.message;

  } else {
    wordpressNotice.message = 'Unknown error occured. Please clear your browser cache and try again.';

  }

  if (has(noticeObject, 'data') && has(noticeObject.data, 'type')) {
    wordpressNotice.type = noticeObject.data.type

  } else {
    wordpressNotice.type = 'error';

  }

  return wordpressNotice;

}


function createDefaultNotice() {

  return {
    type: 'warning',
    message: 'An unexpected error occured. Please clear your browser cache and try again.'
  }

}



function authenticationError(noticeData) {

  if (has(noticeData, 'action_name') && noticeData.action_name === 'get_shopify_credssss') {
    return true;

  } else {
    return false;
  }

}


function quantitySelectionError(noticeData) {

  if (has(noticeData, 'message') && noticeData.message === "Cannot read property 'checkoutLineItemsUpdate' of undefined") {
    return true;

  } else {
    return false;
  }

}

function checkoutCompletedError(noticeData) {

  if (has(noticeData, 'message') && noticeData.message === "Checkout is already completed.") {
    return true;

  } else {
    return false;
  }

}

function shopifyGraphQLQueryError(noticeData) {

  if (has(noticeData, 'message') && noticeData.message === "Cannot read property 'checkoutLineItemsAdd' of undefined") {
    return true;

  } else {
    return false;
  }

}


function checkoutCompletedMessage() {
  return 'It looks like you completed a previous shopping session. Please clear your browser cache to start a new one.';
}

function quantitySelectionMessage() {
  return 'Unable to change product quantity. Please clear your browser cache and try again.';
}

function authenticationMessage() {
  return 'Sorry, it looks like our store might be down. Please clear your browser cache and try again.';
}

function shopifyGraphQLQueryMessage() {
  return 'Opps, it looks like we couldn\'t find the product date you\'re looking for. Please clear your browser cache and try again.';
}




function getUserFriendlyMessage(noticeData) {

  if (authenticationError(noticeData)) {
    return authenticationMessage();
  }

  if (quantitySelectionError(noticeData)) {
    return quantitySelectionMessage();
  }

  if (checkoutCompletedError(noticeData)) {
    return checkoutCompletedMessage();
  }

  if (shopifyGraphQLQueryError(noticeData)) {
    return shopifyGraphQLQueryMessage();
  }

  // Falls back to system / network generated error messages
  return noticeData.message;

}


/*

Checks whether a string is a valid json string

*/
function isJSONString(str) {

  try {
    JSON.parse(str);

  } catch (e) {
    return false;
  }

  return true;

}



function getNotice(noticeData, type = false) {

  var fallbackType = noticeData.type ? noticeData.type : 'error';


  // Handles any JS runtime errors and GraphQL erors
  if (isError(noticeData) || isGraphqlError(noticeData)) {

    if (!isJSONString(noticeData.message)) {

      var parsedMessageData = noticeData;

    } else {

      var parsedMessageData = JSON.parse(noticeData.message)[0];

    }

    return {
      type: 'error',
      message: getUserFriendlyMessage(parsedMessageData)
    }

  }

  // Handles any Wordpress related errors
  if (isWordPressError(noticeData)) {
    return getWordPressErrorObject(noticeData);
  }

  // If a raw string was passed in
  if (isString(noticeData)) {

    var finalType = type !== false ? type : fallbackType;

    return {
      type: finalType,
      message: noticeData
    }

  }

  // If something weird was passed in, return a default error
  if (isObject(noticeData) && has(noticeData, 'message')) {

    return {
      type: type !== false ? type : fallbackType,
      message: getUserFriendlyMessage(noticeData)
    }

  } else {

    return createDefaultNotice();
  }

}





/*

Checks for A GraphQL Error

*/
function isGraphqlError(response) {

  if (isObject(response) && has(response, 'errors')) {
    return true;

  } else {
    return false;
  }

}


/*

Is WordPress Error
Returns true only for wp_send_json_error

TRUE  - send_error
FALSE - send_warning
FALSE - send_success

*/
function isWordPressError(response) {

  var foundError = false;

  // A single error is being checked
  if (isObject(response) && has(response, 'success')) {

    if (!response.success) {
      foundError = true;
    }

  }

  // Used when using promise all for checking more than one returned response
  if (isArray(response) && !isEmpty(response)) {

    forEach(response, function(possibleError) {

      if (isObject(possibleError) && has(possibleError, 'success')) {

        if (!possibleError.success) {
          foundError = true;
        }

      }

    });

  }

  return foundError;

}






function noticeConfigEmptyLineItemsBeforeUpdate() {

  return {
    type: 'warning',
    message: 'Uh oh, we were unable to locate the product you attempted to update. Please clear your browser cache and try again.'
  }

}


function noticeConfigBadCredentials() {

  return {
    type: 'error',
    message: 'Uh oh, we were unable to find your Shopify credentials. Please clear your browser cache and try again.'
  }

}


function noticeConfigUnableToBuildCheckout() {

  return {
    type: 'error',
    message: 'Unable to connect to store. This could be because your network is down or the Shop owners credentials are wrong. A possible <a href="https://wpshop.io/docs" target="_blank">solution can be found here</a>. Try double checking the API keys used in WP Shopify, clearing your browser cache and reloading the page.'
  }

}


function noticeConfigUnableToFlushCache() {

  return {
    type: 'error',
    message: 'Unable to flush the store cache. Please clear your browser cache and reload the page.'
  }

}


function noticeConfigUnableToCreateNewShoppingSession() {

  return {
    type: 'error',
    message: 'Unable to create new session after checking out. Please clear your browser cache and reload the page.'
  }

}


export {
  logNotice,
  showCartNotice,
  showProductMetaNotice,
  hideProductMetaNotice,
  hideAllProductMetaNotices,
  showSingleNotice,
  showGlobalNotice,
  isGraphqlError,
  getErrorContents,
  isWordPressError,
  noticeConfigBadCredentials,
  noticeConfigUnableToBuildCheckout,
  noticeConfigUnableToFlushCache,
  noticeConfigUnableToCreateNewShoppingSession,
  showSingleCartNotice,
  noticeConfigEmptyLineItemsBeforeUpdate,
  clearAllCartNotices
}
