/*

Get Connection Progress

*/
function connectionInProgress() {
  return localStorage.getItem('wps-connection-in-progress');
}


/*

Get Connection Progress

*/
function isConnectionInProgress() {

  if (connectionInProgress() === 'true') {
    return true;

  } else {
    return false;
  }

}


/*

Set Connection Progress

*/
function setConnectionProgress(status) {
  localStorage.setItem('wps-connection-in-progress', status);
}


/*

Remove Connection Nonce

*/
function removeConnectionNonce() {
  localStorage.removeItem('wps-nonce');
}


/*

Remove Cache Expiration

*/
function removeCacheExpiration() {
  localStorage.removeItem('wps-cache-expiration');
}


/*

Remove Modal Cache

*/
function removeConnectionProgress() {
  localStorage.removeItem('wps-connection-in-progress');
}


/*

Set cache modal

*/
function setModalCache(modal) {
  localStorage.setItem('wps-modal-connector-cache', modal);
}


/*

Get cache modal

*/
function getModalCache() {
  return localStorage.getItem("wps-modal-connector-cache");
}


/*

Remove Modal Cache

*/
function removeModalCache() {
  localStorage.removeItem('wps-modal-connector-cache');
}


/*

Remove Animating

*/
function removeAnimating() {
  localStorage.removeItem('wps-animating');
}


/*

Remove Last Cart ID

*/
function removeLastCartID() {
  localStorage.removeItem('wps-last-cart-id');
}


/*

Remove Product Selection

*/
function removeProductSelection() {
  localStorage.removeItem('wps-product-selection-id');
}


/*

Remove Money Format

*/
function removeMoneyFormat() {
  localStorage.removeItem('wps-money-format');
}


/*

Set the starting URL

*/
function setStartingURL(url) {
  localStorage.setItem('wps-starting-url', url);
}


/*

Get the starting URL

*/
function getStartingURL() {
  return localStorage.getItem('wps-starting-url');
}


/*

Remove Cache Expiration

*/
function removeStartingURL() {
  localStorage.removeItem('wps-starting-url');
}


/*

Set canceling indicator

*/
function setCancelSync(flag) {
  localStorage.setItem('wps-is-canceling', flag);
}


/*

Set canceling indicator

*/
function getCancelSync() {
  return localStorage.getItem('wps-is-canceling');
}


/*

Set canceling indicator

*/
function removeCancelSync() {
  localStorage.removeItem('wps-is-canceling');
}





/*

Set canceling indicator

*/
function setWebhooksReconnect(flag) {
  localStorage.setItem('wps-webhooks-reconnect', flag);
}


/*

Set canceling indicator

*/
function getWebhooksReconnect() {

  if (localStorage.getItem('wps-webhooks-reconnect') === 'false' || localStorage.getItem('wps-webhooks-reconnect') === null) {
    return 0;

  } else {
    return localStorage.getItem('wps-webhooks-reconnect');
  }

}


/*

Set canceling indicator

*/
function removeWebhooksReconnect() {
  localStorage.removeItem('wps-webhooks-reconnect');
}


/*

Set canceling indicator

*/
function syncIsCanceled() {

  if (getCancelSync() === 'true') {

    jQuery('.wps-connector').addClass('wps-is-stopping');

    return true;

  } else {
    return false;
  }

}


/*

Remove Modal Cache
Clears all localstorage cache

*/
function clearLocalstorageCache() {
  // removeModalCache();
  // removeConnectionProgress();
  // removeConnectionNonce();
  // removeCacheExpiration();
  // removeAnimating();
  // removeLastCartID();
  // removeProductSelection();
  // removeMoneyFormat();
  // removeStartingURL();
  // removeCancelSync();
  // removeWebhooksReconnect();
  localStorage.clear();
}


export {
  connectionInProgress,
  setConnectionProgress,
  removeConnectionProgress,
  removeConnectionNonce,
  setModalCache,
  removeModalCache,
  getModalCache,
  clearLocalstorageCache,
  setStartingURL,
  getStartingURL,
  isConnectionInProgress,
  syncIsCanceled,
  setCancelSync,
  getCancelSync,
  setWebhooksReconnect,
  getWebhooksReconnect
};
