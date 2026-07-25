/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!******************************!*\
  !*** ./resources/js/init.js ***!
  \******************************/
if ('serviceWorker' in navigator) {
  window.addEventListener('load', function () {
    navigator.serviceWorker.register('/serviceworker.js').then(function () {
      initialisePushSubscription();
    });
  });
}
function initialisePushSubscription() {
  if ('showNotification' in ServiceWorkerRegistration.prototype && Notification.permission === 'granted' && 'PushManager' in window) {
    navigator.serviceWorker.ready.then(function (registration) {
      registration.pushManager.getSubscription().then(function (subscription) {
        if (!subscription) {
          return;
        }
        updatePushSubscription(subscription);
      })["catch"](function (error) {
        console.log(error);
      });
    });
  }
}
function updatePushSubscription(pushSubscription) {
  var csrfToken = document.querySelector('meta[name=csrf-token]').getAttribute('content');
  var key = pushSubscription.getKey('p256dh');
  var token = pushSubscription.getKey('auth');
  var contentEncoding = (PushManager.supportedContentEncodings || ['aesgcm'])[0];
  var data = {
    endpoint: pushSubscription.endpoint,
    public_key: key ? btoa(String.fromCharCode.apply(null, new Uint8Array(key))) : null,
    auth_token: token ? btoa(String.fromCharCode.apply(null, new Uint8Array(token))) : null,
    content_encoding: contentEncoding
  };
  fetch('/webpush', {
    method: 'POST',
    body: JSON.stringify(data),
    headers: {
      'Accept': 'application/json',
      'Content-Type': 'application/json',
      'X-CSRF-Token': csrfToken
    }
  }).then(function (result) {
    return result.json();
  })["catch"](function (error) {
    console.log(error);
  });
}
/******/ })()
;