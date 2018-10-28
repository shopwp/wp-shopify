import { CheckboxControl } from '@wordpress/components';
import React from 'react';
import ReactDOM from 'react-dom';
import { toBoolean } from '../../utils/utils';
import { updateSettingCheckoutEnableCustomCheckoutDomain } from "../../ws/ws-api";
import { showNotice } from "../../notices/notices";
import { showLoader, hideLoader } from "../../utils/utils";
import to from 'await-to-js';


/*

<ProductsHeading />

*/
class ToggleCustomCheckoutDomain extends React.Component {

	state = {
		submitElement: jQuery("#submitSettings"),
		checked: toBoolean(WP_Shopify.settings.enableCustomCheckoutDomain)
	}

	onToggle = async state => {

		this.setState( state => ( { checked: !this.state.checked } ) );

		showLoader(this.state.submitElement);

		var [updateError, updateResponse] = await to( updateSettingCheckoutEnableCustomCheckoutDomain({ value: !this.state.checked }) );

		showNotice(updateError, updateResponse);

    hideLoader(this.state.submitElement);

	}


  render() {

    return (
			<CheckboxControl
        checked={ this.state.checked }
        onChange={ this.onToggle }
    	/>
    );

  }

}


/*

Init <ToggleCustomCheckoutDomain />

*/
function initEnableCustomCheckoutDomain() {

  ReactDOM.render(
    <ToggleCustomCheckoutDomain />,
    document.getElementById("wps-enable-custom-checkout-domain")
  );

}

export {
  initEnableCustomCheckoutDomain
}
