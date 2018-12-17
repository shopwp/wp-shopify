import Timer from 'easytimer.js';


function createTimer() {
  return new Timer();
}

function isTimerRunning(timer) {
  return timer.isRunning();
}

function startTimer(timer) {
  return timer.start();
}

function stopTimer() {

  if (WP_Shopify.timers.syncing) {
    WP_Shopify.timers.syncing.stop();
  }

}


function startUpdatingTimerCount(timer) {

  timer.addEventListener('secondsUpdated', function (e) {
    jQuery('#wps-sync-duration').html( timer.getTimeValues().toString() );
  });

}


function initSyncingTimer() {

  var timer = createTimer();

  startUpdatingTimerCount(timer);
  startTimer(timer);

  WP_Shopify.timers.syncing = timer;

}

export {
  initSyncingTimer,
  stopTimer
}
