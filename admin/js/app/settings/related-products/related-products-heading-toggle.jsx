import { FormToggle } from '@wordpress/components';
import React from 'react';
import ReactDOM from 'react-dom';
import { toBoolean } from '../../utils/utils';
import { updateSettingRelatedProductsHeadingToggle } from "../../ws/ws-api";
import { showNotice } from "../../notices/notices";
import { showLoader, hideLoader } from "../../utils/utils";
import to from 'await-to-js';


/*

<RelatedProductsHeading />

*/
class RelatedProductsHeadingToggle extends React.Component {

	state = {
		submitButton: jQuery("#submitSettings"),
		checked: toBoolean(WP_Shopify.settings.relatedProductsHeadingToggle)
	}
	

	onToggle = async state => {

		this.setState( state => ( { checked: ! this.state.checked } ) );
		jQuery('input[aria-describedby="wps-related-products-heading-toggle"]').attr('disabled', this.state.checked);

		var [updateError, updateResponse] = await to( updateSettingRelatedProductsHeadingToggle({
			value: jQuery('#wps-related-products-heading-toggle').prop('checked')
		}) );

	}


  render() {

    return (
      <FormToggle
				checked={ this.state.checked }
				onChange={ this.onToggle }
				id="wps-related-products-heading-toggle"
			/>
    );

  }

}


/*

Init <RelatedProductsHeadingToggle />

*/
function initRelatedProductsHeadingToggle() {

  ReactDOM.render(
    <RelatedProductsHeadingToggle />,
    document.getElementById("wps-settings-related-products-heading-toggle")
  );

}

export {
  initRelatedProductsHeadingToggle
}
