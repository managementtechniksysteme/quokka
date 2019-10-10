<template>
    <div>
        <input v-if="selected" type="hidden" id="address_id" name="address_id" :value="selected.id" />
        <v-select :options="addresses" label="address_line" placeholder="Addresse auswählen" :value="selected" @input="setSelected"></v-select>
    </div>
</template>

<script>
    export default {
        name: "AddressForm",

        data() {
            return {
                selected: this.current_address,
            }
        },

        methods: {
            setSelected(value) {
                this.selected = value;
            }
        },

        props: {
            addresses: {
                type: Array,
                default() {
                    return [];
                }
            },
            current_address: {
                type: Object,
                default() {
                    return null;
                }
            }
        }

    }
</script>