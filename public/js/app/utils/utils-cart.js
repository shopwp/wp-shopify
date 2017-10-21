import {
  getCacheFlushStatus,
  updateCacheFlushStatus
} from '../ws/ws-settings';


async function needsCacheFlush() {


  try {

    var cacheFlushStatus = await getCacheFlushStatus();

    if (cacheFlushStatus.data == 1) {

      await updateCacheFlushStatus(0);

      return true;

    }

  } catch(errors) {
    console.error(errors);
    return true;

  }


  /*

  If recently connected, or if not connected but something exists in cart ...

  */
  if (!window.wps.is_connected && localStorage.getItem('wps-last-cart-id') || window.wps.is_recently_connected) {
    return true;

  } else {
    return false;
  }

}


function flushCache() {
  localStorage.removeItem('wps-cache-expiration');
  localStorage.removeItem('wps-animating');
  localStorage.removeItem('wps-connection-in-progress');
  localStorage.removeItem('wps-product-selection-id');
}


export {
  needsCacheFlush,
  flushCache
}
