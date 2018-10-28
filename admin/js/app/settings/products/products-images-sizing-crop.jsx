import { SelectControl } from '@wordpress/components';
import React from 'react';
import ReactDOM from 'react-dom';
import to from 'await-to-js';
import { updateSettingProductsImagesSizingCrop } from "../../ws/ws-api";
import { showNotice } from "../../notices/notices";
import { showLoader, hideLoader } from "../../utils/utils";
import { toBoolean } from '../../utils/utils';


function cropTypes() {

  return [
    {
      label: 'None',
      value: 'none'
    },
    {
      label: 'Top',
      value: 'top'
    },
    {
      label: 'Center',
      value: 'center'
    },
    {
      label: 'Bottom',
      value: 'bottom'
    },
    {
      label: 'Left',
      value: 'left'
    },
    {
      label: 'Right',
      value: 'right'
    }
  ];

}


/*

<ProductsImagesSizingCrop />

*/
class ProductsImagesSizingCrop extends React.Component {

  state = {
    value: WP_Shopify.settings.productsImagesSizingCrop,
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

  onProductsImagesSizingCropBlur = async value => {

    // If selected the same value, just exit
    if ( !this.state.valueHasChanged ) {
      return this.state.value;
    }

    showLoader(this.state.submitElement);

    var [updateError, updateResponse] = await to( updateSettingProductsImagesSizingCrop({ value: this.state.value }) );

    showNotice(updateError, updateResponse);

    hideLoader(this.state.submitElement);

  }

  render() {

    return (
      <SelectControl
        value={ this.state.value }
        options={ cropTypes() }
        onChange={ this.updateValue }
        onBlur={ this.onProductsImagesSizingCropBlur }
        aria-describedby="wps-products-images-sizing-toggle"
        disabled={ !toBoolean(WP_Shopify.settings.productsImagesSizingToggle) }

      />
    );

  }

}


/*

Init color pickers

*/
function initProductsImagesSizingCrop() {

  ReactDOM.render(
    <ProductsImagesSizingCrop />,
    document.getElementById("wps-settings-products-images-sizing-crop")
  );

}

export {
  initProductsImagesSizingCrop
}
