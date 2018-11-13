import { CheckboxControl, Notice } from '@wordpress/components';
import React from 'react';
import ReactDOM from 'react-dom';
import { toBoolean } from '../../utils/utils';


/*

<ProductsHeading />

*/
class ProductsShowPriceRange extends React.Component {

	state = {
		checked: toBoolean(WP_Shopify.settings.productsShowPriceRange)
	}

	onChangeHandle = checked => {
		this.setState({ checked: !this.state.checked });
	}

  render() {

    return (
			<CheckboxControl
        checked={ this.state.checked }
        onChange={ this.onChangeHandle }
    	/>
    );

  }

}


/*

Init <ProductsShowPriceRange />

*/
function initProductsShowPriceRange() {

  ReactDOM.render(
    <ProductsShowPriceRange />,
    document.getElementById("wps-settings-products-show-price-range")
  );

}

export {
  initProductsShowPriceRange
}
