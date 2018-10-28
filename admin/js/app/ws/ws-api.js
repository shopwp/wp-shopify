import axios from 'axios';

import {
  getRestErrorContents
} from '../utils/utils-data';

import {
  endpointSettingAddToCartColor,
  endpointSettingVariantColor,
  endpointSettingCheckoutColor,
  endpointSettingCartCounterColor,
  endpointSettingCartIconColor,
  endpointSettingProductsHeading,
  endpointSettingCollectionsHeading,
  endpointSettingRelatedProductsHeading,
  endpointSettingProductsHeadingToggle,
  endpointSettingCollectionsHeadingToggle,
  endpointSettingRelatedProductsHeadingToggle,
  endpointSettingProductsImagesSizingToggle,
  endpointSettingProductsImagesSizingWidth,
  endpointSettingProductsImagesSizingHeight,
  endpointSettingProductsImagesSizingCrop,
  endpointSettingProductsImagesSizingScale,
  endpointSettingCollectionsImagesSizingToggle,
  endpointSettingCollectionsImagesSizingWidth,
  endpointSettingCollectionsImagesSizingHeight,
  endpointSettingCollectionsImagesSizingCrop,
  endpointSettingCollectionsImagesSizingScale,
  endpointSettingRelatedProductsImagesSizingToggle,
  endpointSettingRelatedProductsImagesSizingWidth,
  endpointSettingRelatedProductsImagesSizingHeight,
  endpointSettingRelatedProductsImagesSizingCrop,
  endpointSettingRelatedProductsImagesSizingScale,
  endpointSettingCheckoutEnableCustomCheckoutDomain
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


/*

Update setting: products heading toggle

*/
function updateSettingProductsHeadingToggle(data) {

  return new Promise( (resolve, reject) => {

    axios.post( endpointSettingProductsHeadingToggle(), data)
      .then( response => resolve(response) )
      .catch ( error => reject( getRestErrorContents(error.response) ) );

  });

}


/*

Update setting: Cart icon color

*/
function updateSettingProductsHeading(data) {

  return new Promise( (resolve, reject) => {

    axios.post( endpointSettingProductsHeading(), data)
      .then( response => resolve(response) )
      .catch ( error => reject( getRestErrorContents(error.response) ) );

  });

}


/*

Update setting: collections heading

*/
function updateSettingCollectionsHeadingToggle(data) {

  return new Promise( (resolve, reject) => {

    axios.post( endpointSettingCollectionsHeadingToggle(), data)
      .then( response => resolve(response) )
      .catch ( error => reject( getRestErrorContents(error.response) ) );

  });

}


/*

Update setting: related products heading toggle

*/
function updateSettingRelatedProductsHeadingToggle(data) {

  return new Promise( (resolve, reject) => {

    axios.post( endpointSettingRelatedProductsHeadingToggle(), data)
      .then( response => resolve(response) )
      .catch ( error => reject( getRestErrorContents(error.response) ) );

  });

}


/*

Update setting: collections heading

*/
function updateSettingCollectionsHeading(data) {

  return new Promise( (resolve, reject) => {

    axios.post( endpointSettingCollectionsHeading(), data)
      .then( response => resolve(response) )
      .catch ( error => reject( getRestErrorContents(error.response) ) );

  });

}


/*

Update setting: related products heading

*/
function updateSettingRelatedProductsHeading(data) {

  return new Promise( (resolve, reject) => {

    axios.post( endpointSettingRelatedProductsHeading(), data)
      .then( response => resolve(response) )
      .catch ( error => reject( getRestErrorContents(error.response) ) );

  });

}


/*

Update setting: related products heading toggle

*/
function updateSettingProductsImagesSizingToggle(data) {

  return new Promise( (resolve, reject) => {

    axios.post( endpointSettingProductsImagesSizingToggle(), data)
      .then( response => resolve(response) )
      .catch ( error => reject( getRestErrorContents(error.response) ) );

  });

}


/*

Update setting: related products heading toggle

*/
function updateSettingProductsImagesSizingWidth(data) {

  return new Promise( (resolve, reject) => {

    axios.post( endpointSettingProductsImagesSizingWidth(), data)
      .then( response => resolve(response) )
      .catch ( error => reject( getRestErrorContents(error.response) ) );

  });

}


/*

Update setting: related products heading toggle

*/
function updateSettingProductsImagesSizingHeight(data) {

  return new Promise( (resolve, reject) => {

    axios.post( endpointSettingProductsImagesSizingHeight(), data)
      .then( response => resolve(response) )
      .catch ( error => reject( getRestErrorContents(error.response) ) );

  });

}


/*

Update setting: related products heading toggle

*/
function updateSettingProductsImagesSizingCrop(data) {

  return new Promise( (resolve, reject) => {

    axios.post( endpointSettingProductsImagesSizingCrop(), data)
      .then( response => resolve(response) )
      .catch ( error => reject( getRestErrorContents(error.response) ) );

  });

}


/*

Update setting: related products heading toggle

*/
function updateSettingProductsImagesSizingScale(data) {

  return new Promise( (resolve, reject) => {

    axios.post( endpointSettingProductsImagesSizingScale(), data)
      .then( response => resolve(response) )
      .catch ( error => reject( getRestErrorContents(error.response) ) );

  });

}





/*

Update setting: related products heading toggle

*/
function updateSettingCollectionsImagesSizingToggle(data) {

  return new Promise( (resolve, reject) => {

    axios.post( endpointSettingCollectionsImagesSizingToggle(), data)
      .then( response => resolve(response) )
      .catch ( error => reject( getRestErrorContents(error.response) ) );

  });

}


/*

Update setting: related products heading toggle

*/
function updateSettingCollectionsImagesSizingWidth(data) {

  return new Promise( (resolve, reject) => {

    axios.post( endpointSettingCollectionsImagesSizingWidth(), data)
      .then( response => resolve(response) )
      .catch ( error => reject( getRestErrorContents(error.response) ) );

  });

}


/*

Update setting: related products heading toggle

*/
function updateSettingCollectionsImagesSizingHeight(data) {

  return new Promise( (resolve, reject) => {

    axios.post( endpointSettingCollectionsImagesSizingHeight(), data)
      .then( response => resolve(response) )
      .catch ( error => reject( getRestErrorContents(error.response) ) );

  });

}


/*

Update setting: related products heading toggle

*/
function updateSettingCollectionsImagesSizingCrop(data) {

  return new Promise( (resolve, reject) => {

    axios.post( endpointSettingCollectionsImagesSizingCrop(), data)
      .then( response => resolve(response) )
      .catch ( error => reject( getRestErrorContents(error.response) ) );

  });

}


/*

Update setting: related products heading toggle

*/
function updateSettingCollectionsImagesSizingScale(data) {

  return new Promise( (resolve, reject) => {

    axios.post( endpointSettingCollectionsImagesSizingScale(), data)
      .then( response => resolve(response) )
      .catch ( error => reject( getRestErrorContents(error.response) ) );

  });

}












/*

Update setting: related products heading toggle

*/
function updateSettingRelatedProductsImagesSizingToggle(data) {

  return new Promise( (resolve, reject) => {

    axios.post( endpointSettingRelatedProductsImagesSizingToggle(), data)
      .then( response => resolve(response) )
      .catch ( error => reject( getRestErrorContents(error.response) ) );

  });

}


/*

Update setting: related products heading toggle

*/
function updateSettingRelatedProductsImagesSizingWidth(data) {

  return new Promise( (resolve, reject) => {

    axios.post( endpointSettingRelatedProductsImagesSizingWidth(), data)
      .then( response => resolve(response) )
      .catch ( error => reject( getRestErrorContents(error.response) ) );

  });

}


/*

Update setting: related products heading toggle

*/
function updateSettingRelatedProductsImagesSizingHeight(data) {

  return new Promise( (resolve, reject) => {

    axios.post( endpointSettingRelatedProductsImagesSizingHeight(), data)
      .then( response => resolve(response) )
      .catch ( error => reject( getRestErrorContents(error.response) ) );

  });

}


/*

Update setting: related products heading toggle

*/
function updateSettingRelatedProductsImagesSizingCrop(data) {

  return new Promise( (resolve, reject) => {

    axios.post( endpointSettingRelatedProductsImagesSizingCrop(), data)
      .then( response => resolve(response) )
      .catch ( error => reject( getRestErrorContents(error.response) ) );

  });

}


/*

Update setting: related products heading toggle

*/
function updateSettingRelatedProductsImagesSizingScale(data) {

  return new Promise( (resolve, reject) => {

    axios.post( endpointSettingRelatedProductsImagesSizingScale(), data)
      .then( response => resolve(response) )
      .catch ( error => reject( getRestErrorContents(error.response) ) );

  });

}


/*

Update setting: related products heading toggle

*/
function updateSettingCheckoutEnableCustomCheckoutDomain(data) {

  return new Promise( (resolve, reject) => {

    axios.post( endpointSettingCheckoutEnableCustomCheckoutDomain(), data)
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
  updateSettingCartIconColor,
  updateSettingProductsHeadingToggle,
  updateSettingProductsHeading,
  updateSettingCollectionsHeading,
  updateSettingRelatedProductsHeading,
  updateSettingCollectionsHeadingToggle,
  updateSettingRelatedProductsHeadingToggle,
  updateSettingProductsImagesSizingToggle,
  updateSettingProductsImagesSizingWidth,
  updateSettingProductsImagesSizingHeight,
  updateSettingProductsImagesSizingCrop,
  updateSettingProductsImagesSizingScale,
  updateSettingCollectionsImagesSizingToggle,
  updateSettingCollectionsImagesSizingWidth,
  updateSettingCollectionsImagesSizingHeight,
  updateSettingCollectionsImagesSizingCrop,
  updateSettingCollectionsImagesSizingScale,
  updateSettingRelatedProductsImagesSizingToggle,
  updateSettingRelatedProductsImagesSizingWidth,
  updateSettingRelatedProductsImagesSizingHeight,
  updateSettingRelatedProductsImagesSizingCrop,
  updateSettingRelatedProductsImagesSizingScale,
  updateSettingCheckoutEnableCustomCheckoutDomain

}
