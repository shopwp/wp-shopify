import { SelectControl } from '@wordpress/components';
import React from 'react';
import ReactDOM from 'react-dom';
import to from 'await-to-js';
import { updateSettingRelatedProductsImagesSizingCrop } from "../../ws/ws-api";
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

<RelatedProductsImagesSizingCrop />

*/
class RelatedProductsImagesSizingCrop extends React.Component {

  state = {
    value: WP_Shopify.settings.relatedProductsImagesSizingCrop,
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

  onRelatedProductsImagesSizingCropBlur = async value => {

    // If selected the same value, just exit
    if ( !this.state.valueHasChanged ) {
      return this.state.value;
    }

    showLoader(this.state.submitElement);

    var [updateError, updateResponse] = await to( updateSettingRelatedProductsImagesSizingCrop({ value: this.state.value }) );

    showNotice(updateError, updateResponse);

    hideLoader(this.state.submitElement);

  }

  render() {

    return (
      <SelectControl
        value={ this.state.value }
        options={ cropTypes() }
        onChange={ this.updateValue }
        onBlur={ this.onRelatedProductsImagesSizingCropBlur }
        aria-describedby="wps-related-products-images-sizing-toggle"
        disabled={ !toBoolean(WP_Shopify.settings.relatedProductsImagesSizingToggle) }

      />
    );

  }

}


/*

Init color pickers

*/
function initRelatedProductsImagesSizingCrop() {

  ReactDOM.render(
    <RelatedProductsImagesSizingCrop />,
    document.getElementById("wps-settings-related-products-images-sizing-crop")
  );

}

export {
  initRelatedProductsImagesSizingCrop
}
