<template>
    <div>
        <input v-if="selected" type="hidden" id="company_id" name="company_id" :value="selected.id" />
        <v-select :options="companies" label="full_name" placeholder="Firma auswählen" :value="selected" :selectOnTab="true" @input="setSelected">
            <template v-slot:no-options>Keine passenden Einträge.</template>
        </v-select>
    </div>
</template>

<script>
    export default {
        name: "CompanyDropdown",

        data() {
            return {
                selected: this.current_company,
            }
        },

        methods: {
            setSelected(value) {
                this.selected = value;
            }
        },

        props: {
            companies: {
                type: Array,
                default() {
                    return [];
                }
            },
            current_company: {
                type: Object,
                default() {
                    return null;
                }
            }
        }

    }
</script>
