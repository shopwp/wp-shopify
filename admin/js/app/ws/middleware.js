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
    return new Error(error);
  });

}

export {
  syncPluginData
}
