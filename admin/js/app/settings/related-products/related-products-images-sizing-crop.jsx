import { SelectControl } from '@wordpress/components';
import React from 'react';
import ReactDOM from 'react-dom';
import { toBoolean } from '../../utils/utils';
import { imageCropTypes } from '../settings.jsx';


/*

<RelatedProductsImagesSizingCrop />

*/
class RelatedProductsImagesSizingCrop extends React.Component {

  state = {
    value: WP_Shopify.settings.relatedProductsImagesSizingCrop
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
        aria-describedby="wps-related-products-images-sizing-toggle"
        disabled={ !toBoolean(WP_Shopify.settings.relatedProductsImagesSizingToggle) }

      />
    );

  }

}


/*

Init color pickers

*/
function initRelatedProductsImagesSizingCrop() {

  ReactDOM.render(
    <RelatedProductsImagesSizingCrop />,
    document.getElementById("wps-settings-related-products-images-sizing-crop")
  );

}

export {
  initRelatedProductsImagesSizingCrop
}
