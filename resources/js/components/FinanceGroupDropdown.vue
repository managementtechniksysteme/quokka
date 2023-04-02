<template>
    <div>
        <input v-if="selected && selected.type === 'group'" type="hidden" :id="group_inputname" :name="group_inputname"  :value="selected.id" />
        <input v-if="selected && selected.type === 'project'" type="hidden" :id="project_inputname" :name="project_inputname"  :value="selected.id" />
        <v-select :getOptionKey="getOptionKey" :options="finance_groups" label="title_string" placeholder="Finanzgruppe auswählen" :value="selected" :selectOnTab="true" @input="setSelected">
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

            getOptionKey(value) {
                return value.type + value.id.toString();
            }
        },

        props: {
            group_inputname: {
                type: String,
                default() {
                    return 'finance_group_id';
                }
            },
            project_inputname: {
                type: String,
                default() {
                    return 'project_id';
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
