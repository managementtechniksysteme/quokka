<template>
    <div>
        <input v-if="selected" type="hidden" :id="inputname" :name="inputname" :value="selected" />
        <v-select :options="units" placeholder="Einheit auswählen" :value="selected" :selectOnTab="true" @input="setSelected" :taggable="taggable">
            <template v-slot:no-options>Keine passenden Einträge.</template>
        </v-select>
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
            inputname: {
                type: String,
                default() {
                    return 'unit';
                }
            },

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
            },

            taggable: {
                type: Boolean,
                default() {
                    return true;
                }
            }
        }

    }
</script>
