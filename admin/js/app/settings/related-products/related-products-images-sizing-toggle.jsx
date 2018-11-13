import { FormToggle } from '@wordpress/components';
import React from 'react';
import ReactDOM from 'react-dom';
import { toBoolean } from '../../utils/utils';
import to from 'await-to-js';


/*

<ProductsImagesSizing />

*/
class RelatedProductsImagesSizingToggle extends React.Component {

	state = {
		checked: toBoolean(WP_Shopify.settings.relatedProductsImagesSizingToggle)
	}


	onToggleHandle = async state => {

		this.setState({ checked: ! this.state.checked });
		
		jQuery('[aria-describedby="wps-related-products-images-sizing-toggle"]').attr('disabled', this.state.checked);

	}


  render() {

    return (
      <FormToggle
				checked={ this.state.checked }
				onChange={ this.onToggleHandle }
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
