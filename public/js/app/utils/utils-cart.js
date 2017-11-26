import {
  getCacheFlushStatus,
  updateCacheFlushStatus
} from '../ws/ws-settings';

import {
  fetchCart
} from '../ws/ws-cart';


async function needsCacheFlush() {

  try {

    var cacheFlushStatus = await getCacheFlushStatus();

    if (cacheFlushStatus.data == 1) {
      return true;

    } else {
      return false;

    }

  } catch(errors) {
    console.error(errors);
    return true;

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

  localStorage.removeItem('wps-cache-expiration');
  localStorage.removeItem('wps-animating');
  localStorage.removeItem('wps-connection-in-progress');
  localStorage.removeItem('wps-product-selection-id');

  // Clearing the cart
  try {
    await cart.clearLineItems();

  } catch(error) {
    console.error("flushCache clearLineItems ", error);
    return;
  }

  // Updating cache status
  try {
    await updateCacheFlushStatus(0);

  } catch(error) {
    console.error("flushCache updateCacheStatus ", error);
    return;
  }

}


export {
  needsCacheFlush,
  flushCache
}
