<template>
    <div>
        <input v-if="selected" type="hidden" :id="inputname" :name="inputname"  :value="selected.id" />
        <v-select :options="projects" label="name" placeholder="Projekt auswählen" :value="selected" @input="setSelected"></v-select>
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

        methods: {
            setSelected(value) {
                this.selected = value;
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
            }
        }

    }
</script>
