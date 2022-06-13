<template>
    <div>
        <v-select :options="unselected" label="name_with_unit" placeholder="Leistung auswählen" value="" :selectOnTab="true" @input="addSelected">
            <template v-slot:no-options>Keine passenden Einträge.</template>
        </v-select>
        <div v-if="selected.length" class="container-fluid mt-2">
            <div class="row py-2 align-items-center hover-highlight" v-for="service in selected">
                <input v-if="selected.length" type="hidden" :id="service.id" :name="inputname" :value="service.id" />
                <div class="col">
                    {{service.name_with_unit}}
                </div>
                <div class="col-auto ml-auto">
                    <button type="button" class="btn btn-sm btn-outline-danger" @click="removeSelected(service)">Entfernen</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "AccountingServicesSelector",

        data() {
            return {
                selected: this.current_services ? this.current_services : [],
                unselected: this.current_services ? this.services.filter(service => {
                    return this.current_services.findIndex(current => current.id === service.id) === -1;
                }) : this.services
            }
        },

        methods: {
            addSelected(value) {
                this.unselected = this.removeFromArray(this.unselected, value);
                this.selected.push(value);
                this.sortArrayByName(this.selected);
            },

            removeSelected(value) {
                this.selected = this.removeFromArray(this.selected, value);
                this.unselected.push(value);
                this.sortArrayByName(this.unselected);
            },

            removeFromArray(services, value) {
                return services.filter(service => {
                    return service.id !== value.id;
                });
            },

            sortArrayByName(services) {
                people.sort((a, b) => {
                    let a_lower = a.name_with_unit.toLowerCase();
                    let b_lower = b.name_with_unit.toLowerCase();
                    return a_lower > b_lower ? 1 : b_lower > a_lower ? -1 : 0;
                });
            }
        },

        props: {
            inputname: {
                type: String,
                default() {
                    return 'service_ids[]';
                }
            },
            services: {
                type: Array,
                default() {
                    return [];
                }
            },
            current_services: {
                type: Array,
                default() {
                    return [];
                }
            }
        }

    }
</script>
