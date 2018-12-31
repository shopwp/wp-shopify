import { CheckboxControl } from '@wordpress/components';
import React from 'react';
import ReactDOM from 'react-dom';
import { toBoolean } from '../../utils/utils';


/*

<CartShowFixedCartTab />

*/
class CartShowFixedCartTab extends React.Component {

	state = {
		checked: WP_Shopify.settings.cartShowFixedCartTab
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

Init <CartShowFixedCartTab />

*/
function initCartShowFixedCartTab() {

  ReactDOM.render(
    <CartShowFixedCartTab />,
    document.getElementById("wps-settings-cart-show-fixed-cart-tab")
  );

}

export {
  initCartShowFixedCartTab
}
