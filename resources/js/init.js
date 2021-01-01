if ('serviceWorker' in navigator) {
    window.addEventListener('load', function() {
        navigator.serviceWorker.register('/serviceworker.js')
            .then(function () {
                initialisePushSubscription();
            });
    });
}

function initialisePushSubscription() {
    if('showNotification' in ServiceWorkerRegistration.prototype && Notification.permission === 'granted' && 'PushManager' in window) {
        navigator.serviceWorker.ready.then(registration => {
            registration.pushManager.getSubscription()
                .then(subscription => {
                    if (!subscription) {
                        return
                    }
                    updatePushSubscription(subscription)
                })
                .catch(error => {
                    console.log(error)
                })
        });
    }
}

function updatePushSubscription(pushSubscription) {
    const csrfToken = document.querySelector('meta[name=csrf-token]').getAttribute('content');

    const key = pushSubscription.getKey('p256dh')
    const token = pushSubscription.getKey('auth')
    const contentEncoding = (PushManager.supportedContentEncodings || ['aesgcm'])[0]

    const data = {
        endpoint: pushSubscription.endpoint,
        public_key: key ? btoa(String.fromCharCode.apply(null, new Uint8Array(key))) : null,
        auth_token: token ? btoa(String.fromCharCode.apply(null, new Uint8Array(token))) : null,
        content_encoding: contentEncoding
    }

    fetch('/webpush', {
        method: 'POST',
        body: JSON.stringify(data),
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-Token': csrfToken
        }
    })
        .then((result) => {
            return result.json();
        })
        .catch((error) => {
            console.log(error)
        });
}
