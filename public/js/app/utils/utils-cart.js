import {
  getCartCache
} from '../ws/ws-settings';

import {
  fetchCart
} from '../ws/ws-cart';

import isObject from 'lodash/isObject';
import isEmpty from 'lodash/isEmpty';

/*

Needs Cache Flush
Calls Server

*/
async function needsCacheFlush(cartID) {

  try {

    var cacheFlushStatus = await getCartCache(cartID);

    // True if found, false if not
    return cacheFlushStatus.success;

  } catch(errors) {
    return false;

  }

}


/*

Flush cache

*/
function flushCache(shopify) {

  return new Promise( async (resolve, reject) => {

    // Get the current cart
    try {

      // Calls LS
      var cart = await fetchCart(shopify);

    } catch(error) {
      reject(error);
      return;
    }

    localStorage.removeItem('wps-cache-expiration'); // Used for money format
    localStorage.removeItem('wps-animating');
    localStorage.removeItem('wps-connection-in-progress');
    localStorage.removeItem('wps-product-selection-id');

    if (cart.lineItemCount > 0) {

      // Clearing the cart
      try {
        await cart.clearLineItems();

      } catch(error) {
        reject(error);
        return;
      }

    }

    resolve();

  });

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


/*

Predicate function that checks whether the current cart is empty or not

*/
function isEmptyCart(cart) {

  if ( isObject(cart) && !isEmpty(cart) ) {
    return cart.lineItemCount === 0;
  }

}


export {
  needsCacheFlush,
  flushCache,
  emptyCartID,
  isEmptyCart
}
