import { FormToggle } from '@wordpress/components';
import React from 'react';
import ReactDOM from 'react-dom';
import { toBoolean } from '../../utils/utils';
import { updateSettingRelatedProductsImagesSizingToggle } from "../../ws/ws-api";
import { showNotice } from "../../notices/notices";
import { showLoader, hideLoader } from "../../utils/utils";
import to from 'await-to-js';


/*

<ProductsImagesSizing />

*/
class RelatedProductsImagesSizingToggle extends React.Component {

	state = {
		submitButton: jQuery("#submitSettings"),
		checked: toBoolean(WP_Shopify.settings.relatedProductsImagesSizingToggle)
	}


	onToggle = async state => {

		this.setState( state => ( { checked: ! this.state.checked } ) );
		jQuery('[aria-describedby="wps-related-products-images-sizing-toggle"]').attr('disabled', this.state.checked);

		var [updateError, updateResponse] = await to( updateSettingRelatedProductsImagesSizingToggle({
			value: jQuery('#wps-related-products-images-sizing-toggle').prop('checked')
		}) );

	}


  render() {

    return (
      <FormToggle
				checked={ this.state.checked }
				onChange={ this.onToggle }
				id="wps-related-products-images-sizing-toggle"
			/>
    );

  }

}


/*

Init <RelatedProductsImagesSizingToggle />

*/
function initRelatedProductsImagesSizingToggle() {

  ReactDOM.render(
    <RelatedProductsImagesSizingToggle />,
    document.getElementById("wps-settings-related-products-images-sizing-toggle")
  );

}

export {
  initRelatedProductsImagesSizingToggle
}
