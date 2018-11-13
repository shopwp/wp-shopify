import { TextControl } from '@wordpress/components';
import React from 'react';
import ReactDOM from 'react-dom';
import { toBoolean } from '../../utils/utils';
import { convertToRealSize } from '../../utils/utils-data';


/*

<ProductsImagesSizingHeight />

*/
class ProductsImagesSizingHeight extends React.Component {

  state = {
    value: WP_Shopify.settings.productsImagesSizingHeight === 0 ? 'auto' : WP_Shopify.settings.productsImagesSizingHeight
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
          aria-describedby="wps-products-images-sizing-toggle"
          disabled={ !toBoolean(WP_Shopify.settings.productsImagesSizingToggle) }

      />
    );

  }

}


/*

Init color pickers

*/
function initProductsImagesSizingHeight() {

  ReactDOM.render(
    <ProductsImagesSizingHeight />,
    document.getElementById("wps-settings-products-images-sizing-height")
  );

}

export {
  initProductsImagesSizingHeight
}
