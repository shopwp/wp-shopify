import { getUrlParamByID } from './utils';


/*

Toggle Tab

*/
function toggleTab() {

  jQuery('.wps-admin-wrap .nav-tab').on('click', function(e) {

    e.preventDefault();

    var targetTab = jQuery(this).data('tab');
    var $currentTab = jQuery('.wps-admin-wrap .nav-tab.nav-tab-active');
    var $targetTabElement = findTab(targetTab);
    var $targetTabContentElement = findTabContent(targetTab);
    var $currentTabContentElement = jQuery('.wps-admin-wrap .tab-content.tab-content-active')

    var $msgContainer = jQuery('#wps-errors');

    setActiveTab(targetTab);

    $currentTabContentElement.removeClass('tab-content-active');
    $currentTab.removeClass('nav-tab-active');

    $targetTabElement.addClass('nav-tab-active');
    $targetTabContentElement.addClass('tab-content-active');
    $msgContainer.addClass('wps-is-hidden');


  });

}


function toggleSubNav() {

  jQuery('.wps-submenu .wps-sub-section-link').on('click', function(e) {

    e.preventDefault();

    var targetSubNav = jQuery(this).data('sub-section');
    var $currentSubNav = jQuery('.wps-submenu .wps-sub-section-link.current');
    var $targetSubNavElement = findSubNav(targetSubNav);
    var $targetSubNavContentElement = findSubNavContent(targetSubNav);
    var $currentSubNavContentElement = jQuery('.wps-admin-sub-section.is-active');
    var $msgContainer = jQuery('#wps-errors');

    setActiveSubNav(targetSubNav);

    $currentSubNavContentElement.removeClass('tab-content-active');
    $currentSubNav.removeClass('current');

    $targetSubNavElement.addClass('current');

    $targetSubNavContentElement.addClass('is-active');
    $msgContainer.addClass('wps-is-hidden');


  });

}


function setActiveSubNav(activeSubNav) {
  window.history.pushState( {}, "", updateQueryStringParameter(window.location.href, 'activesubnav', activeSubNav) );
}


function setActiveTab(activeTab) {
  window.history.pushState( {}, "", updateQueryStringParameter(window.location.href, 'activetab', activeTab) );
}


function updateQueryStringParameter(uri, key, value) {

  var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
  var separator = uri.indexOf('?') !== -1 ? "&" : "?";

  if (uri.match(re)) {
    return uri.replace(re, '$1' + key + "=" + value + '$2');

  } else {
    return uri + separator + key + "=" + value;
  }

}


/*

Find Tab

*/
function findTab(targetTab) {
  return jQuery('.wps-admin-wrap .nav-tab[data-tab="' + targetTab + '"]');
}


/*

Find Tab

*/
function findSubNav(targetSubNav) {
  return jQuery('.wps-sub-section-link[data-sub-section="' + targetSubNav + '"]');
}


/*

Find Tab Content

*/
function findTabContent(targetTabContent) {
  return jQuery('.wps-admin-wrap .tab-content[data-tab-content="' + targetTabContent + '"]');
}


/*

Find Tab Content

*/
function findSubNavContent(targetTabContent) {
  return jQuery('#' + targetTabContent);
}


/*

Remove Current Active Tab

*/
function removeCurrentActiveTab(currentTab) {

  jQuery('.wps-admin-wrap .nav-tab').removeClass('nav-tab-active');
  jQuery('.wps-admin-wrap .tab-content').removeClass('tab-content-active');

}


/*

Tabs Init

*/
function tabsInit() {
  toggleTab();
  toggleSubNav();
};


export {
  tabsInit
};
