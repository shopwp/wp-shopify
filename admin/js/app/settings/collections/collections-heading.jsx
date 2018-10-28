import { TextControl } from '@wordpress/components';
import React from 'react';
import ReactDOM from 'react-dom';
import to from 'await-to-js';
import { updateSettingCollectionsHeading } from "../../ws/ws-api";
import { showNotice } from "../../notices/notices";
import { showLoader, hideLoader } from "../../utils/utils";
import { toBoolean } from '../../utils/utils';


/*

<CollectionsHeading />

*/
class CollectionsHeading extends React.Component {

  state = {
    value: WP_Shopify.settings.collectionsHeading,
    valueHasChanged: false,
    submitButton: jQuery("#submitSettings"),
    toggleElement: jQuery('#wps-collections-heading-toggle')
  }

  updateValue = newValue => {

    if (newValue !== this.state.value) {
      this.state.valueHasChanged = true;
    }

    this.setState({
      value: newValue
    });

  }

  onCollectionsHeadingBlur = async value => {

    // If the user hasn't selected a new color, just exit
    if (!this.state.valueHasChanged) {
      return this.state.value;
    }

    showLoader(this.state.submitButton);

    // Updates DB with the new color
    var [updateError, updateResponse] = await to( updateSettingCollectionsHeading({ value: this.state.value }) );

    showNotice(updateError, updateResponse);

    hideLoader(this.state.submitButton);

  }

  render() {

    return (
      <TextControl
          value={ this.state.value }
          onChange={ this.updateValue }
          onBlur={ this.onCollectionsHeadingBlur }
          aria-describedby="wps-collections-heading-toggle"
          disabled={ !toBoolean(WP_Shopify.settings.collectionsHeadingToggle) }
      />
    );

  }

}


/*

Init Collections heading

*/
function initCollectionsHeading() {

  ReactDOM.render(
    <CollectionsHeading />,
    document.getElementById("wps-settings-collections-heading")
  );

}

export {
  initCollectionsHeading
}
