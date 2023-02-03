<template>
    <div>
        <input v-if="selected" type="hidden" :id="inputname" :name="inputname"  :value="selected.id" />
        <v-select :options="projects" label="name" placeholder="Projekt auswählen" :value="selected" :selectOnTab="true" @input="setSelected">
            <template v-slot:no-options>Keine passenden Einträge.</template>
        </v-select>
    </div>
</template>

<script>
    export default {
        name: "ProjektDropdown",

        data() {
            return {
                selected: this.current_project,
            }
        },

        mounted() {
            if(this.selected && this.change_event) {
                this.$nextTick(function() {
                    document.dispatchEvent(new CustomEvent(this.change_event, { detail: this.selected.id }));
                })
            }
        },

        methods: {
            setSelected(value) {
                this.selected = value;

                if(this.change_event) {
                    document.dispatchEvent(new CustomEvent(this.change_event, { detail: value.id }));
                }
            }
        },

        props: {
            inputname: {
                type: String,
                default() {
                    return 'project_id';
                }
            },
            projects: {
                type: Array,
                default() {
                    return [];
                }
            },
            current_project: {
                type: Object,
                default() {
                    return null;
                }
            },
            change_event: {
                type: String,
                default() {
                    return null;
                }
            }
        }

    }
</script>
