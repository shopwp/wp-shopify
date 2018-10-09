import {
  createCheckmark,
  createX,
  modalDOM,
  injectConnectorModal
} from '../../../utils/utils-dom';


it('Should check create checkmark', () => {

  const $checkMark = createCheckmark();

  expect($checkMark)
    .toBejQueryObject()
    .toHaveClass('dashicons')
    .toHaveClass('dashicons-yes')
    .toBeEmpty()

});


it('Should check create x mark', () => {

  const $xMark = createX();

  expect($xMark)
    .toBejQueryObject()
    .toHaveClass('dashicons')
    .toHaveClass('dashicons-no')
    .toBeEmpty()

});


it('Should create modal DOM', () => {

  const $modalDOM = modalDOM();

  expect( $modalDOM )
    .toBejQueryObject()
    .toHaveClass('wps-connector-wrapper')
    .not.toBeInDom()
    .not.toBeEmpty()

});


it('Should inject modal', () => {

  injectConnectorModal( jQuery('<div id="test-modal"></div>') );

  expect( jQuery('#test-modal') )
    .toExist()
    .toBeInDom()

  jQuery('#test-modal').remove();

});
