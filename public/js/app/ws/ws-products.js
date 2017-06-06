/*

Get Single Product by ID
Returns: Promise

*/
function getProduct(shopify, productId) {

  console.log("shopify: ", shopify);
  console.log("productId: ", productId);

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
function hasItemsInLocalStorage() {
  return localStorage.getItem('wps-last-cart-id');
};


/*

Getting all products, returns promise

*/
function getAllProducts(shopify) {
  return shopify.fetchAllProducts();
}


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

export {
  getProduct,
  getProductVariantID,
  hasItemsInLocalStorage,
  getVariantIdFromOptions
};
