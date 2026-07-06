<template>
    <div class="q-toast" :class="[{'show': show}, variantClass]" v-show="show" v-cloak>
        <slot></slot>
    </div>
</template>

<script>
    export default {
        name: "Notification",

        props: ['type'],

        data() {
            return {
                show: false,
            }
        },

        computed: {
            variantClass: function () {
                return {
                    'q-toast--success': this.type === 'success',
                    'q-toast--info': this.type === 'info',
                    'q-toast--warning': this.type === 'warning',
                    'q-toast--danger': this.type === 'danger'
                }
            }
        },

        mounted() {
            if (this.type) {
                this.flash();
            }
        },

        methods: {
            flash() {
                this.show = true;

                setTimeout(() => {
                    this.hide()
                }, 5000);
            },

            hide() {
                this.show = false;
            }
        }
    }
</script>
