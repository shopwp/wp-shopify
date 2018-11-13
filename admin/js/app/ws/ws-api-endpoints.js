function endpointPrefix() {
  return WP_Shopify.API.restUrl + WP_Shopify.API.namespace + '/';
}

function endpointSettingAddToCartColor() {
  return endpointPrefix() + 'settings/products_add_to_cart_color';
}

function endpointSettingVariantColor() {
  return endpointPrefix() + 'settings/products_variant_color';
}

function endpointSettingCheckoutColor() {
  return endpointPrefix() + 'settings/cart_checkout_color';
}

function endpointSettingCartCounterColor() {
  return endpointPrefix() + 'settings/cart_counter_color';
}

function endpointSettingCartIconColor() {
  return endpointPrefix() + 'settings/cart_icon_color';
}

function endpointSettingProductsHeadingToggle() {
  return endpointPrefix() + 'settings/products_heading_toggle';
}

function endpointSettingProductsHeading() {
  return endpointPrefix() + 'settings/products_heading';
}

function endpointSettingCollectionsHeadingToggle() {
  return endpointPrefix() + 'settings/collections_heading_toggle';
}

function endpointSettingCollectionsHeading() {
  return endpointPrefix() + 'settings/collections_heading';
}

function endpointSettingRelatedProductsHeading() {
  return endpointPrefix() + 'settings/related_products_heading';
}

function endpointSettingRelatedProductsHeadingToggle() {
  return endpointPrefix() + 'settings/related_products_heading_toggle';
}

function endpointSettingProductsImagesSizingToggle() {
  return endpointPrefix() + 'settings/products_images_sizing_toggle';
}

function endpointSettingProductsImagesSizingWidth() {
  return endpointPrefix() + 'settings/products_images_sizing_width';
}

function endpointSettingProductsImagesSizingHeight() {
  return endpointPrefix() + 'settings/products_images_sizing_height';
}

function endpointSettingProductsImagesSizingCrop() {
  return endpointPrefix() + 'settings/products_images_sizing_crop';
}

function endpointSettingProductsImagesSizingScale() {
  return endpointPrefix() + 'settings/products_images_sizing_scale';
}

function endpointSettingCollectionsImagesSizingToggle() {
  return endpointPrefix() + 'settings/collections_images_sizing_toggle';
}

function endpointSettingCollectionsImagesSizingWidth() {
  return endpointPrefix() + 'settings/collections_images_sizing_width';
}

function endpointSettingCollectionsImagesSizingHeight() {
  return endpointPrefix() + 'settings/collections_images_sizing_height';
}

function endpointSettingCollectionsImagesSizingCrop() {
  return endpointPrefix() + 'settings/collections_images_sizing_crop';
}

function endpointSettingCollectionsImagesSizingScale() {
  return endpointPrefix() + 'settings/collections_images_sizing_scale';
}

function endpointSettingRelatedProductsImagesSizingToggle() {
  return endpointPrefix() + 'settings/related_products_images_sizing_toggle';
}

function endpointSettingRelatedProductsImagesSizingWidth() {
  return endpointPrefix() + 'settings/related_products_images_sizing_width';
}

function endpointSettingRelatedProductsImagesSizingHeight() {
  return endpointPrefix() + 'settings/related_products_images_sizing_height';
}

function endpointSettingRelatedProductsImagesSizingCrop() {
  return endpointPrefix() + 'settings/related_products_images_sizing_crop';
}

function endpointSettingRelatedProductsImagesSizingScale() {
  return endpointPrefix() + 'settings/related_products_images_sizing_scale';
}

function endpointSettingCheckoutEnableCustomCheckoutDomain() {
  return endpointPrefix() + 'settings/checkout_enable_custom_checkout_domain';
}

function endpointSettingProductsCompareAt() {
  return endpointPrefix() + 'settings/products_compare_at';
}

export {
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
  endpointSettingProductsCompareAt
}
