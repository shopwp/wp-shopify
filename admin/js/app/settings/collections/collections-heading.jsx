import { TextControl } from '@wordpress/components';
import React from 'react';
import ReactDOM from 'react-dom';
import { toBoolean } from '../../utils/utils';


/*

<CollectionsHeading />

*/
class CollectionsHeading extends React.Component {

  state = {
    value: WP_Shopify.settings.collectionsHeading
  }

  onUpdateHandle = newValue => {

    this.setState({
      value: newValue
    });

  }

  render() {

    return (
      <TextControl
          value={ this.state.value }
          onChange={ this.onUpdateHandle }
          aria-describedby="wps-collections-heading-toggle"
          disabled={ !toBoolean(WP_Shopify.settings.collectionsHeadingToggle) }
      />
    );

  }

}


/*

Init Collections heading

*/
function initCollectionsHeading() {

  ReactDOM.render(
    <CollectionsHeading />,
    document.getElementById("wps-settings-collections-heading")
  );

}

export {
  initCollectionsHeading
}
