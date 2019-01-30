import { CheckboxControl, Notice } from '@wordpress/components';
import { withState } from '@wordpress/compose';
import React from 'react';
import ReactDOM from 'react-dom';
import { toBoolean } from '../../utils/utils';


/*

<ProductsHeading />

*/
class SynchronousSync extends React.Component {

	state = {
		checked: toBoolean(WP_Shopify.settings.synchronousSync)
	}

	onChangeHandle = checked => {
		this.setState({ checked: !this.state.checked });
	}

  render() {

    return (
			<CheckboxControl
        checked={ this.state.checked }
        onChange={ this.onChangeHandle }
				id="wps-settings-syncing-synchronous"
    	/>
    );

  }

}



/*

Init <SynchronousSync />

*/
function initSynchronousSync() {

  ReactDOM.render(
    <SynchronousSync />,
    document.getElementById("wps-settings-syncing-synchronous")
  );

}

export {
  initSynchronousSync
}
