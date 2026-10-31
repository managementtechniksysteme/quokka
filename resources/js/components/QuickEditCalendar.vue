<template>
    <div class="q-calendar" role="dialog" :aria-label="aria_label">
        <div class="q-calendar__head">
            <button type="button" class="q-calendar__nav" aria-label="Vorheriger Monat" @click="shiftMonth(-1)">
                <svg class="icon-bs icon-14"><use href="/svg/bootstrap-icons.svg#chevron-left"></use></svg>
            </button>
            <span class="q-calendar__title" aria-live="polite">{{ monthNames[viewMonth] }} {{ viewYear }}</span>
            <button type="button" class="q-calendar__nav" aria-label="Nächster Monat" @click="shiftMonth(1)">
                <svg class="icon-bs icon-14"><use href="/svg/bootstrap-icons.svg#chevron-right"></use></svg>
            </button>
        </div>

        <div class="q-calendar__weekdays" aria-hidden="true">
            <span v-for="weekday in weekdays" :key="weekday">{{ weekday }}</span>
        </div>

        <div class="q-calendar__grid" role="grid" @keydown="onKeydown">
            <button v-for="day in days" :key="day.iso" type="button" class="q-calendar__day" :class="{ 'is-outside': !day.inMonth, 'is-today': day.today, 'is-selected': day.selected }" :data-date="day.iso" :tabindex="day.iso === focused ? 0 : -1" :disabled="day.disabled" :aria-label="longLabel(day.iso)" :aria-pressed="day.selected ? 'true' : 'false'" @click="$emit('select', day.iso)">
                {{ day.day }}
            </button>
        </div>

        <div class="q-calendar__foot">
            <button type="button" class="q-calendar__action" :disabled="todayDisabled" @click="$emit('select', todayIso)">Heute</button>
            <button v-if="clearable && value" type="button" class="q-calendar__action q-calendar__action--danger" @click="$emit('clear')">Entfernen</button>
        </div>
    </div>
</template>

<script>
    const pad = number => String(number).padStart(2, '0');

    const toIso = date => date.getFullYear() + '-' + pad(date.getMonth() + 1) + '-' + pad(date.getDate());

    const fromIso = iso => {
        const [year, month, day] = iso.split('-').map(Number);

        return new Date(year, month - 1, day);
    };

    export default {
        name: "QuickEditCalendar",

        data() {
            const start = this.value ? fromIso(this.value) : new Date();

            return {
                viewYear: start.getFullYear(),
                viewMonth: start.getMonth(),
                focused: this.value || toIso(new Date()),
                monthNames: ['Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'],
                weekdays: ['Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa', 'So'],
            }
        },

        computed: {
            todayIso() {
                return toIso(new Date());
            },

            todayDisabled() {
                return this.isDisabled(this.todayIso);
            },

            // six Monday-first weeks covering the viewed month
            days() {
                const first = new Date(this.viewYear, this.viewMonth, 1);
                const offset = (first.getDay() + 6) % 7;
                const days = [];

                for (let index = 0; index < 42; index++) {
                    const date = new Date(this.viewYear, this.viewMonth, 1 - offset + index);
                    const iso = toIso(date);

                    days.push({
                        iso,
                        day: date.getDate(),
                        inMonth: date.getMonth() === this.viewMonth,
                        today: iso === this.todayIso,
                        selected: iso === this.value,
                        disabled: this.isDisabled(iso),
                    });
                }

                return days;
            }
        },

        mounted() {
            // keyboard users land on the selected (or today's) day
            this.$nextTick(() => this.focusDay());
        },

        methods: {
            isDisabled(iso) {
                return (this.min !== null && iso < this.min) || (this.max !== null && iso > this.max);
            },

            longLabel(iso) {
                const date = fromIso(iso);

                return date.getDate() + '. ' + this.monthNames[date.getMonth()] + ' ' + date.getFullYear();
            },

            shiftMonth(delta) {
                const date = new Date(this.viewYear, this.viewMonth + delta, 1);

                this.viewYear = date.getFullYear();
                this.viewMonth = date.getMonth();
            },

            focusDay() {
                const button = this.$el.querySelector('.q-calendar__day[tabindex="0"]');

                if (button) {
                    button.focus();
                }
            },

            // arrows move by day / week, page keys by month
            onKeydown(event) {
                const steps = { ArrowLeft: -1, ArrowRight: 1, ArrowUp: -7, ArrowDown: 7 };
                const date = fromIso(this.focused);

                if (event.key in steps) {
                    date.setDate(date.getDate() + steps[event.key]);
                } else if (event.key === 'PageUp' || event.key === 'PageDown') {
                    date.setMonth(date.getMonth() + (event.key === 'PageUp' ? -1 : 1));
                } else {
                    return;
                }

                event.preventDefault();

                this.focused = toIso(date);
                this.viewYear = date.getFullYear();
                this.viewMonth = date.getMonth();
                this.$nextTick(() => this.focusDay());
            }
        },

        props: {
            // ISO date (YYYY-MM-DD) or null
            value: {
                type: String,
                default: null
            },
            min: {
                type: String,
                default: null
            },
            max: {
                type: String,
                default: null
            },
            clearable: {
                type: Boolean,
                default: false
            },
            aria_label: {
                type: String,
                default: 'Datum wählen'
            }
        }
    }
</script>
