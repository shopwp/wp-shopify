import { TextControl } from '@wordpress/components';
import React from 'react';
import ReactDOM from 'react-dom';
import to from 'await-to-js';
import toInteger from 'lodash/toInteger';
import { updateSettingProductsImagesSizingHeight } from "../../ws/ws-api";
import { showNotice } from "../../notices/notices";
import { showLoader, hideLoader } from "../../utils/utils";
import { toBoolean } from '../../utils/utils';


/*

<ProductsImagesSizingHeight />

*/
class ProductsImagesSizingHeight extends React.Component {

  state = {
    value: WP_Shopify.settings.productsImagesSizingHeight === 0 ? 'auto' : WP_Shopify.settings.productsImagesSizingHeight,
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

  onProductsImagesSizingHeightBlur = async value => {

    // If the user hasn't selected a new color, just exit
    if (!this.state.valueHasChanged) {
      return this.state.value;
    }

    showLoader(this.state.submitElement);

    var [updateError, updateResponse] = await to( updateSettingProductsImagesSizingHeight({ value: toInteger(this.state.value) }) );

    showNotice(updateError, updateResponse);

    hideLoader(this.state.submitElement);

  }

  render() {

    return (
      <TextControl
          type="text"
          value={ this.state.value }
          onChange={ this.updateValue }
          onBlur={ this.onProductsImagesSizingHeightBlur }
          aria-describedby="wps-products-images-sizing-toggle"
          disabled={ !toBoolean(WP_Shopify.settings.productsImagesSizingToggle) }

      />
    );

  }

}


/*

Init color pickers

*/
function initProductsImagesSizingHeight() {

  ReactDOM.render(
    <ProductsImagesSizingHeight />,
    document.getElementById("wps-settings-products-images-sizing-height")
  );

}

export {
  initProductsImagesSizingHeight
}
