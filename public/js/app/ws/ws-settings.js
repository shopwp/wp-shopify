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
      action: 'wps_get_currency_format'
    }
  });

};



function getMoneyFormat() {

  return jQuery.ajax({
    method: 'GET',
    url: wps.ajax,
    dataType: 'json',
    data: {
      action: 'wps_get_money_format'
    }
  });

};


function getMoneyFormatWithCurrency() {

  return jQuery.ajax({
    method: 'GET',
    url: wps.ajax,
    dataType: 'json',
    data: {
      action: 'wps_get_money_format_with_currency'
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
      cartID: cartID
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
      cartID: cartID
    }
  });

};


export {
  getCurrencyFormat,
  getMoneyFormat,
  getMoneyFormatWithCurrency,
  getCartCache,
  setCartCache
}
