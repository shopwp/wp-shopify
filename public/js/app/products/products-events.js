import { disable, enable, showLoader, hideLoader, animate, shake } from '../utils/utils-ux';
import { getProduct, getProductVariantID, getVariantIdFromOptions } from '../ws/ws-products';
import { updateCart } from '../ws/ws-cart';
import { updateCartCounter, toggleCart } from '../cart/cart-ui';


/*

showProductMetaError

*/
function showProductMetaError($element, errorMessage) {

  $element
    .closest('.wps-product-meta')
    .find('.wps-product-notice')
    .html(errorMessage)
    .addClass('wps-is-visible wps-notice-error');

  shake( jQuery('.wps-btn-dropdown[data-selected="false"]') );

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

Attach and control listeners onto buy button
TODO: Add try catches below

*/
function onAddProductToCart(shopify) {

  jQuery('.wps-add-to-cart').on('click', async function addToCartHandler(event) {

    event.preventDefault();

    var $container = jQuery(this).closest('.wps-product-meta'),
        matchingProductVariantID = $container.attr('data-product-selected-variant'),
        productID = $container.attr('data-product-id'),
        productQuantity = $container.attr('data-product-quantity'),
        product,
        productVariant;

    if( allProductVariantsSelected() ) {

      disable(jQuery(this));
      showLoader(jQuery(this));

      try {
        product = await getProduct(shopify, productID);
        productVariant = getProductVariantID(product, matchingProductVariantID);

      } catch(error) {
        enable(jQuery(this));
        hideLoader(jQuery(this));
        showProductMetaError(jQuery(this),  error + '. Code: 3');
        return;

      }


      /*

      Update Cart Instance

      */
      try {
        await updateCart(productVariant, productQuantity, shopify);

      } catch(error) {
        enable(jQuery(this));
        hideLoader(jQuery(this));
        showProductMetaError(jQuery(this),  error + '. Code: 4');
        return;
      }


      /*

      Update Cart Counter

      */
      try {
        await updateCartCounter(shopify);

      } catch(error) {
        enable(jQuery(this));
        hideLoader(jQuery(this));
        showProductMetaError(jQuery(this),  error + '. Code: 5');
        return;
      }


      enable(jQuery(this));
      hideLoader(jQuery(this));
      toggleCart();

    } else {
      showProductMetaError(jQuery(this), 'Please select the required options');

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


/*

Product Variant Change

*/
function onProductVariantChange() {

  var $productMetaContainer = jQuery('.wps-product-meta');
  $productMetaContainer.data('product-selected-options', []);

  jQuery('.wps-product-meta .wps-btn-dropdown').each(function() {

    var availableOptions = [];

    jQuery(this).find('.wps-product-style').each(function() {
      availableOptions.push( jQuery(this).text() );
    });

    jQuery(this).data('available-options', availableOptions);
    jQuery(this).attr('data-available-options', availableOptions);

  });

  $productMetaContainer.on('click', '.wps-product-style', async function productStyleHandler(event) {

    var variantID = jQuery(this).data('id'),
        variantText = jQuery(this).text(),
        previouslySelectedOptions = $productMetaContainer.data('product-selected-options'),
        availableOptions = jQuery(this).closest('.wps-btn-dropdown').data('available-options');

    /*

    Checks if user chose an option already selected

    */
    if(R.intersection([variantText], previouslySelectedOptions).length === 0) {
      // Compare available options with selected options
      // console.log("Previously Selected: ", previouslySelectedOptions);
      // console.log("Currently Selected: ", [variantText]);
      // console.log("Available Options: ", availableOptions);


      var previouslySee = jQuery(this).closest('.wps-btn-dropdown').data('selected-val');
      // console.log("previouslySee: ", previouslySee);

      // var fruits = ["Banana", "Orange", "Apple", "Mango"];
      var iiiinndeexx = previouslySelectedOptions.indexOf(previouslySee);

      // console.log("iiiinndeexx: ", iiiinndeexx);

      if (iiiinndeexx === -1) {

        var updatedArray = previouslySelectedOptions;
        updatedArray.push(variantText);

        // console.log('Updated Array: ', updatedArray);

        $productMetaContainer.data('product-selected-options', updatedArray);
        $productMetaContainer.attr('data-product-selected-options', updatedArray);

      } else {

        var updatedArray = R.update(iiiinndeexx, variantText, previouslySelectedOptions);

        // console.log('Updated Array: ', updatedArray);

        $productMetaContainer.data('product-selected-options', updatedArray);
        $productMetaContainer.attr('data-product-selected-options', updatedArray);

      }

    } else {
      console.log('Grr, clicked same val');

    }


    // Setting the text value
    jQuery(this).parent().prev().text(variantText);

    jQuery(this).closest('.wps-btn-dropdown').data('selected-val', variantText);
    jQuery(this).closest('.wps-btn-dropdown').attr('data-selected-val', variantText);

    jQuery(this).closest('.wps-btn-dropdown').data('selected', true);
    jQuery(this).closest('.wps-btn-dropdown').attr('data-selected', true);

    console.log(" allProductVariantsSelected: ", allProductVariantsSelected());

    if (allProductVariantsSelected()) {

      var productID = $productMetaContainer.data('product-post-id');
      var selectedOptions = $productMetaContainer.data('product-selected-options');

      console.log('selectedOptions', selectedOptions);
      console.log('productID', productID);

      disable(jQuery('.wps-product-meta .wps-btn'));
      showLoader(jQuery(this));

      // All variants selected, find actual variant ID
      try {

        var foundVariantID = await getVariantIdFromOptions(productID, selectedOptions);
        console.log('yep', foundVariantID);

        enable(jQuery('.wps-product-meta .wps-btn'));
        hideLoader(jQuery(this));

        $productMetaContainer.data('product-selected-variant', foundVariantID);
        $productMetaContainer.attr('data-product-selected-variant', foundVariantID);

        hideProductMetaErrors(jQuery(this));

      } catch(error) {
        console.log('NO', error);
        showProductMetaError(jQuery(this),  error + '. Code: 7');

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

      console.log('clickedd');

      if (!jQuery(this).next().hasClass('wps-is-visible')) {

        animate({
          inClass: 'wps-flipInX',
          outClass: 'wps-fadeOut',
          element: jQuery(this).next()
        });

      }

    });
  }

}


/*

allProductVariantsSelected

*/
function allProductVariantsSelected() {

  var dropdownsAmount = jQuery('.wps-btn-dropdown').length;
  var dropdownsSelectedAmount = jQuery('.wps-btn-dropdown[data-selected="true"]').length;

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
