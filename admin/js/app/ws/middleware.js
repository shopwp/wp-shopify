import {
  syncConnection,
  syncShop,
  syncProducts,
  syncCollects,
  syncSmartCollections,
  syncCustomCollections
} from './syncing';

import {
  setConnectionStepMessage
} from '../connect/connect';

import {
  uninstallPluginData
} from '../disconnect/disconnect';

import {
  updateModalHeadingText
} from '../utils/utils-dom';

import {
  removePluginData
} from '../ws/ws';


/*

Syncing Shopify data with WordPress CPT

*/
async function syncPluginData() {


  // 1. Smart Collections
  try {
    await syncSmartCollections();

  } catch(syncSmartCollectionsError) {

    console.error('Error syncing smart collections data: ', syncSmartCollectionsError);

    if (syncSmartCollectionsError.hasOwnProperty('data')) {
      return new Error(syncSmartCollectionsError.data);

    } else {
      return new Error(syncSmartCollectionsError);

    }

  }


  // 2. Smart Collections
  try {
    await syncCustomCollections();

  } catch(syncCustomCollectionsError) {

    console.error('Error syncing custom collections data: ', syncCustomCollectionsError);

    if (syncCustomCollectionsError.hasOwnProperty('data')) {
      return new Error(syncCustomCollectionsError.data);

    } else {
      return new Error(syncCustomCollectionsError);

    }

  }


  // 3. Remaining data
  try {

    var remainingResp = await Promise.all([
      syncConnection(),
      syncShop(),
      syncProducts(),
      syncCollects()
    ]);

  } catch(remainingError) {

    console.error('Error syncing plugin data: ', remainingError);

    if (remainingError.hasOwnProperty('data')) {
      return new Error(remainingError.data);

    } else {
      return new Error(remainingError);

    }

  }

  return remainingResp;


}

export {
  syncPluginData
}
