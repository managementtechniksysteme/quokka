<template>
    <div>
        <div v-if="qr_scanner_error !== null">
            <notification type="danger" v-cloak>
                <div class="d-inline-flex align-items-center">
                    <svg class="feather feather-24 mr-2">
                        <use xlink:href="svg/feather-sprite.svg#alert-octagon"></use>
                    </svg>
                    {{qr_scanner_error}}
                </div>
            </notification>
            <div class="text-center">
                <img class="empty-state" src="svg/notify.svg" alt="notify" />
                <p class="lead text-muted">{{qr_scanner_error}}</p>
            </div>
        </div>

        <notification v-if="qr_url_error !== null" type="warning" v-cloak>
            <div class="d-inline-flex align-items-center">
                <svg class="feather feather-24 mr-2">
                    <use xlink:href="svg/feather-sprite.svg#alert-triangle"></use>
                </svg>
                {{qr_url_error}}
            </div>
        </notification>

        <qrcode-stream @init="onInit" @decode="onDecode"></qrcode-stream>

    </div>
</template>

<script>
    export default {
        name: "QrScanner",

        data() {
            return {
                qr_scanner_error: null,
                qr_url_error: null,
                vibration_duration: 100,
            }
        },

        methods: {
            async onInit (promise) {
                try {
                    const { capabilities } = await promise;
                } catch (error) {
                    if (error.name === 'NotAllowedError') {
                        this.qr_scanner_error = 'Es fehlen die Berechtigungen zur Benutztung der Kamera.'
                    } else if (error.name === 'NotFoundError') {
                        console.log(this.url_whitelist);
                        this.qr_scanner_error = 'Es wurde kein passende Kamerahardware gefunden.'
                    } else if (error.name === 'NotSupportedError') {
                        this.qr_scanner_error = 'Die Kamerafunktion kann auf dieser Seite nicht verwendet werden.'
                    } else if (error.name === 'NotReadableError') {
                        this.qr_scanner_error = 'Die Kamera ist eventuell bereits in Verwendung.'
                    } else if (error.name === 'OverconstrainedError') {
                        this.qr_scanner_error = 'Die angeforderte Kamera wurde nicht gefunden.'
                    } else if (error.name === 'StreamApiNotSupportedError') {
                        this.qr_scanner_error = 'Der Browser unterstützt nicht alle notwendigen Funktionen zur Verwendung der Kamera.'
                    } else {
                        this.qr_scanner_error = 'Es ist ein unbekannter Fehler aufgetreten.'
                    }
                }
            },

            onDecode (decodedString) {
                this.qr_url_error = null;

                if(decodedString.startsWith(this.url_whitelist)) {
                    window.navigator.vibrate(this.vibration_duration);
                    window.location = decodedString;
                } else {
                    this.qr_url_error = 'Der eingescannte QR-Code wurde nicht von dieser Applikation generiert.'
                }
            }
        },

        props: {
            url_whitelist: {
                type: String,
                default() {
                    return '';
                }
            },
        }

    }
</script>
