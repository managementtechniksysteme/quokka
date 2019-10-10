<template>
    <div id="notification" :class="[{'show': show}, backgroundClass, colorClass]" v-show="show" v-cloak>
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
            backgroundClass: function () {
                return {
                    'bg-success': this.type === 'success',
                    'bg-info': this.type === 'info',
                    'bg-warning': this.type === 'warning',
                    'bg-danger': this.type === 'danger'
                }
            },

            colorClass: function () {
                return {
                    'text-white': this.type === 'success' || this.type === 'info' ||this.type === 'danger',
                    'text-dark': this.type === 'warning',
                }
            }
        },

        mounted() {
            if(this.type) {
                this.flash();
            }
        },

        methods: {
            flash() {
                this.show = true;

                setTimeout(() => {
                    this.hide()
                },5000);
            },

            hide() {
                this.show = false;
            }
        }
    }
</script>

<style scoped>
    #notification {
        width: 100%;
        top: 0;
        left: 0;
        text-align: center;
        padding: 16px;
        position: fixed;
        font-size: 18px;
        z-index: 1;
    }

    #notification.show {
        -webkit-animation: fadein 0.5s, fadeout 0.5s 4.5s;
        animation: fadein 0.5s, fadeout 0.5s 4.5s;
    }

    @-webkit-keyframes fadein {
        from {top: -30px; opacity: 0;}
        to {top: 0; opacity: 1;}
    }

    @keyframes fadein {
        from {top: -30px; opacity: 0;}
        to {top: 0; opacity: 1;}
    }

    @-webkit-keyframes fadeout {
        from {top: 0; opacity: 1;}
        to {top: -30px; opacity: 0;}
    }

    @keyframes fadeout {
        from {top: 0; opacity: 1;}
        to {top: -30px; opacity: 0;}
    }
</style>