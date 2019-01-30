import { CheckboxControl, Notice } from '@wordpress/components';
import React from 'react';
import ReactDOM from 'react-dom';
import { toBoolean } from '../../utils/utils';


/*

<ProductsHeading />

*/
class PricingShowPriceRange extends React.Component {

	state = {
		checked: toBoolean(WP_Shopify.settings.pricingShowPriceRange)
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

Init <PricingShowPriceRange />

*/
function initPricingShowPriceRange() {

  ReactDOM.render(
    <PricingShowPriceRange />,
    document.getElementById("wps-settings-pricing-show-price-range")
  );

}

export {
  initPricingShowPriceRange
}
