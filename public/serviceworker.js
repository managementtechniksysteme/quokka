!function(e){var t={};function n(i){if(t[i])return t[i].exports;var o=t[i]={i:i,l:!1,exports:{}};return e[i].call(o.exports,o,o.exports,n),o.l=!0,o.exports}n.m=e,n.c=t,n.d=function(e,t,i){n.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:i})},n.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},n.t=function(e,t){if(1&t&&(e=n(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var i=Object.create(null);if(n.r(i),Object.defineProperty(i,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var o in e)n.d(i,o,function(t){return e[t]}.bind(null,o));return i},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p="/",n(n.s=121)}({121:function(e,t,n){e.exports=n(122)},122:function(e,t){var n=["/offline","/css/app.css","/js/app.js","/js/alpine.min.js","/js/clipboard.min.js","/svg/bootstrap-icons.svg","/svg/feather-sprite.svg","/svg/offline.svg"];self.addEventListener("install",(function(e){self.skipWaiting(),e.waitUntil(caches.open("quokka-cache-v0.1.17-d52694c").then((function(e){return e.addAll(n)})))})),self.addEventListener("activate",(function(e){var t=["quokka-cache-v0.1.17-d52694c"];e.waitUntil(caches.keys().then((function(e){return Promise.all(e.map((function(e){if(-1===t.indexOf(e))return caches.delete(e)})))})))})),self.addEventListener("fetch",(function(e){e.respondWith(caches.match(e.request).then((function(t){return t||fetch(e.request).catch((function(){return caches.match("/offline")}))})))})),self.addEventListener("push",(function(e){if(self.Notification&&"granted"===self.Notification.permission&&e.data){var t=e.data.json();e.waitUntil(self.registration.showNotification(t.title,{body:t.body,icon:t.icon,tag:t.tag,data:t.data,badge:t.badge,dir:t.dir,image:t.image,renotify:t.renotify,requireInteraction:t.requireInteraction,actions:t.actions,vibrate:t.vibrate}))}})),self.addEventListener("notificationclick",(function(e){e.notification.close(),clients.openWindow&&e.notification.data.url&&e.waitUntil(clients.openWindow(e.notification.data.url))}),!1)}});