import { FormToggle } from '@wordpress/components';
import React from 'react';
import ReactDOM from 'react-dom';
import { toBoolean } from '../../utils/utils';


/*

<RelatedProductsHeading />

*/
class RelatedProductsHeadingToggle extends React.Component {

	state = {
		checked: toBoolean(WP_Shopify.settings.relatedProductsHeadingToggle)
	}


	onToggle = async state => {

		this.setState( state => ( { checked: ! this.state.checked } ) );
		jQuery('input[aria-describedby="wps-related-products-heading-toggle"]').attr('disabled', this.state.checked);

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
