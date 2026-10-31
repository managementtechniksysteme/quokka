<template>
    <span class="q-quick-edit q-quick-edit-date" ref="root">
        <component :is="editable ? 'button' : 'span'" class="q-quick-edit__chip q-quick-edit__chip--plain q-quick-edit-date__value" :class="{ 'q-quick-edit__chip--static': !editable }" :type="editable ? 'button' : null" :aria-label="editable ? aria_label : null" :aria-expanded="editable ? (open ? 'true' : 'false') : null" :disabled="editable ? saving : null" @click="toggle">
            <span :class="{ 'q-statbar__value--empty': !selected }">{{ selected ? display(selected) : empty_text }}</span>
            <svg v-if="saved" class="icon-bs icon-12 q-quick-edit__saved"><use href="/svg/bootstrap-icons.svg#check-lg"></use></svg>
        </component>

        <template v-if="shownHint">
            <span class="q-statbar__sep">·</span>
            <span :class="shownHint.class">{{ shownHint.text }}</span>
        </template>

        <transition name="q-quick-edit-options">
            <div v-if="open" class="q-quick-edit-date__popover" :class="{ 'q-quick-edit-date__popover--right': alignRight }">
                <quick-edit-calendar :value="selected" :min="min" :max="max" :clearable="clearable" :aria_label="aria_label" @select="save" @clear="save(null)"></quick-edit-calendar>
            </div>
        </transition>

        <span v-if="error" class="q-quick-edit__error" role="alert">{{ error }}</span>

        <!-- narrow screens: the calendar opens as a bottom sheet instead of a popover -->
        <div v-if="editable" class="offcanvas offcanvas-bottom q-sheet" tabindex="-1" ref="sheet" :aria-label="aria_label">
            <div class="q-sheet__handle" aria-hidden="true"><span class="q-sheet__handle-bar"></span></div>
            <div class="offcanvas-body">
                <div class="q-sheet__label">{{ aria_label }}</div>
                <quick-edit-calendar v-if="sheetOpen" :value="selected" :min="min" :max="max" :clearable="clearable" :aria_label="aria_label" @select="save" @clear="save(null)"></quick-edit-calendar>
            </div>
        </div>
    </span>
</template>

<script>
    import QuickEditCalendar from './QuickEditCalendar.vue';

    export default {
        name: "QuickEditDate",

        components: { QuickEditCalendar },

        data() {
            return {
                selected: this.value || null,
                shownHint: this.hint,
                open: false,
                sheetOpen: false,
                alignRight: false,
                saving: false,
                saved: false,
                error: '',
            }
        },

        mounted() {
            document.addEventListener('mousedown', this.closeOnOutsideClick);
            document.addEventListener('keydown', this.closeOnEscape);

            if (this.$refs.sheet) {
                this.$refs.sheet.addEventListener('hidden.bs.offcanvas', () => this.sheetOpen = false);
            }
        },

        beforeUnmount() {
            document.removeEventListener('mousedown', this.closeOnOutsideClick);
            document.removeEventListener('keydown', this.closeOnEscape);
        },

        methods: {
            // "2026-10-31" -> "31.10.2026", like the pages render dates
            display(iso) {
                const [year, month, day] = iso.split('-');

                return day + '.' + month + '.' + year;
            },

            isMobile() {
                return window.matchMedia('(max-width: 767.98px)').matches;
            },

            sheet() {
                return window.bootstrap.Offcanvas.getOrCreateInstance(this.$refs.sheet);
            },

            toggle() {
                if (!this.editable || this.saving) {
                    return;
                }

                this.error = '';

                if (this.isMobile()) {
                    this.sheetOpen = true;
                    this.sheet().show();

                    return;
                }

                this.open = !this.open;

                if (this.open) {
                    // keep the popover inside the viewport when the cell sits near its right edge
                    const rect = this.$refs.root.getBoundingClientRect();

                    this.alignRight = rect.left + 290 > window.innerWidth;
                }
            },

            close() {
                this.open = false;

                if (this.sheetOpen && this.$refs.sheet) {
                    this.sheet().hide();
                }
            },

            // one click anywhere outside closes the calendar
            closeOnOutsideClick(event) {
                if (this.open && !this.$refs.root.contains(event.target)) {
                    this.open = false;
                }
            },

            closeOnEscape(event) {
                if (event.key === 'Escape') {
                    this.open = false;
                }
            },

            save(date) {
                this.close();

                if (date === this.selected) {
                    return;
                }

                const previous = this.selected;

                this.saving = true;
                this.error = '';
                this.selected = date;

                axios.patch(this.url, { [this.field]: date })
                    .then(response => {
                        this.selected = response.data.value;
                        this.shownHint = 'hint' in response.data ? response.data.hint : this.shownHint;
                        this.saved = true;
                        setTimeout(() => this.saved = false, 1500);

                        window.dispatchEvent(new CustomEvent('quick-edit-saved', {
                            detail: { url: this.url, field: this.field, value: response.data.value }
                        }));

                        if (this.reload) {
                            setTimeout(() => window.location.reload(), 500);
                        }
                    })
                    .catch(error => {
                        this.selected = previous;
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
            // ISO date (YYYY-MM-DD) or null
            value: {
                type: String,
                default: null
            },
            empty_text: {
                type: String,
                default: 'kein Datum'
            },
            aria_label: {
                type: String,
                default: 'Datum ändern'
            },
            clearable: {
                type: Boolean,
                default: false
            },
            editable: {
                type: Boolean,
                default: true
            },
            min: {
                type: String,
                default: null
            },
            max: {
                type: String,
                default: null
            },
            // note shown after the date, e.g. "überfällig": { text, class }
            hint: {
                type: Object,
                default: null
            },
            reload: {
                type: Boolean,
                default: false
            }
        }
    }
</script>
