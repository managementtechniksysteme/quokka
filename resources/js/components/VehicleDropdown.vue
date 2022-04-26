<template>
    <div>
        <input v-if="selected" type="hidden" :id="inputname" :name="inputname"  :value="selected.id" />
        <v-select :options="vehicles" label="registration_identifier" placeholder="Fahrzeug auswählen" :value="selected" :selectOnTab="true" @input="setSelected"></v-select>
    </div>
</template>

<script>
    export default {
        name: "VehicleDropdown",

        data() {
            return {
                selected: this.current_vehicle,
            }
        },

        methods: {
            setSelected(value) {
                this.selected = value;
            }
        },

        props: {
            inputname: {
                type: String,
                default() {
                    return 'vehicle_id';
                }
            },
            people: {
                type: Array,
                default() {
                    return [];
                }
            },
            current_person: {
                type: Object,
                default() {
                    return null;
                }
            }
        }

    }
</script>
