function endpointPrefix() {
  return WP_Shopify.API.restUrl + WP_Shopify.API.namespace + '/';
}

function endpointSettingAddToCartColor() {
  return endpointPrefix() + 'settings/add_to_cart_color';
}

function endpointSettingVariantColor() {
  return endpointPrefix() + 'settings/variant_color';
}

function endpointSettingCheckoutColor() {
  return endpointPrefix() + 'settings/checkout_color';
}

function endpointSettingCartCounterColor() {
  return endpointPrefix() + 'settings/cart_counter_color';
}

function endpointSettingCartIconColor() {
  return endpointPrefix() + 'settings/cart_icon_color';
}

export {
  endpointSettingAddToCartColor,
  endpointSettingVariantColor,
  endpointSettingCheckoutColor,
  endpointSettingCartCounterColor,
  endpointSettingCartIconColor
}
