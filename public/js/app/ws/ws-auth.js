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

  console.log('WOW: ', creds);

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
      action: 'wps_get_credentials'
    }
  });

};


/*

Check for cached credentials

*/
function hasExistingCredentials() {
  return localStorage.getItem('wps-shopify-api-credentials');
};


/*

Get Shopify credentials from cache

*/
function getExistingShopifyCreds() {
  return JSON.parse( localStorage.getItem('wps-shopify-api-credentials') );
};


/*

Set Shopify credentials into cache

*/
function setShopifyCreds(creds) {
  return localStorage.setItem('wps-shopify-api-credentials', JSON.stringify(creds));
};


export {
  shopifyInit,
  hasExistingCredentials,
  getExistingShopifyCreds,
  getShopifyCreds,
  setShopifyCreds
}
