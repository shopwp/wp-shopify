import { FormToggle } from '@wordpress/components';
import React from 'react';
import ReactDOM from 'react-dom';
import { toBoolean } from '../../utils/utils';


/*

<ProductsHeading />

*/
class ProductsHeadingToggle extends React.Component {

	state = {
		checked: toBoolean(WP_Shopify.settings.productsHeadingToggle)
	}

	onChangeHandle = async state => {

		this.setState( state => ( { checked: ! this.state.checked } ) );

		jQuery('input[aria-describedby="wps-products-heading-toggle"]').attr('disabled', this.state.checked);

	}


  render() {

    return (
      <FormToggle
				checked={ this.state.checked }
				onChange={ this.onChangeHandle }
				id="wps-products-heading-toggle"
			/>
    );

  }

}



/*

Init <ProductsHeadingToggle />

*/
function initProductsHeadingToggle() {

  ReactDOM.render(
    <ProductsHeadingToggle />,
    document.getElementById("wps-settings-products-heading-toggle")
  );

}

export {
  initProductsHeadingToggle
}
