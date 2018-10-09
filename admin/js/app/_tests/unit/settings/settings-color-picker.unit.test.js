import React from "react";
import { render, cleanup, fireEvent } from 'react-testing-library'
import 'jest-dom/extend-expect';

import {
  initColorPickers,
  ColorPickerAddToCart,
  ColorPickerVariant,
  ColorPickerCheckout,
  ColorPickerCartCounter
} from "../../../settings/settings-color-picker.jsx";


// Cleans up after each test
afterEach(cleanup);



it("Should create add to cart color picker", () => {

  const { getByTestId } = render( <ColorPickerAddToCart /> );

  expect( getByTestId('wps-component-add-to-cart-color-swatch') )
    .toBeInTheDocument()
    .toHaveStyle(`
      padding: 4px;
      background: rgb(255, 255, 255);
      border-radius: 1px;
      box-shadow: 0 0 0 1px rgba(0,0,0,.1);
      display: inline-block;
      cursor: pointer;
    `);

});


it("Should create variant color picker", () => {

  const { getByTestId } = render( <ColorPickerVariant /> );

  expect( getByTestId('wps-component-variant-color-swatch') )
    .toBeInTheDocument()
    .toHaveStyle(`
      padding: 4px;
      background: rgb(255, 255, 255);
      border-radius: 1px;
      box-shadow: 0 0 0 1px rgba(0,0,0,.1);
      display: inline-block;
      cursor: pointer;
    `);

});


it("Should create checkout color picker", () => {

  const { getByTestId } = render( <ColorPickerCheckout /> );

  expect( getByTestId('wps-component-checkout-color-swatch') )
    .toBeInTheDocument()
    .toHaveStyle(`
      padding: 4px;
      background: rgb(255, 255, 255);
      border-radius: 1px;
      box-shadow: 0 0 0 1px rgba(0,0,0,.1);
      display: inline-block;
      cursor: pointer;
    `);

});


it("Should create cart counter color picker", () => {

  const { getByTestId } = render( <ColorPickerCartCounter /> );

  expect( getByTestId('wps-component-cart-counter-color-swatch') )
    .toBeInTheDocument()
    .toHaveStyle(`
      padding: 4px;
      background: rgb(255, 255, 255);
      border-radius: 1px;
      box-shadow: 0 0 0 1px rgba(0,0,0,.1);
      display: inline-block;
      cursor: pointer;
    `);

});


it('Should open modal when clicking the add to cart color picker', () => {

  // Assemble
  const handleClick = jest.fn();
  const { getByTestId, debug } = render( <ColorPickerAddToCart onClick={handleClick} /> );

  // Act
  fireEvent.click( getByTestId('wps-component-add-to-cart-color-swatch') );

  // Assert
  expect( document.querySelector('.chrome-picker') )
    .toBeInTheDocument()
    .toHaveStyle(`
      background: rgb(255, 255, 255);
      border-radius: 2px;
      box-shadow: 0 0 2px rgba(0,0,0,.3), 0 4px 8px rgba(0,0,0,.3);
      box-sizing: initial;
      width: 225px;
      font-family: Menlo;
    `);

});


it('Should open modal when clicking the variant color picker', () => {

  // Assemble
  const handleClick = jest.fn();
  const { getByTestId, debug } = render( <ColorPickerVariant onClick={handleClick} /> );

  // Act
  fireEvent.click( getByTestId('wps-component-variant-color-swatch') );

  // Assert
  expect( document.querySelector('.chrome-picker') )
    .toBeInTheDocument()
    .toHaveStyle(`
      background: rgb(255, 255, 255);
      border-radius: 2px;
      box-shadow: 0 0 2px rgba(0,0,0,.3), 0 4px 8px rgba(0,0,0,.3);
      box-sizing: initial;
      width: 225px;
      font-family: Menlo;
    `);

});


it('Should open modal when clicking the checkout color picker', () => {

  // Assemble
  const handleClick = jest.fn();
  const { getByTestId, debug } = render( <ColorPickerCheckout onClick={handleClick} /> );

  // Act
  fireEvent.click( getByTestId('wps-component-checkout-color-swatch') );

  // Assert
  expect( document.querySelector('.chrome-picker') )
    .toBeInTheDocument()
    .toHaveStyle(`
      background: rgb(255, 255, 255);
      border-radius: 2px;
      box-shadow: 0 0 2px rgba(0,0,0,.3), 0 4px 8px rgba(0,0,0,.3);
      box-sizing: initial;
      width: 225px;
      font-family: Menlo;
    `);

});


it('Should open modal when clicking the cart counter color picker', () => {

  // Assemble
  const handleClick = jest.fn();
  const { getByTestId, debug } = render( <ColorPickerCartCounter onClick={handleClick} /> );

  // Act
  fireEvent.click( getByTestId('wps-component-cart-counter-color-swatch') );

  // Assert
  expect( document.querySelector('.chrome-picker') )
    .toBeInTheDocument()
    .toHaveStyle(`
      background: rgb(255, 255, 255);
      border-radius: 2px;
      box-shadow: 0 0 2px rgba(0,0,0,.3), 0 4px 8px rgba(0,0,0,.3);
      box-sizing: initial;
      width: 225px;
      font-family: Menlo;
    `);

});
