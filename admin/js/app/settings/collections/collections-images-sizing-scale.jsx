import { SelectControl } from '@wordpress/components';
import React from 'react';
import ReactDOM from 'react-dom';
import to from 'await-to-js';
import toInteger from 'lodash/toInteger';
import { updateSettingCollectionsImagesSizingScale } from "../../ws/ws-api";
import { showNotice } from "../../notices/notices";
import { showLoader, hideLoader } from "../../utils/utils";
import { toBoolean } from '../../utils/utils';


function scaleTypes() {

  return [
    {
      label: 'None',
      value: false
    },
    {
      label: '2',
      value: 2
    },
    {
      label: '3',
      value: 3
    }
  ];

}

/*

<CollectionsImagesSizingScale />

*/
class CollectionsImagesSizingScale extends React.Component {

  state = {
    value: WP_Shopify.settings.collectionsImagesSizingScale === false ? 'none' : WP_Shopify.settings.collectionsImagesSizingScale,
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

  onCollectionsImagesSizingScaleBlur = async value => {

    // If selected the same value, just exit
    if ( !this.state.valueHasChanged ) {
      return this.state.value;
    }

    showLoader(this.state.submitElement);

    var [updateError, updateResponse] = await to( updateSettingCollectionsImagesSizingScale({
      value: toInteger(this.state.value)
    }));

    showNotice(updateError, updateResponse);

    hideLoader(this.state.submitElement);

  }

  render() {

    return (
      <SelectControl
        value={ this.state.value }
        options={ scaleTypes() }
        onChange={ this.updateValue }
        onBlur={ this.onCollectionsImagesSizingScaleBlur }
        aria-describedby="wps-collections-images-sizing-toggle"
        disabled={ !toBoolean(WP_Shopify.settings.collectionsImagesSizingToggle) }

      />
    );

  }

}


/*

Init color pickers

*/
function initCollectionsImagesSizingScale() {

  ReactDOM.render(
    <CollectionsImagesSizingScale />,
    document.getElementById("wps-settings-collections-images-sizing-scale")
  );

}

export {
  initCollectionsImagesSizingScale
}
