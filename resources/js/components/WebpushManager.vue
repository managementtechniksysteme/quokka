<template>
    <div>

        <div v-if="webpush_enabled !== null">
            <div v-if="webpush_enabled === false">
                <p>Push Benachrictungen sind auf diesem Gerät deaktiviert. Aktiviere Benachrichtigungen mit einem Klick auf den Button.</p>
                <button class="btn btn-primary d-inlin-flex align-items-center" @click="subscribeUser">
                    <svg class="icon icon-16 mr-2">
                        <use xlink:href="svg/feather-sprite.svg#bell"></use>
                    </svg>
                    Push Benachrichtigungen aktivieren
                </button>
            </div>
            <div v-if="webpush_enabled === true">
                <p>Push Benachrictungen sind auf diesem Gerät aktiviert. Deaktiviere Benachrichtigungen mit einem Klick auf den Button.</p>
                <button class="btn btn-outline-danger d-inline-flex align-items-center" @click="unsubscribeUser">
                    <svg class="icon icon-16 mr-2">
                        <use xlink:href="svg/feather-sprite.svg#bell-off"></use>
                    </svg>
                    Push Benachrichtigungen deaktivieren
                </button>
            </div>

            <div v-if="success_notification !== null" :key="notification_key">
                <notification type="success" v-cloak>
                    <div class="d-inline-flex align-items-center">
                        <svg class="icon icon-24 mr-2">
                            <use xlink:href="svg/feather-sprite.svg#check"></use>
                        </svg>
                        {{success_notification}}
                    </div>
                </notification>
            </div>
            <div v-if="error_notification !== null" :key="notification_key">
                <notification type="danger" v-cloak>
                    <div class="d-inline-flex align-items-center">
                        <svg class="icon icon-24 mr-2">
                            <use xlink:href="svg/feather-sprite.svg#alert-octagon"></use>
                        </svg>
                        {{error_notification}}
                    </div>
                </notification>
            </div>
        </div>

        <div v-if="webpush_enabled === null">
            <p>Push Benachrictungen sind auf diesem Gerät nicht verfügbar.</p>
        </div>

    </div>
</template>

<script>
    export default {
        name: "WebpushManager",

        data() {
            return {
                webpush_enabled: null,
                error_notification: null,
                success_notification: null,
                notification_key: 1,
            }
        },

        created() {
            if ('showNotification' in ServiceWorkerRegistration.prototype && 'PushManager' in window) {
                navigator.serviceWorker.ready.then(registration => {
                    registration.pushManager.getSubscription()
                        .then(subscription => {
                            this.webpush_enabled = !!subscription;
                        })
                        .catch(error => {
                            console.log(error)
                        })
                })
            }
        },

        methods: {
            subscribeUser() {
                navigator.serviceWorker.ready
                    .then((registration) => {
                        const subscribeOptions = {
                            userVisibleOnly: true,
                            applicationServerKey: this.urlBase64ToUint8Array(VAPID_PUBLIC_KEY)
                        };

                        return registration.pushManager.subscribe(subscribeOptions);
                    })
                    .then((pushSubscription) => {
                        this.updatePushSubscription(pushSubscription);

                        this.webpush_enabled = true;
                        this.success_notification = 'Push Benachrichtigungen wurden erfolgreich aktiviert.';
                        this.error_notification = null;
                        this.notification_key++;
                    })
                    .catch(error => {
                        this.webpush_enabled = false;
                        this.success_notification = null;
                        this.notification_key++;

                        if (Notification.permission === 'denied') {
                            this.error_notification = 'Die notwendigen Berechtigungen wurden nicht erteilt.';
                        } else {
                            this.error_notification = 'Unerwarteter Fehler beim Aktivieren der Push Benachrichtigungen.';
                        }
                    });
            },

            unsubscribeUser() {
                navigator.serviceWorker.ready
                    .then(registration => {
                        registration.pushManager.getSubscription()
                            .then(subscription => {
                                if (!subscription) {
                                    this.webpush_enabled = false;
                                    this.success_notification = null;
                                    this.error_notification = null;
                                    return
                                }
                                subscription.unsubscribe().then(() => {
                                    this.deleteSubscription(subscription);

                                    this.webpush_enabled = false;
                                    this.success_notification = 'Push Benachrichtigungen wurden erfolgreich deaktiviert.';
                                    this.error_notification = null;
                                    this.notification_key++;
                            }).catch(error => {
                                    this.webpush_enabled = true;
                                    this.success_notification = null;
                                    this.error_notification = 'Unerwarteter Fehler beim Deaktivieren der Push Benachrichtigungen.';
                                    this.notification_key++;
                            })
                    }).catch(error => {
                            this.webpush_enabled = true;
                            this.success_notification = null;
                            this.error_notification = 'Unerwarteter Fehler beim Deaktivieren der Push Benachrichtigungen.';
                            this.notification_key++;
                    })
                })
            },

            updatePushSubscription(pushSubscription) {
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
                    .catch(error => {
                        console.log(error);
                    });
            },

            deleteSubscription(pushSubscription) {
                const csrfToken = document.querySelector('meta[name=csrf-token]').getAttribute('content');

                const data = {
                    endpoint: pushSubscription.endpoint,
                }

                fetch('/webpush', {
                    method: 'DELETE',
                    body: JSON.stringify(data),
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': csrfToken
                    }
                })
                    .then((result) => {
                        return result;
                    })
                    .catch(error => {
                        console.log(error);
                    });
            },

            urlBase64ToUint8Array(base64String) {
                var padding = '='.repeat((4 - base64String.length % 4) % 4);
                var base64 = (base64String + padding)
                    .replace(/\-/g, '+')
                    .replace(/_/g, '/');

                var rawData = window.atob(base64);
                var outputArray = new Uint8Array(rawData.length);

                for (var i = 0; i < rawData.length; ++i) {
                    outputArray[i] = rawData.charCodeAt(i);
                }
                return outputArray;
            }
        }

    }
</script>
