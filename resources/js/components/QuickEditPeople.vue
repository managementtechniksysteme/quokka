<template>
    <div class="q-aside__group q-quick-edit-people">
        <div class="q-aside__label q-quick-edit-people__head">
            <span>{{ label }}<template v-if="multiple"> · {{ shown.length }}</template></span>
            <button v-if="editable && !editing" type="button" class="q-quick-edit-people__edit" :aria-label="label + ' ändern'" @click="startEditing">
                <svg class="icon-bs icon-12"><use href="/svg/bootstrap-icons.svg#pencil"></use></svg>
            </button>
        </div>

        <template v-if="!editing">
            <div v-for="person in shown" :key="person.id" class="q-aside__person" :class="{ 'q-aside__person--muted': multiple }">
                <span class="q-avatar q-avatar--round q-avatar--sm" :class="avatarClass(person)" :style="avatarStyle(person)">{{ person.avatar ? person.avatar.initials : '' }}</span>
                <span class="q-aside__name text-truncate">{{ person.name }}</span>
            </div>
            <div v-if="!shown.length" class="q-aside__person q-aside__person--muted">
                <span class="q-aside__name">{{ empty_text }}</span>
            </div>
        </template>

        <template v-else>
            <person-dropdown v-if="!multiple" :key="resetKey" :people="people" :current_person="shown[0] || null" @selected="saveSingle"></person-dropdown>
            <people-selector v-else :key="resetKey" :people="people" :current_people="draftStart" @changed="draft = $event"></people-selector>

            <div class="q-quick-edit-people__actions">
                <button v-if="multiple" type="button" class="btn btn-primary btn-sm text-white" :disabled="saving" @click="saveMultiple">Speichern</button>
                <button type="button" class="btn q-btn btn-sm" :disabled="saving" @click="cancel">{{ multiple ? 'Abbrechen' : 'Schließen' }}</button>
            </div>
        </template>

        <div v-if="error" class="q-quick-edit__error" role="alert">{{ error }}</div>
    </div>
</template>

<script>
    export default {
        name: "QuickEditPeople",

        data() {
            return {
                shown: [...this.current],
                draft: null,
                draftStart: [],
                editing: false,
                saving: false,
                resetKey: 0,
                error: '',
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
                    this.shown = this.shown.filter(person => person.id !== value);
                }
            },

            startEditing() {
                this.error = '';
                this.draft = this.shown.map(person => person.id);
                this.draftStart = [...this.shown];
                this.resetKey++;
                this.editing = true;
            },

            cancel() {
                this.editing = false;
                this.error = '';
            },

            saveSingle(person) {
                if (!person || (this.shown[0] && person.id === this.shown[0].id)) {
                    return;
                }

                this.save(person.id);
            },

            saveMultiple() {
                this.save(this.draft);
            },

            save(value) {
                this.saving = true;
                this.error = '';

                axios.patch(this.url, { [this.field]: value })
                    .then(response => {
                        const saved = response.data.value;
                        const ids = Array.isArray(saved) ? saved : [saved];

                        this.shown = this.people.filter(person => ids.includes(person.id));
                        this.editing = false;

                        // sibling editors on the same page (e.g. the involved employees) may need to react
                        window.dispatchEvent(new CustomEvent('quick-edit-saved', {
                            detail: { url: this.url, field: this.field, value: saved }
                        }));

                        if (this.reload) {
                            window.location.reload();
                        }
                    })
                    .catch(error => {
                        this.error = this.errorMessage(error);
                        // drop the editor's unsaved selection so it matches what is stored
                        this.resetKey++;
                        this.draft = this.shown.map(person => person.id);
                        this.draftStart = [...this.shown];
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
            // reload after saving, when the change affects other parts of the page
            reload: {
                type: Boolean,
                default: false
            }
        }
    }
</script>
