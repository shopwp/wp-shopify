import isError from 'lodash/isError';

import {
  updateSettings
} from '../ws/ws.js';

import {
  clearAllCache
} from '../tools/cache.js';

import {
  enable,
  disable,
  showSpinner,
  hideSpinner,
  showLoader,
  hideLoader,
  containsTrailingForwardSlash,
  removeTrailingForwardSlash,
  isWordPressError
} from '../utils/utils.js';

import {
  showAdminNotice,
  toggleActive
} from '../utils/utils-dom.js';

import {
  rejectedPromise
} from '../utils/utils-data.js';


/*

When License key form is submitted ...

*/
function onSettingsFormSubmit() {

  var $formSettings = jQuery("#wps-settings");

  $formSettings.submit(function(e) {
    e.preventDefault();

  }).validate({

    rules: {
      "wps_settings_general[wps_general_url_products]": {
        alphaNumeric: false
      },
      "wps_settings_general[wps_general_url_collections]": {
        alphaNumeric: false
      },
      "wps_settings_general[wps_general_num_posts]": {
        number: true
      },
      /* @if NODE_ENV='pro' */
      "wps_settings_general[wps_general_webhooks_products]": {
        urlRule: true
      }
      /* @endif */
    },
    errorPlacement: function(error) {
      showAdminNotice(error.text(), 'error');

    },
    submitHandler: async function(form) {

      var $submitButton = jQuery(form).find('input[type="submit"]');
      var $spinner = jQuery(form).find('.spinner');
      var nonce = jQuery("#wps_settings_general_urls_nonce_id").val();
      var productsURL = jQuery(form).find("#wps_settings_general_url_products").val();
      var collectionsURL = jQuery(form).find("#wps_settings_general_url_collections").val();
      /* @if NODE_ENV='pro' */
      var webhooksURL = jQuery(form).find("#wps_settings_general_url_webhooks").val();
      /* @endif */
      var numPosts = jQuery(form).find("#wps_settings_general_num_posts").val();

      // var styles = jQuery(form).find("#wps_settings_general_styles").val();

      disable($submitButton);
      toggleActive($spinner);


      // If URL contains a trailing forward slash
      /* @if NODE_ENV='pro' */
      if (containsTrailingForwardSlash(webhooksURL)) {

        webhooksURL = removeTrailingForwardSlash(webhooksURL);

        // Reset the modified string val in form field
        jQuery(form).find("#wps_settings_general_url_webhooks").val(webhooksURL);

      }
      /* @endif */

      var stylesAllAttr = jQuery(form).find("#wps_settings_general_styles_all").attr("checked");
      var stylesCoreAttr = jQuery(form).find("#wps_settings_general_styles_core").attr("checked");
      var stylesGridAttr = jQuery(form).find("#wps_settings_general_styles_grid").attr("checked");
      var priceFormatAttr = jQuery(form).find("#wps_settings_general_price_with_currency").attr("checked");
      var cartLoaddedAttr = jQuery(form).find("#wps_settings_general_cart_loaded").attr("checked");
      var titlesAsAltAttr = jQuery(form).find("#wps_settings_general_title_as_alt").attr("checked");
      var productsLinkToShopifyAttr = jQuery(form).find("#wps_settings_general_products_link_to_shopify").attr("checked");
      var showBreadcrumbsAttr = jQuery(form).find("#wps_settings_general_show_breadcrumbs").attr("checked");
      var hidePaginationAttr = jQuery(form).find("#wps_settings_general_hide_pagination").attr("checked");

      var $selectiveSyncAll = jQuery(form).find("#wps_settings_general_selective_sync_all");

      if ($selectiveSyncAll !== undefined) {
        var selectiveSyncAllAttr = jQuery(form).find("#wps_settings_general_selective_sync_all").attr("checked");
      } else {
        var selectiveSyncAllAttr = false;
      }

      var selectiveSyncProductsAttr = jQuery(form).find("#wps_settings_general_selective_sync_products").attr("checked");
      var selectiveSyncCollectionsAttr = jQuery(form).find("#wps_settings_general_selective_sync_collections").attr("checked");
      var selectiveSyncCustomersAttr = jQuery(form).find("#wps_settings_general_selective_sync_customers").attr("checked");
      var selectiveSyncOrdersAttr = jQuery(form).find("#wps_settings_general_selective_sync_orders").attr("checked");
      var selectiveSyncShopAttr = jQuery(form).find("#wps_settings_general_selective_sync_shop").attr("checked");


      if ($selectiveSyncAll === undefined || typeof selectiveSyncAllAttr !== typeof undefined && selectiveSyncAllAttr !== false) {
        var selectiveSyncAll = 1;

      } else {
        var selectiveSyncAll = 0;
      }

      if (typeof selectiveSyncProductsAttr !== typeof undefined && selectiveSyncProductsAttr !== false) {
        var selectiveSyncProducts = 1;

      } else {
        var selectiveSyncProducts = 0;
      }

      if (typeof selectiveSyncCollectionsAttr !== typeof undefined && selectiveSyncCollectionsAttr !== false) {
        var selectiveSyncCollections = 1;

      } else {
        var selectiveSyncCollections = 0;
      }

      if (typeof selectiveSyncCustomersAttr !== typeof undefined && selectiveSyncCustomersAttr !== false) {
        var selectiveSyncCustomers = 1;

      } else {
        var selectiveSyncCustomers = 0;
      }

      if (typeof selectiveSyncOrdersAttr !== typeof undefined && selectiveSyncOrdersAttr !== false) {
        var selectiveSyncOrders = 1;

      } else {
        var selectiveSyncOrders = 0;
      }

      if (typeof selectiveSyncShopAttr !== typeof undefined && selectiveSyncShopAttr !== false) {
        var selectiveSyncShop = 1;

      } else {
        var selectiveSyncShop = 0;
      }




      if (typeof productsLinkToShopifyAttr !== typeof undefined && productsLinkToShopifyAttr !== false) {
        var productsLinkToShopify = 1;

      } else {
        var productsLinkToShopify = 0;
      }


      if (typeof showBreadcrumbsAttr !== typeof undefined && showBreadcrumbsAttr !== false) {
        var showBreadcrumbs = 1;

      } else {
        var showBreadcrumbs = 0;
      }


      if (typeof hidePaginationAttr !== typeof undefined && hidePaginationAttr !== false) {
        var hidePagination = 1;

      } else {
        var hidePagination = 0;
      }





      if (typeof titlesAsAltAttr !== typeof undefined && titlesAsAltAttr !== false) {
        var titlesAsAlt = 1;

      } else {
        var titlesAsAlt = 0;
      }


      if (typeof cartLoaddedAttr !== typeof undefined && cartLoaddedAttr !== false) {
        var cartLoaded = 1;

      } else {
        var cartLoaded = 0;
      }


      if (typeof stylesAllAttr !== typeof undefined && stylesAllAttr !== false) {
        var stylesAll = 1;

      } else {
        var stylesAll = 0;
      }


      if (typeof stylesCoreAttr !== typeof undefined && stylesCoreAttr !== false) {
        var stylesCore = 1;

      } else {
        var stylesCore = 0;
      }


      if (typeof stylesGridAttr !== typeof undefined && stylesGridAttr !== false) {
        var stylesGrid = 1;

      } else {
        var stylesGrid = 0;
      }


      if (typeof priceFormatAttr !== typeof undefined && priceFormatAttr !== false) {
        var priceFormat = 1;

      } else {
        var priceFormat = 0;
      }


      var settings = {

        wps_settings_general_products_url: productsURL,
        wps_settings_general_collections_url: collectionsURL,
        /* @if NODE_ENV='pro' */
        wps_settings_general_url_webhooks: webhooksURL,
        wps_settings_general_selective_sync_customers: selectiveSyncCustomers,
        wps_settings_general_selective_sync_orders: selectiveSyncOrders,
        /* @endif */
        wps_settings_general_num_posts: numPosts,
        wps_settings_general_title_as_alt: titlesAsAlt,
        wps_settings_general_products_link_to_shopify: productsLinkToShopify,
        wps_settings_general_show_breadcrumbs: showBreadcrumbs,
        wps_settings_general_hide_pagination: hidePagination,

        wps_settings_general_styles_all: stylesAll,
        wps_settings_general_styles_core: stylesCore,
        wps_settings_general_styles_grid: stylesGrid,
        wps_settings_general_price_with_currency: priceFormat,
        wps_settings_general_cart_loaded: cartLoaded,
        wps_settings_general_selective_sync_all: selectiveSyncAll,
        wps_settings_general_selective_sync_products: selectiveSyncProducts,
        wps_settings_general_selective_sync_collections: selectiveSyncCollections,
        wps_settings_general_selective_sync_shop: selectiveSyncShop,

      }


      WP_Shopify.selective_sync.all = selectiveSyncAll;
      WP_Shopify.selective_sync.products = selectiveSyncProducts;
      WP_Shopify.selective_sync.custom_collections = selectiveSyncCollections;
      WP_Shopify.selective_sync.smart_collections = selectiveSyncCollections;
      /* @if NODE_ENV='pro' */
      WP_Shopify.selective_sync.customers = selectiveSyncCustomers;
      WP_Shopify.selective_sync.orders = selectiveSyncOrders;
      /* @endif */
      WP_Shopify.selective_sync.shop = selectiveSyncShop;


      /*

      Step 1. Update settings

      */
      try {
        var settingsResponse = await updateSettings(settings);

      } catch (errorMsg) {

        showAdminNotice(errorMsg, 'error');
        enable($submitButton);
        toggleActive($spinner);

      }


      /*

      Step 2. Clear all plugin cache

      */
      try {

        var clearAllCacheResponse = await clearAllCache();

        if (isWordPressError(clearAllCacheResponse)) {
          throw clearAllCacheResponse.data;

        } else if (isError(clearAllCacheResponse)) {
          throw clearAllCacheResponse;

        } else {
          showAdminNotice('Successfully updated settings', 'updated');
          toggleActive($spinner);
          enable($submitButton);

        }

      } catch(errorMsg) {

        showAdminNotice(errorMsg, 'error');
        enable($submitButton);
        toggleActive($spinner);

      }

    }

  });

}


/*

Toggle Styles Checkboxes

*/
function toggleCheckboxes() {

  jQuery('.wps-checkbox-all input').on('click', function() {

    var $clicked = jQuery(this);

    if (typeof $clicked.attr("checked") !== typeof undefined && $clicked.attr("checked") !== false) {

      $clicked.closest('.wps-checkbox-wrapper')
        .find('.wps-checkbox')
        .attr('checked', false)
        .attr('disabled', true)
        .parent()
        .addClass('wps-is-disabled');

    } else {

      $clicked.closest('.wps-checkbox-wrapper')
        .find('.wps-checkbox')
        .attr('disabled', false)
        .parent()
        .removeClass('wps-is-disabled');

    }

  });

}


function getSelectiveSyncOptions() {

  if (WP_Shopify.selective_sync.all) {
    return [];

  } else {

    var includes = [];

    if (WP_Shopify.selective_sync.smart_collections) {
      includes.push('smart_collections');
    }

    if (WP_Shopify.selective_sync.custom_collections) {
      includes.push('custom_collections');
    }

    /* @if NODE_ENV='pro' */
    if (WP_Shopify.selective_sync.customers) {
      includes.push('customers');
    }

    if (WP_Shopify.selective_sync.orders) {
      includes.push('orders');
    }
    /* @endif */

    if (WP_Shopify.selective_sync.products) {
      includes.push('products');
      includes.push('collects');
    }

    if (WP_Shopify.selective_sync.shop) {
      includes.push('shop');
    }

    return includes;

  }

}


/*

Form Events Init

*/
function settingsInit() {

  onSettingsFormSubmit();
  toggleCheckboxes();

}

export {
  settingsInit,
  getSelectiveSyncOptions
};
