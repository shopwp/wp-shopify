import { SelectControl } from '@wordpress/components';
import React from 'react';
import ReactDOM from 'react-dom';
import { toBoolean } from '../../utils/utils';
import { imageCropTypes } from '../settings.jsx';


/*

<CollectionsImagesSizingCrop />

*/
class CollectionsImagesSizingCrop extends React.Component {

  state = {
    value: WP_Shopify.settings.collectionsImagesSizingCrop
  }

  onUpdateHandle = newValue => {

    this.setState({
      value: newValue
    });

  }

  render() {

    return (
      <SelectControl
        value={ this.state.value }
        options={ imageCropTypes() }
        onChange={ this.onUpdateHandle }
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
