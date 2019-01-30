import { CheckboxControl, Notice } from '@wordpress/components';
import { withState } from '@wordpress/compose';
import React from 'react';
import ReactDOM from 'react-dom';
import { toBoolean } from '../../utils/utils';


/*

<ProductsHeading />

*/
class PricingCompareAt extends React.Component {

	state = {
		checked: toBoolean(WP_Shopify.settings.pricingCompareAt)
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

Init <PricingCompareAt />

*/
function initPricingCompareAt() {

  ReactDOM.render(
    <PricingCompareAt />,
    document.getElementById("wps-settings-pricing-compare-at")
  );

}

export {
  initPricingCompareAt
}
