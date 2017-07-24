/*

Updates product title (product is the actual DOM element)

*/
function updateProductTitle(productTitle, title) {
  jQuery(productTitle).text(title);
};


/*

Updates product image

*/
function updateVariantImage(productImage, image) {
  jQuery(productImage).attr('src', image.src);
};


/*

Update product variant title

*/
function updateVariantTitle(productVariantTitle, variant) {
  jQuery(productVariantTitle).text(variant.title);
};


/*

Update product variant price

*/
function updateVariantPrice(productVariantPrice, variant) {
  jQuery(productVariantPrice).text('$' + variant.price);
};


/*

When product variants change ...

*/
function attachOnVariantSelectListeners(product, element) {

  var productTitleDOM = '.wps-product-' + product.id + ' .wps-product-title';
  var productImageDOM = '.wps-product-' + product.id + ' .wps-variant-image';
  var productVariantTitleDOM = '.wps-product-' + product.id + ' .wps-variant-title';
  var productVariantPriceDOM = '.wps-product-' + product.id + ' .wps-variant-price';
  var variantSelectors = '.wps-product-' + product.id + ' .wps-variant-selectors';

  jQuery(variantSelectors).on('change', 'select', function variantSelectorsHandler(event) {

    var $element = jQuery(event.target);
    var name = $element.attr('name');
    var value = $element.val();

    product.options.filter(function productOptionsFilter(option) {
      return option.name === name;
    })[0].selected = value;

    var selectedVariant = product.selectedVariant;
    var selectedVariantImage = product.selectedVariantImage;

    updateProductTitle(productTitleDOM, product.title);
    updateVariantImage(productImageDOM, selectedVariantImage);
    updateVariantTitle(productVariantTitleDOM, selectedVariant);
    updateVariantPrice(productVariantPriceDOM, selectedVariant);

  });

}





function resetVariantSelectors($parent) {

  jQuery('.wps-btn-dropdown[data-selected=true]').each(function (index, element) {

    var $dropdown = jQuery(element);
    var $dropdownLink = $dropdown.find('.wps-modal-trigger');

    $dropdown.attr('data-selected', false);
    $dropdown.data('selected', false);
    $dropdownLink.html($dropdownLink.attr("data-option"));

  });

}





export {
  resetVariantSelectors,
  updateProductTitle,
  updateVariantImage,
  updateVariantTitle,
  updateVariantPrice
};
