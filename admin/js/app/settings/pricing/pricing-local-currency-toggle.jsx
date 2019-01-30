import { FormToggle } from '@wordpress/components';
import React from 'react';
import ReactDOM from 'react-dom';
import { toBoolean } from '../../utils/utils';


/*

<ProductsHeading />

*/
class PricingLocalCurrencyToggle extends React.Component {

	state = {
		checked: toBoolean(WP_Shopify.settings.pricingLocalCurrencyToggle)
	}


	onToggleHandle = async state => {

		this.setState({ checked: ! this.state.checked });

		jQuery('[aria-describedby="wps-settings-pricing-local-currency-toggle"]').attr('disabled', this.state.checked);

	}


	render() {

		return (
			<FormToggle
				checked={ this.state.checked }
				onChange={ this.onToggleHandle }
				id="wps-pricing-local-currency-toggle"
			/>
		);

	}
}


/*

Init <ProductsShowLocalCurrency />

*/
function initPricingLocalCurrencyToggle() {

  ReactDOM.render(
    <PricingLocalCurrencyToggle />,
    document.getElementById("wps-settings-pricing-local-currency-toggle")
  );

}

export {
  initPricingLocalCurrencyToggle
}
