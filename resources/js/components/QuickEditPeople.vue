<template>
    <div class="q-aside__group q-quick-edit-people">
        <div class="q-aside__label q-quick-edit-people__head">
            <span>{{ label }}<template v-if="multiple"> · {{ shown.length }}</template></span>
            <button v-if="multiple && editable && !adding" type="button" class="q-quick-edit-people__add" :aria-label="label + ': Person hinzufügen'" :disabled="saving" @click="startAdding">
                <svg class="icon-bs icon-14"><use href="/svg/bootstrap-icons.svg#plus-lg"></use></svg>
            </button>
        </div>

        <!-- one person: the whole row opens the picker -->
        <template v-if="!multiple">
            <person-dropdown v-if="editing" :people="people" :current_person="shown[0] || null" :autoopen="true" @selected="saveSingle" @blurred="editing = false"></person-dropdown>
            <component :is="editable ? 'button' : 'div'" v-else-if="shown.length" :type="editable ? 'button' : null" :aria-label="editable ? label + ' ändern' : null" class="q-aside__person q-quick-edit-people__row" :class="{ 'q-quick-edit-people__row--editable': editable }" @click="startEditing">
                <span class="q-avatar q-avatar--round q-avatar--sm" :class="avatarClass(shown[0])" :style="avatarStyle(shown[0])">{{ shown[0].avatar ? shown[0].avatar.initials : '' }}</span>
                <span class="q-aside__name text-truncate">{{ shown[0].name }}</span>
                <svg v-if="editable" class="icon-bs icon-12 q-quick-edit-people__icon"><use href="/svg/bootstrap-icons.svg#pencil"></use></svg>
            </component>
            <div v-else class="q-aside__person q-aside__person--muted">
                <span class="q-aside__name">{{ empty_text }}</span>
            </div>
        </template>

        <!-- several people: remove with the x on a row, add with the + in the header -->
        <template v-else>
            <div v-for="person in shown" :key="person.id" class="q-aside__person q-quick-edit-people__row" :class="{ 'q-aside__person--muted': muted, 'q-quick-edit-people__row--editable': editable }" @click="onRowClick(person)">
                <span class="q-avatar q-avatar--round q-avatar--sm" :class="avatarClass(person)" :style="avatarStyle(person)">{{ person.avatar ? person.avatar.initials : '' }}</span>
                <span class="q-aside__name text-truncate">{{ person.name }}</span>
                <button v-if="editable" type="button" class="q-quick-edit-people__remove" :aria-label="person.name + ' entfernen'" :disabled="saving || shown.length <= min" @click.stop="remove(person)">
                    <svg class="icon-bs icon-12"><use href="/svg/bootstrap-icons.svg#x-lg"></use></svg>
                </button>
            </div>
            <div v-if="!shown.length" class="q-aside__person q-aside__person--muted">
                <span class="q-aside__name">{{ empty_text }}</span>
            </div>
            <person-dropdown v-if="adding" class="mt-2" :people="unselected" :autoopen="true" @selected="add" @blurred="adding = false"></person-dropdown>
        </template>

        <div v-if="error" class="q-quick-edit__error" role="alert">{{ error }}</div>

        <!-- touch screens have no hover: tapping a row offers the removal in a bottom sheet -->
        <div v-if="multiple && editable" class="offcanvas offcanvas-bottom q-sheet" tabindex="-1" ref="sheet" :aria-label="label">
            <div class="q-sheet__handle" aria-hidden="true"><span class="q-sheet__handle-bar"></span></div>
            <div class="offcanvas-body">
                <div class="q-sheet__label">{{ sheetPerson ? sheetPerson.name : label }}</div>
                <button type="button" class="q-row q-quick-edit__row q-quick-edit-people__sheet-remove" @click="removeFromSheet">
                    <span class="q-row__title">Aus „{{ label }}“ entfernen</span>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "QuickEditPeople",

        data() {
            return {
                shown: [...this.current],
                blocked: [...this.exclude],
                editing: false,
                adding: false,
                saving: false,
                sheetPerson: null,
                error: '',
            }
        },

        computed: {
            // who can still be added: not already listed, and not someone the page rules out
            unselected() {
                return this.people.filter(person => {
                    return !this.shown.some(shown => shown.id === person.id) && !this.blocked.includes(person.id);
                });
            }
        },

        mounted() {
            window.addEventListener('quick-edit-saved', this.onSiblingSaved);
        },

        beforeUnmount() {
            window.removeEventListener('quick-edit-saved', this.onSiblingSaved);
        },

        methods: {
            // the responsible employee can't also be an involved one: the server drops them from the list
            onSiblingSaved(event) {
                const { url, field, value } = event.detail;

                if (this.multiple && url === this.url && field === 'employee_id') {
                    this.blocked = [value];
                    this.shown = this.shown.filter(person => person.id !== value);
                }
            },

            startEditing() {
                if (this.editable && !this.saving) {
                    this.error = '';
                    this.editing = true;
                }
            },

            startAdding() {
                this.error = '';
                this.adding = true;
            },

            saveSingle(person) {
                this.editing = false;

                if (this.shown[0] && person.id === this.shown[0].id) {
                    return;
                }

                this.save([person.id]);
            },

            add(person) {
                this.adding = false;
                this.save([...this.shown.map(shown => shown.id), person.id]);
            },

            remove(person) {
                if (this.shown.length <= this.min) {
                    this.error = this.min === 1 ? 'Es muss mindestens eine Person bleiben.' : 'Es müssen mindestens ' + this.min + ' Personen bleiben.';
                    return;
                }

                this.save(this.shown.filter(shown => shown.id !== person.id).map(shown => shown.id));
            },

            onRowClick(person) {
                if (!this.editable || !window.matchMedia('(hover: none)').matches) {
                    return;
                }

                this.sheetPerson = person;
                window.bootstrap.Offcanvas.getOrCreateInstance(this.$refs.sheet).show();
            },

            removeFromSheet() {
                window.bootstrap.Offcanvas.getOrCreateInstance(this.$refs.sheet).hide();

                if (this.sheetPerson) {
                    this.remove(this.sheetPerson);
                }
            },

            // shows the new list right away and puts the old one back if saving fails
            save(ids) {
                const previous = this.shown;

                this.saving = true;
                this.error = '';
                this.shown = this.people.filter(person => ids.includes(person.id));

                axios.patch(this.url, { [this.field]: this.multiple ? ids : ids[0] })
                    .then(response => {
                        const saved = response.data.value;
                        const savedIds = Array.isArray(saved) ? saved : [saved];

                        this.shown = this.people.filter(person => savedIds.includes(person.id));

                        // sibling editors on the same page (e.g. the involved employees) may need to react
                        window.dispatchEvent(new CustomEvent('quick-edit-saved', {
                            detail: { url: this.url, field: this.field, value: saved }
                        }));

                        if (this.reload) {
                            window.location.reload();
                        }
                    })
                    .catch(error => {
                        this.shown = previous;
                        this.error = this.errorMessage(error);
                    })
                    .finally(() => {
                        this.saving = false;
                    });
            },

            errorMessage(error) {
                const response = error.response;

                if (response && response.status === 422 && response.data.errors) {
                    return Object.values(response.data.errors)[0][0];
                }

                if (response && response.status === 403) {
                    return 'Dazu fehlt dir die Berechtigung.';
                }

                return 'Die Änderung konnte nicht gespeichert werden.';
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
            url: {
                type: String,
                required: true
            },
            field: {
                type: String,
                required: true
            },
            label: {
                type: String,
                required: true
            },
            multiple: {
                type: Boolean,
                default: false
            },
            // everyone that can be picked, shaped like the edit forms' people lists
            people: {
                type: Array,
                default() {
                    return [];
                }
            },
            current: {
                type: Array,
                default() {
                    return [];
                }
            },
            empty_text: {
                type: String,
                default: 'niemand'
            },
            editable: {
                type: Boolean,
                default: true
            },
            // lighter names, for secondary people lists
            muted: {
                type: Boolean,
                default: false
            },
            // a list can't be emptied below this many people
            min: {
                type: Number,
                default: 0
            },
            // people who can't be added (e.g. the responsible employee on the involved list)
            exclude: {
                type: Array,
                default() {
                    return [];
                }
            },
            // reload after saving, when the change affects other parts of the page
            reload: {
                type: Boolean,
                default: false
            }
        }
    }
</script>
