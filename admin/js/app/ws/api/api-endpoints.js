function endpointPrefix() {
  return WP_Shopify.API.restUrl + WP_Shopify.API.namespace + '/';
}


/*

Notices Endpoints

*/
function endpointNotices() {
  return endpointPrefix() + 'notices';
}

function endpointNoticesDismiss() {
  return endpointPrefix() + 'notices/dismiss';
}


/*

Settings Endpoints

*/
function endpointSettings() {
  return endpointPrefix() + 'settings';
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

function endpointSettingCartCounterFixedColor() {
  return endpointPrefix() + 'settings/cart_counter_fixed_color';
}

function endpointSettingCartFixedBackgroundColor() {
  return endpointPrefix() + 'settings/cart_fixed_background_color';
}

function endpointSettingCartIconFixedColor() {
  return endpointPrefix() + 'settings/cart_icon_fixed_color';
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

function endpointSettingPricingCompareAt() {
  return endpointPrefix() + 'settings/products_compare_at';
}

function endpointSettingSelectedCollections() {
  return endpointPrefix() + 'settings/selected_collections';
}



/*

Syncing Endpoints

*/
function endpointSyncingStatus() {
  return endpointPrefix() + 'syncing/status';
}

function endpointSyncingStatusPosts() {
  return endpointPrefix() + 'syncing/status/posts';
}

function endpointSyncingStatusWebhooks() {
  return endpointPrefix() + 'syncing/status/webhooks';
}

function endpointSyncingStatusRemoval() {
  return endpointPrefix() + 'syncing/status/removal';
}

function endpointSyncingStop() {
  return endpointPrefix() + 'syncing/stop';
}

function endpointSyncingNotices() {
  return endpointPrefix() + 'syncing/notices';
}

function endpointSyncingIndicator() {
  return endpointPrefix() + 'syncing/indicator';
}

function endpointSyncingCounts() {
  return endpointPrefix() + 'syncing/counts';
}

function endpointSyncingCount() {
  return endpointPrefix() + 'syncing/count';
}


/*

Collections Endpoints

*/
function endpointSmartCollections() {
  return endpointPrefix() + 'smart_collections';
}

function endpointSmartCollectionsCount() {
  return endpointPrefix() + 'smart_collections/count';
}

function endpointCustomCollections() {
  return endpointPrefix() + 'custom_collections';
}

function endpointCustomCollectionsCount() {
  return endpointPrefix() + 'custom_collections/count';
}

function endpointAllCollections() {
  return endpointPrefix() + 'collections';
}


/*

Products Endpoints

*/
function endpointProducts() {
  return endpointPrefix() + 'products';
}

function endpointProductsCount() {
  return endpointPrefix() + 'products/count';
}

function endpointPublishedProductIds() {
  return endpointPrefix() + 'products/ids';
}


/*

Posts Endpoints

*/
function endpointPosts() {
  return endpointPrefix() + 'posts';
}

function endpointPostsProducts() {
  return endpointPrefix() + 'posts/products';
}

function endpointPostsCollections() {
  return endpointPrefix() + 'posts/collections';
}


/*

Variants

*/
function endpointVariants() {
  return endpointPrefix() + 'variants';
}


/*

Collects Endpoints

*/
function endpointCollects() {
  return endpointPrefix() + 'collects';
}

function endpointCollectsCount() {
  return endpointPrefix() + 'collects/count';
}


/*

Shop Endpoints

*/
function endpointShop() {
  return endpointPrefix() + 'shop';
}

function endpointShopCount() {
  return endpointPrefix() + 'shop/count';
}


/*

Orders Endpoints

*/
function endpointOrders() {
  return endpointPrefix() + 'orders';
}

function endpointOrdersCount() {
  return endpointPrefix() + 'orders/count';
}


/*

Customers Endpoints

*/
function endpointCustomers() {
  return endpointPrefix() + 'customers';
}

function endpointCustomersCount() {
  return endpointPrefix() + 'customers/count';
}


/*

Webhooks Endpoints

*/

function endpointWebhooks() {
  return endpointPrefix() + 'webhooks';
}

function endpointWebhooksCount() {
  return endpointPrefix() + 'webhooks/count';
}

function endpointWebhooksDelete() {
  return endpointPrefix() + 'webhooks/delete';
}


/*

Tools Endpoints

*/
function endpointToolsClearCache() {
  return endpointPrefix() + 'cache';
}

function endpointToolsClearAll() {
  return endpointPrefix() + 'clear/all';
}

function endpointToolsClearSynced() {
  return endpointPrefix() + 'clear/synced';
}


/*

License Endpoints

*/
function endpointLicense() {
  return endpointPrefix() + 'license';
}

function endpointLicenseDelete() {
  return endpointPrefix() + 'license/delete';
}


/*

Connection Endpoints

*/
function endpointConnection() {
  return endpointPrefix() + 'connection';
}

function endpointConnectionDelete() {
  return endpointPrefix() + 'connection/delete';
}

function endpointConnectionCheck() {
  return endpointPrefix() + 'connection/check';
}


export {
  endpointSettings,
  endpointSettingAddToCartColor,
  endpointSettingVariantColor,
  endpointSettingCheckoutColor,
  endpointSettingCartCounterColor,
  endpointSettingCartIconColor,
  endpointSettingCartIconFixedColor,
  endpointSettingCartFixedBackgroundColor,
  endpointSettingCartCounterFixedColor,
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
  endpointSettingPricingCompareAt,
  endpointSettingSelectedCollections,
  endpointSyncingStatus,
  endpointSyncingStatusPosts,
  endpointSyncingStatusWebhooks,
  endpointSyncingStatusRemoval,
  endpointSyncingIndicator,
  endpointSyncingNotices,
  endpointSyncingStop,
  endpointSyncingCounts,
  endpointSyncingCount,
  endpointSmartCollections,
  endpointSmartCollectionsCount,
  endpointCustomCollections,
  endpointCustomCollectionsCount,
  endpointProducts,
  endpointProductsCount,
  endpointPostsProducts,
  endpointPostsCollections,
  endpointCollects,
  endpointCollectsCount,
  endpointShop,
  endpointShopCount,
  endpointOrders,
  endpointOrdersCount,
  endpointCustomers,
  endpointCustomersCount,
  endpointWebhooksCount,
  endpointWebhooksDelete,
  endpointWebhooks,
  endpointPublishedProductIds,
  endpointAllCollections,
  endpointVariants,
  endpointToolsClearCache,
  endpointToolsClearSynced,
  endpointToolsClearAll,
  endpointPosts,
  endpointLicense,
  endpointLicenseDelete,
  endpointNotices,
  endpointNoticesDismiss,
  endpointConnection,
  endpointConnectionCheck,
  endpointConnectionDelete
}
