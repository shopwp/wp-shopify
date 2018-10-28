import { SelectControl } from '@wordpress/components';
import React from 'react';
import ReactDOM from 'react-dom';
import to from 'await-to-js';
import { updateSettingCollectionsImagesSizingCrop } from "../../ws/ws-api";
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

<CollectionsImagesSizingCrop />

*/
class CollectionsImagesSizingCrop extends React.Component {

  state = {
    value: WP_Shopify.settings.collectionsImagesSizingCrop,
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

  onCollectionsImagesSizingCropBlur = async value => {

    // If selected the same value, just exit
    if ( !this.state.valueHasChanged ) {
      return this.state.value;
    }

    showLoader(this.state.submitElement);

    var [updateError, updateResponse] = await to( updateSettingCollectionsImagesSizingCrop({ value: this.state.value }) );

    showNotice(updateError, updateResponse);

    hideLoader(this.state.submitElement);

  }

  render() {

    return (
      <SelectControl
        value={ this.state.value }
        options={ cropTypes() }
        onChange={ this.updateValue }
        onBlur={ this.onCollectionsImagesSizingCropBlur }
        aria-describedby="wps-collections-images-sizing-toggle"
        disabled={ !toBoolean(WP_Shopify.settings.collectionsImagesSizingToggle) }

      />
    );

  }

}


/*

Init color pickers

*/
function initCollectionsImagesSizingCrop() {

  ReactDOM.render(
    <CollectionsImagesSizingCrop />,
    document.getElementById("wps-settings-collections-images-sizing-crop")
  );

}

export {
  initCollectionsImagesSizingCrop
}
