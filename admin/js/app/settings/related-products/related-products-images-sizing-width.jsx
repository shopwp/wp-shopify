import { TextControl } from '@wordpress/components';
import React from 'react';
import ReactDOM from 'react-dom';
import to from 'await-to-js';
import toInteger from 'lodash/toInteger';
import { updateSettingRelatedProductsImagesSizingWidth } from "../../ws/ws-api";
import { showNotice } from "../../notices/notices";
import { showLoader, hideLoader } from "../../utils/utils";
import { toBoolean } from '../../utils/utils';


/*

<RelatedProductsImagesSizingWidth />

*/
class RelatedProductsImagesSizingWidth extends React.Component {

  state = {
    value: WP_Shopify.settings.relatedProductsImagesSizingWidth === 0 ? 'auto' : WP_Shopify.settings.relatedProductsImagesSizingWidth,
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

  onRelatedProductsImagesSizingWidthBlur = async value => {

    // If the user hasn't selected a new color, just exit
    if (!this.state.valueHasChanged) {
      return this.state.value;
    }

    showLoader(this.state.submitElement);

    // Updates DB with the new color
    var [updateError, updateResponse] = await to( updateSettingRelatedProductsImagesSizingWidth({ value: toInteger(this.state.value) }) );

    showNotice(updateError, updateResponse);

    hideLoader(this.state.submitElement);

  }

  render() {

    return (
      <TextControl
          type="text"
          value={ this.state.value }
          onChange={ this.updateValue }
          onBlur={ this.onRelatedProductsImagesSizingWidthBlur }
          aria-describedby="wps-related-products-images-sizing-toggle"
          disabled={ !toBoolean(WP_Shopify.settings.relatedProductsImagesSizingToggle) }

      />
    );

  }

}


/*

Init color pickers

*/
function initRelatedProductsImagesSizingWidth() {

  ReactDOM.render(
    <RelatedProductsImagesSizingWidth />,
    document.getElementById("wps-settings-related-products-images-sizing-width")
  );

}

export {
  initRelatedProductsImagesSizingWidth
}
