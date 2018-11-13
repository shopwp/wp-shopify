import { FormToggle } from '@wordpress/components';
import React from 'react';
import ReactDOM from 'react-dom';
import { toBoolean } from '../../utils/utils';



/*

<ProductsImagesSizing />

*/
class ProductsImagesSizingToggle extends React.Component {

	state = {
		checked: toBoolean(WP_Shopify.settings.productsImagesSizingToggle)
	}


	onToggleHandle = async state => {

		this.setState({ checked: ! this.state.checked });

		jQuery('[aria-describedby="wps-products-images-sizing-toggle"]').attr('disabled', this.state.checked);

	}


  render() {

    return (
      <FormToggle
				checked={ this.state.checked }
				onChange={ this.onToggleHandle }
				id="wps-products-images-sizing-toggle"
			/>
    );

  }

}


/*

Init <ProductsImagesSizingToggle />

*/
function initProductsImagesSizingToggle() {

  ReactDOM.render(
    <ProductsImagesSizingToggle />,
    document.getElementById("wps-settings-products-images-sizing-toggle")
  );

}

export {
  initProductsImagesSizingToggle
}
