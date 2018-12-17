import isError from 'lodash/isError';
import forEach from 'lodash/forEach';
import has from 'lodash/has';
import to from 'await-to-js';
import { post } from '../ws/ws';

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
import { initProductsCompareAt } from './products/products-compare-at.jsx';
import { initProductsShowPriceRange } from './products/products-show-price-range.jsx';

import { initCheckoutButtonTarget } from './checkout/checkout-button-target.jsx';

import {
  endpointSettings
} from '../ws/api/api-endpoints';

import {
  messageSettingsSuccessfulSave
} from '../messages/messages';

import {
  getSelectiveCollections,
  clearAllCache
} from '../ws/wrappers';

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



function isUndefined(value) {
  return typeof value === typeof undefined;
}


function getDataState($form, selector, dataAttr) {

  var val = $form.find(selector).attr(dataAttr);

  if ( isUndefined(val) ) {
    return false;
  }

  return val;

}

function getCheckedState($form, selector) {

  var state = $form.find(selector).prop("checked");

  if ( isUndefined(state) ) {
    return false;
  }

  return state;

}


function getInputState($form, selector) {

  var val = $form.find(selector).val();

  if ( isUndefined(val) ) {
    return false;
  }

  return val;

}


function setGlobals(settingsData) {


  // Need to keep the global updated during AJAX requests
  WP_Shopify.itemsPerRequest = settingsData.wps_settings_general_items_per_request;
  WP_Shopify.settings.connection.saveConnectionOnly = settingsData.wps_settings_general_save_connection_only;

}


function gatherSettingsData($submitForm) {

  return {

    wps_settings_general_products_url: getInputState($submitForm, '#wps_settings_general_url_products'),
    wps_settings_general_collections_url: getInputState($submitForm, '#wps_settings_general_url_collections'),
    wps_settings_general_num_posts: getInputState($submitForm, '#wps_settings_general_num_posts'),
    wps_settings_general_products_link_to_shopify: getCheckedState($submitForm, '#wps_settings_general_products_link_to_shopify'),
    wps_settings_general_show_breadcrumbs: getCheckedState($submitForm, '#wps_settings_general_show_breadcrumbs'),
    wps_settings_general_hide_pagination: getCheckedState($submitForm, '#wps_settings_general_hide_pagination'),
    wps_settings_general_styles_all: getCheckedState($submitForm, '#wps_settings_general_styles_all'),
    wps_settings_general_styles_core: getCheckedState($submitForm, '#wps_settings_general_styles_core'),
    wps_settings_general_styles_grid: getCheckedState($submitForm, '#wps_settings_general_styles_grid'),
    wps_settings_general_price_with_currency: getCheckedState($submitForm, '#wps_settings_general_price_with_currency'),
    wps_settings_general_cart_loaded: getCheckedState($submitForm, '#wps_settings_general_cart_loaded'),
    wps_settings_general_enable_beta: getCheckedState($submitForm, '#wps_settings_general_enable_beta'),
    wps_settings_general_enable_cart_terms: getCheckedState($submitForm, '#wps_settings_general_enable_cart_terms'),
    wps_settings_general_save_connection_only: getCheckedState($submitForm, '#wps_settings_general_save_connection_only'),
    wps_settings_general_related_products_show: getCheckedState($submitForm, '#wps_settings_general_related_products_show'),
    wps_settings_general_related_products_sort: getInputState($submitForm, '#wps_settings_general_related_products_sort_type input:checked'),
    wps_settings_general_related_products_amount: getInputState($submitForm, '#wps_settings_general_related_products_amount'),
    wps_settings_general_cart_terms_content: getInputState($submitForm, '#wps_settings_general_cart_terms_content'),
    wps_settings_general_items_per_request: parseInt( $submitForm.find("#wps-items-per-request-amount").text() ),
    wps_settings_general_add_to_cart_color: getDataState($submitForm, '.wps-color-swatch[data-picker-type="add-to-cart"]', 'data-color'),
    wps_settings_general_variant_color: getDataState($submitForm, '.wps-color-swatch[data-picker-type="variant"]', 'data-color'),
    wps_settings_general_checkout_button_color: getDataState($submitForm, '.wps-color-swatch[data-picker-type="checkout"]', 'data-color'),

    wps_settings_general_cart_icon_color: getDataState($submitForm, '.wps-color-swatch[data-picker-type="cart-icon"]', 'data-color'),
    wps_settings_general_cart_counter_color: getDataState($submitForm, '.wps-color-swatch[data-picker-type="cart-counter"]', 'data-color'),

    wps_settings_general_products_heading_toggle: getCheckedState($submitForm, '#wps-products-heading-toggle'),
    wps_settings_general_products_heading: getInputState($submitForm, '#wps-settings-products-heading input'),
    wps_settings_general_collections_heading_toggle: getCheckedState($submitForm, '#wps-collections-heading-toggle'),
    wps_settings_general_collections_heading: getInputState($submitForm, '#wps-settings-collections-heading input'),
    wps_settings_general_related_products_heading_toggle: getCheckedState($submitForm, '#wps-related-products-heading-toggle'),
    wps_settings_general_related_products_heading: getInputState($submitForm, '#wps-settings-related-products-heading input'),
    wps_settings_products_images_sizing_toggle: getCheckedState($submitForm, '#wps-products-images-sizing-toggle'),
    wps_settings_products_images_sizing_width: getInputState($submitForm, '#wps-settings-products-images-sizing-width input'),
    wps_settings_products_images_sizing_height: getInputState($submitForm, '#wps-settings-products-images-sizing-height input'),
    wps_settings_products_images_sizing_crop: getInputState($submitForm, '#wps-settings-products-images-sizing-crop select'),
    wps_settings_products_images_sizing_scale: getInputState($submitForm, '#wps-settings-products-images-sizing-scale select'),
    wps_settings_collections_images_sizing_toggle: getCheckedState($submitForm, '#wps-settings-collections-images-sizing-toggle input'),
    wps_settings_collections_images_sizing_width: getInputState($submitForm, '#wps-settings-collections-images-sizing-width input'),
    wps_settings_collections_images_sizing_height: getInputState($submitForm, '#wps-settings-collections-images-sizing-height input'),
    wps_settings_collections_images_sizing_crop: getInputState($submitForm, '#wps-settings-collections-images-sizing-crop select'),
    wps_settings_collections_images_sizing_scale: getInputState($submitForm, '#wps-settings-collections-images-sizing-scale select'),
    wps_settings_related_products_images_sizing_toggle: getCheckedState($submitForm, '#wps-related-products-images-sizing-toggle'),
    wps_settings_related_products_images_sizing_width: getInputState($submitForm, '#wps-settings-related-products-images-sizing-width input'),
    wps_settings_related_products_images_sizing_height: getInputState($submitForm, '#wps-settings-related-products-images-sizing-height input'),
    wps_settings_related_products_images_sizing_crop: getInputState($submitForm, '#wps-settings-related-products-images-sizing-crop select'),
    wps_settings_related_products_images_sizing_scale: getInputState($submitForm, '#wps-settings-related-products-images-sizing-scale select'),
    wps_settings_products_compare_at: getCheckedState($submitForm, '#wps-settings-products-compare-at input'),
    wps_settings_checkout_enable_custom_checkout_domain: getCheckedState($submitForm, '#wps-enable-custom-checkout-domain input'),
    wps_settings_products_show_price_range: getCheckedState($submitForm, '#wps-settings-products-show-price-range input'),
    wps_settings_checkout_button_target: getInputState($submitForm, '#wps-settings-checkout-button-target select'),


  }


}


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

      const $submitForm = jQuery(form);

      var $submitButton = $submitForm.find('input[type="submit"]');
      var $spinner = $submitForm.find('.spinner');




      disable($submitButton);
      toggleActive($spinner);



      var settings = gatherSettingsData($submitForm);


      setGlobals(settings);



      /*

      Step 1. Update settings

      */
      var [settingsError, settingsData] = await to( post(
        endpointSettings(),
        { settings: settings }
      ));

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

  if (allCollections.status === 200 && has(allCollections, 'data')) {

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


function imageCropTypes() {

  return [
    {
      label: 'None',
      value: 'none'
    },
    {
      label: 'Top',
      value: 'top'
    },
    {
      label: 'Center',
      value: 'center'
    },
    {
      label: 'Bottom',
      value: 'bottom'
    },
    {
      label: 'Left',
      value: 'left'
    },
    {
      label: 'Right',
      value: 'right'
    }
  ];

}


function imageScaleTypes() {

  return [
    {
      label: 'None',
      value: false
    },
    {
      label: '2',
      value: 2
    },
    {
      label: '3',
      value: 3
    }
  ];

}


function checkoutButtonTargets() {

  return [
    {
      label: 'Current tab / window',
      value: '_self'
    },
    {
      label: 'New tab / window',
      value: '_blank'
    }
  ];

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
  initProductsCompareAt();
  initProductsShowPriceRange();

  initCheckoutButtonTarget();

}

export {
  settingsInit,
  getSelectiveSyncOptions,
  populateSyncByCollections,
  imageCropTypes,
  imageScaleTypes,
  checkoutButtonTargets
}
