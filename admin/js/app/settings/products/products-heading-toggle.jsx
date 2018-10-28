import { FormToggle } from '@wordpress/components';
import React from 'react';
import ReactDOM from 'react-dom';
import { toBoolean } from '../../utils/utils';
import { updateSettingProductsHeadingToggle } from "../../ws/ws-api";
import { showNotice } from "../../notices/notices";
import { showLoader, hideLoader } from "../../utils/utils";
import to from 'await-to-js';


/*

<ProductsHeading />

*/
class ProductsHeadingToggle extends React.Component {

	state = {
		submitButton: jQuery("#submitSettings"),
		checked: toBoolean(WP_Shopify.settings.productsHeadingToggle)
	}
	

	onToggle = async state => {

		this.setState( state => ( { checked: ! this.state.checked } ) );
		jQuery('input[aria-describedby="wps-products-heading-toggle"]').attr('disabled', this.state.checked);

		var [updateError, updateResponse] = await to( updateSettingProductsHeadingToggle({
			value: jQuery('#wps-products-heading-toggle').prop('checked')
		}) );

	}


  render() {

    return (
      <FormToggle
				checked={ this.state.checked }
				onChange={ this.onToggle }
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
