import { hideAllOpenProductDropdowns } from '../products/products-ui';
import { closeCart } from '../cart/cart-ui';


function globalEvents(client) {

  jQuery(document).on("click", function (event) {

    onOutsideClick(event);

  });

  jQuery(document).on('keyup', function(event) {

    if (event.keyCode == 27) {
      onEsc(event);
    }

  });

}



function onOutsideClick(event) {


  if ( jQuery(event.target).closest(".wps-btn-dropdown").length === 0) {
    jQuery('.wps-btn-dropdown').attr('data-open', false);
  }

  if ( jQuery(event.target).closest(".wps-cart").length === 0) {
    closeCart();
  }



}



function onEsc() {
  hideAllOpenProductDropdowns();
  closeCart();
}


export {
  globalEvents
}
