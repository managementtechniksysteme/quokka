<template>
    <div>
        <input v-if="selected" type="hidden" :id="inputname" :name="inputname"  :value="selected.id" />
        <v-select :options="people" label="name" placeholder="Person auswählen" v-model="selected" :selectOnTab="true">
            <template v-slot:no-options>Keine passenden Einträge.</template>
            <template v-slot:option="person">
                <div class="d-flex align-items-center gap-2">
                    <span class="q-avatar q-avatar--round q-avatar--sm" :class="avatarClass(person)" :style="avatarStyle(person)" v-if="person.avatar">{{ person.avatar.initials }}</span>
                    <span>{{ person.name }}</span>
                </div>
            </template>
            <template v-slot:selected-option="person">
                <span class="q-avatar q-avatar--round q-avatar--sm me-2" :class="avatarClass(person)" :style="avatarStyle(person)" v-if="person.avatar">{{ person.avatar.initials }}</span>
                <span>{{ person.name }}</span>
            </template>
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
            },

            avatarClass(person) {
                return person.avatar && !person.avatar.hex ? 'q-avatar--' + person.avatar.colour : '';
            },

            avatarStyle(person) {
                if (person.avatar && person.avatar.hex) {
                    return { background: 'color-mix(in srgb, ' + person.avatar.hex + ' 20%, transparent)', color: person.avatar.hex };
                }
                return {};
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
