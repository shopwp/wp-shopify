import { TextControl } from '@wordpress/components';
import React from 'react';
import ReactDOM from 'react-dom';
import to from 'await-to-js';
import toInteger from 'lodash/toInteger';
import { updateSettingRelatedProductsImagesSizingHeight } from "../../ws/ws-api";
import { showNotice } from "../../notices/notices";
import { showLoader, hideLoader } from "../../utils/utils";
import { toBoolean } from '../../utils/utils';


/*

<RelatedProductsImagesSizingHeight />

*/
class RelatedProductsImagesSizingHeight extends React.Component {

  state = {
    value: WP_Shopify.settings.relatedProductsImagesSizingHeight === 0 ? 'auto' : WP_Shopify.settings.relatedProductsImagesSizingHeight,
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

  onRelatedProductsImagesSizingHeightBlur = async value => {

    // If the user hasn't selected a new color, just exit
    if (!this.state.valueHasChanged) {
      return this.state.value;
    }

    showLoader(this.state.submitElement);

    var [updateError, updateResponse] = await to( updateSettingRelatedProductsImagesSizingHeight({ value: toInteger(this.state.value) }) );

    showNotice(updateError, updateResponse);

    hideLoader(this.state.submitElement);

  }

  render() {

    return (
      <TextControl
          type="text"
          value={ this.state.value }
          onChange={ this.updateValue }
          onBlur={ this.onRelatedProductsImagesSizingHeightBlur }
          aria-describedby="wps-related-products-images-sizing-toggle"
          disabled={ !toBoolean(WP_Shopify.settings.relatedProductsImagesSizingToggle) }

      />
    );

  }

}


/*

Init color pickers

*/
function initRelatedProductsImagesSizingHeight() {

  ReactDOM.render(
    <RelatedProductsImagesSizingHeight />,
    document.getElementById("wps-settings-related-products-images-sizing-height")
  );

}

export {
  initRelatedProductsImagesSizingHeight
}
