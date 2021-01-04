<template>
    <div class="gesture-area rounded" ref="gestureArea" v-hammer:pan="onPan">
        <div v-bind:class="panClasses" class="overlay rounded position-absolute w-100 h-100 z-10" ref="overlay">
            <div v-bind:class="panRightClasses" class="rounded bg-blue-100 text-blue-800 align-items-center justify-content-center h-100 w-100 mr-auto">
                <svg class="feather feather-24 mr-2">
                    <use xlink:href="svg/feather-sprite.svg#edit"></use>
                </svg>
            </div>
            <div v-bind:class="panLeftClasses" class="rounded bg-yellow-100 text-yellow-800 align-items-center justify-content-center h-100 w-100 ml-auto">
                <svg class="feather feather-24 mr-2">
                    <use xlink:href="svg/feather-sprite.svg#star"></use>
                </svg>
            </div>
        </div>

        <slot></slot>
    </div>
</template>

<script>
    export default {
        name: "GestureLinks",

        data() {
            return {
                totalOffset: 0
            }
        },

        computed: {
            panClasses: function() {
                return {
                    'd-none': this.totalOffset === 0,
                    'd-flex': this.totalOffset !== 0,
                }
            },
            panLeftClasses: function() {
                return {
                    'd-none': this.totalOffset >= 0,
                    'd-flex': this.totalOffset < 0,
                }
            },
            panRightClasses: function () {
                return {
                    'd-none': this.totalOffset <= 0,
                    'd-flex': this.totalOffset > 0,
                }
            }
        },

        methods: {
            onPan(event) {
                const minimumOffset = 200;
                const scaleFactor = 10;
                const vibrationDuration = 100;

                this.totalOffset = this.totalOffset + event.deltaX/scaleFactor;

                this.$refs.gestureArea.style.setProperty("--x", event.deltaX/scaleFactor);
                this.$refs.overlay.style.setProperty("--opacity", Math.abs(event.deltaX/scaleFactor));

                if(event.isFinal) {
                    this.$refs.gestureArea.style.setProperty("--x", 0);

                    if(this.totalOffset < -minimumOffset && this.pan_left) {
                        window.navigator.vibrate(vibrationDuration);
                        window.location = this.pan_left;
                    }
                    else if(this.totalOffset > minimumOffset && this.pan_right) {
                        window.navigator.vibrate(vibrationDuration);
                        window.location = this.pan_right;
                    }

                    this.totalOffset = 0;
                }
            },
        },

        props: ['pan_left', 'pan_right']
    }
</script>

<style>
    .gesture-area {
        transform: translateX(calc(var(--x, 0) * 1%));
    }

    .overlay {
        opacity: calc(var(--opacity, 0) * 0.2);
    }
</style>
