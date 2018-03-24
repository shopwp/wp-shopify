/*

Get Shopify credentials from WordPress
Returns: Promise

*/
function getCurrencyFormat() {

  return jQuery.ajax({
    method: 'GET',
    url: WP_Shopify.ajax,
    dataType: 'json',
    data: {
      action: 'wps_get_currency_format',
      nonce: WP_Shopify.nonce
    }
  });

};


function getCurrencyFormats() {

  return jQuery.ajax({
    method: 'GET',
    url: WP_Shopify.ajax,
    dataType: 'json',
    data: {
      action: 'wps_get_currency_formats',
      nonce: WP_Shopify.nonce
    }
  });

};


function getMoneyFormat() {

  return jQuery.ajax({
    method: 'GET',
    url: WP_Shopify.ajax,
    dataType: 'json',
    data: {
      action: 'wps_get_money_format',
      nonce: WP_Shopify.nonce
    }
  });

};


function getMoneyFormatWithCurrency() {

  return jQuery.ajax({
    method: 'GET',
    url: WP_Shopify.ajax,
    dataType: 'json',
    data: {
      action: 'wps_get_money_format_with_currency',
      nonce: WP_Shopify.nonce
    }
  });

};


/*

Returns true if transient exists / found -- false otherwise

*/
function getCartCache(cartID) {

  return jQuery.ajax({
    method: 'POST',
    url: WP_Shopify.ajax,
    dataType: 'json',
    data: {
      action: 'wps_get_cart_cache',
      cartID: cartID,
      nonce: WP_Shopify.nonce
    }
  });

};


/*

Cache Cart

*/
function setCartCache(cartID) {

  return jQuery.ajax({
    method: 'POST',
    url: WP_Shopify.ajax,
    dataType: 'json',
    data: {
      action: 'wps_set_cart_cache',
      cartID: cartID,
      nonce: WP_Shopify.nonce
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
