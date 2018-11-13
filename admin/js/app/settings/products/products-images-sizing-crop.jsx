import { SelectControl } from '@wordpress/components';
import React from 'react';
import ReactDOM from 'react-dom';
import { toBoolean } from '../../utils/utils';
import { imageCropTypes } from '../settings.jsx';

/*

<ProductsImagesSizingCrop />

*/
class ProductsImagesSizingCrop extends React.Component {

  state = {
    value: WP_Shopify.settings.productsImagesSizingCrop
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
        aria-describedby="wps-products-images-sizing-toggle"
        disabled={ !toBoolean(WP_Shopify.settings.productsImagesSizingToggle) }

      />
    );

  }

}


/*

Init color pickers

*/
function initProductsImagesSizingCrop() {

  ReactDOM.render(
    <ProductsImagesSizingCrop />,
    document.getElementById("wps-settings-products-images-sizing-crop")
  );

}

export {
  initProductsImagesSizingCrop
}
