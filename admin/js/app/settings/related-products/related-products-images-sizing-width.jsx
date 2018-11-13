import { TextControl } from '@wordpress/components';
import React from 'react';
import ReactDOM from 'react-dom';
import toInteger from 'lodash/toInteger';
import isInteger from 'lodash/isInteger';
import { toBoolean } from '../../utils/utils';
import { convertToRealSize } from '../../utils/utils-data';


/*

<RelatedProductsImagesSizingWidth />

*/
class RelatedProductsImagesSizingWidth extends React.Component {

  state = {
    value: WP_Shopify.settings.relatedProductsImagesSizingWidth === 0 ? 'auto' : WP_Shopify.settings.relatedProductsImagesSizingWidth,
    valueHasChanged: false
  }

  onUpdateHandle = newValue => {

    this.setState({
      value: newValue
    });

  }

  onBlurHandle = event => {

    this.setState({
      value: convertToRealSize(event.currentTarget.value)
    });

  }

  render() {

    return (
      <TextControl
          type="text"
          value={ this.state.value }
          onChange={ this.onUpdateHandle }
          onBlur={ this.onBlurHandle }
          aria-describedby="wps-related-products-images-sizing-toggle"
          disabled={ !toBoolean(WP_Shopify.settings.relatedProductsImagesSizingToggle) }

      />
    );

  }

}


/*

Init color pickers

*/
function initRelatedProductsImagesSizingWidth() {

  ReactDOM.render(
    <RelatedProductsImagesSizingWidth />,
    document.getElementById("wps-settings-related-products-images-sizing-width")
  );

}

export {
  initRelatedProductsImagesSizingWidth
}
