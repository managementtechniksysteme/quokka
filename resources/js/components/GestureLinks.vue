<template>
    <div class="gesture-area" ref="gestureArea" v-hammer:pan="onPan">
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

        methods: {
            onPan(event) {
                const minimumOffset = 200;
                const scaleFactor = 10;
                const vibrationDuration = 100;

                this.totalOffset = this.totalOffset + event.deltaX/scaleFactor;

                this.$refs.gestureArea.style.setProperty("--x", event.deltaX/scaleFactor);

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

</style>