import { TextControl } from '@wordpress/components';
import React from 'react';
import ReactDOM from 'react-dom';
import { toBoolean } from '../../utils/utils';


/*

<ProductsHeading />

*/
class ProductsHeading extends React.Component {

  state = {
    value: WP_Shopify.settings.productsHeading
  }

  onUpdateHandle = newValue => {

    this.setState({
      value: newValue
    });

  }

  render() {

    return (
      <TextControl
          value={ this.state.value }
          onChange={ this.onUpdateHandle }
          aria-describedby="wps-products-heading-toggle"
          disabled={ !toBoolean(WP_Shopify.settings.productsHeadingToggle) }

      />
    );

  }

}


/*

Init color pickers

*/
function initProductsHeading() {

  ReactDOM.render(
    <ProductsHeading />,
    document.getElementById("wps-settings-products-heading")
  );

}

export {
  initProductsHeading
}
