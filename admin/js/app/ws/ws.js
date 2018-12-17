import axios from 'axios';

import {
  mergeNewDataIntoCurrent,
  convertAuthDataToString,
  getErrorContents,
  getRestErrorContents
} from '../utils/utils-data';

import {
  isTimeout,
  findStatusCodeFirstNum
} from '../utils/utils';

import {
  nonce_api
} from '../globals/globals-general';


/*

EDD - Get License Key Info
Returns Promise

*/
function getProductInfo(key) {

  return new Promise((resolve, reject) => {

    const action_name = 'edd_action=get_version';

    jQuery.ajax({
      type: 'GET',
      url: 'https://wpshop.io/edd-sl?edd_action=get_version&item_name=WP+Shopify&license=' + key + '&url=' + WP_Shopify.siteUrl,
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}


/*

EDD - Check License Key Validity
Returns Promise

*/
function getLicenseKeyStatus(key) {

  return new Promise((resolve, reject) => {

    const action_name = 'edd_action=check_license';

    jQuery.ajax({
      type: 'GET',
      url: 'https://wpshop.io/edd-sl?edd_action=check_license&item_name=WP+Shopify&license=' + key + '&url=' + WP_Shopify.siteUrl,
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}


/*

EDD - Activate License Key
Returns Promise

*/
function activateLicenseKey(key) {

  return new Promise((resolve, reject) => {

    const action_name = 'edd_action=activate_license';

    jQuery.ajax({
      type: 'GET',
      url: 'https://wpshop.io/edd-sl?edd_action=activate_license&item_name=WP+Shopify&license=' + key + '&url=' + WP_Shopify.siteUrl,
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}


/*

EDD - Deactivate License Key
Returns Promise

*/
function deactivateLicenseKey(key) {

  return new Promise((resolve, reject) => {

    const action_name = 'edd_action=deactivate_license';

    var url = 'https://wpshop.io/edd-sl?edd_action=deactivate_license&item_name=WP+Shopify&license=' + key + '&url=' + WP_Shopify.siteUrl;

    jQuery.ajax({
      type: 'GET',
      url: url,
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}


/*

Kicks off the table migration process

*/
function migrateTables() {

  return new Promise( (resolve, reject) => {

    const action_name = 'run_table_migration_' + WP_Shopify.latestVersionCombined;

    jQuery.ajax({
      method: 'POST',
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

}


/*

Generic GET method for communicating with the WP Shopify REST API

endpoint - string representing the API enpoint

*/
function get(endpoint) {
  return request('get', endpoint);
}


/*

Generic POST method for communicating with the WP Shopify REST API

endpoint - string representing the API enpoint
data - the POST data object

*/
function post(endpoint, data = {}) {
  return request('post', endpoint, data);
}


/*

Generic DELETE method for communicating with the WP Shopify REST API

endpoint - string representing the API enpoint
data - the DELETE data object

*/
function deletion(endpoint, data = {}) {
  return request('delete', endpoint, data);
}


/*

Main request function

*/
function request(method, endpoint, data = {}) {

  return new Promise( (resolve, reject) => {

    axios({
      method: method,
      url: endpoint,
      data: data,
      headers: {
        'X-WP-Nonce': nonce_api()
      }
    })
    .then( response => resolve(response) )
    .catch ( error => reject( getRestErrorContents(error.response) ) );

  });

}


export {
  get,
  post,
  deletion,
  deactivateLicenseKey,
  activateLicenseKey,
  getLicenseKeyStatus,
  getProductInfo,
  migrateTables
}
