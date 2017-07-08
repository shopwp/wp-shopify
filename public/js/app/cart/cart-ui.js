import { formatAsMoney } from '../utils/utils-common';
import { fetchCart } from '../ws/ws-cart';
import { animate, animateIn, enable, disable } from '../utils/utils-ux';

/*

Update product variant price

*/
async function updateTotalCartPricing(shopify, cart = false) {

  if (!cart) {
    cart = await fetchCart(shopify);
  }

  try {
    var formattedSubtotal = await formatAsMoney(cart.subtotal);

  } catch(e) {
    return e;
  }

  jQuery('.wps-cart .wps-pricing').text(formattedSubtotal);

  return formattedSubtotal;

};


/*

Creates our Empty Cart Notice

*/
function createEmptyCartNotice() {
  return jQuery('<aside class="wps-cart-empty-notice"><h2>Your cart is empty</h2></aside>');
}


/*

Creates our Empty Cart Notice

*/
function removeVariantSelections() {
  jQuery('.wps-btn-dropdown[data-selected="true"]').attr('data-selected', false);
  jQuery('.wps-btn-dropdown[data-selected="true"]').data('selected', false);
}


/*

Update Cart Icon Amount

*/
async function updateCartCounter(shopify) {

  return new Promise(async function(resolve, reject) {

    var $cartCounter = jQuery('.wps-cart-counter');

    try {
      var cart = await fetchCart(shopify);

    } catch(error) {
      reject(error);

    }

    if (cart.lineItemCount > 0) {

      enable(jQuery('.wps-btn-checkout'));

      var totalItems = cart.lineItems.reduce(function(total, item) {
        return total + item.quantity;
      }, 0);

      $cartCounter.html(totalItems);
      $cartCounter.removeClass('wps-is-hidden');

      if(cart.lineItemCount >= 10) {
        jQuery('.wps-cart-counter').addClass('wps-cart-counter-lg');

      } else {
        jQuery('.wps-cart-counter').removeClass('wps-cart-counter-lg');

      }

      if($cartCounter.length) {

        animateIn({
          element: $cartCounter,
          inClass: 'wps-bounceInDown',
          oneway: true
        });

      }

      resolve('Done updating cart counter');

    } else {

      disable(jQuery('.wps-btn-checkout'));
      $cartCounter.addClass('wps-is-hidden');

      resolve('Counter is zero. Done updating.');

    }

  });

};


















































function findLineItemByVariantID(cart, lineitem) {

  var lineItemArray = cart.lineItems.filter(function(value, index, ar) {
    return value.variant_id === lineitem.id;
  });

  return lineItemArray[0];

}



function updateSingleProductCartDOM(lineItem, singleProduct) {

  return new Promise(async function(resolve, reject) {

    var $lineItemsContainer = jQuery('.wps-cart-item-container');

    if (typeof lineItem === "undefined") {

      var $foundLineItem = $lineItemsContainer.find('.wps-quantity-decrement[data-variant-id="' + singleProduct.id + '"]').closest('.wps-cart-item');

      $foundLineItem.remove();

      resolve();

    } else {

      var $foundLineItem = $lineItemsContainer.find('.wps-quantity-decrement[data-variant-id="' + lineItem.variant_id + '"]').closest('.wps-cart-item');

      $foundLineItem.find('.wps-cart-item__img').css('background-image', 'url(' + lineItem.image.src + ')');
      $foundLineItem.find('.wps-cart-item__title').text(lineItem.title);
      $foundLineItem.find('.wps-cart-item__variant-title').text(lineItem.variant_title);
      $foundLineItem.find('.wps-cart-item__quantity').attr('value', lineItem.quantity);
      $foundLineItem.find('.wps-quantity-decrement').attr('data-variant-id', lineItem.variant_id);
      $foundLineItem.find('.wps-quantity-increment').attr('data-variant-id', lineItem.variant_id);
      $foundLineItem.find('.wps-quantity-decrement').attr('data-product-id', lineItem.product_id);
      $foundLineItem.find('.wps-quantity-increment').attr('data-product-id', lineItem.product_id);

      try {
        var formatedPrice = await formatAsMoney(lineItem.line_price);

      } catch(e) {
        reject(e);
      }

      $foundLineItem.find('.wps-cart-item__price').text(formatedPrice);

      resolve($foundLineItem);

    }

  });

}




/*

Render Cart Items

*/
async function renderCartItems(shopify, cart = false, singleProduct = false) {

  var cartLineItemCount;
  var $cartItemContainer = jQuery('.wps-cart-item-container');
  var totalPrice = 0;

  return new Promise(async function(resolve, reject) {

    if(!cart) {

      try {
        cart = await fetchCart(shopify);

      } catch(error) {
        cart = await createCart(shopify);
      }

    }

    if(cart.lineItemCount === 0) {

      $cartItemContainer.empty();
      $cartItemContainer.append( createEmptyCartNotice() );

    } else {

      var lineItemEmptyTemplate = jQuery('#wps-cart-item-template').html();

      /*

      If updating only one lineItem ...

      */
      if (singleProduct) {

        var lineItem = findLineItemByVariantID(cart, singleProduct);

        try {
          var lineItemPromises = await updateSingleProductCartDOM(lineItem, singleProduct);

        } catch(e) {
          return e;
        }


      } else {

        var lineItemPromises = cart.lineItems.map(async function (lineItem, index) {

          return new Promise(async function(resolve, reject) {

            var $lineItemTemplate = jQuery(lineItemEmptyTemplate);
            var itemImage = lineItem.image.src;

            $lineItemTemplate.find('.wps-cart-item__img').css('background-image', 'url(' + itemImage + ')');
            $lineItemTemplate.find('.wps-cart-item__title').text(lineItem.title);
            $lineItemTemplate.find('.wps-cart-item__variant-title').text(lineItem.variant_title);

            var formatedPrice = await formatAsMoney(lineItem.line_price);

            $lineItemTemplate.find('.wps-cart-item__price').text(formatedPrice);
            $lineItemTemplate.find('.wps-cart-item__quantity').attr('value', lineItem.quantity);
            $lineItemTemplate.find('.wps-quantity-decrement').attr('data-variant-id', lineItem.variant_id);
            $lineItemTemplate.find('.wps-quantity-increment').attr('data-variant-id', lineItem.variant_id);
            $lineItemTemplate.find('.wps-quantity-decrement').attr('data-product-id', lineItem.product_id);
            $lineItemTemplate.find('.wps-quantity-increment').attr('data-product-id', lineItem.product_id);

            if (cartLineItemCount < cart.lineItems.length && (index === cart.lineItems.length - 1)) {
              $lineItemTemplate.addClass('wps-js-hidden');
              cartLineItemCount = cart.lineItems.length;
            }

            if (cartLineItemCount > cart.lineItems.length) {
              cartLineItemCount = cart.lineItems.length;
            }

            resolve($lineItemTemplate);

          });

        });


        try {
          var $cartLineItems = await Promise.all(lineItemPromises);

        } catch(error) {
          reject(error);

        }

        $cartItemContainer.empty();
        $cartItemContainer.append($cartLineItems);

      }


      //$cartItemContainer.find('.wps-js-hidden').removeClass('wps-js-hidden');

    }

    return resolve(cart);

  });


}


function updateCartVariant(variant, quantity, shopify) {

  return new Promise(async function(resolve, reject) {

    var options = {
      variant: variant,
      quantity: quantity
    };

    try {
      var cart = await fetchCart(shopify);

    } catch(error) {
      reject(error);
    }


    try {
      await cart.createLineItemsFromVariants(options);

    } catch(error) {
      reject(error);
    }

    try {
      await renderCartItems(shopify, cart, variant);

    } catch(error) {
      reject(error);
    }

    try {
      await updateTotalCartPricing(shopify, cart);

    } catch(error) {
      reject(error);
    }

    resolve();

  });

};


async function toggleCart() {

  await animate({
    inClass: 'wps-slideInRight',
    outClass: 'wps-bounceOutRight',
    element: jQuery('.wps-cart')
  });

  // Removing green button success icons
  removeVariantSelections();

}


export {
  updateTotalCartPricing,
  updateCartCounter,
  renderCartItems,
  updateCartVariant,
  toggleCart
}
