import {
  syncConnection,
  syncShop,
  syncProducts,
  syncCollects,
  syncSmartCollections,
  syncCustomCollections,
  syncOrders,
  syncCustomers
} from './syncing';

import {
  setConnectionStepMessage
} from '../connect/connect';

import {
  updateModalHeadingText,
  updateCurrentConnectionStepText
} from '../utils/utils-dom';

import {
  uninstallPluginData
} from '../disconnect/disconnect';


/*

Syncing Shopify data with WordPress CPT

*/
async function syncPluginData() {


  // 1. Smart Collections
  try {
    await syncSmartCollections();

  } catch(errors) {

    updateCurrentConnectionStepText('Unable to finish syncing');
    return new Error(errors);

  }


  // 2. Smart Collections
  try {
    await syncCustomCollections();

  } catch(errors) {

    updateCurrentConnectionStepText('Unable to finish syncing');
    return new Error(errors);

  }


  // 3. Remaining data
  try {

    var remainingResp = await Promise.all([
      syncConnection(),
      syncShop(),
      syncProducts(),
      syncCollects(),
      syncOrders(),
      syncCustomers()
    ]);

  } catch(errors) {

    updateCurrentConnectionStepText('Unable to finish syncing');
    return new Error(errors);

  }

  return remainingResp;


}

export {
  syncPluginData
}
