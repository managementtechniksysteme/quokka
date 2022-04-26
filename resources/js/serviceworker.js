let CACHE_NAME = 'quokka-cache-v0.1.11-0dc41f2';
let OFFLINE_URL = '/offline';

let urlsToCache = [
    OFFLINE_URL,
    '/css/app.css',
    '/js/app.js',
    '/svg/feather-sprite.svg',
    '/svg/offline.svg'
];

self.addEventListener('install', function(event) {
    self.skipWaiting();

    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(function(cache) {
                return cache.addAll(urlsToCache);
            })
    );
});

self.addEventListener('activate', function(event) {

    var cacheAllowlist = [CACHE_NAME];

    event.waitUntil(
        caches.keys().then(function(cacheNames) {
            return Promise.all(
                cacheNames.map(function(cacheName) {
                    if (cacheAllowlist.indexOf(cacheName) === -1) {
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
});


self.addEventListener('fetch', function(event) {
    event.respondWith(
        caches.match(event.request)
            .then(function(response) {
                    // Cache hit - return response
                    if (response) {
                        return response;
                    }

                    return fetch(event.request)
                        .catch(function() {
                            return caches.match(OFFLINE_URL);
                    });
            })
    );
});

self.addEventListener('push', function (event) {
    if (self.Notification && self.Notification.permission === 'granted' && event.data) {
        let msg = event.data.json();

        event.waitUntil(self.registration.showNotification(msg.title, {
            body: msg.body,
            icon: msg.icon,
            tag: msg.tag,
            data: msg.data,
            badge: msg.badge,
            dir: msg.dir,
            image: msg.image,
            renotify: msg.renotify,
            requireInteraction: msg.requireInteraction,
            actions: msg.actions,
            vibrate: msg.vibrate
        }));
    }
});

self.addEventListener('notificationclick', function(event) {
    event.notification.close();

    if (clients.openWindow && event.notification.data.url) {
        event.waitUntil(clients.openWindow(event.notification.data.url));
    }
}, false);
