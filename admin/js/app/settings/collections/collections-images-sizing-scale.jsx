import { SelectControl } from '@wordpress/components';
import React from 'react';
import ReactDOM from 'react-dom';
import { toBoolean } from '../../utils/utils';
import { imageScaleTypes } from '../settings.jsx';


/*

<CollectionsImagesSizingScale />

*/
class CollectionsImagesSizingScale extends React.Component {

  state = {
    value: WP_Shopify.settings.collectionsImagesSizingScale === false ? 'none' : WP_Shopify.settings.collectionsImagesSizingScale
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
        options={ imageScaleTypes() }
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
function initCollectionsImagesSizingScale() {

  ReactDOM.render(
    <CollectionsImagesSizingScale />,
    document.getElementById("wps-settings-collections-images-sizing-scale")
  );

}

export {
  initCollectionsImagesSizingScale
}
