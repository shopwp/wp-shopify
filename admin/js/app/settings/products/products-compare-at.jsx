import { CheckboxControl, Notice } from '@wordpress/components';
import { withState } from '@wordpress/compose';
import React from 'react';
import ReactDOM from 'react-dom';
import { toBoolean } from '../../utils/utils';


/*

<ProductsHeading />

*/
class ProductsCompareAt extends React.Component {

	state = {
		checked: toBoolean(WP_Shopify.settings.productsCompareAt)
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

Init <ProductsCompareAt />

*/
function initProductsCompareAt() {

  ReactDOM.render(
    <ProductsCompareAt />,
    document.getElementById("wps-settings-products-compare-at")
  );

}

export {
  initProductsCompareAt
}
