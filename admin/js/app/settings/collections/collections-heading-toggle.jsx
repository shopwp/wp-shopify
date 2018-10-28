import { FormToggle } from '@wordpress/components';
import React from 'react';
import ReactDOM from 'react-dom';
import { toBoolean } from '../../utils/utils';
import { updateSettingCollectionsHeadingToggle } from "../../ws/ws-api";
import { showNotice } from "../../notices/notices";
import { showLoader, hideLoader } from "../../utils/utils";
import to from 'await-to-js';


/*

<CollectionsHeadingToggle />

*/
class CollectionsHeadingToggle extends React.Component {

	state = {
		submitButton: jQuery("#submitSettings"),
		checked: toBoolean(WP_Shopify.settings.collectionsHeadingToggle)
	}

	onToggle = async state => {

		this.setState( state => ( { checked: ! this.state.checked } ) );
		jQuery('input[aria-describedby="wps-collections-heading-toggle"]').attr('disabled', this.state.checked);

		var [updateError, updateResponse] = await to( updateSettingCollectionsHeadingToggle({
			value: jQuery('#wps-collections-heading-toggle').prop('checked')
		}) );

	}


  render() {

    return (
      <FormToggle
				checked={ this.state.checked }
				onChange={ this.onToggle }
				id="wps-collections-heading-toggle"
			/>
    );

  }

}


/*

Init <CollectionsHeadingToggle />

*/
function initCollectionsHeadingToggle() {

  ReactDOM.render(
    <CollectionsHeadingToggle />,
    document.getElementById("wps-settings-collections-heading-toggle")
  );

}

export {
  initCollectionsHeadingToggle
}
