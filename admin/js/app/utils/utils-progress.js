import {
  getProgressCount
} from '../ws/ws';


/*

Create Progress Loader

*/
function createProgressLoader() {
  jQuery('.wps-progress-notice:first-of-type').append('<div class="wps-loader"></div>');
}


/*

Remove Progress Loader

*/
function removeProgressLoader() {
  jQuery('.wps-progress-notice .wps-loader').remove();
}


/*

Stop Progress Loader

*/
function stopProgressLoader(timer) {
  clearInterval(timer);
}


/*

Update Progress Loader

*/
function updateProgressLoader() {

  // getProgressCount();
  // stopProgressLoader(timer)

}


export {
  createProgressLoader,
  removeProgressLoader,
  updateProgressLoader
};
