<template>
    <div>
        <input v-if="selected" type="hidden" :id="inputname" :name="inputname"  :value="selected.id" />
        <v-select :options="services" label="name_with_unit" placeholder="Leistung auswählen" :value="selected" @input="setSelected"></v-select>
    </div>
</template>

<script>
    export default {
        name: "ServiceDropdown",

        data() {
            return {
                selected: this.current_service,
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
                    return 'service_id';
                }
            },
            services: {
                type: Array,
                default() {
                    return [];
                }
            },
            current_service: {
                type: Object,
                default() {
                    return null;
                }
            }
        }

    }
</script>
