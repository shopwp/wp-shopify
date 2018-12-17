import {
  get,
  post
} from '../ws';

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
  endpointSettingCheckoutEnableCustomCheckoutDomain,
  endpointSettingSelectedCollections
} from './api-endpoints';


/*

Update setting: Add to cart color

*/
function updateSettingAddToCartColor(data) {
  return post( endpointSettingAddToCartColor(), data );
}


/*

Update setting: Variant color

*/
function updateSettingVariantColor(data) {
  return post( endpointSettingVariantColor(), data );
}


/*

Update setting: Checkout color

*/
function updateSettingCheckoutColor(data) {
  return post( endpointSettingCheckoutColor(), data );
}


/*

Update setting: Cart counter color

*/
function updateSettingCartCounterColor(data) {
  return post( endpointSettingCartCounterColor(), data );
}


/*

Update setting: Cart icon color

*/
function updateSettingCartIconColor(data) {
  return post( endpointSettingCartIconColor(), data );
}


/*

Update setting: products heading toggle

*/
function updateSettingProductsHeadingToggle(data) {
  return post( endpointSettingProductsHeadingToggle(), data );
}


/*

Update setting: Cart icon color

*/
function updateSettingProductsHeading(data) {
  return post( endpointSettingProductsHeading(), data );
}


/*

Update setting: collections heading

*/
function updateSettingCollectionsHeadingToggle(data) {
  return post( endpointSettingCollectionsHeadingToggle(), data );
}


/*

Update setting: related products heading toggle

*/
function updateSettingRelatedProductsHeadingToggle(data) {
  return post( endpointSettingRelatedProductsHeadingToggle(), data );
}


/*

Update setting: collections heading

*/
function updateSettingCollectionsHeading(data) {
  return post( endpointSettingCollectionsHeading(), data );
}


/*

Update setting: related products heading

*/
function updateSettingRelatedProductsHeading(data) {
  return post( endpointSettingRelatedProductsHeading(), data );
}


/*

Update setting: related products heading toggle

*/
function updateSettingProductsImagesSizingToggle(data) {
  return post( endpointSettingProductsImagesSizingToggle(), data );
}


/*

Update setting: related products heading toggle

*/
function updateSettingProductsImagesSizingWidth(data) {
  return post( endpointSettingProductsImagesSizingWidth(), data );
}


/*

Update setting: related products heading toggle

*/
function updateSettingProductsImagesSizingHeight(data) {
  return post( endpointSettingProductsImagesSizingHeight(), data );
}


/*

Update setting: related products heading toggle

*/
function updateSettingProductsImagesSizingCrop(data) {
  return post( endpointSettingProductsImagesSizingCrop(), data );
}


/*

Update setting: related products heading toggle

*/
function updateSettingProductsImagesSizingScale(data) {
  return post( endpointSettingProductsImagesSizingScale(), data );
}





/*

Update setting: related products heading toggle

*/
function updateSettingCollectionsImagesSizingToggle(data) {
  return post( endpointSettingCollectionsImagesSizingToggle(), data );
}


/*

Update setting: related products heading toggle

*/
function updateSettingCollectionsImagesSizingWidth(data) {
  return post( endpointSettingCollectionsImagesSizingWidth(), data );
}


/*

Update setting: related products heading toggle

*/
function updateSettingCollectionsImagesSizingHeight(data) {
  return post( endpointSettingCollectionsImagesSizingHeight(), data );
}


/*

Update setting: related products heading toggle

*/
function updateSettingCollectionsImagesSizingCrop(data) {
  return post( endpointSettingCollectionsImagesSizingCrop(), data );
}


/*

Update setting: related products heading toggle

*/
function updateSettingCollectionsImagesSizingScale(data) {
  return post( endpointSettingCollectionsImagesSizingScale(), data );
}


/*

Update setting: related products heading toggle

*/
function updateSettingRelatedProductsImagesSizingToggle(data) {
  return post( endpointSettingRelatedProductsImagesSizingToggle(), data );
}


/*

Update setting: related products heading toggle

*/
function updateSettingRelatedProductsImagesSizingWidth(data) {
  return post( endpointSettingRelatedProductsImagesSizingWidth(), data );
}


/*

Update setting: related products heading toggle

*/
function updateSettingRelatedProductsImagesSizingHeight(data) {
  return post( endpointSettingRelatedProductsImagesSizingHeight(), data );
}


/*

Update setting: related products heading toggle

*/
function updateSettingRelatedProductsImagesSizingCrop(data) {
  return post( endpointSettingRelatedProductsImagesSizingCrop(), data );
}


/*

Update setting: related products heading toggle

*/
function updateSettingRelatedProductsImagesSizingScale(data) {
  return post( endpointSettingRelatedProductsImagesSizingScale(), data );
}


/*

Update setting: related products heading toggle

*/
function updateSettingCheckoutEnableCustomCheckoutDomain(data) {
  return post( endpointSettingCheckoutEnableCustomCheckoutDomain(), data );
}


/*

Get Smart Collections Count

Returns: promise

*/
function getSelectedCollections() {
  return get( endpointSettingSelectedCollections() );
}


export {
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
  updateSettingCheckoutEnableCustomCheckoutDomain,
  getSelectedCollections
}
