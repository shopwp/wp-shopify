/*

Toggle Tab

*/
function toggleTab() {

  jQuery('.wps-admin-wrap .nav-tab').on('click', function(e) {
    e.preventDefault();

    var targetTab = jQuery(this).data('tab');
    var $targetTabElement = findTab(targetTab);
    var $targetTabContentElement = findTabContent(targetTab);
    var $msgContainer = jQuery('#wps-errors');

    removeCurrentActiveTab();

    $targetTabElement.addClass('nav-tab-active');
    $targetTabContentElement.addClass('tab-content-active');
    $msgContainer.addClass('wps-is-hidden');

  });

};


/*

Find Tab

*/
function findTab(targetTab) {
  return jQuery('.wps-admin-wrap .nav-tab[data-tab="' + targetTab + '"]');
}


/*

Find Tab Content

*/
function findTabContent(targetTabContent) {
  return jQuery('.wps-admin-wrap .tab-content[data-tab-content="' + targetTabContent + '"]');
}


/*

Remove Current Active Tab

*/
function removeCurrentActiveTab() {
  jQuery('.wps-admin-wrap .nav-tab').removeClass('nav-tab-active');
  jQuery('.wps-admin-wrap .tab-content').removeClass('tab-content-active');
}


/*

Tabs Init

*/
function tabsInit() {
  toggleTab();
};


export {
  tabsInit
};
