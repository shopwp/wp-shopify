import isError from 'lodash/isError';
import forEach from 'lodash/forEach';
import has from 'lodash/has';
import to from 'await-to-js';

import { initColorPickers } from './settings-color-picker.jsx';
import { initProductsHeading } from './products/products-heading.jsx';
import { initCollectionsHeading } from './collections/collections-heading.jsx';
import { initRelatedProductsHeading } from './related-products/related-products-heading.jsx';
import { initProductsHeadingToggle } from './products/products-heading-toggle.jsx';
import { initCollectionsHeadingToggle } from './collections/collections-heading-toggle.jsx';
import { initRelatedProductsHeadingToggle } from './related-products/related-products-heading-toggle.jsx';

import { initProductsImagesSizingToggle } from './products/products-images-sizing-toggle.jsx';
import { initProductsImagesSizingWidth } from './products/products-images-sizing-width.jsx';
import { initProductsImagesSizingHeight } from './products/products-images-sizing-height.jsx';
import { initProductsImagesSizingCrop } from './products/products-images-sizing-crop.jsx';
import { initProductsImagesSizingScale } from './products/products-images-sizing-scale.jsx';

import { initCollectionsImagesSizingToggle } from './collections/collections-images-sizing-toggle.jsx';
import { initCollectionsImagesSizingWidth } from './collections/collections-images-sizing-width.jsx';
import { initCollectionsImagesSizingHeight } from './collections/collections-images-sizing-height.jsx';
import { initCollectionsImagesSizingCrop } from './collections/collections-images-sizing-crop.jsx';
import { initCollectionsImagesSizingScale } from './collections/collections-images-sizing-scale.jsx';

import { initRelatedProductsImagesSizingToggle } from './related-products/related-products-images-sizing-toggle.jsx';
import { initRelatedProductsImagesSizingWidth } from './related-products/related-products-images-sizing-width.jsx';
import { initRelatedProductsImagesSizingHeight } from './related-products/related-products-images-sizing-height.jsx';
import { initRelatedProductsImagesSizingCrop } from './related-products/related-products-images-sizing-crop.jsx';
import { initRelatedProductsImagesSizingScale } from './related-products/related-products-images-sizing-scale.jsx';

import { initEnableCustomCheckoutDomain } from './checkout/checkout-enable-custom-checkout-domain.jsx';



import {
  updateSettings
} from '../ws/ws';

import {
  messageSettingsSuccessfulSave
} from '../messages/messages';

import {
  getSelectiveCollections
} from '../ws/wrappers';

import {
  clearAllCache
} from '../tools/cache';

import {
  enable,
  disable,
  showLoader,
  containsTrailingForwardSlash,
  removeTrailingForwardSlash,
  isWordPressError,
  getJavascriptErrorMessage,
  getWordPressErrorType,
  getWordPressErrorMessage
} from '../utils/utils';

import {
  showAdminNotice,
  showCollectionsNotice,
  toggleActive,
  resetSyncByCollectionOptions,
  showSyncByCollectionsNotice
} from '../utils/utils-dom';

import {
  rejectedPromise,
  hasConnection,
  returnOnlyFirstError
} from '../utils/utils-data';



/*

When License key form is submitted ...

*/
function onSettingsFormSubmit() {

  jQuery("#wps-settings").submit(function(e) {
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
      var numPosts = jQuery(form).find("#wps_settings_general_num_posts").val();

      // var styles = jQuery(form).find("#wps_settings_general_styles").val();

      disable($submitButton);
      toggleActive($spinner);


      // If URL contains a trailing forward slash

      var stylesAllAttr = jQuery(form).find("#wps_settings_general_styles_all").attr("checked");
      var stylesCoreAttr = jQuery(form).find("#wps_settings_general_styles_core").attr("checked");
      var stylesGridAttr = jQuery(form).find("#wps_settings_general_styles_grid").attr("checked");
      var priceFormatAttr = jQuery(form).find("#wps_settings_general_price_with_currency").attr("checked");
      var cartLoaddedAttr = jQuery(form).find("#wps_settings_general_cart_loaded").attr("checked");
      var enableBetaAttr = jQuery(form).find("#wps_settings_general_enable_beta").attr("checked");
      var enableCartTermsAttr = jQuery(form).find("#wps_settings_general_enable_cart_terms").attr("checked");
      var cartTermsContent = jQuery(form).find("#wps_settings_general_cart_terms_content").val();
      var productsLinkToShopifyAttr = jQuery(form).find("#wps_settings_general_products_link_to_shopify").attr("checked");
      var showBreadcrumbsAttr = jQuery(form).find("#wps_settings_general_show_breadcrumbs").attr("checked");
      var hidePaginationAttr = jQuery(form).find("#wps_settings_general_hide_pagination").attr("checked");
      var saveConnectionOnlyAttr = jQuery(form).find("#wps_settings_general_save_connection_only").attr("checked");

      var $relatedProductsShow = jQuery(form).find("#wps_settings_general_related_products_show");
      var $relatedProductsSortRandom = jQuery(form).find("#wps_settings_general_related_products_sort_random");
      var $relatedProductsSortCollections = jQuery(form).find("#wps_settings_general_related_products_sort_collections");
      var $relatedProductsSortTags = jQuery(form).find("#wps_settings_general_related_products_sort_tags");
      var $relatedProductsSortVendors = jQuery(form).find("#wps_settings_general_related_products_sort_vendors");
      var $relatedProductsSortTypes = jQuery(form).find("#wps_settings_general_related_products_sort_types");
      var relatedProductsAmount = jQuery(form).find("#wps_settings_general_related_products_amount").val();

      var addToCartColor = jQuery(form).find('.wps-color-swatch[data-picker-type="add-to-cart"]').attr('data-color');
      var variantColor = jQuery(form).find('.wps-color-swatch[data-picker-type="variant"]').attr('data-color');
      var checkoutButtonColor = jQuery(form).find('.wps-color-swatch[data-picker-type="checkout"]').attr('data-color');
      var cartIconColor = jQuery(form).find('.wps-color-swatch[data-picker-type="cart-icon"]').attr('data-color');
      var cartCounterColor = jQuery(form).find('.wps-color-swatch[data-picker-type="cart-counter"]').attr('data-color');

      var productsHeadingToggle = jQuery(form).find('#wps-products-heading-toggle').prop('checked');
      var collectionsHeadingToggle = jQuery(form).find('#wps-collections-heading-toggle').prop('checked');
      var relatedProductsHeadingToggle = jQuery(form).find('#wps-related-products-heading-toggle').prop('checked');

      var productsImagesSizingToggle = jQuery(form).find('#wps-products-images-sizing-toggle').prop('checked');
      var productsImagesSizingWidth = jQuery(form).find('#wps-settings-products-images-sizing-width input').val();
      var productsImagesSizingHeight = jQuery(form).find('#wps-settings-products-images-sizing-height input').val();
      var productsImagesSizingCrop = jQuery(form).find('#wps-settings-products-images-sizing-crop select').val();
      var productsImagesSizingScale = jQuery(form).find('#wps-settings-products-images-sizing-scale select').val();

      var collectionsImagesSizingToggle = jQuery(form).find('#wps-collections-images-sizing-toggle').prop('checked');
      var collectionsImagesSizingWidth = jQuery(form).find('#wps-settings-collections-images-sizing-width input').val();
      var collectionsImagesSizingHeight = jQuery(form).find('#wps-settings-collections-images-sizing-height input').val();
      var collectionsImagesSizingCrop = jQuery(form).find('#wps-settings-collections-images-sizing-crop select').val();
      var collectionsImagesSizingScale = jQuery(form).find('#wps-settings-collections-images-sizing-scale select').val();

      var relatedProductsImagesSizingToggle = jQuery(form).find('#wps-related-products-images-sizing-toggle').prop('checked');
      var relatedProductsImagesSizingWidth = jQuery(form).find('#wps-settings-related-products-images-sizing-width input').val();
      var relatedProductsImagesSizingHeight = jQuery(form).find('#wps-settings-related-products-images-sizing-height input').val();
      var relatedProductsImagesSizingCrop = jQuery(form).find('#wps-settings-related-products-images-sizing-crop select').val();
      var relatedProductsImagesSizingScale = jQuery(form).find('#wps-settings-related-products-images-sizing-scale select').val();



      var relatedProductsSort;
      var relatedProductsShow = 0;

      var syncByCollectionsValue = jQuery(form).find("#wps-sync-by-collections").val();
      var itemsPerRequest = parseInt( jQuery(form).find("#wps-items-per-request-amount").text() );

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



      /*

      Related Products: Show

      */
      if ($relatedProductsShow.is(':checked')) {
        relatedProductsShow = 1;
      }


      /*

      Related Products Sort: Random

      */
      if ($relatedProductsSortRandom.is(':checked')) {
        relatedProductsSort = $relatedProductsSortRandom.val();
      }


      /*

      Related Products Sort: Collection

      */
      if ($relatedProductsSortCollections.is(':checked')) {
        relatedProductsSort = $relatedProductsSortCollections.val();
      }


      /*

      Related Products Sort: Tag

      */
      if ($relatedProductsSortTags.is(':checked')) {
        relatedProductsSort = $relatedProductsSortTags.val();
      }


      /*

      Related Products Sort: Vendor

      */
      if ($relatedProductsSortVendors.is(':checked')) {
        relatedProductsSort = $relatedProductsSortVendors.val();
      }


      /*

      Related Products Sort: Type

      */
      if ($relatedProductsSortTypes.is(':checked')) {
        relatedProductsSort = $relatedProductsSortTypes.val();
      }





      if ($selectiveSyncAll === undefined || selectiveSyncAllAttr !== undefined && selectiveSyncAllAttr !== false) {
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


      if (typeof saveConnectionOnlyAttr !== typeof undefined && saveConnectionOnlyAttr !== false) {
        var saveConnectionOnly = 1;

      } else {
        var saveConnectionOnly = 0;
      }


      if (typeof cartLoaddedAttr !== typeof undefined && cartLoaddedAttr !== false) {
        var cartLoaded = 1;

      } else {
        var cartLoaded = 0;
      }


      if (typeof enableBetaAttr !== typeof undefined && enableBetaAttr !== false) {
        var enableBeta = 1;

      } else {
        var enableBeta = 0;
      }


      if (typeof enableCartTermsAttr !== typeof undefined && enableCartTermsAttr !== false) {
        var enableCartTerms = 1;

      } else {
        var enableCartTerms = 0;
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


        wps_settings_general_num_posts: numPosts,
        wps_settings_general_products_link_to_shopify: productsLinkToShopify,
        wps_settings_general_show_breadcrumbs: showBreadcrumbs,
        wps_settings_general_hide_pagination: hidePagination,
        wps_settings_general_styles_all: stylesAll,
        wps_settings_general_styles_core: stylesCore,
        wps_settings_general_styles_grid: stylesGrid,
        wps_settings_general_price_with_currency: priceFormat,
        wps_settings_general_cart_loaded: cartLoaded,
        wps_settings_general_enable_beta: enableBeta,
        wps_settings_general_enable_cart_terms: enableCartTerms,
        wps_settings_general_enable_cart_terms: enableCartTerms,
        wps_settings_general_save_connection_only: saveConnectionOnly,
        wps_settings_general_related_products_show: relatedProductsShow,
        wps_settings_general_related_products_sort: relatedProductsSort,
        wps_settings_general_related_products_amount: relatedProductsAmount,
        wps_settings_general_cart_terms_content: cartTermsContent,
        wps_settings_general_items_per_request: itemsPerRequest,
        wps_settings_general_add_to_cart_color: addToCartColor,
        wps_settings_general_variant_color: variantColor,
        wps_settings_general_checkout_button_color: checkoutButtonColor,
        wps_settings_general_cart_icon_color: cartIconColor,
        wps_settings_general_cart_counter_color: cartCounterColor,
        wps_settings_general_products_heading_toggle: productsHeadingToggle,
        wps_settings_general_collections_heading_toggle: collectionsHeadingToggle,
        wps_settings_general_related_products_heading_toggle: relatedProductsHeadingToggle,
        wps_settings_products_images_sizing_toggle: productsImagesSizingToggle,
        wps_settings_products_images_sizing_width: productsImagesSizingWidth,
        wps_settings_products_images_sizing_height: productsImagesSizingHeight,
        wps_settings_products_images_sizing_crop: productsImagesSizingCrop,
        wps_settings_products_images_sizing_scale: productsImagesSizingScale,
        wps_settings_collections_images_sizing_toggle: collectionsImagesSizingToggle,
        wps_settings_collections_images_sizing_width: collectionsImagesSizingWidth,
        wps_settings_collections_images_sizing_height: collectionsImagesSizingHeight,
        wps_settings_collections_images_sizing_crop: collectionsImagesSizingCrop,
        wps_settings_collections_images_sizing_scale: collectionsImagesSizingScale,
        wps_settings_related_products_images_sizing_toggle: relatedProductsImagesSizingToggle,
        wps_settings_related_products_images_sizing_width: relatedProductsImagesSizingWidth,
        wps_settings_related_products_images_sizing_height: relatedProductsImagesSizingHeight,
        wps_settings_related_products_images_sizing_crop: relatedProductsImagesSizingCrop,
        wps_settings_related_products_images_sizing_scale: relatedProductsImagesSizingScale,


      }




      /*

      Step 1. Update settings

      update_settings_general

      */

      var [settingsError, settingsData] = await to( updateSettings(settings) );

      if (settingsError) {
        showAdminNotice( getJavascriptErrorMessage(settingsError) );
        return;
      }

      if (isWordPressError(settingsData)) {

        showAdminNotice(
          getWordPressErrorMessage(settingsData),
          getWordPressErrorType(settingsData)
        );
        return;

      }


      /*

      Step 2. Clear all plugin cache

      */

      var [cacheError, cacheData] = await to( clearAllCache() );

      if (cacheError) {
        showAdminNotice( getJavascriptErrorMessage(cacheError) );
        return;
      }

      if (isWordPressError(cacheData)) {

        showAdminNotice(
          getWordPressErrorMessage(cacheData),
          getWordPressErrorType(cacheData)
        );

        return;

      }

      // Needed to keep the global updated during AJAX requests
      WP_Shopify.itemsPerRequest = itemsPerRequest;

      showAdminNotice( messageSettingsSuccessfulSave(), 'updated' );

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
        .prop('checked', false)
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


    return includes;

  }

}


function toggleActiveSubSection() {

  jQuery('.wps-sub-section-link').on('click', function(e) {

    e.preventDefault();

    var subSectionID = jQuery(this).data('sub-section');

    jQuery('.wps-sub-section-link').removeClass('current');
    jQuery(this).addClass('current');

    jQuery('.wps-admin-sub-section').removeClass('is-active');
    jQuery('#' + subSectionID).addClass('is-active');

  });

}



function chosenInit() {

  jQuery(".wps-chosen").chosen({
    no_results_text: "Oops, nothing found!",
    width: "300px"
  }).change(function(e) {

  });

}





function populateCollectionOptions(allCollections) {

  var $selectMenu = jQuery("#wps-sync-by-collections");

  $selectMenu.empty();

  forEach(allCollections, collection => {

    $selectMenu
      .append('<option id="wps-collection-option" value="' + collection.id + '">' + collection.title + '</option>');

  });

}


/*

Populate sync by collections

*/
async function populateSyncByCollections() {

  // Don't do anything if no active connection exists
  if ( !hasConnection() ) {
    return showSyncByCollectionsNotice();
  }

  var [collectionsError, collectionsData] = await to( getSelectiveCollections() );

  if (collectionsError) {
    return showSyncByCollectionsNotice( getJavascriptErrorMessage(collectionsError) );
  }

  if (isWordPressError(collectionsData)) {
    return showSyncByCollectionsNotice( getWordPressErrorMessage( returnOnlyFirstError(collectionsData) ) );
  }


  var allCollections = collectionsData[0];
  var selectedCollections = collectionsData[1];

  if (allCollections.success && has(allCollections, 'data')) {
    populateCollectionOptions(allCollections.data);


  } else {
    showSyncByCollectionsNotice();
  }


}


/*

Init items per request

*/
function initItemsPerRequest() {

  var $slider = jQuery('.wps-slider-items-per-request');
  var $sliderAmount = jQuery('#wps-items-per-request-amount');

  $slider.slider({
    range: "max",
    value: parseInt( $sliderAmount.text() ),
    min: 1,
    max: parseInt(WP_Shopify.maxItemsPerRequest),
    slide: function( event, ui ) {
      $sliderAmount.text( ui.value );
    }
  });

}


/*

Form Events Init

*/
function settingsInit() {

  onSettingsFormSubmit();
  toggleCheckboxes();
  toggleActiveSubSection();
  chosenInit();
  populateSyncByCollections();

  /*

  Begin Rendering React Components ...

  */
  initItemsPerRequest();
  initColorPickers();
  initProductsHeadingToggle();
  initProductsHeading();
  initCollectionsHeadingToggle();
  initCollectionsHeading();
  initRelatedProductsHeading();
  initRelatedProductsHeadingToggle();

  initProductsImagesSizingToggle();
  initProductsImagesSizingWidth();
  initProductsImagesSizingHeight();
  initProductsImagesSizingCrop();
  initProductsImagesSizingScale();

  initCollectionsImagesSizingToggle();
  initCollectionsImagesSizingWidth();
  initCollectionsImagesSizingHeight();
  initCollectionsImagesSizingCrop();
  initCollectionsImagesSizingScale();

  initRelatedProductsImagesSizingToggle();
  initRelatedProductsImagesSizingWidth();
  initRelatedProductsImagesSizingHeight();
  initRelatedProductsImagesSizingCrop();
  initRelatedProductsImagesSizingScale();

  initEnableCustomCheckoutDomain();

}

export {
  settingsInit,
  getSelectiveSyncOptions,
  populateSyncByCollections
}
