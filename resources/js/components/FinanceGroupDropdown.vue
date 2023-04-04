<template>
    <div>
        <input v-if="selected" type="hidden" :id="group_inputname" :name="inputname"  :value="selected.id" />
        <v-select :options="finance_groups" label="title" placeholder="Finanzgruppe auswählen" :value="selected" :selectOnTab="true" @input="setSelected">
            <template v-slot:no-options>Keine passenden Einträge.</template>
        </v-select>
    </div>
</template>

<script>
    export default {
        name: "FinanceGroupDropdown",

        data() {
            return {
                selected: this.current_finance_group,
            }
        },

        methods: {
            setSelected(value) {
                this.selected = value;
            },
        },

        props: {
            inputname: {
                type: String,
                default() {
                    return 'finance_group_id';
                }
            },
            finance_groups: {
                type: Array,
                default() {
                    return [];
                }
            },
            current_finance_group: {
                type: Object,
                default() {
                    return null;
                }
            }
        }

    }
</script>
