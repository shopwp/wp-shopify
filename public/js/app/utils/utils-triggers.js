function triggerEventAfterBootstrap(checkout) {
  jQuery(document).trigger("wpshopify_bootstrap_after", [checkout]);
}

function triggerEventBeforeBootstrap() {
  jQuery(document).trigger("wpshopify_bootstrap_before");
}

function triggerEventAfterAddToCart(product, checkout) {
  jQuery(document).trigger("wpshopify_add_to_cart_after", [product, checkout]);
}

function triggerEventBeforeAddToCart(product) {
  jQuery(document).trigger("wpshopify_add_to_cart_before", [product]);
}




export {
  triggerEventAfterBootstrap,
  triggerEventBeforeBootstrap,
  triggerEventAfterAddToCart,
  triggerEventBeforeAddToCart
}
