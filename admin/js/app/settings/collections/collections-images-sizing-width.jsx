import { TextControl } from '@wordpress/components';
import React from 'react';
import ReactDOM from 'react-dom';
import to from 'await-to-js';
import toInteger from 'lodash/toInteger';
import { updateSettingCollectionsImagesSizingWidth } from "../../ws/ws-api";
import { showNotice } from "../../notices/notices";
import { showLoader, hideLoader } from "../../utils/utils";
import { toBoolean } from '../../utils/utils';


/*

<CollectionsImagesSizingWidth />

*/
class CollectionsImagesSizingWidth extends React.Component {

  state = {
    value: WP_Shopify.settings.collectionsImagesSizingWidth === 0 ? 'auto' : WP_Shopify.settings.collectionsImagesSizingWidth,
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

  onCollectionsImagesSizingWidthBlur = async value => {

    // If the user hasn't selected a new color, just exit
    if (!this.state.valueHasChanged) {
      return this.state.value;
    }

    showLoader(this.state.submitElement);

    // Updates DB with the new color
    var [updateError, updateResponse] = await to( updateSettingCollectionsImagesSizingWidth({ value: toInteger(this.state.value) }) );

    showNotice(updateError, updateResponse);

    hideLoader(this.state.submitElement);

  }

  render() {

    return (
      <TextControl
          type="text"
          value={ this.state.value }
          onChange={ this.updateValue }
          onBlur={ this.onCollectionsImagesSizingWidthBlur }
          aria-describedby="wps-collections-images-sizing-toggle"
          disabled={ !toBoolean(WP_Shopify.settings.collectionsImagesSizingToggle) }

      />
    );

  }

}


/*

Init color pickers

*/
function initCollectionsImagesSizingWidth() {

  ReactDOM.render(
    <CollectionsImagesSizingWidth />,
    document.getElementById("wps-settings-collections-images-sizing-width")
  );

}

export {
  initCollectionsImagesSizingWidth
}
