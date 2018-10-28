import { TextControl } from '@wordpress/components';
import React from 'react';
import ReactDOM from 'react-dom';
import to from 'await-to-js';
import toInteger from 'lodash/toInteger';
import { updateSettingProductsImagesSizingWidth } from "../../ws/ws-api";
import { showNotice } from "../../notices/notices";
import { showLoader, hideLoader } from "../../utils/utils";
import { toBoolean } from '../../utils/utils';


/*

<ProductsImagesSizingWidth />

*/
class ProductsImagesSizingWidth extends React.Component {

  state = {
    value: WP_Shopify.settings.productsImagesSizingWidth === 0 ? 'auto' : WP_Shopify.settings.productsImagesSizingWidth,
    valueHasChanged: false,
    submitElement: jQuery("#submitSettings")
  }

  updateValue = newValue => {

    if (toInteger(newValue) === 0) {
      newValue = 'auto';
    }

    if (newValue !== this.state.value) {
      this.state.valueHasChanged = true;
    }

    this.setState({
      value: newValue
    });

  }

  onProductsImagesSizingWidthBlur = async value => {

    // If the user hasn't selected a new color, just exit
    if (!this.state.valueHasChanged) {
      return this.state.value;
    }

    showLoader(this.state.submitElement);

    // Updates DB with the new color
    var [updateError, updateResponse] = await to( updateSettingProductsImagesSizingWidth({ value: toInteger(this.state.value) }) );

    showNotice(updateError, updateResponse);

    hideLoader(this.state.submitElement);

  }

  render() {

    return (
      <TextControl
          type="text"
          value={ this.state.value }
          onChange={ this.updateValue }
          onBlur={ this.onProductsImagesSizingWidthBlur }
          aria-describedby="wps-products-images-sizing-toggle"
          disabled={ !toBoolean(WP_Shopify.settings.productsImagesSizingToggle) }

      />
    );

  }

}


/*

Init color pickers

*/
function initProductsImagesSizingWidth() {

  ReactDOM.render(
    <ProductsImagesSizingWidth />,
    document.getElementById("wps-settings-products-images-sizing-width")
  );

}

export {
  initProductsImagesSizingWidth
}
