import to from 'await-to-js';
import isError from 'lodash/isError';

import {
  setSyncingIndicator
} from './api/api-syncing';


/*

Turn syncing flag on. Changes the "is_syncing" column inside the connection table

*/
function syncOn() {

  return setSyncingIndicator({
    syncing: true
  });

}


/*

Turn syncing flag off. Changes the "is_syncing" column inside the connection table

*/
function syncOff() {

  return setSyncingIndicator({
    syncing: false
  });

}


export {
  syncOn,
  syncOff
}
