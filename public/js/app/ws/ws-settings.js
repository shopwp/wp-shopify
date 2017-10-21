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


function getCacheFlushStatus() {

  return jQuery.ajax({
    method: 'GET',
    url: wps.ajax,
    dataType: 'json',
    data: {
      action: 'wps_get_cache_flush_status'
    }
  });

};


function updateCacheFlushStatus(status) {

  return jQuery.ajax({
    method: 'POST',
    url: wps.ajax,
    dataType: 'json',
    data: {
      action: 'wps_update_cache_flush_status',
      status: status
    }
  });

};


export {
  getCurrencyFormat,
  getMoneyFormat,
  getMoneyFormatWithCurrency,
  getCacheFlushStatus,
  updateCacheFlushStatus
}
