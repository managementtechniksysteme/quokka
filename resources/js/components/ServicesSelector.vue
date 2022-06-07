<template>
  <div>
        <div class="form-row">
            <div class="form-group col-6 col-lg-3">
                <label for="date">Datum</label>
                <input type="date" class="form-control" v-bind:class="{'is-invalid': provided_on_invalid}" id="date" name="date" placeholder="" required v-model="date" @keydown.enter.prevent="addService()" />
                <div class="invalid-feedback">
                    Datum muss ausgefüllt sein.
                </div>
            </div>
            <div class="form-group col-6 col-lg-3">
                <label for="hours">Stunden</label>
                <input type="number" class="form-control" v-bind:class="{'is-invalid': hours_invalid}" min="0.5" step="0.5" id="hours" name="hours" placeholder="5" v-model="hours" @keydown.enter.prevent="addService()" />
                <div class="invalid-feedback">
                    Stunden muss mindestens 0.5 sein.
                </div>
            </div>
            <div class="form-group col-6 col-lg-3">
                <label for="kilometres">gefahrene KM</label>
                <input type="number" class="form-control" v-bind:class="{'is-invalid': kilometres_invalid}" min="1" step="1" id="kilometres" name="kilometres" placeholder="12" v-model="kilometres" @keydown.enter.prevent="addService()" />
                <div class="invalid-feedback">
                    Kilometer muss mindestens 1 sein.
                </div>
            </div>
            <div class="form-group col-6 col-lg-3">
                <label for="submit">&nbsp;</label>
                <button id="submit" type="button" class="form-control btn btn-outline-secondary" @click="addService()" @keydown.enter.prevent="addService()">Hinzufügen</button>
            </div>
        </div>

        <div v-if="services.length" class="mt-2">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th scope="col" class="col-4 col-lg-3">Datum</th>
                        <th scope="col" class="col-2">Stunden</th>
                        <th scope="col" class="col-3 col-lg-5">gef<span class="d-inline d-md-none">.</span><span class="d-none d-md-inline">ahrene</span> KM</th>
                        <th scope="col" class="col-auto text-right"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(service, index) in services"  class="hover-highlight">
                        <input v-if="services.length" type="hidden" :id="'services['+index+'][service_report_id]'" :name="'services['+index+'][service_report_id]'" :value="service.service_report_id" />
                        <input v-if="services.length" type="hidden" :id="'services['+index+'][provided_on]'" :name="'services['+index+'][provided_on]'" :value="service.provided_on.toISOString().substr(0, 10)" />
                        <input v-if="services.length" type="hidden" :id="'services['+index+'][hours]'" :name="'services['+index+'][hours]'" :value="service.hours" />
                        <input v-if="services.length" type="hidden" :id="'services['+index+'][kilometres]'" :name="'services['+index+'][kilometres]'" :value="service.kilometres" />

                        <th scope="row" class="col-4 col-lg-3" @click="setEdit(service, 'provided_on')">
                            <span v-if="service.edit !== 'provided_on'">{{ service.provided_on.toLocaleDateString("de", { month: '2-digit', day: '2-digit', year: 'numeric' }) }}</span>
                            <input v-if="service.edit === 'provided_on'" type="date" class="form-control form-control-sm" v-bind:class="{'is-invalid': table_provided_on_invalid}" ref="table_input" id="table_provided_on" name="table_provided_on" :value="getDateStringForInputField(service.provided_on)" placeholder="" required @blur="changeServiceProvidedOn($event, service)" />
                        </th>
                        <td class="col-2" @click="setEdit(service, 'hours')">
                            <span v-if="service.edit !== 'hours'">{{ service.hours }}</span>
                            <input v-if="service.edit === 'hours'" type="number" min="0" step="0.5" class="form-control form-control-sm" v-bind:class="{'is-invalid': table_hours_invalid}" ref="table_input" id="table_hours" name="table_hours" :value="service.hours" placeholder="5" @blur="changeServiceHours($event, service)" />
                        </td>
                        <td class="col-3 col-lg-5" @click="setEdit(service, 'kilometres')">
                            <span v-if="service.edit !== 'kilometres'">{{ service.kilometres }}</span>
                            <input v-if="service.edit === 'kilometres'" type="number" min="0" step="1" class="form-control form-control-sm" v-bind:class="{'is-invalid': table_kilometres_invalid}" ref="table_input" id="table_kilometres" name="table_kilometres" :value="service.kilometres" placeholder="12" @blur="changeServiceKilometres($event, service)" />
                        </td>
                        <td class="col-auto text-right"><button type="button" class="btn btn-sm btn-outline-danger" @click="removeService(service)">Entfernen</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    const ERROR_CLASS = "text-red";

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
            }
        },

        created() {
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

                let service = this.services.find(service => service.provided_on.getDate() === date.getDate());

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
        },

        props: {
            current_services: {
                type: Array,
                default() {
                    return [];
                }
            }
        }

    }
</script>
