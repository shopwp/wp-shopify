import { isError } from '../utils/utils-common';
import { disable, enable, showLoader, hideLoader, animate, animateIn, shake } from '../utils/utils-ux';

import {
  getProduct,
  getProductVariantID,
  getVariantIdFromOptions,
  setProductSelectionID,
  getProductSelectionID
} from '../ws/ws-products';


import {
  resetVariantSelectors,
} from './products-meta';

import {
  updateCart
} from '../ws/ws-cart';

import {
  updateCartCounter,
  toggleCart,
  cartIsOpen
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

      */
      try {

        await updateCart(productVariant, productQuantity, shopify);

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
        await updateCartCounter(shopify);

      } catch(error) {
        enable($addToCartButton);
        hideLoader($addToCartButton);
        showProductMetaError($addToCartButton,  error + '. Code: 5');
        return;
      }


      enable($addToCartButton);
      hideLoader($addToCartButton);
      toggleCart();

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
    $productMetaContainer.attr('data-product-quantity', quantity);
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









function constructVariantTitleSelections($trigger, previouslySelectedOptions) {

  var variantText = $trigger.text();

  if(R.intersection([variantText], previouslySelectedOptions).length === 0) {

    var previouslySee = $trigger.closest('.wps-btn-dropdown').data('selected-val');
    var index = previouslySelectedOptions.indexOf(previouslySee);

    if (index === -1) {

      var updatedArray = previouslySelectedOptions;
      updatedArray.push(variantText);

    } else {

      var updatedArray = R.update(index, variantText, previouslySelectedOptions);

    }

    return updatedArray;

  } else {
    return [];

  }


}


/*

Update Variant Title Selection

*/
function updateVariantTitleSelection($container, titles) {
  $container.data('product-selected-options', titles);
  $container.attr('data-product-selected-options', titles);
}


/*

Adds option titles to meta container

*/
function addAvailableOptionsToProduct() {

  jQuery('.wps-product-meta .wps-btn-dropdown').each(function() {

    var availableOptions = [];

    jQuery(this).find('.wps-product-style').each(function() {
      availableOptions.push( jQuery(this).text() );
    });

    jQuery(this).data('available-options', availableOptions);
    jQuery(this).attr('data-available-options', availableOptions);

  });

}


/*

Updates values of a single variant after selection

*/
function updateSingleVariantValues($variantTrigger) {

  var variantText = $variantTrigger.text();
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

Product Variant Change

*/
function onProductVariantChange() {

  var $productMetaContainer = jQuery('.wps-product-meta');
  $productMetaContainer.data('product-selected-options', []);

  addAvailableOptionsToProduct();

  $productMetaContainer.on('click', '.wps-product-style', async function productStyleHandler(event) {

    var $trigger = jQuery(this),
        variantID = $trigger.data('id'),
        variantText = $trigger.text(),
        $newProductMetaContainer = $trigger.closest('.wps-product-meta'),
        currentProductID = $newProductMetaContainer.data('product-id'),
        previouslySelectedOptions = $newProductMetaContainer.data('product-selected-options'),
        availableOptions = $trigger.closest('.wps-btn-dropdown').data('available-options');

    // Gets options from currently selected, or empty array if newly selected
    previouslySelectedOptions = checkForLastSelection(previouslySelectedOptions, currentProductID);

    // Reset all Variant IDs
    resetAllVariantIDs();

    // Checks selected variant titles and removes / adds to array if nessesary
    updateVariantTitleSelection(
      $newProductMetaContainer,
      constructVariantTitleSelections($trigger, previouslySelectedOptions)
    );

    // Updates values of variant after selection
    updateSingleVariantValues($trigger);

    if (allProductVariantsSelected($newProductMetaContainer)) {

      var newCurrentProductID = $newProductMetaContainer.attr('data-product-post-id');
      var selectedOptions = $trigger.closest('.wps-product-meta').data('product-selected-options');

      disable($newProductMetaContainer.find('.wps-btn'));
      showLoader($trigger);

      // All variants selected, find actual variant ID
      try {
        var foundVariantIDResponse = await getVariantIdFromOptions(newCurrentProductID, selectedOptions);

        if (isError(foundVariantIDResponse)) {
          throw foundVariantIDResponse.data;

        } else {

          var foundVariantID = foundVariantIDResponse.data;

          $newProductMetaContainer.data('product-selected-variant', foundVariantID);
          $newProductMetaContainer.attr('data-product-selected-variant', foundVariantID);

        }

        enable($newProductMetaContainer.find('.wps-btn'));
        hideLoader($trigger);
        hideProductMetaErrors($trigger);


      } catch(error) {

        showProductMetaError($trigger,  error);
        enable($newProductMetaContainer.find('.wps-btn'));
        shake($newProductMetaContainer.find('.wps-btn-dropdown[data-selected=true]'));

        resetVariantSelectors($newProductMetaContainer);

        hideLoader($trigger);

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

    $productMetaContainer.on('click', '.wps-modal-trigger', function modalTriggerHandler(event) {

      event.stopPropagation();
      event.preventDefault();

      jQuery('.wps-modal.wps-is-visible').removeClass('wps-is-visible');

      var $triggeredDropdown = jQuery(this),
          $triggeredDropdownContainer = $triggeredDropdown.next();

      if (!$triggeredDropdownContainer.hasClass('wps-is-visible')) {

        animate({
          inClass: 'wps-flipInX',
          outClass: 'wps-fadeOut',
          element: $triggeredDropdownContainer
        });

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
