/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!*********************************!*\
  !*** ./resources/js/webpush.js ***!
  \*********************************/
function initWebpush() {
  if (!navigator.serviceWorker.ready) {
    return;
  }
  new Promise(function (resolve, reject) {
    var permissionResult = Notification.requestPermission(function (result) {
      resolve(result);
    });
    if (permissionResult) {
      permissionResult.then(resolve, reject);
    }
  }).then(function (permissionResult) {
    if (permissionResult === 'granted') {
      subscribeUser();
    }
  });
}
function subscribeUser() {
  navigator.serviceWorker.ready.then(function (registration) {
    var subscribeOptions = {
      userVisibleOnly: true,
      applicationServerKey: urlBase64ToUint8Array(VAPID_PUBLIC_KEY)
    };
    return registration.pushManager.subscribe(subscribeOptions);
  }).then(function (pushSubscription) {
    console.log('Received PushSubscription: ', JSON.stringify(pushSubscription));
    storePushSubscription(pushSubscription);
  });
}
function storePushSubscription(pushSubscription) {
  var token = document.querySelector('meta[name=csrf-token]').getAttribute('content');
  fetch('/webpush', {
    method: 'POST',
    body: JSON.stringify(pushSubscription),
    headers: {
      'Accept': 'application/json',
      'Content-Type': 'application/json',
      'X-CSRF-Token': token
    }
  }).then(function (res) {
    return res.json();
  }).then(function (res) {
    console.log(res);
  })["catch"](function (err) {
    console.log(err);
  });
}
function urlBase64ToUint8Array(base64String) {
  var padding = '='.repeat((4 - base64String.length % 4) % 4);
  var base64 = (base64String + padding).replace(/\-/g, '+').replace(/_/g, '/');
  var rawData = window.atob(base64);
  var outputArray = new Uint8Array(rawData.length);
  for (var i = 0; i < rawData.length; ++i) {
    outputArray[i] = rawData.charCodeAt(i);
  }
  return outputArray;
}
/******/ })()
;