<template>
    <div>
        <v-select :options="unselected" label="name" placeholder="Person auswählen" :modelValue="null" :selectOnTab="true" @update:modelValue="addSelected">
            <template v-slot:no-options>Keine passenden Einträge.</template>
            <template v-slot:option="person">
                <div class="d-flex align-items-center gap-2">
                    <span class="q-avatar q-avatar--round q-avatar--sm" :class="avatarClass(person)" :style="avatarStyle(person)" v-if="person.avatar">{{ person.avatar.initials }}</span>
                    <span>{{ person.name }}</span>
                </div>
            </template>
        </v-select>
        <div v-if="selected.length" class="container-fluid mt-2">
            <div class="row py-2 align-items-center hover-highlight" v-for="person in selected">
                <input v-if="selected.length" type="hidden" :id="person.id" :name="inputname" :value="person.id" />
                <div class="col d-flex align-items-center gap-2">
                    <span class="q-avatar q-avatar--round q-avatar--sm" :class="avatarClass(person)" :style="avatarStyle(person)" v-if="person.avatar">{{ person.avatar.initials }}</span>
                    <span>{{ person.name }}</span>
                </div>
                <div class="col-auto ms-auto">
                    <button type="button" class="btn btn-sm btn-outline-danger" @click="removeSelected(person)">Entfernen</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "PeopleSelector",

        data() {
            return {
                selected: this.current_people ? this.current_people : [],
                unselected: this.current_people ? this.people.filter(person => {
                    return this.current_people.findIndex(current => current.id === person.id) === -1;
                }) : this.people
            }
        },

        methods: {
            addSelected(value) {
                this.unselected = this.removeFromArray(this.unselected, value);
                this.selected.push(value);
                this.sortArrayByName(this.selected);
            },

            removeSelected(value) {
                this.selected = this.removeFromArray(this.selected, value);
                this.unselected.push(value);
                this.sortArrayByName(this.unselected);
            },

            removeFromArray(people, value) {
                return people.filter(person => {
                    return person.id !== value.id;
                });
            },

            avatarClass(person) {
                return person.avatar && !person.avatar.hex ? 'q-avatar--' + person.avatar.colour : '';
            },

            avatarStyle(person) {
                if (person.avatar && person.avatar.hex) {
                    return { background: 'color-mix(in srgb, ' + person.avatar.hex + ' 20%, transparent)', color: person.avatar.hex };
                }
                return {};
            },

            sortArrayByName(people) {
                people.sort((a, b) => {
                    let a_lower = a.name.toLowerCase();
                    let b_lower = b.name.toLowerCase();
                    return a_lower > b_lower ? 1 : b_lower > a_lower ? -1 : 0;
                });
            }
        },

        props: {
            inputname: {
                type: String,
                default() {
                    return 'person_ids[]';
                }
            },
            people: {
                type: Array,
                default() {
                    return [];
                }
            },
            current_people: {
                type: Array,
                default() {
                    return [];
                }
            }
        }

    }
</script>
