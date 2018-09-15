import to from 'await-to-js';
import intersection from 'lodash/intersection';
import filter from 'lodash/filter';
import matches from 'lodash/matches';
import isEmpty from 'lodash/isEmpty';
import uniqWith from 'lodash/uniqWith';
import isEqual from 'lodash/isEqual';
import forEach from 'lodash/forEach';
import forIn from 'lodash/forIn';
import has from 'lodash/has';
import merge from 'lodash/merge';
import reduce from 'lodash/reduce';
import size from 'lodash/size';


import {
  update,
  formatAsMoney
} from '../utils/utils-common';

import {
  getClient
} from '../utils/utils-client';

import {
  logNotice,
  showSingleNotice,
  hideProductMetaNotice,
  isWordPressError
} from '../utils/utils-notices';

import {
  triggerEventAfterAddToCart,
  triggerEventBeforeAddToCart
} from '../utils/utils-triggers';

import {
  disable,
  enable,
  enableNoLoader,
  disableNoLoader,
  showLoader,
} from '../utils/utils-ux';

import {
  pulse,
  pulseSoft
} from '../utils/utils-animations';

import {
  getProductByID,
  getProductVariantID,
  getVariantIdFromOptions,
  setProductSelectionID,
  getProductSelectionID,
  getProductOptionIds,
  setProductOptionIds,
  removeProductOptionIds,
  setCurrentlySelectedVariants,
  getCurrentlySelectedVariants,
  setFromPricing,
  getFromPricing,
  addLineItems,
  getCheckoutID,
  getProductByHandle,
  getProductIDByHandle,
  setProductIDByHandle
} from '../ws/ws-products';

import {
  buildSelectedOptions
} from './products-options';

import {
  resetVariantSelectors,
  resetOptionsSelection,
  getDeselectedDropdowns,
  getCurrentlySelectedOptionsAmount,
  getCurrentlySelectedOptions,
  showHiddenProductVariants
} from './products-meta';

import {
  hideAllOpenProductDropdowns
} from './products-ui';

import {
  updateCartCounter,
  openCart,
  cartIsOpen,
  closeCart,
  renderSingleCartItem,
  updateTotalCartPricing,
  addLineItemIDs,
  removeVariantSelections,
  enableCheckoutButton,
  enableCart,
  getLineItemFromVariantID,
  getStoredWordPressURLs,
  setStoredWordPressURLs,
  buildWordPressURLsObj
} from '../cart/cart-ui';

import {
  onCartQuantity
} from '../cart/cart-events';

import {
  getPluginInstance
} from "../plugin/plugin";





/*

Remove Inline Notices

*/
function removeInlineNotices($productMetaWrapper) {
  $productMetaWrapper.find('.wps-notice-inline').removeClass('wps-is-visible wps-notice-error wps-notice-warning wps-notice-info').text('');
}


/*

Used to reset values of a single product's variant selctions.
Needed after user adds to cart or selects a different product

*/
function resetSingleProductVariantSelector($addToCartButton) {

  var $group = $addToCartButton.closest('.wps-product-actions-group');
  var $productMetaWrapper = $addToCartButton.closest('.wps-product-meta');
  var $productMetaDropdown = $productMetaWrapper.find('.wps-btn-dropdown');
  var $variantSelectors = $group.find('.wps-modal-trigger');

  $variantSelectors.each(function(index, value) {

    var variantTitle = jQuery(value).data('option');
    jQuery(value).text(variantTitle);

    $productMetaWrapper.data('product-selected-options', '');
    $productMetaWrapper.attr('data-product-selected-options', '');
    $productMetaDropdown.attr('data-selected', false);
    $productMetaDropdown.data('selected', false);

  });

}





function variantForOptions(product, options) {
  return product.variants.find((variant) => {
    return variant.selectedOptions.every((selectedOption) => {
      return options[selectedOption.name] === selectedOption.value.valueOf();
    });
  });
}



function getProductQuantitySelection($container) {
  return $container.find('.wps-product-quantity').val();
}



function getAvailableVariants(product) {
  return product.variants.filter( variant => variant.available );
}



function getAddLineItemsConfig(variant, productQuantity) {

  return {
    checkoutId: getCheckoutID(), // ID of an existing checkout
    lineItems: [{
      variantId: variant.id,
      quantity: parseInt(productQuantity)
    }]
  }

}



function resetVariantSelection($addToCartButton) {
  removeVariantSelections();
  resetVariantSelectors(); // Resets DOM related to selecting options
  resetSingleProductVariantSelector($addToCartButton);
  enable($addToCartButton);
}


function buildSelectedOptionsData(selectedGraphOptions) {

  selectedGraphOptions = JSON.parse(selectedGraphOptions);

  // Reduce to a single object
  return reduce(selectedGraphOptions, merge);

}


/*

Attach and control listeners onto buy button
TODO: Add try catches below

*/
function onProductAddToCart(client) {

  jQuery('.wps-btn-wrapper').on('click', '.wps-add-to-cart', async function addToCartHandler(event) {

    event.preventDefault();

    var $addToCartButton = jQuery(this),
        $container = $addToCartButton.closest('.wps-product-meta'),
        matchingProductVariantID = $container.attr('data-product-selected-variant'),
        productID = $container.attr('data-product-id'),
        productQuantity = getProductQuantitySelection($container),
        $cartForm = jQuery('.wps-cart-form .wps-cart-item-container'),
        graphqlID = $container.attr('data-product-graphql-id'),
        selectedOptions = $container.attr('data-product-selected-options'),
        productHandle = $container.attr('data-product-handle'),
        wordpressProductURL = $container.attr('data-product-url'),
        productStorefrontID = $container.attr('data-product-storefront-id'),
        selectedGraphOptions = $container.attr('data-product-selected-options-and-variants');

    // Stop if all variants are not selected
    if ( !allProductVariantsSelected($container) ) {

      pulse( $container.find('.wps-btn-dropdown[data-selected="false"] .wps-btn') );

      return showSingleNotice('Please select the required options', $addToCartButton, 'warning');

    }


    // If cart is open when add to cart button is clicked, close it
    closeCart();


    showLoader($addToCartButton);
    disable($addToCartButton);
    showHiddenProductVariants();



    var [productError, product] = await to( getProductByHandle(client, productHandle) );

    if (!product) {

      logNotice('getProductByHandle', productError, 'error');
      showSingleNotice('Sorry, it looks like this product is currently unavailable to purchase. Please ensure that the correct Sales Channel is assigned and try again.', $addToCartButton);

      resetVariantSelection($addToCartButton);

      return;

    }


    if (productError) {

      logNotice('getProductByID', productError, 'error');
      showSingleNotice('Oops, something went wrong when trying to add ' + jQuery('.wps-product-heading') + ' to cart. Please clear your browser cache and try again.', $addToCartButton);

      resetVariantSelection($addToCartButton);

      return;

    }




    triggerEventBeforeAddToCart(product);




    var availVariants = getAvailableVariants(product);

    if ( size(availVariants) > 1) {
      var constructedSelectedGraphOptions = buildSelectedOptionsData(selectedGraphOptions);
      var variant = variantForOptions(product, constructedSelectedGraphOptions);

    } else {

      if (!isEmpty(availVariants)) {
        var variant = availVariants[0];

      } else {

        logNotice('getAvailableVariants', 'No product variant found.', 'error');
        showSingleNotice('Sorry, that product variant doesn\'t exit. Please clear your browser cache and try again.', $addToCartButton);

        resetVariantSelection($addToCartButton);

        return;

      }

    }



    var { checkoutId, lineItems } = getAddLineItemsConfig(variant, productQuantity);



    /*

    Adding new item to checkout ...

    */
    var [addLineitemsError, checkout] = await to( addLineItems(client, checkoutId, lineItems) );



    var foundLineItem = getLineItemFromVariantID(checkout.lineItems, lineItems[0].variantId);
    var storedWordPressURLs = getStoredWordPressURLs();











    if (addLineitemsError) {
      logNotice('addLineItems', addLineitemsError, 'error');
      showSingleNotice( addLineitemsError, $addToCartButton);
      resetVariantSelection($addToCartButton);
      return;
    }






    updateCartCounter(client, checkout);

    resetVariantSelection($addToCartButton);
    showVariantPrice( getFromPricing(), $container );

    enableCheckoutButton();

    updateTotalCartPricing(checkout);




    if ( !storedWordPressURLs ) {
      setStoredWordPressURLs( buildWordPressURLsObj(foundLineItem, wordpressProductURL) );

    } else {
      setStoredWordPressURLs( buildWordPressURLsObj(foundLineItem, wordpressProductURL), storedWordPressURLs );
    }


    // Responsible for adding the line item ids to the DOM element -- important
    addLineItemIDs( renderSingleCartItem(client, checkout, variant), variant, checkout);


    // Responsible for adding cart item events
    onCartQuantity(client, checkout);


    triggerEventAfterAddToCart(product, checkout);


    openCart();







  });

};


/*

Toggle product gallery image

*/
function onProductGalleryClick() {

  var $productGalleryImgs = jQuery('.wps-product-gallery-img-thumb-wrapper'),
      productGalleryFeatClass = 'wps-product-gallery-img-feat',
      productGalleryThumbClass = 'wps-product-gallery-img-thumb';

  $productGalleryImgs.on('click', '.' + productGalleryThumbClass, function productGalleryImgHandler() {

    var $replacementThumb = jQuery(this);
    var $replacementParent = $replacementThumb.parent();
    var $featWrapper = $replacementThumb.closest('.wps-product-single').find('.wps-product-gallery-img-feat-wrapper');
    var $replacedThumb = $featWrapper.find('.' + productGalleryFeatClass);

    $replacedThumb.removeClass(productGalleryFeatClass);
    $replacedThumb.addClass(productGalleryThumbClass);

    $replacementThumb.addClass(productGalleryFeatClass);
    $replacementThumb.removeClass(productGalleryThumbClass);

    $featWrapper.html($replacementThumb);
    $replacementParent.html($replacedThumb);

  });

}


/*

Product Quantity Change

*/
function onProductQuantityChange() {

  var $productMetaContainer = jQuery('.wps-product-meta');

  $productMetaContainer.on('blur', '.wps-product-quantity', function productQuantityHandler(event) {
    var quantity = jQuery(this).val();
    jQuery(this).closest('.wps-product-meta').attr('data-product-quantity', quantity);
  });

}


/*

Auto-select quantity input

*/
function onProductQuantitySelect() {

  jQuery('.wps-form-input[type="number"]').on('click', function selectNumberHandler() {
    jQuery(this).select();
  });

}


/*

Reset All Variant IDs

*/
function resetAllVariantIDs() {

  var $productContainers = jQuery('.wps-product-meta');

  $productContainers.each(function (index, product) {

    if (jQuery(product).attr('data-product-variants-count') > 1) {
      jQuery(product).attr('data-product-selected-variant', '');
    }

  });


}


/*

Construct Variant Title Selections

*/
function constructVariantTitleSelections($trigger, previouslySelectedOptions) {

  var variantText = $trigger.text().trim();

  if (intersection(previouslySelectedOptions, [variantText]).length === 0) {

    var previouslySee = $trigger.closest('.wps-btn-dropdown').data('selected-val');
    var index = previouslySelectedOptions.indexOf(previouslySee);

    if (index === -1) {

      var updatedArray = previouslySelectedOptions;
      updatedArray.push(variantText);

    } else {

      var updatedArray = update(index, variantText, previouslySelectedOptions);

    }

    return updatedArray;

  } else {

    return [];

  }

}


/*

Update Variant Title Selection
TODO: titles param currently not used

*/
function updateVariantTitleSelection($container, titles) {

  var newTitles = getCurrentlySelectedOptions();

  $container.data('product-selected-options', newTitles);
  $container.attr('data-product-selected-options', newTitles);

}


/*

Adds option titles to meta container

*/
function addAvailableOptionsToProduct() {

  jQuery('.wps-product-meta .wps-btn-dropdown').each(function() {

    var availableOptions = [];

    jQuery(this).find('.wps-product-style').each(function() {
      availableOptions.push( jQuery(this).text().trim() );
    });

    jQuery(this).data('available-options', availableOptions);
    jQuery(this).attr('data-available-options', availableOptions);

  });

}


/*

Updates values of a single variant after selection

*/
function updateSingleVariantValues($variantTrigger) {

  var variantText = $variantTrigger.text().trim();
  var optionName = $variantTrigger.parent().prev().data('option');

  $variantTrigger.parent().prev().text(optionName + ': ' + variantText);
  $variantTrigger.closest('.wps-btn-dropdown').data('selected-val', variantText);
  $variantTrigger.closest('.wps-btn-dropdown').attr('data-selected-val', variantText);

  $variantTrigger.closest('.wps-btn-dropdown').data('selected', true);
  $variantTrigger.closest('.wps-btn-dropdown').attr('data-selected', true);

}


/*

Check for last selection

*/
function checkForLastSelection(previouslySelectedOptions, currentProductID) {

  var prevSelectedProductID = parseInt(getProductSelectionID());
  var $addToCartButton = jQuery('.wps-product-meta[data-product-id="' + prevSelectedProductID + '"]').find('.wps-add-to-cart');

  if (prevSelectedProductID === currentProductID) {

    if (!previouslySelectedOptions) {
      previouslySelectedOptions = [];
    }

  } else {
    previouslySelectedOptions = [];

    resetSingleProductVariantSelector($addToCartButton);
    setProductSelectionID(currentProductID);

  }

  return previouslySelectedOptions;

}


/*

Find Matching Variant Image

*/
function findMatchingVariantImageByID(variantID) {

  var $images =jQuery('.wps-product-gallery-imgs .wps-product-gallery-img[data-wps-image-variants*="' + variantID + '" ]');

  if ($images.length > 0) {
    return $images;

  } else {
    return false;
  }

}


/*

Show Variant Image

*/
function showVariantImage(variantID) {

  var $images = findMatchingVariantImageByID(variantID);

  if ($images) {
    $images.click();
  }

}


function emptyPriceDOM($metaContainer) {
  $metaContainer.parent().find('.wps-products-price').empty();
}


function showVariantPrice(price, $metaContainer) {

  if (price) {

    emptyPriceDOM($metaContainer);

    $metaContainer.parent().find('.wps-products-price')
      .append('<span itemprop="price" class="wps-product-individual-price">' + price + '</span>');

  }


}


/*

Looks through the canonical array of available variants for a match
based on an object from user selection:

{ option1: "Extra Small" }

[
  { option1: "Extra Small", option2: "Large", option3: "Black" },
  { option1: "Medium", option2: "Gold", option3: "Black" },
  { option1: "Large", option2: "Extra Small", option3: "Black" },
]

*/
function findVariantFromTitle(availableVariants, selectedVariant) {
  return filter(availableVariants, matches(selectedVariant));
}


/*

Construct Variant Selector From Match

*/
function constructVariantSelectorFromMatch($dropdown, availableMatch) {

  var availableMatchKey = 'option' + jQuery($dropdown).data('option');
  var variantTitle = availableMatch[availableMatchKey];

  return '.wps-product-style:not([data-variant-title="' + variantTitle + '"])';

}


/*

Handles showing / hiding the appropriate varints depending on what
the user currently has seleected.

Param   => selectedVariant    => Object             => { option1: "Extra Small" }
Param   => availableVariants  => Array of Objects   => [{ option1: "Extra Small", option2: "Large", option3: "Black" }]

*/
function toggleAvailableVariantSelections($trigger, selectedVariant, availableVariants) {


  /*

  Represents an array of variants that are available to select based on a previous selection

  */
  var selectableVariants = findVariantFromTitle(availableVariants, selectedVariant);


  /*

  Dropdowns that are currently deselected
  Represents the options so we can have up to three

  */
  var $deselectedDropdowns = getDeselectedDropdowns($trigger);


  /*

  Look through each deselected dropdown

  */
  jQuery.each($deselectedDropdowns, (index, $dropdown) => {

    var finalSelector = '';

    /*

    For each available variant ...

    */
    jQuery.each(selectableVariants, (index, availableMatch) => {

      var selectionSelector = constructVariantSelectorFromMatch($dropdown, availableMatch);

      if (finalSelector !== selectionSelector) {
        finalSelector += selectionSelector;
      }

    });

    var individualSelection = jQuery($dropdown).find(finalSelector);

    individualSelection.addClass('wps-is-hidden');

  });


}


/*

Construct Selected Variant Options

*/
function constructSelectedVariantOptions($trigger) {

  var key = 'option' + $trigger.data('option-position');
  var currentlySelected = getCurrentlySelectedVariants();

  if (isEmpty(currentlySelected)) {
    currentlySelected = {};
  } else {
    currentlySelected = JSON.parse(currentlySelected);
  }

  currentlySelected[key] = $trigger.data('variant-title');

  setCurrentlySelectedVariants(currentlySelected);

  return currentlySelected;

}


/*

Turns on selecting meta state

*/
function turnOnMetaSelectingState($newProductMetaContainer) {
  $newProductMetaContainer.addClass('wps-is-selecting');
}











/*

Product Variant Change

*/
function onProductVariantChange() {

  var $productMetaContainer = jQuery('.wps-product-meta');

  // Resets selected options on load
  $productMetaContainer.data('product-selected-options', []);

  // Adds data-available-options to the dropdown elements
  addAvailableOptionsToProduct();

  // Click handler for individual variant selections
  $productMetaContainer.on('click', '.wps-product-style', async function productStyleHandler(event) {

    var $trigger = jQuery(this),
        optionID = $trigger.data('option-id'),
        variantTitle = $trigger.data('variant-title'),
        variantText = $trigger.text().trim(),
        $newProductMetaContainer = $trigger.closest('.wps-product-meta'),
        $variantDropdownContainer = $trigger.closest('.wps-btn-dropdown'),
        variantOptionName = $variantDropdownContainer.data('option-name'),
        currentProductID = $newProductMetaContainer.data('product-id'),
        previouslySelectedOptions = $newProductMetaContainer.data('product-selected-options'),
        availableOptions = $variantDropdownContainer.data('available-options'),
        dropdownAlreadySelected = $variantDropdownContainer.data('selected'),
        availableVariants = $newProductMetaContainer.data('product-available-variants'),
        prevSelected = $newProductMetaContainer.attr('data-product-selected-options-and-variants');


    hideAllOpenProductDropdowns();


    // Turns on meta selecting state
    turnOnMetaSelectingState($newProductMetaContainer);


    /*

    This builds the selected options needed for the eventual GraphQL request

    */
    buildSelectedOptions($newProductMetaContainer, prevSelected, variantOptionName, variantTitle);



    /*

    Resets the selection process if the user picks a variant from an
    option that's already selected. We need to do this because our
    calculated "available selections" is dependent on what the user
    has already a selected. Therefore we need to keep this green.

    */
    if (dropdownAlreadySelected) {
      resetVariantSelectors();
      resetOptionsSelection();
    }


    /*

    Gets options from currently selected, or empty array if newly selected
    Contains an array of titles: ['Extra Small', 'Black']

    */
    previouslySelectedOptions = checkForLastSelection(previouslySelectedOptions, currentProductID);


    /*

    Reset all Variant IDs

    */
    resetAllVariantIDs();


    /*

    Updates values of variant after selection

    */
    updateSingleVariantValues($trigger);


    /*

    Checks selected variant titles and removes / adds to array if nessesary
    Modifies the [data-product-selected-options] on '.wps-product-meta'

    */
    updateVariantTitleSelection(
      $newProductMetaContainer,
      constructVariantTitleSelections($trigger, previouslySelectedOptions)
    );



    toggleAvailableVariantSelections(
      $trigger,
      constructSelectedVariantOptions($trigger),
      availableVariants
    );



    /*

    If all variants are selected ...

    */
    if (allProductVariantsSelected($newProductMetaContainer)) {

      setCurrentlySelectedVariants({});

      var newCurrentProductID = $newProductMetaContainer.attr('data-product-post-id');
      var selectedOptions = $trigger.closest('.wps-product-meta').data('product-selected-options');
      var $optionButtons = $newProductMetaContainer.find('.wps-btn-dropdown .wps-btn');
      var $addToCartButton = $newProductMetaContainer.find('.wps-add-to-cart');



      disable($optionButtons);
      disableNoLoader($addToCartButton);

      // resetOptionsSelection();

      // resetVariantSelectors(); // Resets DOM related to selecting options
      // resetSingleProductVariantSelector($addToCartButton);

      // All variants selected, find actual variant ID


      // Calls get_variant_id_from_product_options
      var [foundVariantError, foundVariantResponse] = await to( getVariantIdFromOptions(newCurrentProductID, selectedOptions) );

      if (foundVariantError) {

        console.log('foundVariantError: ', foundVariantError);

        logNotice('getVariantIdFromOptions', foundVariantError, 'error');
        showSingleNotice(foundVariantError, $trigger);

        enable($newProductMetaContainer.find('.wps-btn'));

        pulse($newProductMetaContainer.find('.wps-btn-dropdown[data-selected=true]'));

        resetVariantSelectors();
        removeProductOptionIds();
        resetOptionsSelection();

        return;

      }


      if (isWordPressError(foundVariantResponse)) {

        console.log('foundVariantResponse: ', foundVariantResponse);

        logNotice('getVariantIdFromOptions', foundVariantResponse, 'error');
        showSingleNotice(foundVariantResponse, $trigger);

        enable($newProductMetaContainer.find('.wps-btn'));


        pulse( $newProductMetaContainer.find('.wps-btn-dropdown[data-selected=true]') );



        resetVariantSelectors();
        removeProductOptionIds();
        resetOptionsSelection();

        return;

      }



      var foundVariant = foundVariantResponse.data;

      $newProductMetaContainer.data('product-selected-variant', foundVariant.id);
      $newProductMetaContainer.attr('data-product-selected-variant', foundVariant.id);

      showVariantImage(foundVariant.id);

      setFromPricing();
      showVariantPrice( formatAsMoney(foundVariant.price), $newProductMetaContainer );


      pulseSoft($addToCartButton);

      enable($optionButtons);
      enable($addToCartButton);

      hideProductMetaNotice($trigger);





    }

  });

}






function toggleProductDropdownOpenState($clickedDropdown) {

  $clickedDropdown.attr('data-open', $clickedDropdown.attr('data-open') === 'true' ? 'false' : 'true');

  if ($clickedDropdown.attr('data-open') === 'true') {
    jQuery('.wps-btn-dropdown').attr('data-open', false);
    $clickedDropdown.attr('data-open', true);
  }

}





/*

Product Dropdown Change

*/
function onProductDropdown() {

  var $productMetaContainer = jQuery('.wps-product-meta');
  var $productDropdown = $productMetaContainer.find('.wps-modal-trigger');

  if (!$productDropdown.hasClass('is-disabled')) {

    $productMetaContainer.on('click', '.wps-modal-trigger', function modalTriggerHandler(e) {

      e.preventDefault();

      var $trigger = jQuery(this),
          $dropdownModal = $trigger.next(),
          $dropdown = $trigger.parent();


      closeCart();

      toggleProductDropdownOpenState( $dropdown );

    });

  }

}


/*

allProductVariantsSelected

*/
function allProductVariantsSelected($container = null) {

  var dropdownsAmount = $container.find('.wps-btn-dropdown').length;
  var dropdownsSelectedAmount = $container.find('.wps-btn-dropdown[data-selected="true"]').length;

  return dropdownsAmount === dropdownsSelectedAmount ? true : false;

}


/*

Initialize Product Events

*/
function productEvents(client) {

  onProductAddToCart(client);
  onProductGalleryClick();
  onProductQuantityChange();
  onProductVariantChange();
  onProductDropdown();
  onProductQuantitySelect();

}


export {
  productEvents
}
