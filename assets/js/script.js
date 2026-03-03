document.addEventListener('DOMContentLoaded', function () {
  var banner = document.querySelector('.oes-banner');
  if (!banner) return;

  // Get duration from inline script injected by PHP
  var durationHours =
    window.oesData && window.oesData.durationHours
      ? window.oesData.durationHours
      : 5;
  var durationMs = durationHours * 60 * 60 * 1000;

  var elHours = banner.querySelector('.oes-hours');
  var elMinutes = banner.querySelector('.oes-minutes');
  var elSeconds = banner.querySelector('.oes-seconds');

  function initTimer() {
    var expiry = localStorage.getItem('oes_expiry');
    var now = Date.now();

    // If no expiry, or if expiry is in the past, reset it
    if (!expiry || now > parseInt(expiry, 10)) {
      expiry = now + durationMs;
      localStorage.setItem('oes_expiry', expiry);
    }

    return parseInt(expiry, 10);
  }

  var targetTime = initTimer();

  function updateTimer() {
    var now = Date.now();
    var distance = targetTime - now;

    if (distance < 0) {
      // Time is up, reset the timer again
      targetTime = Date.now() + durationMs;
      localStorage.setItem('oes_expiry', targetTime);
      distance = targetTime - Date.now();
    }

    var hours = Math.floor(distance / (1000 * 60 * 60));
    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((distance % (1000 * 60)) / 1000);

    // Format with leading zeros
    elHours.textContent = hours < 10 ? '0' + hours : hours;
    elMinutes.textContent = minutes < 10 ? '0' + minutes : minutes;
    elSeconds.textContent = seconds < 10 ? '0' + seconds : seconds;
  }

  // Initial update
  updateTimer();

  // Update every second
  setInterval(updateTimer, 1000);
});
