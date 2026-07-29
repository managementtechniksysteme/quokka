<template>
    <div>
        <!-- Mobile: filter trigger lives in the app bar (top right), teleported
             from here so it shares this component's reactive state and opens
             the same sheet — same pattern as Accounting/LogbookSelector's
             mobile-detail-bar actions (2026-07-29). -->
        <teleport to="#metricsMobileActions">
            <button type="button" class="q-appbar__btn" aria-label="Filter" @click="openMobileFilter">
                <svg class="icon-bs icon-20"><use href="/svg/bootstrap-icons.svg#funnel"></use></svg>
            </button>
        </teleport>

        <!-- Mobile: applied filters as removable pill chips, same pattern as
             AccountingSelector's/LogbookSelector's (2026-07-29). -->
        <div v-if="activeFilterChips().length" class="q-meta d-md-none mb-3">
            <span v-for="chip in activeFilterChips()" :key="chip.key" class="q-chip">
                {{ chip.label }}
                <button type="button" class="q-quick-create-summary__clear" :aria-label="'Filter entfernen: ' + chip.label" @click="clearFilterChip(chip.key)">
                    <svg class="icon-bs icon-14"><use href="/svg/bootstrap-icons.svg#x"></use></svg>
                </button>
            </span>
        </div>

        <!-- Desktop filter bar -->
        <div class="q-filterbar q-form d-none d-md-block">
            <div class="q-card">
                <div class="q-card__body">
                    <div class="q-filterbar__fields">
                        <div class="q-filterbar__field">
                            <label>Zeitraum</label>
                            <div class="btn-group">
                                <input type="radio" class="btn-check" name="period" id="period-30d" value="30d" v-model="state.period">
                                <label class="btn" for="period-30d">30 Tage</label>
                                <input type="radio" class="btn-check" name="period" id="period-quarter" value="quarter" v-model="state.period">
                                <label class="btn" for="period-quarter">Quartal</label>
                                <input type="radio" class="btn-check" name="period" id="period-year" value="year" v-model="state.period">
                                <label class="btn" for="period-year">Jahr</label>
                                <input type="radio" class="btn-check" name="period" id="period-custom" value="custom" v-model="state.period">
                                <label class="btn" for="period-custom">Benutzerdefiniert</label>
                            </div>
                        </div>
                        <div class="q-filterbar__field">
                            <label for="from">Von</label>
                            <input id="from" class="form-control" type="date" :disabled="state.period !== 'custom'" v-model="state.from">
                        </div>
                        <div class="q-filterbar__field">
                            <label for="to">Bis</label>
                            <input id="to" class="form-control" type="date" :disabled="state.period !== 'custom'" v-model="state.to">
                        </div>
                        <div class="q-filterbar__field q-filterbar__field--grow">
                            <label>Kunde</label>
                            <v-select :options="companies" label="full_name" placeholder="Alle Kunden" v-model="selectedCompany" :selectOnTab="true">
                                <template v-slot:no-options>Keine passenden Einträge.</template>
                            </v-select>
                        </div>
                        <div class="q-filterbar__field q-filterbar__field--grow">
                            <label>Mitarbeiter</label>
                            <v-select :options="employeeOptions" label="name" placeholder="Alle Mitarbeiter" v-model="selectedEmployee" :selectOnTab="true">
                                <template v-slot:no-options>Keine passenden Einträge.</template>
                            </v-select>
                        </div>
                    </div>
                    <div class="q-filterbar__fields">
                        <div class="q-filterbar__field q-filterbar__field--grow">
                            <label>Projekt</label>
                            <v-select :options="projects" label="name" placeholder="Alle Projekte" v-model="selectedProject" :selectOnTab="true">
                                <template v-slot:no-options>Keine passenden Einträge.</template>
                            </v-select>
                        </div>
                        <div class="q-filterbar__field q-filterbar__field--grow">
                            <label>Berichtstyp</label>
                            <v-select :options="reportTypeOptions" label="label" placeholder="Alle Typen" v-model="selectedReportType" :selectOnTab="true">
                                <template v-slot:no-options>Keine passenden Einträge.</template>
                            </v-select>
                        </div>
                    </div>
                    <div class="q-filterbar__actions">
                        <label class="q-filterbar__switch form-check form-switch">
                            <input type="checkbox" class="form-check-input" v-model="state.only_active_projects">
                            Nur aktive Projekte
                        </label>
                        <div class="q-filterbar__submit">
                            <button type="button" class="btn q-btn" @click="reset">Zurücksetzen</button>
                            <button type="button" class="btn btn-primary text-white d-inline-flex align-items-center gap-2" @click="apply">
                                <svg class="icon-bs icon-16"><use href="/svg/bootstrap-icons.svg#filter"></use></svg>
                                Filtern
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile filter sheet -->
        <div class="offcanvas offcanvas-bottom q-sheet q-form" tabindex="-1" ref="mobileFilterSheet" aria-label="Filter">
            <div class="q-sheet__handle" aria-hidden="true"><span class="q-sheet__handle-bar"></span></div>
            <div class="offcanvas-body">
                <div class="q-sheet__label">Filter</div>

                <div class="d-flex flex-column gap-3 px-2 pb-2">
                    <div class="q-form__row">
                        <label>Zeitraum</label>
                        <div class="btn-group w-100">
                            <input type="radio" class="btn-check" name="period-mobile" id="period-30d-mobile" value="30d" v-model="state.period">
                            <label class="btn" for="period-30d-mobile">30 Tage</label>
                            <input type="radio" class="btn-check" name="period-mobile" id="period-quarter-mobile" value="quarter" v-model="state.period">
                            <label class="btn" for="period-quarter-mobile">Quartal</label>
                            <input type="radio" class="btn-check" name="period-mobile" id="period-year-mobile" value="year" v-model="state.period">
                            <label class="btn" for="period-year-mobile">Jahr</label>
                            <input type="radio" class="btn-check" name="period-mobile" id="period-custom-mobile" value="custom" v-model="state.period">
                            <label class="btn" for="period-custom-mobile">Benutzerdefiniert</label>
                        </div>
                    </div>
                    <div class="q-form__row q-form__row--2">
                        <div>
                            <label for="from-mobile">Von</label>
                            <input id="from-mobile" class="form-control" type="date" :disabled="state.period !== 'custom'" v-model="state.from">
                        </div>
                        <div>
                            <label for="to-mobile">Bis</label>
                            <input id="to-mobile" class="form-control" type="date" :disabled="state.period !== 'custom'" v-model="state.to">
                        </div>
                    </div>
                    <div>
                        <label>Kunde</label>
                        <v-select :options="companies" label="full_name" placeholder="Alle Kunden" v-model="selectedCompany" :selectOnTab="true">
                            <template v-slot:no-options>Keine passenden Einträge.</template>
                        </v-select>
                    </div>
                    <div>
                        <label>Mitarbeiter</label>
                        <v-select :options="employeeOptions" label="name" placeholder="Alle Mitarbeiter" v-model="selectedEmployee" :selectOnTab="true">
                            <template v-slot:no-options>Keine passenden Einträge.</template>
                        </v-select>
                    </div>
                    <div>
                        <label>Projekt</label>
                        <v-select :options="projects" label="name" placeholder="Alle Projekte" v-model="selectedProject" :selectOnTab="true">
                            <template v-slot:no-options>Keine passenden Einträge.</template>
                        </v-select>
                    </div>
                    <div>
                        <label>Berichtstyp</label>
                        <v-select :options="reportTypeOptions" label="label" placeholder="Alle Typen" v-model="selectedReportType" :selectOnTab="true">
                            <template v-slot:no-options>Keine passenden Einträge.</template>
                        </v-select>
                    </div>
                    <div class="form-check form-switch m-0">
                        <input type="checkbox" class="form-check-input" id="only-active-projects-mobile" v-model="state.only_active_projects">
                        <label class="form-check-label" for="only-active-projects-mobile">Nur aktive Projekte</label>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn q-btn flex-fill" @click="reset">Zurücksetzen</button>
                        <button type="button" class="btn btn-primary text-white flex-fill d-inline-flex align-items-center justify-content-center gap-2" @click="apply">
                            <svg class="icon-bs icon-16"><use href="/svg/bootstrap-icons.svg#funnel"></use></svg>
                            Filtern
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "MetricsFilterBar",

        props: {
            actionUrl: { type: String, required: true },
            filters: { type: Object, required: true },
            companies: { type: Array, default: () => [] },
            employees: { type: Array, default: () => [] },
            projects: { type: Array, default: () => [] },
            reportTypes: { type: Object, default: () => ({}) },
        },

        data() {
            return {
                state: {
                    period: this.filters.period,
                    from: this.filters.from,
                    to: this.filters.to,
                    only_active_projects: this.filters.only_active_projects,
                },
                selectedCompany: this.companies.find(c => String(c.id) === String(this.filters.company_id)) ?? null,
                selectedEmployee: this.employeeOptionsFrom(this.employees).find(e => String(e.person_id) === String(this.filters.employee_id)) ?? null,
                selectedProject: this.projects.find(p => String(p.id) === String(this.filters.project_id)) ?? null,
                selectedReportType: this.filters.report_type ? { key: this.filters.report_type, label: this.reportTypes[this.filters.report_type] } : null,
            }
        },

        computed: {
            employeeOptions() {
                return this.employeeOptionsFrom(this.employees);
            },

            reportTypeOptions() {
                return Object.entries(this.reportTypes).map(([key, label]) => ({ key, label }));
            },
        },

        methods: {
            employeeOptionsFrom(employees) {
                return employees.map(employee => ({ person_id: employee.person_id, name: employee.person.name }));
            },

            periodLabel() {
                switch (this.state.period) {
                    case '30d': return '30 Tage';
                    case 'year': return 'Jahr';
                    case 'custom': return this.formatDate(this.state.from) + ' – ' + this.formatDate(this.state.to);
                    default: return null;
                }
            },

            formatDate(value) {
                return new Date(value).toLocaleDateString('de', { day: '2-digit', month: '2-digit', year: 'numeric' });
            },

            activeFilterChips() {
                let chips = [];

                let periodLabel = this.periodLabel();
                if (periodLabel) {
                    chips.push({ key: 'period', label: periodLabel });
                }
                if (this.selectedCompany) {
                    chips.push({ key: 'company', label: this.selectedCompany.full_name });
                }
                if (this.selectedEmployee) {
                    chips.push({ key: 'employee', label: this.selectedEmployee.name });
                }
                if (this.selectedProject) {
                    chips.push({ key: 'project', label: this.selectedProject.name });
                }
                if (this.selectedReportType) {
                    chips.push({ key: 'reportType', label: this.selectedReportType.label });
                }
                if (!this.state.only_active_projects) {
                    chips.push({ key: 'only_active_projects', label: 'Alle Projekte (auch beendete)' });
                }

                return chips;
            },

            clearFilterChip(key) {
                switch (key) {
                    case 'period':
                        this.state.period = 'quarter';
                        break;
                    case 'only_active_projects':
                        this.state.only_active_projects = true;
                        break;
                    case 'company':
                        this.selectedCompany = null;
                        break;
                    case 'employee':
                        this.selectedEmployee = null;
                        break;
                    case 'project':
                        this.selectedProject = null;
                        break;
                    case 'reportType':
                        this.selectedReportType = null;
                        break;
                }
                this.apply();
            },

            openMobileFilter() {
                window.bootstrap.Offcanvas.getOrCreateInstance(this.$refs.mobileFilterSheet).show();
            },

            reset() {
                window.location.href = this.actionUrl;
            },

            apply() {
                let params = new URLSearchParams();
                params.set('period', this.state.period);
                if (this.state.period === 'custom') {
                    params.set('from', this.state.from);
                    params.set('to', this.state.to);
                }
                if (this.selectedCompany) {
                    params.set('company_id', this.selectedCompany.id);
                }
                if (this.selectedEmployee) {
                    params.set('employee_id', this.selectedEmployee.person_id);
                }
                if (this.selectedProject) {
                    params.set('project_id', this.selectedProject.id);
                }
                if (this.selectedReportType) {
                    params.set('report_type', this.selectedReportType.key);
                }
                params.set('only_active_projects', this.state.only_active_projects ? '1' : '0');

                window.location.href = this.actionUrl + '?' + params.toString();
            },
        },
    }
</script>
