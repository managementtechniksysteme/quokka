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
            <div class="form-group col-6 col-lg-3">
                <label for="hours">Stunden</label>
                <input type="number" class="form-control" v-bind:class="{'is-invalid': hours_invalid}" min="0.5" step="0.5" id="hours" name="hours" placeholder="5" v-model="hours" />
                <div class="invalid-feedback">
                    Stunden muss mindestens 0.5 sein.
                </div>
            </div>
            <div class="form-group col-6 col-lg-3">
                <label for="kilometres">gefahrene KM</label>
                <input type="number" class="form-control" v-bind:class="{'is-invalid': kilometres_invalid}" min="1" step="1" id="kilometres" name="kilometres" placeholder="12" v-model="kilometres" />
                <div class="invalid-feedback">
                    Kilometer muss mindestens 1 sein.
                </div>
            </div>
            <div class="form-group col-6 col-lg-3">
                <label for="submit">&nbsp;</label>
                <button id="submit" type="button" class="form-control btn btn-outline-secondary" @click="addService()">Hinzufügen</button>
            </div>
        </div>

        <div v-if="services.length" class="mt-2">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th scope="col">Datum</th>
                        <th scope="col">Stunden</th>
                        <th scope="col">gefahrene Kilometer</th>
                        <th scope="col" class="text-right"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(service, index) in services">
                        <input v-if="services.length" type="hidden" :id="'services['+index+'][provided_on]'" :name="'services['+index+'][provided_on]'" :value="service.provided_on.toISOString().substr(0, 10)" />
                        <input v-if="services.length" type="hidden" :id="'services['+index+'][hours]'" :name="'services['+index+'][hours]'" :value="service.hours" />
                        <input v-if="services.length" type="hidden" :id="'services['+index+'][kilometres]'" :name="'services['+index+'][kilometres]'" :value="service.kilometres" />

                        <th scope="row">{{service.provided_on.toLocaleDateString("de")}}</th>
                        <td contenteditable="true" @keydown.enter.prevent @blur="changeServiceHours($event, service)" v-html="service.hours"></td>
                        <td contenteditable="true" @keydown.enter.prevent @blur="changeServiceKilometres($event, service)" v-html="service.kilometres"></td>
                        <td class="text-right"><button type="button" class="btn btn-sm btn-outline-danger" @click="removeService(service)">Entfernen</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    const ERROR_CLASS = "text-red"

    export default {
        name: "ServicesSelector",

        data() {
            let today = new Date();
            return {
                date: new Date(today.getTime() - today.getTimezoneOffset() * 60 * 1000).toISOString().substr(0, 10),
                date_invalid: false,
                hours: null,
                hours_invalid: false,
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
                let hours = this.hours === null ? 0 : Number(this.hours);
                let kilometres = this.kilometres === null ? 0 : Number(this.kilometres);

                this.provided_on_invalid = isNaN(date.getTime());
                this.hours_invalid = Number.isNaN(hours) || hours % 0.5 !== 0 || (hours !== 0 && hours < 0.5); // hours !== 0 && (isNaN(hours) || hours < 0.5);
                this.kilometres_invalid = !Number.isInteger(kilometres) || (kilometres !== 0 && kilometres < 1); // kilometres !== 0 && (isNaN(kilometres) || kilometres < 1);

                if(this.provided_on_invalid || this.hours_invalid || this.kilometres_invalid) {
                    return;
                }

                let service = this.services.find(service => service.provided_on.getDate() === date.getDate());

                if(service) {
                    service.hours += hours;
                    service.kilometres += kilometres;
                }
                else {
                    this.services.push({provided_on: date, hours: hours, kilometres: kilometres});
                    this.sortArrayByDate(this.services);
                }

                let today = new Date();
                this.date = new Date(today.getTime() - today.getTimezoneOffset() * 60 * 1000).toISOString().substr(0, 10);
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

            sortArrayByDate(services) {
                services.sort((a, b) => {
                    return a.provided_on - b.provided_on;
                });
            },

            changeServiceHours(event, changedService) {
                this.removeEventTargetErrorIndicator(event.target, ERROR_CLASS);

                let existingService = this.services.find(service => service.provided_on.getDate() === changedService.provided_on.getDate());

                existingService.hours = event.target.innerText;

                let hours = Number(event.target.innerText);

                if(!Number.isNaN(hours)) {
                    existingService.hours = hours;
                }

                if(Number.isNaN(hours) || hours % 0.5 !== 0 || (hours !== 0 && hours < 0.5)) {
                    this.addEventTargetErrorIndicator(event.target, ERROR_CLASS);
                }
            },

            changeServiceKilometres(event, changedService) {
                this.removeEventTargetErrorIndicator(event.target, ERROR_CLASS);

                let existingService = this.services.find(service => service.provided_on.getDate() === changedService.provided_on.getDate());

                existingService.kilometres = event.target.innerText;

                let kilometres = Number(event.target.innerText);

                if(!Number.isNaN(kilometres)) {
                    existingService.kilometres = kilometres;
                }

                if(!Number.isInteger(kilometres) || (kilometres !== 0 && kilometres < 1)) {
                    this.addEventTargetErrorIndicator(event.target, ERROR_CLASS);
                }
            },

            addEventTargetErrorIndicator(target, class_name) {
                if(!target.classList.contains(class_name)) {
                    target.classList.add(class_name);
                }
            },

            removeEventTargetErrorIndicator(target, class_name) {
                if(target.classList.contains(class_name)) {
                    target.classList.remove(class_name);
                }
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
