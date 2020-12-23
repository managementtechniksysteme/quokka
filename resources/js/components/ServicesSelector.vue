<template>
  <div>
        <div class="form-row">
            <div class="form-group col-6 col-lg-3">
                <label for="date">Datum</label>
                <input type="date" class="form-control" v-bind:class="{'is-invalid': date_invalid}" id="date" name="date" placeholder="" required v-model="date" />
                <div class="invalid-feedback">
                    Datum muss ausgefüllt sein.
                </div>
            </div>
            <div class="form-group col-6 col-lg-2">
                <label for="hours">Stunden</label>
                <input type="number" class="form-control" v-bind:class="{'is-invalid': hours_invalid}" min="0.5" step="0.5" id="hours" name="hours" placeholder="5" v-model="hours" />
                <div class="invalid-feedback">
                    Stunden muss mindestens 0.5 sein.
                </div>
        </div>
            <div class="form-group col-6 col-lg-2">
                <label for="allowances">Diäten</label>
                <input type="number" class="form-control" v-bind:class="{'is-invalid': allowances_invalid}" min="0.5" step="0.5" id="allowances" name="allowances" placeholder="1" v-model="allowances" />
                <div class="invalid-feedback">
                    Diäten muss mindestens 0.5 sein.
                </div>
            </div>
            <div class="form-group col-6 col-lg-2">
                <label for="kilometres">gefahrene KM</label>
                <input type="number" class="form-control" v-bind:class="{'is-invalid': kilometres_invalid}" min="1" step="1" id="kilometres" name="kilometres" placeholder="12" v-model="kilometres" />
                <div class="invalid-feedback">
                    Kilometer muss mindestens 1 sein.
                </div>
            </div>
            <div class="form-group">
                <label for="submit" class="d-none d-lg-block">&nbsp;</label>
                <button id="submit" type="button" class="form-control btn btn-outline-secondary" @click="addService()">Hinzufügen</button>
            </div>
        </div>

        <div v-if="services.length" class="mt-2">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th scope="col">Datum</th>
                        <th scope="col">Stunden</th>
                        <th scope="col">Diäten</th>
                        <th scope="col">gefahrene Kilometer</th>
                        <th scope="col" class="text-right"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(service, index) in services">
                        <input v-if="services.length" type="hidden" :id="'services['+index+'][provided_on]'" :name="'services['+index+'][provided_on]'" :value="service.provided_on.toISOString().substr(0, 10)" />
                        <input v-if="services.length" type="hidden" :id="'services['+index+'][hours]'" :name="'services['+index+'][hours]'" :value="service.hours" />
                        <input v-if="services.length" type="hidden" :id="'services['+index+'][allowances]'" :name="'services['+index+'][allowances]'" :value="service.allowances" />
                        <input v-if="services.length" type="hidden" :id="'services['+index+'][kilometres]'" :name="'services['+index+'][kilometres]'" :value="service.kilometres" />

                        <th scope="row">{{service.provided_on.toLocaleDateString("de")}}</th>
                        <td>{{service.hours}}</td>
                        <td>{{service.allowances}}</td>
                        <td>{{service.kilometres}}</td>
                        <td class="text-right"><button type="button" class="btn btn-sm btn-outline-danger" @click="removeService(service)">Entfernen</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    export default {
        name: "ServicesSelector",

        data() {
            let today = new Date();
            return {
                date: new Date(today.getTime() - today.getTimezoneOffset() * 60 * 1000).toISOString().substr(0, 10),
                date_invalid: false,
                hours: null,
                hours_invalid: false,
                allowances: null,
                allowances_invalid: false,
                kilometres: null,
                kilometres_invalid: false,
                services: this.current_services ? this.current_services : [],
            }
        },

        created() {
            let userTimezoneOffset = new Date().getTimezoneOffset() * 60 * 1000;

            this.services.forEach(function(service) {
                let date = Date.parse(service.provided_on);
                service.provided_on = new Date(date - userTimezoneOffset);
            });
        },

        methods: {
            addService() {
                let date = new Date(this.date);
                let hours = this.hours === null ? 0 : parseFloat(this.hours);
                let allowances = this.allowances === null ? 0 : parseFloat(this.allowances);
                let kilometres = this.kilometres === null ? 0 : parseInt(this.kilometres);

                this.provided_on_invalid = isNaN(date.getTime());
                this.hours_invalid = hours !== 0 && (isNaN(hours) || hours < 0.5);
                this.allowances_invalid = allowances !== 0 && (isNaN(allowances) || allowances < 0.5);
                this.kilometres_invalid = kilometres !== 0 && (isNaN(kilometres) || kilometres < 1);

                if(this.provided_on_invalid || this.hours_invalid || this.allowances_invalid || this.kilometres_invalid) {
                    return;
                }

                let service = this.services.find(service => service.provided_on.getDate() === date.getDate());

                if(service) {
                    service.hours += hours;
                    service.allowances += allowances;
                    service.kilometres += kilometres;
                }
                else {
                    this.services.push({provided_on: date, hours: hours, allowances: allowances, kilometres: kilometres});
                    this.sortArrayByDate(this.services);
                }

                let today = new Date();
                this.date = new Date(today.getTime() - today.getTimezoneOffset() * 60 * 1000).toISOString().substr(0, 10);
                this.provided_on_invalid = false;
                this.hours = null;
                this.hours_invalid = false;
                this.allowances = null;
                this.allowances_invalid = false;
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

            sortArrayByDate(services) {
                services.sort((a, b) => {
                    return a.provided_on - b.provided_on;
                });
            }
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
