import { SelectControl } from '@wordpress/components';
import React from 'react';
import ReactDOM from 'react-dom';
import { toBoolean } from '../../utils/utils';
import { imageScaleTypes } from '../settings.jsx';


/*

<ProductsImagesSizingScale />

*/
class ProductsImagesSizingScale extends React.Component {

  state = {
    value: WP_Shopify.settings.productsImagesSizingScale === false ? 'none' : WP_Shopify.settings.productsImagesSizingScale
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
        aria-describedby="wps-products-images-sizing-toggle"
        disabled={ !toBoolean(WP_Shopify.settings.productsImagesSizingToggle) }

      />
    );

  }

}


/*

Init color pickers

*/
function initProductsImagesSizingScale() {

  ReactDOM.render(
    <ProductsImagesSizingScale />,
    document.getElementById("wps-settings-products-images-sizing-scale")
  );

}

export {
  initProductsImagesSizingScale
}
