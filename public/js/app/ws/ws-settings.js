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
    dataType: 'html',
    data: {
      action: 'wps_get_money_format'
    }
  });

};


function getMoneyFormatWithCurrency() {

  return jQuery.ajax({
    method: 'GET',
    url: wps.ajax,
    dataType: 'html',
    data: {
      action: 'wps_get_money_format_with_currency'
    }
  });

};

export {
  getCurrencyFormat,
  getMoneyFormat,
  getMoneyFormatWithCurrency
}
