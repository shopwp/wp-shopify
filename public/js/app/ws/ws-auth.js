import ShopifyBuy from 'shopify-buy';

/*

Initialize Shopify
Returns: Promise

*/
function shopifyInit(creds) {

  /*

  TODO: throw an error if creds are empty. Dont set them
  to empty strings like this because it fails silently

  */
  if (!creds) {
    var creds = {
      accessToken: '',
      domain: '',
      appId: ''
    }
  }

  return ShopifyBuy.buildClient({
    accessToken: creds.js_access_token,
    domain: creds.domain,
    appId: creds.app_id
  });

};


/*

Get Shopify credentials from WordPress
Returns: Promise

*/
function getShopifyCreds() {

  return jQuery.ajax({
    method: 'GET',
    url: wps.ajax,
    dataType: 'json',
    data: {
      action: 'wps_get_credentials',
      nonce: wps.nonce
    }
  });

};

export {
  shopifyInit,
  getShopifyCreds
}
