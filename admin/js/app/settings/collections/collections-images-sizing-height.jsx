import { TextControl } from '@wordpress/components';
import React from 'react';
import ReactDOM from 'react-dom';
import to from 'await-to-js';
import toInteger from 'lodash/toInteger';
import { updateSettingCollectionsImagesSizingHeight } from "../../ws/ws-api";
import { showNotice } from "../../notices/notices";
import { showLoader, hideLoader } from "../../utils/utils";
import { toBoolean } from '../../utils/utils';


/*

<CollectionsImagesSizingHeight />

*/
class CollectionsImagesSizingHeight extends React.Component {

  state = {
    value: WP_Shopify.settings.collectionsImagesSizingHeight === 0 ? 'auto' : WP_Shopify.settings.collectionsImagesSizingHeight,
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

  onCollectionsImagesSizingHeightBlur = async value => {

    // If the user hasn't selected a new color, just exit
    if (!this.state.valueHasChanged) {
      return this.state.value;
    }


    showLoader(this.state.submitElement);

    var [updateError, updateResponse] = await to( updateSettingCollectionsImagesSizingHeight({ value: toInteger(this.state.value) }) );

    showNotice(updateError, updateResponse);

    hideLoader(this.state.submitElement);

  }

  render() {

    return (
      <TextControl
          type="text"
          value={ this.state.value }
          onChange={ this.updateValue }
          onBlur={ this.onCollectionsImagesSizingHeightBlur }
          aria-describedby="wps-collections-images-sizing-toggle"
          disabled={ !toBoolean(WP_Shopify.settings.collectionsImagesSizingToggle) }

      />
    );

  }

}


/*

Init color pickers

*/
function initCollectionsImagesSizingHeight() {

  ReactDOM.render(
    <CollectionsImagesSizingHeight />,
    document.getElementById("wps-settings-collections-images-sizing-height")
  );

}

export {
  initCollectionsImagesSizingHeight
}
