import { FormToggle } from '@wordpress/components';
import React from 'react';
import ReactDOM from 'react-dom';
import { toBoolean } from '../../utils/utils';
import { updateSettingProductsImagesSizingToggle } from "../../ws/ws-api";
import { showNotice } from "../../notices/notices";
import { showLoader, hideLoader } from "../../utils/utils";
import to from 'await-to-js';


/*

<ProductsImagesSizing />

*/
class ProductsImagesSizingToggle extends React.Component {

	state = {
		submitButton: jQuery("#submitSettings"),
		checked: toBoolean(WP_Shopify.settings.productsImagesSizingToggle)
	}


	onToggle = async state => {

		this.setState( state => ( { checked: ! this.state.checked } ) );
		jQuery('[aria-describedby="wps-products-images-sizing-toggle"]').attr('disabled', this.state.checked);

		var [updateError, updateResponse] = await to( updateSettingProductsImagesSizingToggle({
			value: jQuery('#wps-products-images-sizing-toggle').prop('checked')
		}) );

	}


  render() {

    return (
      <FormToggle
				checked={ this.state.checked }
				onChange={ this.onToggle }
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
