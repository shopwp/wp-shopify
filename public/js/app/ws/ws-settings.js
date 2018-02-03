/*

Get Shopify credentials from WordPress
Returns: Promise

*/
function getCurrencyFormat() {

  return jQuery.ajax({
    method: 'GET',
    url: wps.ajax,
    dataType: 'json',
    data: {
      action: 'wps_get_currency_format',
      nonce: wps.nonce
    }
  });

};















function getCurrencyFormats() {

  return jQuery.ajax({
    method: 'GET',
    url: wps.ajax,
    dataType: 'json',
    data: {
      action: 'wps_get_currency_formats',
      nonce: wps.nonce
    }
  });

};











function getMoneyFormat() {

  return jQuery.ajax({
    method: 'GET',
    url: wps.ajax,
    dataType: 'json',
    data: {
      action: 'wps_get_money_format',
      nonce: wps.nonce
    }
  });

};


function getMoneyFormatWithCurrency() {

  return jQuery.ajax({
    method: 'GET',
    url: wps.ajax,
    dataType: 'json',
    data: {
      action: 'wps_get_money_format_with_currency',
      nonce: wps.nonce
    }
  });

};


/*

Returns true if transient exists / found -- false otherwise

*/
function getCartCache(cartID) {

  return jQuery.ajax({
    method: 'POST',
    url: wps.ajax,
    dataType: 'json',
    data: {
      action: 'wps_get_cart_cache',
      cartID: cartID,
      nonce: wps.nonce
    }
  });

};


/*

Cache Cart

*/
function setCartCache(cartID) {

  return jQuery.ajax({
    method: 'POST',
    url: wps.ajax,
    dataType: 'json',
    data: {
      action: 'wps_set_cart_cache',
      cartID: cartID,
      nonce: wps.nonce
    }
  });

};


export {
  getCurrencyFormat,
  getCurrencyFormats,
  getMoneyFormat,
  getMoneyFormatWithCurrency,
  getCartCache,
  setCartCache
}
