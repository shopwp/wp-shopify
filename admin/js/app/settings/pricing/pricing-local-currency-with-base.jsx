import { CheckboxControl, Notice } from '@wordpress/components';
import { withState } from '@wordpress/compose';
import React from 'react';
import ReactDOM from 'react-dom';
import { toBoolean } from '../../utils/utils';


/*

<ProductsHeading />

*/
class PricingLocalCurrencyWithBase extends React.Component {

	state = {
		checked: toBoolean(WP_Shopify.settings.pricingLocalCurrencyWithBase)
	}

	onChangeHandle = checked => {
		this.setState({ checked: !this.state.checked });
	}

  render() {

    return (
			<CheckboxControl
        checked={ this.state.checked }
        onChange={ this.onChangeHandle }
				aria-describedby="wps-settings-pricing-local-currency-toggle"
				disabled={ !toBoolean(WP_Shopify.settings.pricingLocalCurrencyToggle) }
				id="wps-pricing-local-currency-with-base"
    	/>
    );

  }

}


/*

Init <PricingLocalCurrencyWithBase />

*/
function initPricingLocalCurrencyWithBase() {

  ReactDOM.render(
    <PricingLocalCurrencyWithBase />,
    document.getElementById("wps-settings-pricing-local-currency-with-base")
  );

}

export {
  initPricingLocalCurrencyWithBase
}
