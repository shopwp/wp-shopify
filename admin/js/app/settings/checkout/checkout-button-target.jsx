import { SelectControl } from '@wordpress/components';
import React from 'react';
import ReactDOM from 'react-dom';
import { checkoutButtonTargets } from '../settings.jsx';


/*

<CheckoutButtonTarget />

*/
class CheckoutButtonTarget extends React.Component {

  state = {
    value: WP_Shopify.settings.checkoutButtonTarget === false ? 'none' : WP_Shopify.settings.checkoutButtonTarget
  }

  onUpdateHandle = newValue => {

    this.setState({
      value: newValue
    });

  }

  render() {

    return (
      <SelectControl
        value={ this.state.value }
        options={ checkoutButtonTargets() }
        onChange={ this.onUpdateHandle }
      />
    );

  }

}


/*

Init color pickers

*/
function initCheckoutButtonTarget() {

  ReactDOM.render(
    <CheckoutButtonTarget />,
    document.getElementById("wps-settings-checkout-button-target")
  );

}

export {
  initCheckoutButtonTarget
}
