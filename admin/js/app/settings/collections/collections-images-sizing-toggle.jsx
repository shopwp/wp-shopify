import { FormToggle } from '@wordpress/components';
import React from 'react';
import ReactDOM from 'react-dom';
import { toBoolean } from '../../utils/utils';
import { updateSettingCollectionsImagesSizingToggle } from "../../ws/ws-api";
import { showNotice } from "../../notices/notices";
import { showLoader, hideLoader } from "../../utils/utils";
import to from 'await-to-js';


/*

<CollectionsImagesSizing />

*/
class CollectionsImagesSizingToggle extends React.Component {

	state = {
		submitButton: jQuery("#submitSettings"),
		checked: toBoolean(WP_Shopify.settings.collectionsImagesSizingToggle)
	}


	onToggle = async state => {

		this.setState( state => ( { checked: ! this.state.checked } ) );
		jQuery('[aria-describedby="wps-collections-images-sizing-toggle"]').attr('disabled', this.state.checked);

		var [updateError, updateResponse] = await to( updateSettingCollectionsImagesSizingToggle({
			value: jQuery('#wps-collections-images-sizing-toggle').prop('checked')
		}) );

	}


  render() {

    return (
      <FormToggle
				checked={ this.state.checked }
				onChange={ this.onToggle }
				id="wps-collections-images-sizing-toggle"
			/>
    );

  }

}


/*

Init <CollectionsImagesSizingToggle />

*/
function initCollectionsImagesSizingToggle() {

  ReactDOM.render(
    <CollectionsImagesSizingToggle />,
    document.getElementById("wps-settings-collections-images-sizing-toggle")
  );

}

export {
  initCollectionsImagesSizingToggle
}
