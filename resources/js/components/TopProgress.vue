<template>
    <div v-if="show" class="top-progress" :class="{ 'top-progress--error': error }">
        <div class="top-progress__bar" :style="barStyle"></div>
    </div>
</template>

<script>
export default {
    name: 'TopProgress',

    props: {
        color: { type: String, default: '#007BFF' },
        errorColor: { type: String, default: '#DC3545' },
    },

    data() {
        return {
            show: false,
            error: false,
            width: 0,
            timer: null,
        };
    },

    computed: {
        barStyle() {
            return {
                width: this.width + '%',
                backgroundColor: this.error ? this.errorColor : this.color,
            };
        },
    },

    methods: {
        start() {
            this.show = true;
            this.error = false;
            this.width = 0;
            clearInterval(this.timer);

            this.timer = setInterval(() => {
                if (this.width < 90) {
                    this.width += (90 - this.width) * 0.1;
                }
            }, 100);
        },

        done() {
            clearInterval(this.timer);
            this.width = 100;
            setTimeout(() => { this.show = false; }, 300);
        },

        fail() {
            clearInterval(this.timer);
            this.error = true;
            this.width = 100;
            setTimeout(() => { this.show = false; }, 1500);
        },
    },
};
</script>

<style scoped>
.top-progress {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 9999;
    height: 3px;
}

.top-progress__bar {
    height: 100%;
    transition: width 0.2s ease;
}
</style>
