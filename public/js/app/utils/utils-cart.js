import {
  getCartCache
} from '../ws/ws-settings';

import {
  fetchCart
} from '../ws/ws-cart';


/*

Needs Cache Flush

*/
async function needsCacheFlush(cartID) {

  try {

    var cacheFlushStatus = await getCartCache(cartID);
    console.log('Existing cart found? ', cacheFlushStatus.success);
    // True if found, false if not
    return cacheFlushStatus.success;

  } catch(errors) {
    return false;

  }

}


/*

Flush cache

*/
async function flushCache(shopify) {

  // Get the current cart
  try {
    var cart = await fetchCart(shopify);

  } catch(error) {
    console.error("flushCache fetchCart ", error);
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
      console.error("flushCache clearLineItems ", error);
      return;
    }

  }

}


export {
  needsCacheFlush,
  flushCache
}
