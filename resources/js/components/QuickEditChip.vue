<template>
    <span class="q-quick-edit" :class="{ 'is-open': open, 'is-saving': saving, 'q-quick-edit--below': placement === 'below' }">
        <component :is="editable ? 'button' : 'span'" class="q-quick-edit__chip" :class="[chip_class, current.class, { 'q-quick-edit__chip--static': !editable }]" :type="editable ? 'button' : null" :aria-label="editable ? aria_label : null" :aria-expanded="editable ? (open ? 'true' : 'false') : null" :disabled="editable ? saving : null" @click="toggle">
            <svg v-if="current.icon" class="icon-bs icon-12"><use :href="icon(current.icon)"></use></svg>
            <span>{{ prefix }}{{ current.label }}{{ suffix }}</span>
            <svg v-if="saved" class="icon-bs icon-12 q-quick-edit__saved"><use :href="icon('check-lg')"></use></svg>
            <svg v-else-if="editable" class="icon-bs icon-12 q-quick-edit__caret"><use :href="icon('chevron-down')"></use></svg>
        </component>

        <transition name="q-quick-edit-options">
            <span v-if="open" class="q-quick-edit__options" role="listbox" :aria-label="aria_label">
                <button v-for="option in options" :key="String(option.value)" type="button" class="q-quick-edit__option" :class="{ 'is-selected': option.value === selected }" role="option" :aria-selected="option.value === selected ? 'true' : 'false'" :disabled="option.disabled" @click="select(option)">
                    <svg v-if="option.icon" class="icon-bs icon-12"><use :href="icon(option.icon)"></use></svg>
                    {{ option.label }}
                </button>
            </span>
        </transition>

        <span v-if="error" class="q-quick-edit__error" role="alert">{{ error }}</span>

        <div v-if="editable" class="offcanvas offcanvas-bottom q-sheet" tabindex="-1" ref="sheet" :aria-label="aria_label">
            <div class="q-sheet__handle" aria-hidden="true"><span class="q-sheet__handle-bar"></span></div>
            <div class="offcanvas-body">
                <div class="q-sheet__label">{{ aria_label }}</div>
                <button v-for="option in options" :key="String(option.value)" type="button" class="q-row q-quick-edit__row" :disabled="option.disabled" @click="select(option)">
                    <span class="q-row__title">{{ option.label }}</span>
                    <svg v-if="option.value === selected" class="icon-bs icon-18 q-row__check"><use :href="icon('check')"></use></svg>
                </button>
            </div>
        </div>
    </span>
</template>

<script>
    export default {
        name: "QuickEditChip",

        data() {
            return {
                selected: this.value,
                open: false,
                saving: false,
                saved: false,
                error: '',
            }
        },

        computed: {
            current() {
                return this.options.find(option => option.value === this.selected) || this.options[0];
            },

            editable() {
                return this.options.length > 1;
            }
        },

        mounted() {
            document.addEventListener('click', this.closeOnOutsideClick);
            document.addEventListener('keydown', this.closeOnEscape);
        },

        beforeUnmount() {
            document.removeEventListener('click', this.closeOnOutsideClick);
            document.removeEventListener('keydown', this.closeOnEscape);
        },

        methods: {
            icon(name) {
                return '/svg/bootstrap-icons.svg#' + name;
            },

            isMobile() {
                return window.matchMedia('(max-width: 767.98px)').matches;
            },

            sheet() {
                return window.bootstrap.Offcanvas.getOrCreateInstance(this.$refs.sheet);
            },

            toggle() {
                if (!this.editable) {
                    return;
                }

                this.error = '';

                if (this.isMobile()) {
                    this.sheet().show();
                } else {
                    this.open = !this.open;
                }
            },

            close() {
                this.open = false;

                if (this.editable && this.$refs.sheet) {
                    this.sheet().hide();
                }
            },

            closeOnOutsideClick(event) {
                if (this.open && !this.$el.contains(event.target)) {
                    this.open = false;
                }
            },

            closeOnEscape(event) {
                if (event.key === 'Escape') {
                    this.open = false;
                }
            },

            select(option) {
                if (option.value === this.selected) {
                    this.close();
                    return;
                }

                this.saving = true;
                this.error = '';

                axios.patch(this.url, { [this.field]: option.value })
                    .then(() => {
                        this.selected = option.value;
                        this.flashSaved();

                        if (this.reload) {
                            setTimeout(() => window.location.reload(), 500);
                        }
                    })
                    .catch(error => {
                        this.error = this.errorMessage(error);
                    })
                    .finally(() => {
                        this.saving = false;
                        this.close();
                    });
            },

            flashSaved() {
                this.saved = true;
                setTimeout(() => this.saved = false, 1500);
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
            value: {
                type: [String, Number, Boolean],
                default: null
            },
            // [{ value, label, class?, icon?, disabled? }] -- the current option decides how the chip looks
            options: {
                type: Array,
                required: true
            },
            prefix: {
                type: String,
                default: ''
            },
            // static text after the label, e.g. the temperature next to the weather
            suffix: {
                type: String,
                default: ''
            },
            // 'beside': the choices unfold next to the chip; 'below': they open as a small menu under
            // it, for chips in a stat strip where unfolding sideways would push the cell onto a new row
            placement: {
                type: String,
                default: 'beside'
            },
            chip_class: {
                type: String,
                default: 'q-chip'
            },
            aria_label: {
                type: String,
                default: 'Wert ändern'
            },
            // reload the page after saving, for fields that change other things on it
            reload: {
                type: Boolean,
                default: false
            }
        }
    }
</script>
