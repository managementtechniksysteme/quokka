<template>
    <div>
        <VueSignaturePad class="bg-white border" :width="width" :height="height" ref="signaturePad" :options="{ onBegin, onEnd }" />
        <input v-if="signature" type="hidden" name="signature" id="signature" :value="signature" />
    </div>
</template>

<script>
    export default {
        name: 'SignaturePad',

        data() {
            return {
                signature: null,
                width: this.width ? this.width : "100%",
                height: this.width ? this.width : "20em",
            }
        },

        mounted() {
            var canvas = this.$refs.signaturePad.$el.querySelector('canvas');
            var ratio =  window.devicePixelRatio || 1;
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext("2d").scale(ratio, ratio);
        },

        methods: {
            onBegin() {

            },
            onEnd() {
                const { isEmpty, data } = this.$refs.signaturePad.saveSignature();
                this.signature = isEmpty ? null : data;
            }
        }
    };
</script>
