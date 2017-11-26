import 'whatwg-fetch';

/*

Get Single Product by ID
Returns: Promise

*/
function getProduct(shopify, productId) {
  return shopify.fetchProduct(productId);
};


/*

Get Product Variant ID

*/
function getProductVariantID(product, productVariantID) {
  return product.variants.filter(function productVariantsFilter(variant) {
    return variant.id == productVariantID;
  })[0];
};


/*

Check if any cart items are in local storage

*/
function getCartID() {
  return localStorage.getItem('wps-last-cart-id');
};


/*

Getting product variant ID from product options
TODO: Move to WS

*/
function getVariantIdFromOptions(productID, selectedOptions) {

  return jQuery.ajax({
    method: 'POST',
    url: wps.ajax,
    dataType: 'json',
    data: {
      action: 'wps_get_variant_id',
      productID: productID,
      selectedOptions: selectedOptions
    }
  });

}


/*

Check if any cart items are in local storage

*/
function setMoneyFormatCache(moneyFormat) {

  localStorage.setItem('wps-money-format', moneyFormat);
  setCacheTime();

};


/*

Check if any cart items are in local storage

*/
function getMoneyFormatCache() {
  return localStorage.getItem('wps-money-format');
};



/*

Check if any cart items are in local storage

*/
function setCacheTime() {
  localStorage.setItem('wps-cache-expiration', new Date().getTime());
};


/*

Check if any cart items are in local storage

*/
function getCacheTime() {
  return localStorage.getItem('wps-cache-expiration');
};


/*

Set Product Selection ID

*/
function setProductSelectionID(id) {
  return localStorage.setItem('wps-product-selection-id', id);
};


/*

Get Product Selection ID

*/
function getProductSelectionID() {
  return localStorage.getItem('wps-product-selection-id');
};


function getProductOptionIds() {
  return localStorage.getItem('wps-option-ids');
};


function setProductOptionIds(optionIds) {
  localStorage.setItem('wps-option-ids', optionIds);
};

function removeProductOptionIds() {
  localStorage.removeItem('wps-option-ids');
};


/*

moneyFormatChanged

*/
function moneyFormatChanged() {

  return jQuery.ajax({
    method: 'POST',
    url: wps.ajax,
    dataType: 'json',
    data: {
      action: 'wps_has_money_format_changed',
      format: getMoneyFormatCache()
    }
  });

}




export {
  getProduct,
  getProductVariantID,
  getCartID,
  getVariantIdFromOptions,
  setMoneyFormatCache,
  getMoneyFormatCache,
  moneyFormatChanged,
  setCacheTime,
  getCacheTime,
  getProductSelectionID,
  setProductSelectionID,
  getProductOptionIds,
  setProductOptionIds,
  removeProductOptionIds
};
