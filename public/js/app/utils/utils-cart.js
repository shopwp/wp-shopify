function needsCacheFlush() {

  if (!window.wps.is_connected && localStorage.getItem('wps-last-cart-id')) {
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
