!function(t){var e={};function n(i){if(e[i])return e[i].exports;var o=e[i]={i:i,l:!1,exports:{}};return t[i].call(o.exports,o,o.exports,n),o.l=!0,o.exports}n.m=t,n.c=e,n.d=function(t,e,i){n.o(t,e)||Object.defineProperty(t,e,{enumerable:!0,get:i})},n.r=function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},n.t=function(t,e){if(1&e&&(t=n(t)),8&e)return t;if(4&e&&"object"==typeof t&&t&&t.__esModule)return t;var i=Object.create(null);if(n.r(i),Object.defineProperty(i,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var o in t)n.d(i,o,function(e){return t[e]}.bind(null,o));return i},n.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return n.d(e,"a",e),e},n.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},n.p="/",n(n.s=123)}({123:function(t,e,n){t.exports=n(124)},124:function(t,e){var n=["/offline","/css/app.css","/js/app.js","/js/clipboard.min.js","/svg/bootstrap-icons.svg","/svg/feather-sprite.svg","/svg/offline.svg"];self.addEventListener("install",(function(t){self.skipWaiting(),t.waitUntil(caches.open("quokka-cache-v0.1.16-8439059").then((function(t){return t.addAll(n)})))})),self.addEventListener("activate",(function(t){var e=["quokka-cache-v0.1.16-8439059"];t.waitUntil(caches.keys().then((function(t){return Promise.all(t.map((function(t){if(-1===e.indexOf(t))return caches.delete(t)})))})))})),self.addEventListener("fetch",(function(t){t.respondWith(caches.match(t.request).then((function(e){return e||fetch(t.request).catch((function(){return caches.match("/offline")}))})))})),self.addEventListener("push",(function(t){if(self.Notification&&"granted"===self.Notification.permission&&t.data){var e=t.data.json();t.waitUntil(self.registration.showNotification(e.title,{body:e.body,icon:e.icon,tag:e.tag,data:e.data,badge:e.badge,dir:e.dir,image:e.image,renotify:e.renotify,requireInteraction:e.requireInteraction,actions:e.actions,vibrate:e.vibrate}))}})),self.addEventListener("notificationclick",(function(t){t.notification.close(),clients.openWindow&&t.notification.data.url&&t.waitUntil(clients.openWindow(t.notification.data.url))}),!1)}});