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
		checked: toBoolean(WP_Shopify.settings.enableCustomCheckoutDomain)
	}

	onUpdateHandle = state => {

		this.setState({
			checked: !this.state.checked
		});

	}


  render() {

    return (
			<CheckboxControl
        checked={ this.state.checked }
        onChange={ this.onUpdateHandle }
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
