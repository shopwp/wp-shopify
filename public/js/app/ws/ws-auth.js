import ShopifyBuy from 'shopify-buy';

/*

Initialize Shopify
Returns: Promise

*/
function shopifyInit(creds) {

  return new Promise( (resolve, reject) => {

    /*

    TODO: throw an error if creds are empty. Dont set them
    to empty strings like this because it fails silently

    */
    if (!creds) {

      reject({
        type: 'error',
        message: 'Unable to find Shopify credentials. Please clear your browser cache and reload.'
      });

      return;

    }

    try {

      resolve(ShopifyBuy.buildClient({
        accessToken: creds.js_access_token,
        domain: creds.domain,
        appId: creds.app_id
      }));

    } catch (error) {
      reject(error);
      return;
    }

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
      action: 'wps_get_credentials_frontend',
      nonce: wps.nonce
    }
  });

};


/*

Get Shopify cart session
Returns: Promise

*/
function getCartSession() {

  return jQuery.ajax({
    method: 'GET',
    url: wps.ajax,
    dataType: 'json',
    data: {
      action: 'wps_get_cart_session',
      nonce: wps.nonce
    }
  });

};


export {
  shopifyInit,
  getShopifyCreds,
  getCartSession
}
