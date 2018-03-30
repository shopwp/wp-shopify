import ShopifyBuy from 'shopify-buy';
import { isError } from '../utils/utils-common';

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
    url: WP_Shopify.ajax,
    dataType: 'json',
    data: {
      action: 'wps_get_credentials_frontend',
      nonce: WP_Shopify.nonce
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
    url: WP_Shopify.ajax,
    dataType: 'json',
    data: {
      action: 'wps_get_cart_session',
      nonce: WP_Shopify.nonce
    }
  });

};





/*

Get Product Option IDs

*/
function getStorefrontCreds() {
  return JSON.parse( localStorage.getItem('wps-storefront-creds') );
};


/*

Set Product Option IDs

*/
function setStorefrontCreds(creds) {
  localStorage.setItem('wps-storefront-creds', JSON.stringify(creds));
};


/*

Finds the Shopify Storefront credentials to use

First we check whether the credentials are cached, if they are, return them.
If they aren't cached (first page load) -- go to the server and get them

*/
function findShopifyCreds() {

  return new Promise( async (resolve, reject) => {

    var existingCreds = getStorefrontCreds();

    if (existingCreds) {
      resolve(existingCreds);

    } else {

      /*

      Step 1. Get Shopify Credentials

      */
      try {

        var creds = await getShopifyCreds(); // wps_get_credentials_frontend

        if (isError(creds)) {
          reject(creds.data);
          return;
        }

        setStorefrontCreds(creds.data);
        resolve(creds);

      } catch(error) {
        reject(error);
        return;
      }

    }

  });

}


export {
  shopifyInit,
  getShopifyCreds,
  getCartSession,
  findShopifyCreds
}
