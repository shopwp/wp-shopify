import to from 'await-to-js';
import { getCheckoutCache } from '../ws/ws-settings';
import { removeAllLineItems } from '../ws/ws-cart';
import { logNotice } from './utils-notices';
import isObject from 'lodash/isObject';
import isEmpty from 'lodash/isEmpty';
import size from 'lodash/size';


/*

Needs Cache Flush
Calls Server

*/
function needsCacheFlush(cartID) {

  return new Promise(async function (resolve, reject) {

    var [cartCacheError, cartCache] = await to( getCheckoutCache(cartID) );

    if (cartCacheError) {
      return reject();
    }

    if (isWordPressError(cartCache)) {
      return reject();
    }

    resolve();

  });

}


/*

Checks whether the cart is empty or not

*/
function isCheckoutEmpty(checkout) {

  if (size(checkout.lineItems) > 0) {
    return false;

  } else {
    return true;
  }

}


/*

Checks whether a line item exists or not

*/
function lineItemExists(lineItem) {

  if (!lineItem || !lineItem.variant) {
    return false;

  } else {
    return true;
  }

}


/*

Complete empty a checkout

*/
function clearCheckout(client, checkout) {

  return new Promise( async (resolve, reject) => {

    if ( !isCheckoutEmpty(checkout) ) {

      var [checkoutError, checkoutData] = await to( removeAllLineItems(client, checkout) );

      if (checkoutError) {
        logNotice('removeAllLineItems', checkoutError, 'error');
        reject(checkoutError);
      }

      resolve(checkoutData);

    }

  });

}


function clearLS() {

  return new Promise( (resolve, reject) => {

    localStorage.clear();

    resolve();

  });

}


/*

Flush cache

*/
function flushCache(client, checkout) {

  return Promise.all([
    clearLS(),
    clearCheckout(client, checkout)
  ]);

}


/*

Empty Cart ID

*/
function emptyCartID(cartID) {

  if (cartID === undefined || cartID === 'undefined' || cartID == false || cartID == null) {
    return true;

  } else {
    return false;
  }

}


export {
  needsCacheFlush,
  flushCache,
  clearLS,
  emptyCartID,
  isCheckoutEmpty,
  lineItemExists
}
