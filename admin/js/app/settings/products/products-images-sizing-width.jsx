import { TextControl } from '@wordpress/components';
import React from 'react';
import ReactDOM from 'react-dom';
import { toBoolean } from '../../utils/utils';
import { convertToRealSize } from '../../utils/utils-data';


/*

<ProductsImagesSizingWidth />

*/
class ProductsImagesSizingWidth extends React.Component {

  state = {
    value: WP_Shopify.settings.productsImagesSizingWidth === 0 ? 'auto' : WP_Shopify.settings.productsImagesSizingWidth
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
function initProductsImagesSizingWidth() {

  ReactDOM.render(
    <ProductsImagesSizingWidth />,
    document.getElementById("wps-settings-products-images-sizing-width")
  );

}

export {
  initProductsImagesSizingWidth
}
