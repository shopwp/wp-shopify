import { TextControl } from '@wordpress/components';
import React from 'react';
import ReactDOM from 'react-dom';
import to from 'await-to-js';
import { toBoolean } from '../../utils/utils';
import { updateSettingRelatedProductsHeading } from "../../ws/ws-api";
import { showNotice } from "../../notices/notices";
import { showLoader, hideLoader } from "../../utils/utils";


/*

<RelatedProductsHeading />

*/
class RelatedProductsHeading extends React.Component {

  state = {
    value: WP_Shopify.settings.relatedProductsHeading,
    valueHasChanged: false,
    submitButton: jQuery("#submitSettings")
  }

  updateValue = newValue => {

    if (newValue !== this.state.value) {
      this.state.valueHasChanged = true;
    }

    this.setState({
      value: newValue
    });

  }

  onRelatedProductsHeadingBlur = async value => {

    // If the user hasn't selected a new color, just exit
    if (!this.state.valueHasChanged) {
      return this.state.value;
    }

    showLoader(this.state.submitButton);

    // Updates DB with the new color
    var [updateError, updateResponse] = await to( updateSettingRelatedProductsHeading({ value: this.state.value }) );

    showNotice(updateError, updateResponse);

    hideLoader(this.state.submitButton);

  }

  render() {

    return (
      <TextControl
          value={ this.state.value }
          onChange={ this.updateValue }
          onBlur={ this.onRelatedProductsHeadingBlur }
          aria-describedby="wps-related-products-heading-toggle"
          disabled={ !toBoolean(WP_Shopify.settings.relatedProductsHeadingToggle) }
      />
    );

  }

}


/*

Init color pickers

*/
function initRelatedProductsHeading() {

  ReactDOM.render(
    <RelatedProductsHeading />,
    document.getElementById("wps-settings-related-products-heading")
  );

}

export {
  initRelatedProductsHeading
}
