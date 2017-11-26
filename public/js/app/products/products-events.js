import update from 'ramda/es/update';
import intersection from 'ramda/es/intersection';
import filter from 'lodash/filter';
import matches from 'lodash/matches';

import {
  isError,
  listenForClose
} from '../utils/utils-common';

import {
  disable,
  enable,
  enableNoLoader,
  disableNoLoader,
  showLoader,
  hideLoader,
  shake
} from '../utils/utils-ux';

import {
  getProduct,
  getProductVariantID,
  getVariantIdFromOptions,
  setProductSelectionID,
  getProductSelectionID,
  getProductOptionIds,
  setProductOptionIds,
  removeProductOptionIds
} from '../ws/ws-products';

import {
  resetVariantSelectors,
  resetOptionsSelection,
  closeOptionsModal,
  getDeselectedDropdowns,
  getCurrentlySelectedOptionsAmount,
  getCurrentlySelectedOptions,
  showHiddenProductVariants
} from './products-meta';

import {
  updateCart,
  fetchCart
} from '../ws/ws-cart';

import {
  updateCartCounter,
  toggleCart,
  cartIsOpen,
  closeCart
} from '../cart/cart-ui';



/*

showProductMetaError

*/
function showProductMetaError($element, errorMessage) {

  // Hides all other error messages
  hideAllProductMetaErrors();

  $element
    .closest('.wps-product-meta')
    .find('.wps-product-notice')
    .html(errorMessage)
    .addClass('wps-is-visible wps-notice-error');

  var $elementToShake = $element.closest('.wps-product-actions-group').find('.wps-btn-dropdown[data-selected="false"]');

  shake($elementToShake);

}


/*

hideProductMetaErrors

*/
function hideAllProductMetaErrors() {

  jQuery('.wps-product-notice')
    .html('')
    .removeClass('wps-is-visible wps-notice-error');

}


/*

hideProductMetaErrors

*/
function hideProductMetaErrors($element) {

  $element
    .closest('.wps-product-meta')
    .find('.wps-product-notice')
    .html('')
    .removeClass('wps-is-visible wps-notice-error');
}


/*

Remove Inline Notices

*/
function removeInlineNotices() {
  jQuery('.wps-notice-inline').removeClass('wps-is-visible wps-notice-error').text('');
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

  // Any errors
  removeInlineNotices();

  $variantSelectors.each(function(index, value) {

    var variantTitle = jQuery(value).data('option');
    jQuery(value).text(variantTitle);

    $productMetaWrapper.data('product-selected-options', '');
    $productMetaWrapper.attr('data-product-selected-options', '');
    $productMetaDropdown.attr('data-selected', false);
    $productMetaDropdown.data('selected', false);

  });

}


/*

Attach and control listeners onto buy button
TODO: Add try catches below

*/
function onAddProductToCart(shopify) {

  jQuery('.wps-btn-wrapper').on('click', '.wps-add-to-cart', async function addToCartHandler(event) {

    event.preventDefault();

    var $addToCartButton = jQuery(this),
        $container = $addToCartButton.closest('.wps-product-meta'),
        matchingProductVariantID = $container.attr('data-product-selected-variant'),
        productID = $container.attr('data-product-id'),
        productQuantity = $container.attr('data-product-quantity'),
        product,
        productVariant;

    if (allProductVariantsSelected($container)) {

      disable($addToCartButton);
      showLoader($addToCartButton);
      showHiddenProductVariants();

      try {

        product = await getProduct(shopify, productID);
        productVariant = getProductVariantID(product, matchingProductVariantID);

      } catch(error) {

        enable($addToCartButton);
        hideLoader($addToCartButton);
        showProductMetaError($addToCartButton,  'Sorry, it looks like this product isn\'t available to purchase');
        return;

      }


      /*

      Update Cart Instance
      newCart === new cart instance after adding / removing

      */
      try {
        var newCart = await updateCart(productVariant, productQuantity, shopify);

      } catch(error) {

        enable($addToCartButton);
        hideLoader($addToCartButton);
        showProductMetaError($addToCartButton,  error + '. Code: 4');
        return;

      }


      /*

      Update Cart Counter

      */
      try {
        await updateCartCounter(shopify, newCart);

      } catch(error) {
        enable($addToCartButton);
        hideLoader($addToCartButton);
        showProductMetaError($addToCartButton,  error + '. Code: 5');
        return;
      }


      enable($addToCartButton);
      hideLoader($addToCartButton);

      if (!cartIsOpen()) {
        toggleCart();
      }

      resetSingleProductVariantSelector($addToCartButton);

    } else {
      showProductMetaError($addToCartButton, 'Please select the required options');

    }

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






function resetAllVariantIDs() {

  var $productContainers = jQuery('.wps-product-meta');

  $productContainers.each(function (index, product) {

    if (jQuery(product).attr('data-product-variants-count') > 1) {
      jQuery(product).attr('data-product-selected-variant', '');
    }

  });


}



/*

TODO: Found Bug when user selects two different options with the same variant text

*/
function constructVariantTitleSelections($trigger, previouslySelectedOptions) {

  var variantText = $trigger.text().trim();

  if (intersection([variantText], previouslySelectedOptions).length === 0) {

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

  var $images = findMatchingVariantImageByID(variantID)

  if ($images) {
    $images.click();
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
function toggleAvailableVariantSelections(selectedVariant, availableVariants) {

  /*

  Represents an array of variants that are available to select based on a previous selection

  */
  var selectableVariants = findVariantFromTitle(availableVariants, selectedVariant);


  /*

  Dropdowns that are currently deselected
  Represents the options so we can have up to three

  */
  var $deselectedDropdowns = getDeselectedDropdowns();


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

  var newlySelected = {};
  var key = 'option' + $trigger.data('option-position');

  newlySelected[key] = $trigger.data('variant-title');

  return newlySelected;

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
        variantText = $trigger.text().trim(),
        $newProductMetaContainer = $trigger.closest('.wps-product-meta'),
        currentProductID = $newProductMetaContainer.data('product-id'),
        previouslySelectedOptions = $newProductMetaContainer.data('product-selected-options'),
        availableOptions = $trigger.closest('.wps-btn-dropdown').data('available-options'),
        dropdownAlreadySelected = $trigger.closest('.wps-btn-dropdown').data('selected'),
        availableVariants = $newProductMetaContainer.data('product-available-variants');

    /*

    Resets the selection process if the user picks a variant from an
    option that's already selected. We need to do this because our
    calculated "available selections" is dependent on what the user
    has already a selected. Therefore we need to keep this green.

    */
    if (dropdownAlreadySelected) {
      resetVariantSelectors($newProductMetaContainer);
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
      constructSelectedVariantOptions($trigger),
      availableVariants
    );

    closeOptionsModal();

    /*

    If all variants are selected ...

    */
    if (allProductVariantsSelected($newProductMetaContainer)) {

      var newCurrentProductID = $newProductMetaContainer.attr('data-product-post-id');
      var selectedOptions = $trigger.closest('.wps-product-meta').data('product-selected-options');
      var $optionButtons = $newProductMetaContainer.find('.wps-btn-dropdown .wps-btn');
      var $addToCartButton = $newProductMetaContainer.find('.wps-add-to-cart');

      disable($optionButtons);
      disableNoLoader($addToCartButton);

      resetOptionsSelection();

      // All variants selected, find actual variant ID
      try {

        var foundVariantIDResponse = await getVariantIdFromOptions(newCurrentProductID, selectedOptions);

        if (isError(foundVariantIDResponse)) {
          throw foundVariantIDResponse.data;

        } else {

          var foundVariantID = foundVariantIDResponse.data;

          $newProductMetaContainer.data('product-selected-variant', foundVariantID);
          $newProductMetaContainer.attr('data-product-selected-variant', foundVariantID);

          showVariantImage(foundVariantID);

        }

        enable($optionButtons);
        enableNoLoader($addToCartButton);

        hideProductMetaErrors($trigger);

      } catch(error) {

        showProductMetaError($trigger,  error);
        enable($newProductMetaContainer.find('.wps-btn'));
        shake($newProductMetaContainer.find('.wps-btn-dropdown[data-selected=true]'));

        resetVariantSelectors($newProductMetaContainer);
        removeProductOptionIds();
        resetOptionsSelection();

      }

    }

  });

}


/*

Product Dropdown Change

*/
function onProductDropdown() {

  var $productMetaContainer = jQuery('.wps-product-meta');
  var $productDropdown = $productMetaContainer.find('.wps-modal-trigger');

  if (!$productDropdown.hasClass('is-disabled')) {

    $productMetaContainer.on('click', '.wps-modal-trigger', function modalTriggerHandler(e) {

      e.stopPropagation();
      e.preventDefault();

      closeCart();

      var $trigger = jQuery(this),
          $dropdownModal = $trigger.next(),
          $dropdown = $trigger.parent();

      /*

      Hide any visible dropdowns before we show the selected one

      */
      if ($dropdown.data('open')) {
        $dropdown.data('open', false);
        $dropdown.attr('data-open', false);

      } else {

        closeOptionsModal();
        $dropdown.data('open', true);
        $dropdown.attr('data-open', true);

        listenForClose();
      }

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
function productEvents(shopify) {

  onAddProductToCart(shopify);
  onProductGalleryClick();
  onProductQuantityChange();
  onProductVariantChange();
  onProductDropdown();
  onProductQuantitySelect();

}


export {
  productEvents
};
