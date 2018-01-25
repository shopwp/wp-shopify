/*

Init

*/
function removeCartIconDescription() {

  jQuery('.wps-submit-menu-cart-icon').on('click', function() {

    var found = false;

    var refreshIntervalId = setInterval(function() {

      if (jQuery('#menu-to-edit .menu-item .field-description .edit-menu-item-description:contains("WP Shopify Cart Icon")').length) {
        found = true;
      }

    }, 500);




    jQuery('#menu-to-edit .menu-item .field-description .edit-menu-item-description:contains("WP Shopify Cart Icon")').closest('.field-description').remove();

    clearInterval(refreshIntervalId);




  });

}


/*

Init

*/
function menusInit() {

  removeCartIconDescription();

}

export {
  menusInit
}
