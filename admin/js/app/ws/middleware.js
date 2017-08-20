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

/*

Syncing Shopify data with WordPress CPT

*/
function syncPluginData() {

  return Promise.all([
    syncConnection(),
    syncShop(),
    syncProducts(),
    syncCollects(),
    syncSmartCollections(),
    syncCustomCollections()
  ])
  .catch(async function(error) {

    console.log('Error syncing plugin data: ', error);

    if (error.hasOwnProperty('data')) {
      return new Error(error.data);

    } else {
      return new Error(error);

    }

  });

}

export {
  syncPluginData
}
