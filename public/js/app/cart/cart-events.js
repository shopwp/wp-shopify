import to from 'await-to-js';
import isEmpty from 'lodash/isEmpty';
import isNaN from 'lodash/isNaN';
import { getProductVariantID, getCheckoutID } from '../ws/ws-products';
import { enable, disable, showLoader, hideLoader } from '../utils/utils-ux';
import { isCheckoutEmpty } from '../utils/utils-cart';
import { getClient } from '../utils/utils-client';
import { pulse } from '../utils/utils-animations';
import { swapDomains } from '../utils/utils-common';
import { hasEnableCustomCheckoutDomain } from '../utils/utils-settings';
import { logNotice, showSingleCartNotice, noticeConfigEmptyLineItemsBeforeUpdate, isWordPressError } from '../utils/utils-notices';
import { getCheckout, updateLineItems, addCheckoutAttributes, cartTermsState, setCartTermsState } from '../ws/ws-cart';
import { quantityFinder, convertCustomAttrsToQueryString, containsInvalidLineItemProps } from '../utils/utils-common';

import {
  updateCartCounter,
  openCart,
  renderEmptyCartMessage,
  emptyCartUI,
  updateTotalCartPricing,
  disableCartItem,
  enableCartItem,
  enableCheckoutButton,
  disableCheckoutButton,
  cartIsOpen,
  closeCart,
  enableCartIcon,
  checkoutConditionsMet,
  getCheckoutSubmit,
  getTermsCheckbox,
  hasCartElements
} from './cart-ui';

import { anyCustomAttrs } from '../ws/ws-checkout';






function onCartTermsChange(client) {

  jQuery('#wps-terms-checkbox').change( async function() {

    if (this.checked) {

      setCartTermsState(true);

      if ( hasCartElements() ) {
        enableCheckoutButton();

      } else {
        disableCheckoutButton();
      }

    } else {

      disableCheckoutButton();
      setCartTermsState(false);

    }

  });

}


function primaryDomainEqualsMyShopifyDomain() {
  return WP_Shopify.settings.myShopifyDomain === WP_Shopify.shop.primaryDomain.host;
}


function getCheckoutURL(checkout) {

  if ( hasEnableCustomCheckoutDomain() ) {

    if ( primaryDomainEqualsMyShopifyDomain() ) {
      return checkout.webUrl;
    }

    return swapDomains(checkout.webUrl, WP_Shopify.settings.myShopifyDomain, WP_Shopify.shop.primaryDomain.host);


  } else {
    return checkout.webUrl;
  }

}


/*

Checkout listener

*/
function onCheckout(client, checkout) {

  return new Promise( async (resolve, reject) => {

    var finalCustomAttrs;

    if (checkout.lineItemCount === 0) {
      disable(jQuery('.wps-btn-checkout'));
    }




    /*

    When the user finally clicks the checkout button ...

    */
    jQuery('.wps-btn-checkout').on('click', async function checkoutHandler(event) {

      event.preventDefault();

      var $checkoutButton = jQuery(this);

      if ($checkoutButton.hasClass('wps-is-disabled')) {
        return;
      }

      jQuery(document).trigger("wpshopify_checkout_before");
      $checkoutButton.addClass('wps-is-disabled wps-is-loading');




      /*

      Step 1. Get cart instance

      */
      try {

        var freshCheckout = await getCheckout(client);

      } catch(error) {

        $checkoutButton.removeClass('wps-is-disabled wps-is-loading');
        reject(error);
        return;

      }


      if (isEmpty(WP_Shopify.checkoutAttributes)) {
        return window.open( getCheckoutURL(freshCheckout) + '&attributes[cartID]=' + getCheckoutID(), '_self');
      }


      var [updatedCheckoutError, updatedCheckout] = await to( addCheckoutAttributes(client, freshCheckout, WP_Shopify.checkoutAttributes) );

      if (updatedCheckoutError) {
        logNotice('anyCustomAttrs', updatedCheckoutError, 'error');
        $checkoutButton.removeClass('wps-is-disabled wps-is-loading');
        return reject(updatedCheckoutError);
      }


      window.open( getCheckoutURL(updatedCheckout) + '&attributes[cartID]=' + getCheckoutID(), '_self');


    });

    resolve();


  });

}


/*

Toggle Cart

*/
function onOpenCart() {

  jQuery('.wps-btn-cart').off('click').on('click', async function openCartHandler(e) {

    e.preventDefault();

    disable(jQuery(this));

    openCart();

    enable(jQuery(this));

  });

}


function onCloseCart() {

  jQuery('.wps-btn-close').on('click', async function openCartHandler(e) {

    e.preventDefault();
    e.stopPropagation();

    closeCart();

  });

}


function getCurrentQuantityFromButton($quantityInput) {
  return parseInt( $quantityInput.val() );
}

function getPrevQuantityFromButton($quantityInput) {

  var prevValue = $quantityInput.attr('data-wps-previous-amount');

  if (!prevValue) {
    prevValue = 1;
  }

  return parseInt(prevValue);

}


function enableAllQuantityInputs() {
  enable( jQuery('.wps-cart-item__quantity') );
}

function disableAllQuantityInputs() {
  disable( jQuery('.wps-cart-item__quantity') );
}





function removeLineItemByID(lineItemVariantID) {
  jQuery('.wps-cart-item[data-wps-line-item-variant-id="' + lineItemVariantID +'"]').remove();
}


function removeLineItemIfZeroQuantity(lineItemQuantity, lineItemVariantID) {

  if (lineItemQuantity === 0) {
    removeLineItemByID(lineItemVariantID);
  }

}


/*

Runs when quantity changes due to manual quantity change

*/
function onManualQuantityChange(client, checkout) {

  jQuery('.wps-cart-item')
    .off('blur')
    .on('blur', '.wps-cart-item__quantity', function(e) {
      onQuantityChange( jQuery(this), client, checkout );
    });

}


function enableCartFunctionality($lineItem) {

  enableCartItem($lineItem);
  enableAllQuantityInputs();
  enableCartIcon();

  if ( checkoutConditionsMet() ) {
    enableCheckoutButton();
  }

}


/*

Runs when quantity changes due to plus minus buttons clicked

*/
function onButtonQuantityChange(client, checkout) {

  jQuery('.wps-cart-item')
    .off('click')
    .on('click', '.wps-quantity-increment, .wps-quantity-decrement', function(e) {
      onQuantityChange( jQuery(this), client, checkout);
    });

}



async function onQuantityChange($trigger, client, checkout) {

  var quantity,
      $quantityTrigger = $trigger,
      $lineItem = $quantityTrigger.closest('.wps-cart-item'),
      lineItemId = getLineItemIdFromInput($quantityTrigger),
      lineItemVariantId = getLineItemVariantIdFromInput($quantityTrigger),
      $quantityWrapper = $quantityTrigger.closest('.wps-cart-item__quantity-container'),
      $quantityInput = $quantityWrapper.find('.wps-cart-item__quantity'),
      currentAmount = getCurrentQuantityFromButton($quantityInput),
      prevAmount = getPrevQuantityFromButton($quantityInput),
      newAmount = getNewAmount($quantityTrigger, prevAmount),
      $lineItemRow = $quantityTrigger.closest('.wps-cart-item__content-row');

  disableCartItem($lineItem);
  disableCheckoutButton();


  const lineItemInfo = getSingleLineItemUpdateOptions(lineItemId, lineItemVariantId, newAmount);

  if (containsInvalidLineItemProps(lineItemInfo)) {

    logNotice('getSingleLineItemUpdateOptions', lineItemInfo, 'warning');
    showSingleCartNotice(noticeConfigEmptyLineItemsBeforeUpdate(), $lineItem);
    enableCartFunctionality($lineItem);

    return;

  }


  const [ updateLineItemsError, updatedCheckout ] = await to( updateLineItems(client, checkout, [lineItemInfo] ) );

  if (updateLineItemsError) {

    logNotice('updateLineItems', updateLineItemsError, 'warning');
    showSingleCartNotice(updateLineItemsError, $lineItem, 'error');
    enableCartFunctionality($lineItem);
    return;

  }




  if ( isCheckoutEmpty(updatedCheckout) ) {
    emptyCartUI(updatedCheckout);

  } else {

    removeLineItemIfZeroQuantity(lineItemInfo.quantity, lineItemVariantId);

    updateQuantityInput($quantityInput, newAmount);
    updateQuantityHelper($lineItemRow, newAmount);
    updateTotalCartPricing(updatedCheckout);
    updateCartCounter(client, updatedCheckout);

    enableCartFunctionality($lineItem);

  }

}


function coerceQuanaityToNumber(currentAmount, prevAmount) {

  var value = parseInt( currentAmount );

  if (isNaN(value)) {

    if (isNaN(prevAmount)) {

      value = 1;

    } else {
      value = prevAmount;
    }

  } else {

    // Make sure only postive or zero values are used
    if (value < 0) {
      value = 0;
    }

  }

  return value;

}



function isIncrementing($button) {
  return $button.hasClass('wps-quantity-increment');
}


function getNewAmount($button, prevAmount) {

  if ($button.hasClass('wps-cart-item__quantity')) {
    return coerceQuanaityToNumber($button.val(), prevAmount);
  }

  if ( isIncrementing($button) ) {
    return prevAmount + 1;

  } else {
    return prevAmount - 1;
  }

}







function getSingleLineItemUpdateOptions(lineItemId, variantId, newAmount) {

  return {
    id: lineItemId,
    quantity: parseInt(newAmount),
    variantId: variantId
  }

}



/*

It's important that the id we add to [data-wps-line-item-id] be correct!

*/
function getLineItemIdFromInput($inputButton) {
  return $inputButton.closest('.wps-cart-item').attr('data-wps-line-item-id');
}

function getLineItemVariantIdFromInput($inputButton) {
  return $inputButton.closest('.wps-cart-item').attr('data-wps-line-item-variant-id');
}


function updateQuantityInput($quantityInput, newAmount) {

  $quantityInput.val(newAmount);
  $quantityInput.attr('val', newAmount);

  $quantityInput.attr('data-wps-previous-amount', newAmount);
  $quantityInput.data('wps-previous-amount', newAmount);

  pulse($quantityInput);

}


function updateQuantityHelper($rowElement, newAmount) {
  $rowElement.find('.wps-cart-item__price .wps-cart-item__quantity').text('x' + newAmount);
}







/*

Initialize Cart Events

*/
function cartEvents(client, checkout) {
  onCheckout(client, checkout);
  onOpenCart();
  onCloseCart();
  onCartQuantity(client, checkout);
  onCartTermsChange(client);
}

function onCartQuantity(client, checkout) {
  onButtonQuantityChange(client, checkout);
  onManualQuantityChange(client, checkout);
}


export {
  cartEvents,
  onCartQuantity
}
