import uniqWith from 'lodash/uniqWith';
import isEqual from 'lodash/isEqual';
import forEach from 'lodash/forEach';
import forIn from 'lodash/forIn';
import has from 'lodash/has';


/*

Loops through existing selection to determine whether
what we just selected already exists in selection.

Shopify option names are forced unique, so we can safely
check against them here.

*/
function checkForExistingObjectOption(arrayOfObjects, maybeAdd) {

  var found = false;

  forEach(arrayOfObjects, function(object, arrayKey) {

    forIn(object, function(value, key) {

      // There already exists an object with this Option name
      if (has(maybeAdd, key)) {
        found = arrayKey;
        return found;
      }

    });

  });

  return found;

}


/*

Constructs the newly selected options

*/
function constructNewlySelectedOptions(variantOptionName, variantTitle) {

  var newlySelected = {};
  newlySelected[variantOptionName] = variantTitle;

  return newlySelected;

}


/*

Constructs the previously selected options

*/
function constructPrevSelectedOptions(prevSelected) {

  if (!prevSelected) {
    prevSelected = [];

  } else {
    prevSelected = JSON.parse(prevSelected);
  }

  return prevSelected;

}


/*

Checks whether we need to add or replace a given object inside
the selection array

*/
function addOrReplaceVariantSelection(indexOfExisting, prevSelected, newlySelected) {

  if (indexOfExisting === false) {
    prevSelected.push(newlySelected);

  } else {
    prevSelected[indexOfExisting] = newlySelected;
  }

  return prevSelected;

}


/*

Removes duplicates from selections

*/
function removeDuplicateSelections(prevSelected) {
  return uniqWith(prevSelected, isEqual);
}


/*

Adds new selection to meta container

*/
function addNewSelectionToMetaContainer($newProductMetaContainer, selectedString) {
  $newProductMetaContainer.attr('data-product-selected-options-and-variants', selectedString);
}



/*

Main wrapper for building out the selected options

*/
function buildSelectedOptions($newProductMetaContainer, prevSelected, variantOptionName, variantTitle) {

  prevSelected = constructPrevSelectedOptions(prevSelected);

  var newlySelected = constructNewlySelectedOptions(variantOptionName, variantTitle);
  var indexOfExisting = checkForExistingObjectOption(prevSelected, newlySelected);

  prevSelected = addOrReplaceVariantSelection(indexOfExisting, prevSelected, newlySelected)

  prevSelected = removeDuplicateSelections(prevSelected);

  addNewSelectionToMetaContainer( $newProductMetaContainer, JSON.stringify(prevSelected) )

}


export {
  buildSelectedOptions
}
