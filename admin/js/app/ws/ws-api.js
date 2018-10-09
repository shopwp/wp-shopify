import axios from 'axios';

import {
  getRestErrorContents
} from '../utils/utils-data';

import {
  endpointSettingAddToCartColor,
  endpointSettingVariantColor,
  endpointSettingCheckoutColor,
  endpointSettingCartCounterColor,
  endpointSettingCartIconColor
} from './ws-api-endpoints';


/*

Get setting: Add to cart color

*/
function getSettingAddToCartColor() {

  return new Promise( (resolve, reject) => {

    axios.get( endpointSettingAddToCartColor(), data)
      .then( response => resolve(response) )
      .catch ( error => reject( getRestErrorContents(error.response) ) );

  });

}


/*

Update setting: Add to cart color

*/
function updateSettingAddToCartColor(data) {

  return new Promise( (resolve, reject) => {

    axios.post( endpointSettingAddToCartColor(), data)
      .then( response => resolve(response) )
      .catch ( error => reject( getRestErrorContents(error.response) ) );

  });

}


/*

Update setting: Variant color

*/
function updateSettingVariantColor(data) {

  return new Promise( (resolve, reject) => {

    axios.post( endpointSettingVariantColor(), data)
      .then( response => resolve(response) )
      .catch ( error => reject( getRestErrorContents(error.response) ) );

  });

}


/*

Update setting: Checkout color

*/
function updateSettingCheckoutColor(data) {

  return new Promise( (resolve, reject) => {

    axios.post( endpointSettingCheckoutColor(), data)
      .then( response => resolve(response) )
      .catch ( error => reject( getRestErrorContents(error.response) ) );

  });

}


/*

Update setting: Cart counter color

*/
function updateSettingCartCounterColor(data) {

  return new Promise( (resolve, reject) => {

    axios.post( endpointSettingCartCounterColor(), data)
      .then( response => resolve(response) )
      .catch ( error => reject( getRestErrorContents(error.response) ) );

  });

}


/*

Update setting: Cart icon color

*/
function updateSettingCartIconColor(data) {

  return new Promise( (resolve, reject) => {

    axios.post( endpointSettingCartIconColor(), data)
      .then( response => resolve(response) )
      .catch ( error => reject( getRestErrorContents(error.response) ) );

  });

}

export {
  getSettingAddToCartColor,
  updateSettingAddToCartColor,
  updateSettingVariantColor,
  updateSettingCheckoutColor,
  updateSettingCartCounterColor,
  updateSettingCartIconColor
}
