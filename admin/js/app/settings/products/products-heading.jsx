import { TextControl } from '@wordpress/components';
import React from 'react';
import ReactDOM from 'react-dom';
import to from 'await-to-js';
import { updateSettingProductsHeading, updateSettingProductsHeadingToggle } from "../../ws/ws-api";
import { showNotice } from "../../notices/notices";
import { showLoader, hideLoader } from "../../utils/utils";
import { toBoolean } from '../../utils/utils';


/*

<ProductsHeading />

*/
class ProductsHeading extends React.Component {

  state = {
    value: WP_Shopify.settings.productsHeading,
    valueHasChanged: false,
    submitElement: jQuery("#submitSettings")
  }

  updateValue = newValue => {

    if (newValue !== this.state.value) {
      this.state.valueHasChanged = true;
    }

    this.setState({
      value: newValue
    });

  }

  onProductsHeadingBlur = async value => {

    // If the user hasn't selected a new color, just exit
    if (!this.state.valueHasChanged) {
      return this.state.value;
    }

    showLoader(this.state.submitElement);

    // Updates DB with the new color
    var [updateError, updateResponse] = await to( updateSettingProductsHeading({ value: this.state.value }) );

    showNotice(updateError, updateResponse);

    hideLoader(this.state.submitElement);

  }

  render() {

    return (
      <TextControl
          value={ this.state.value }
          onChange={ this.updateValue }
          onBlur={ this.onProductsHeadingBlur }
          aria-describedby="wps-products-heading-toggle"
          disabled={ !toBoolean(WP_Shopify.settings.productsHeadingToggle) }

      />
    );

  }

}


/*

Init color pickers

*/
function initProductsHeading() {

  ReactDOM.render(
    <ProductsHeading />,
    document.getElementById("wps-settings-products-heading")
  );

}

export {
  initProductsHeading
}
