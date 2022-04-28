<template>
    <div>
        <input v-if="selected" type="hidden" :id="inputname" :name="inputname"  :value="selected.id" />
        <v-select :options="people" label="name" placeholder="Person auswählen" :value="selected" :selectOnTab="true" @input="setSelected">
            <template v-slot:no-options>Keine passenden Einträge.</template>
        </v-select>
    </div>
</template>

<script>
    export default {
        name: "PersonDropdown",

        data() {
            return {
                selected: this.current_person,
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
                    return 'person_id';
                }
            },
            people: {
                type: Array,
                default() {
                    return [];
                }
            },
            current_person: {
                type: Object,
                default() {
                    return null;
                }
            }
        }

    }
</script>
