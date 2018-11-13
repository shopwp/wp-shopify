import { FormToggle } from '@wordpress/components';
import React from 'react';
import ReactDOM from 'react-dom';
import { toBoolean } from '../../utils/utils';


/*

<CollectionsHeadingToggle />

*/
class CollectionsHeadingToggle extends React.Component {

	state = {
		checked: toBoolean(WP_Shopify.settings.collectionsHeadingToggle)
	}

	onToggleHandle = state => {

		this.setState( state => ( { checked: ! this.state.checked } ) );

		jQuery('input[aria-describedby="wps-collections-heading-toggle"]').attr('disabled', this.state.checked);

	}


  render() {

    return (
      <FormToggle
				checked={ this.state.checked }
				onChange={ this.onToggleHandle }
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
