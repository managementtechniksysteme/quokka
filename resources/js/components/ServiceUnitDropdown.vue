<template>
    <div>
        <input v-if="selected" type="hidden" id="unit" name="unit"  :value="selected" />
        <v-select :options="units" placeholder="Einheit auswählen" :value="selected" @input="setSelected" taggable></v-select>
    </div>
</template>

<script>
    export default {
        name: "ServiceUnitDropdown",

        data() {
            return {
                selected: this.current_unit,
            }
        },

        methods: {
            setSelected(value) {
                this.selected = value;
            }
        },

        props: {
            units: {
                type: Array,
                default() {
                    return [];
                }
            },
            current_unit: {
                type: String,
                default() {
                    return '';
                }
            }
        }

    }
</script>
