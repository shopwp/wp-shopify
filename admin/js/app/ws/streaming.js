import to from 'await-to-js';

import {
  isWordPressError
} from '../utils/utils';

import {
  cleanUpAfterSync
} from '../utils/utils-progress';

import {
  syncingConfigJavascriptError,
  syncingConfigErrorBeforeSync
} from './syncing-config';


/*

Construct Streaming Options

itemCount = (int)

*/
function constructStreamingOptions(itemCount) {

  return {
    currentPage: 1,
    pages: Math.ceil( (parseInt(itemCount) / parseInt(WP_Shopify.itemsPerRequest)) )
  }

}


/*

Fires requests to batch items

*/
function batchItemsPerPage(itemCount, batchFunction) {

  return new Promise( async function batchItemsHandler(resolve, reject) {

    var { currentPage, pages } = constructStreamingOptions(itemCount);

    while (currentPage <= pages) {

      var [error, data] = await to( batchFunction({
        'page': currentPage
      }) );

      if (error) {
        cleanUpAfterSync( syncingConfigJavascriptError(error) );
        resolve();
        break;
      }

      if ( isWordPressError(data) ) {
        cleanUpAfterSync( syncingConfigErrorBeforeSync(data) );
        resolve();
        break;
      }

      currentPage++;

    }

    resolve();

  });

}


/*

Begins the Products background syncing process

*/
function streamItems(itemCount, batchFunction) {

  return new Promise( async function streamItemsHandler(resolve, reject) {
    return resolve( await batchItemsPerPage(itemCount, batchFunction) );
  });

}


export {
  streamItems
}
