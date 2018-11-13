import { SelectControl } from '@wordpress/components';
import React from 'react';
import ReactDOM from 'react-dom';
import toInteger from 'lodash/toInteger';
import { toBoolean } from '../../utils/utils';
import { imageScaleTypes } from '../settings.jsx';


/*

<RelatedProductsImagesSizingScale />

*/
class RelatedProductsImagesSizingScale extends React.Component {

  state = {
    value: WP_Shopify.settings.relatedProductsImagesSizingScale === false ? 'none' : WP_Shopify.settings.relatedProductsImagesSizingScale
  }

  onUpdateHandle = newValue => {

    this.setState({
      value: newValue
    });

  }

  render() {

    return (
      <SelectControl
        value={ this.state.value }
        options={ imageScaleTypes() }
        onChange={ this.onUpdateHandle }
        aria-describedby="wps-related-products-images-sizing-toggle"
        disabled={ !toBoolean(WP_Shopify.settings.relatedProductsImagesSizingToggle) }

      />
    );

  }

}


/*

Init color pickers

*/
function initRelatedProductsImagesSizingScale() {

  ReactDOM.render(
    <RelatedProductsImagesSizingScale />,
    document.getElementById("wps-settings-related-products-images-sizing-scale")
  );

}

export {
  initRelatedProductsImagesSizingScale
}
