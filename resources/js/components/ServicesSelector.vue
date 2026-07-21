<template>
    <div>
        <div class="row g-2">
            <div class="col-6 col-lg-3">
                <label for="date">Datum</label>
                <input type="date" class="form-control" :class="{'is-invalid': provided_on_invalid}" id="date" required v-model="date" @keydown.enter.prevent="addService()" />
                <div class="invalid-feedback">Datum muss ausgefüllt sein.</div>
            </div>
            <div class="col-6 col-lg-3">
                <label for="hours">Stunden</label>
                <input type="number" class="form-control" :class="{'is-invalid': hours_invalid}" min="0.5" step="0.5" id="hours" placeholder="5" v-model="hours" @keydown.enter.prevent="addService()" />
                <div class="invalid-feedback">Stunden muss mindestens 0.5 sein.</div>
            </div>
            <div class="col-6 col-lg-3">
                <label for="kilometres">gefahrene KM</label>
                <input type="number" class="form-control" :class="{'is-invalid': kilometres_invalid}" min="1" step="1" id="kilometres" placeholder="12" v-model="kilometres" @keydown.enter.prevent="addService()" />
                <div class="invalid-feedback">Kilometer muss mindestens 1 sein.</div>
            </div>
            <div class="col-6 col-lg-3">
                <label>&nbsp;</label>
                <button type="button" class="btn q-btn w-100 d-flex align-items-center justify-content-center gap-2" @click="addService()" @keydown.enter.prevent="addService()">
                    <svg class="icon-bs icon-16"><use href="/svg/bootstrap-icons.svg#plus"></use></svg>
                    Hinzufügen
                </button>
            </div>
        </div>

        <template v-if="services.length">
            <input v-for="(service, index) in services" :key="'sr_'+index"   type="hidden" :name="'services['+index+'][service_report_id]'" :value="service.service_report_id" />
            <input v-for="(service, index) in services" :key="'date_'+index" type="hidden" :name="'services['+index+'][provided_on]'"      :value="service.provided_on.toISOString().substr(0, 10)" />
            <input v-for="(service, index) in services" :key="'h_'+index"    type="hidden" :name="'services['+index+'][hours]'"             :value="service.hours" />
            <input v-for="(service, index) in services" :key="'km_'+index"   type="hidden" :name="'services['+index+'][kilometres]'"        :value="service.kilometres" />

            <div v-if="overlapping_reports.length" class="q-banner mt-3" role="alert">
                <svg class="icon-bs icon-16" style="flex-shrink:0"><use href="/svg/bootstrap-icons.svg#exclamation-triangle"></use></svg>
                <div>
                    <p class="m-0">Zu den eingetragenen Daten existieren für das gewählte Projekt bereits folgende Serviceberichte von dir. <strong>Bitte überprüfe, ob Serviceleistungen bereits in einem Servicebericht vermerkt sind!</strong></p>
                    <ul class="m-0 mt-1">
                        <li v-for="report in overlapping_reports"><a :href="report.link" target="_blank">{{ report.title }}</a></li>
                    </ul>
                </div>
            </div>

            <div class="q-card mt-3">
                <div class="q-lines--editable">
                    <div class="q-lines__head">
                        <span>Datum</span>
                        <span class="q-lines__num">Stunden</span>
                        <span class="q-lines__num">Gef. km</span>
                        <span></span>
                    </div>
                    <div v-for="(service, index) in services" :key="'row_'+index" class="q-lines__row">
                        <div class="q-lines__cell" @click="setEdit(service, 'provided_on')">
                            <span v-if="service.edit !== 'provided_on'">{{ service.provided_on.toLocaleDateString("de", { month: '2-digit', day: '2-digit', year: 'numeric' }) }}</span>
                            <input v-else type="date" class="form-control form-control-sm" :class="{'is-invalid': table_provided_on_invalid}" ref="table_input" :value="getDateStringForInputField(service.provided_on)" required @blur="changeServiceProvidedOn($event, service)" />
                        </div>
                        <div class="q-lines__num q-lines__cell" @click="setEdit(service, 'hours')">
                            <span v-if="service.edit !== 'hours'">{{ service.hours }}</span>
                            <input v-else type="number" min="0" step="0.5" class="form-control form-control-sm" :class="{'is-invalid': table_hours_invalid}" ref="table_input" :value="service.hours" placeholder="5" @blur="changeServiceHours($event, service)" />
                        </div>
                        <div class="q-lines__num q-lines__cell" @click="setEdit(service, 'kilometres')">
                            <span v-if="service.edit !== 'kilometres'">{{ service.kilometres }}</span>
                            <input v-else type="number" min="0" step="1" class="form-control form-control-sm" :class="{'is-invalid': table_kilometres_invalid}" ref="table_input" :value="service.kilometres" placeholder="12" @blur="changeServiceKilometres($event, service)" />
                        </div>
                        <div class="text-end">
                            <button type="button" class="btn btn-sm btn-outline-danger p-1 d-inline-flex align-items-center gap-2" @click="removeService(service)">
                                <svg class="icon-bs icon-16"><use href="/svg/bootstrap-icons.svg#trash"></use></svg>
                                <span class="d-none d-md-inline">Entfernen</span>
                            </button>
                        </div>
                    </div>
                    <div class="q-lines__sum">
                        <span class="q-lines__sumlabel">Summe</span>
                        <span class="q-lines__sumval">{{ totalHours }} h</span>
                        <span class="q-lines__sumval">{{ totalKilometres }} km</span>
                        <span></span>
                    </div>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
    export default {
        name: "ServicesSelector",

        data() {
            let today = new Date();
            return {
                date: this.getDateStringForInputField(new Date(today.getTime() - today.getTimezoneOffset() * 60 * 1000)),
                provided_on_invalid: false,
                table_provided_on_invalid: false,
                hours: null,
                hours_invalid: false,
                table_hours_invalid: false,
                kilometres: null,
                kilometres_invalid: false,
                table_kilometres_invalid: false,
                services: [],
                project_id: null,
                report_id: this.current_report_id ? this.current_report_id : null,
                overlapping_reports: [],
            }
        },

        mounted() {
            if(this.current_services) {
                let userTimezoneOffset = new Date().getTimezoneOffset() * 60 * 1000;

                this.current_services.forEach(service => {
                    let date = Date.parse(service.provided_on);

                    this.services.push({
                        edit: null,
                        service_report_id: service.service_report_id,
                        provided_on: new Date(date - userTimezoneOffset),
                        hours: service.hours,
                        kilometres: service.kilometres,
                    });
                });
            }

            document.addEventListener('onservicereportprojectchange', this.handleProjectChange);
        },

        computed: {
            totalHours() {
                return this.services.reduce((sum, s) => sum + Number(s.hours), 0);
            },
            totalKilometres() {
                return this.services.reduce((sum, s) => sum + Number(s.kilometres), 0);
            },
        },

        methods: {
            addService() {
                let date = new Date(this.date);
                let hours = Number(this.hours);
                let kilometres = Number(this.kilometres);

                this.provided_on_invalid = isNaN(date.getTime());
                this.hours_invalid = Number.isNaN(hours) || hours % 0.5 !== 0 || (hours !== 0 && hours < 0.5);
                this.kilometres_invalid = !Number.isInteger(kilometres) || (kilometres !== 0 && kilometres < 1);

                if((hours === 0 && kilometres === 0) ||
                    this.provided_on_invalid || this.hours_invalid || this.kilometres_invalid) {
                    return;
                }

              let service = this.services.find(service =>
                service.provided_on.getYear() === date.getYear() &&
                service.provided_on.getMonth() === date.getMonth() &&
                service.provided_on.getDate() === date.getDate()
	            );

                if(service) {
                    service.hours += hours;
                    service.kilometres += kilometres;
                }
                else {
                    this.services.push({
                        edit: null,
                        service_report_id: null,
                        provided_on: date,
                        hours: hours,
                        kilometres: kilometres});
                    this.sortArrayByDate(this.services);

                    this.fetchOverlappingServices(
                        this.report_id,
                        this.project_id,
                        this.services.map(service => this.getDateStringForInputField(service.provided_on))
                    );
                }

                let today = new Date();
                this.date = this.getDateStringForInputField(new Date(today.getTime() - today.getTimezoneOffset() * 60 * 1000));
                this.provided_on_invalid = false;
                this.hours = null;
                this.hours_invalid = false;
                this.kilometres = null;
                this.kilometres_invalid = false;

            },

            removeService(value) {
                this.services = this.removeFromArray(this.services, value);

                this.fetchOverlappingServices(
                    this.report_id,
                    this.project_id,
                    this.services.map(service => this.getDateStringForInputField(service.provided_on))
                );
            },

            removeFromArray(services, value) {
                return services.filter(service => {
                    return service.provided_on !== value.provided_on;
                });
            },

            changeServiceProvidedOn(event, changedService) {
                let date = new Date(event.target.value);

                if(isNaN(date.getTime())) {
                    this.table_provided_on_invalid = true;
                    return;
                }

                changedService.provided_on = date;

                changedService.edit = null;

                this.sortArrayByDate(this.services);

                this.fetchOverlappingServices(
                    this.report_id,
                    this.project_id,
                    this.services.map(service => this.getDateStringForInputField(service.provided_on))
                );
            },

            changeServiceHours(event, changedService) {
                let hours = Number(event.target.value);

                if(Number.isNaN(hours) || hours % 0.5 !== 0 || (hours !== 0 && hours < 0.5)) {
                    this.table_hours_invalid = true;
                    return;
                }

                changedService.hours = hours;

                changedService.edit = null;
            },

            changeServiceKilometres(event, changedService) {
                let kilometres = Number(event.target.value);

                if(!Number.isInteger(kilometres) || (kilometres !== 0 && kilometres < 1)) {
                    this.table_kilometres_invalid = true;
                    return;
                }

                changedService.kilometres = kilometres;

                changedService.edit = null;
            },

            setEdit(service, field) {
                this.getEditService().forEach(editService => {
                    editService.edit = null;
                });

                service.edit = field;

                this.$nextTick(() => {
                    this.$refs.table_input[0].focus();
                });

                this.table_provided_on_invalid = false;
                this.table_hours_invalid = false;
                this.table_kilometres_invalid = false;
            },

            unsetEdit(service) {
                this.setEdit(service, null);
            },

            getEditService() {
                return this.services.filter(service => service.edit !== null);
            },

            sortArrayByDate(services) {
                services.sort((a, b) => {
                    return a.provided_on - b.provided_on;
                });
            },

            getDateStringForInputField(date) {
                return date.toISOString().substr(0, 10);
            },

            handleProjectChange(event) {
                this.project_id = event.detail;
                this.fetchOverlappingServices(
                    this.report_id,
                    this.project_id,
                    this.services.map(service => this.getDateStringForInputField(service.provided_on))
                );
            },

            fetchOverlappingServices(service_report_id, project_id, service_dates) {
                if(!this.project_id || !this.services.length) {
                    return;
                }

                let params = {
                    project_id: project_id,
                    dates: service_dates
                }

                if(service_report_id) {
                    params.report_id = this.report_id;
                }

                let axiosInstance = axios.create({
                    validateStatus: function (status) {
                        return status < 300;
                    }
                });

                axiosInstance.get('/service-reports/check-overlap', {
                    params: params
                })
                .then(response => {
                    this.overlapping_reports = response.data.reports;
                })
                .catch(error => {
                    console.log(error);
                });
            }
        },

        props: {
            current_services: {
                type: Array,
                default() {
                    return [];
                }
            },

            project_change_event: {
                type: String,
                default() {
                    return 'onservicereportprojectchange';
                }
            },

            current_report_id: {
                type: Number,
                default() {
                    return null;
                }
            }
        }

    }
</script>
