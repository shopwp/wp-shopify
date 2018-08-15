import has from 'lodash/has';
import isObject from 'lodash/isObject';
import to from 'await-to-js';
import { getErrorContents, isWordPressError, noticeConfigBadCredentials } from '../utils/utils-notices';
import { clientActive, getClient, setClient } from '../utils/utils-client';
import { buildClient } from './ws-client';



function formatCredsFromServer(credsResponse) {

  if (!isObject(credsResponse)) {
    return false;
  }

  if (has(credsResponse, 'data') && has(credsResponse.data, 'js_access_token')) {
    return credsResponse.data;
  }

  if (has(credsResponse, 'js_access_token')) {
    return credsResponse;
  }

}



/*

Initialize Shopify
Returns: Promise

*/
function shopifyInit(creds) {

  return new Promise( async (resolve, reject) => {

    // If client cached, just return it
    if ( clientActive() ) {
      return resolve( getClient() );
    }

    if (!creds) {
      return reject( noticeConfigBadCredentials() );
    }

    // If creds look good, build the Client!
    var client = buildClient(creds);

    setClient(client);

    resolve(client);

  });

};


/*

Get Shopify credentials from WordPress
Returns: Promise

*/
function getShopifyCreds() {

  return new Promise((resolve, reject) => {

    const action_name = 'get_shopify_creds';

    jQuery.ajax({
      method: 'GET',
      url: WP_Shopify.ajax,
      dataType: 'json',
      data: {
        action: action_name,
        nonce: WP_Shopify.nonce
      },
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

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

      localStorage.clear();

      var [credsError, creds] = await to( getShopifyCreds() ); // get_shopify_creds

      if (credsError) {
        return reject(credsError);
      }

      if (isWordPressError(creds)) {
        return reject(creds);
      }

      setStorefrontCreds(creds.data);
      return resolve(creds);

    }

  });

}


export {
  shopifyInit,
  getShopifyCreds,
  findShopifyCreds,
  formatCredsFromServer
}
