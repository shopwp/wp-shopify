import { FormToggle } from '@wordpress/components';
import React from 'react';
import ReactDOM from 'react-dom';
import { toBoolean } from '../../utils/utils';

/*

<CollectionsImagesSizing />

*/
class CollectionsImagesSizingToggle extends React.Component {

	state = {
		checked: toBoolean(WP_Shopify.settings.collectionsImagesSizingToggle)
	}


	onToggleHandle = async state => {

		this.setState({ checked: ! this.state.checked });

		jQuery('[aria-describedby="wps-collections-images-sizing-toggle"]').attr('disabled', this.state.checked);

	}


  render() {

    return (
      <FormToggle
				checked={ this.state.checked }
				onChange={ this.onToggleHandle }
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
