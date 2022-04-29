<template>
    <div>
        <input v-if="selected" type="hidden" id="role_id" name="role_id" :value="selected.id" />
        <v-select :options="roles" label="name" placeholder="Rolle auswählen" :value="selected" :selectOnTab="true" @input="setSelected">
            <template v-slot:no-options>Keine passenden Einträge.</template>
        </v-select>
    </div>
</template>

<script>
    export default {
        name: "RoleDropdown",

        data() {
            return {
                selected: this.current_role,
            }
        },

        methods: {
            setSelected(value) {
                this.selected = value;
            }
        },

        props: {
            roles: {
                type: Array,
                default() {
                    return [];
                }
            },
            current_role: {
                type: Object,
                default() {
                    return null;
                }
            }
        }

    }
</script>
