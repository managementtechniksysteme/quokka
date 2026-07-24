<template>
    <div>
        <vue-signature-pad class="q-signpad" :custom-style="customStyle" ref="signaturePad" :options="{ onBegin, onEnd }" />
        <input v-if="signature" type="hidden" name="signature" id="signature" :value="signature" />
    </div>
</template>

<script>
    import VueSignaturePad from 'vue3-signature-pad';

    export default {
        name: 'SignaturePadWrapper',

        components: { VueSignaturePad },

        data() {
            return {
                signature: null,
                customStyle: { width: '100%', height: '20em' },
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
