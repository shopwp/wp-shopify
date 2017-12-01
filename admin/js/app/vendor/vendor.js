import {
  containsDomain,
  containsAlphaNumeric,
  containsProtocol,
  containsURL
} from '../utils/utils';


/*

Used for form validations

*/
function addCustomFormValidators() {

  jQuery.validator.addMethod('containsProtocol', containsProtocol, 'Please remove http:// or https:// from domain');
  jQuery.validator.addMethod('domainRule', containsDomain, 'Domain must contain ".myshopify.com"');
  jQuery.validator.addMethod('alphaNumeric', containsAlphaNumeric, 'Must contain only numbers and letters');
  jQuery.validator.addMethod('urlRule', containsURL, 'Must be a valid URL');

}


/*

Used for CSS animations

*/
function animateCSS() {

  jQuery.fn.extend({
    animateCss: function (animationName, callback) {
      var animationEnd = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
      this.addClass('animated ' + animationName).one(animationEnd, function() {
        jQuery(this).removeClass('animated ' + animationName);
        callback();
      });
    }
  });

}


/*

Form Events Init

*/
function vendorInit() {
  addCustomFormValidators();
  animateCSS();
}

export {
  vendorInit
};
