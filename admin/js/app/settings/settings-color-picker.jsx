import React from 'react';
import ReactDOM from 'react-dom';
import to from "await-to-js";
import { ChromePicker } from "react-color";
import { showNotice } from "../notices/notices";
import { pickerStyles, swatchStyles } from "./settings-color-picker-styles";

import {
  showLoader,
  hideLoader
} from "../utils/utils";

import {
  updateSettingAddToCartColor,
  updateSettingVariantColor,
  updateSettingCheckoutColor,
  updateSettingCartCounterColor,
  updateSettingCartIconColor
} from "../ws/ws-api";


/*

Picker: Variant button

*/
class ColorPickerVariant extends React.Component {

  updateColor = (colorData) => {
    return updateSettingVariantColor(colorData);
  };

  render() {
    return (
      <ColorPicker
        color={WP_Shopify.settings.colorVariant}
        updateColor={this.updateColor}
        pickerType="variant"
      />
    );
  }

}


/*

Picker: Add to cart button

*/
class ColorPickerAddToCart extends React.Component {

  updateColor = (colorData) => {
    return updateSettingAddToCartColor(colorData);
  };

  render() {
    return (
      <ColorPicker
        color={WP_Shopify.settings.colorAddToCart}
        updateColor={this.updateColor}
        pickerType="add-to-cart"
      />
    );
  }

}


/*

Picker: Checkout button

*/
class ColorPickerCheckout extends React.Component {

  updateColor = (colorData) => {
    return updateSettingCheckoutColor(colorData);
  };

  render() {
    return (
      <ColorPicker
        color={WP_Shopify.settings.colorCheckout}
        updateColor={this.updateColor}
        pickerType="checkout"
      />
    );
  }

}


/*

Picker: Cart Counter

*/
class ColorPickerCartCounter extends React.Component {

  updateColor = (colorData) => {
    return updateSettingCartCounterColor(colorData);
  };

  render() {
    return (
      <ColorPicker
        color={WP_Shopify.settings.colorCartCounter}
        updateColor={this.updateColor}
        pickerType="cart-counter"
      />
    );
  }

}


/*

Picker: Cart Counter

*/
class ColorPickerCartIcon extends React.Component {

  updateColor = (colorData) => {
    return updateSettingCartIconColor(colorData);
  };

  render() {
    return (
      <ColorPicker
        color={WP_Shopify.settings.colorCartIcon}
        updateColor={this.updateColor}
        pickerType="cart-icon"
      />
    );
  }

}


class ColorPicker extends React.Component {

  state = {
    displayColorPicker: false,
    color: this.props.color,
    pickerStyles: pickerStyles(),
    swatchStyles: swatchStyles(this.props.color),
    componentId: "wps-component-" + this.props.pickerType + "-color-swatch",
    colorHasChanged: false,
    submitButton: jQuery("#submitSettings")
  }


  /*

  Close picker

  */
  closePicker = () => this.setState({ displayColorPicker: false });


  /*

  On picker click ...

  */
  handleClick = () => this.setState({ displayColorPicker: !this.state.displayColorPicker });


  /*

  On picker close ...

  */
  handleClose = async () => {

    // If the user hasn't selected a new color, just exit
    if (!this.state.colorHasChanged) {
      return this.closePicker();
    }


    showLoader(this.state.submitButton);

    this.closePicker();

    // Updates DB with the new color
    var [updateError, updateResponse] = await to( this.props.updateColor({ color: this.state.color }) );


    showNotice(updateError, updateResponse);

    hideLoader(this.state.submitButton);

  }


  /*

  On picker change ...

  */
  handleChange = color => {

    this.setState({
      swatchStyles: swatchStyles(color.hex),
      color: color.hex,
      colorHasChanged: color.hex !== this.state.color ? true : false
    });

  }

  render() {

    return (
      <div>
        <div
          className="wps-color-swatch-wrapper"
          style={this.state.pickerStyles.wrapper}
          onClick={this.handleClick}
          data-testid={this.state.componentId}
        >
          <div
            className="wps-color-swatch"
            style={this.state.swatchStyles}
            data-color={this.state.color}
            data-picker-type={this.props.pickerType}
          />
        </div>

        {this.state.displayColorPicker ? (
          <div style={this.state.pickerStyles.popover}>
            <div style={this.state.pickerStyles.cover} onClick={this.handleClose} />
            <ChromePicker
              color={this.state.color}
              onChange={this.handleChange}
            />
          </div>
        ) : null}
      </div>
    );

  }

}


/*

Init color pickers

*/
function initColorPickers() {

  ReactDOM.render(
    <ColorPickerAddToCart />,
    document.getElementById("wps-color-picker-add-to-cart")
  );

  ReactDOM.render(
    <ColorPickerVariant />,
    document.getElementById("wps-color-picker-variant")
  );

  ReactDOM.render(
    <ColorPickerCheckout />,
    document.getElementById("wps-color-picker-checkout")
  );

  ReactDOM.render(
    <ColorPickerCartCounter />,
    document.getElementById("wps-color-picker-cart-counter")
  );

  ReactDOM.render(
    <ColorPickerCartIcon />,
    document.getElementById("wps-color-picker-cart-icon")
  );

}


export {
  initColorPickers,
  ColorPickerAddToCart,
  ColorPickerVariant,
  ColorPickerCheckout,
  ColorPickerCartCounter,
  ColorPickerCartIcon
}
