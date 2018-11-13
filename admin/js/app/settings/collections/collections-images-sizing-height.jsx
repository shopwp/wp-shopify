import { TextControl } from '@wordpress/components';
import React from 'react';
import ReactDOM from 'react-dom';
import { toBoolean } from '../../utils/utils';
import { convertToRealSize } from '../../utils/utils-data';


/*

<CollectionsImagesSizingHeight />

*/
class CollectionsImagesSizingHeight extends React.Component {

  state = {
    value: WP_Shopify.settings.collectionsImagesSizingHeight === 0 ? 'auto' : WP_Shopify.settings.collectionsImagesSizingHeight
  }

  onUpdateHandle = newValue => {

    this.setState({
      value: newValue
    });

  }

  onBlurHandle = event => {

    this.setState({
      value: convertToRealSize(event.currentTarget.value)
    });

  }

  render() {

    return (
      <TextControl
          type="text"
          value={ this.state.value }
          onChange={ this.onUpdateHandle }
          onBlur={ this.onBlurHandle }
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
