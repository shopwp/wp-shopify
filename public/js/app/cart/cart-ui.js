import { formatAsMoney } from '../utils/utils-common';
import { fetchCart } from '../ws/ws-cart';
import { animate, animateIn, enable, disable } from '../utils/utils-ux';

/*

Update product variant price

*/
async function updateTotalCartPricing(shopify) {
  var cart = await fetchCart(shopify);
  jQuery('.wps-cart .wps-pricing').text( formatAsMoney(cart.subtotal) );
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
function updateCartCounter(shopify) {

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
          oneWay: true
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


/*

Render Cart Items

*/
async function renderCartItems(shopify) {

  // console.log('1', shopify);
  var cart = await fetchCart(shopify);
  // console.log('2');

  // var cart;
  var cartLineItemCount;
  var $cartItemContainer = jQuery('.wps-cart-item-container');
  var totalPrice = 0;

  if(cart.lineItemCount === 0) {

    $cartItemContainer.empty();
    $cartItemContainer.append( createEmptyCartNotice() );

  } else {

    $cartItemContainer.empty();
    var lineItemEmptyTemplate = jQuery('#wps-cart-item-template').html();

    var $cartLineItems = cart.lineItems.map(function (lineItem, index) {

      var $lineItemTemplate = jQuery(lineItemEmptyTemplate);
      var itemImage = lineItem.image.src;
      
      $lineItemTemplate.find('.wps-cart-item__img').css('background-image', 'url(' + itemImage + ')');
      $lineItemTemplate.find('.wps-cart-item__title').text(lineItem.title);
      $lineItemTemplate.find('.wps-cart-item__variant-title').text(lineItem.variant_title);
      $lineItemTemplate.find('.wps-cart-item__price').text(formatAsMoney(lineItem.line_price));
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

      return $lineItemTemplate;
    });

    $cartItemContainer.append($cartLineItems);

    setTimeout(function () {
      $cartItemContainer.find('.wps-js-hidden').removeClass('wps-js-hidden');

    }, 0);
  }

  return cart;

}


function updateCartVariant(variant, quantity, shopify) {

  return new Promise(async function(resolve, reject) {

    var options = {
      variant: variant,
      quantity: quantity
    };

    // console.log("options: ", options);

    var cart = await fetchCart(shopify);

    // console.log("cart: ", cart);

    await cart.createLineItemsFromVariants(options);

    renderCartItems(shopify);
    updateTotalCartPricing(shopify);

    resolve('Doneeeeeee');

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
