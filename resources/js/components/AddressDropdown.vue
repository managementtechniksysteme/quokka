<template>
    <div>
        <input v-if="selected" type="hidden" :id="input_name" :name="input_name" :value="selected.id" />
        <v-select :options="addresses" label="address_line" placeholder="Addresse auswählen" :value="selected" @input="setSelected">
            <template slot="option" slot-scope="option">
                <span class="text-muted">{{ option.name }}</span> <br />
                {{ option.address_line }}
            </template>
        </v-select>
    </div>
</template>

<script>
    export default {
        name: "AddressDropdown",

        data() {
            return {
                selected: this.current_address,
            }
        },

        methods: {
            setSelected(value) {
                this.selected = value;
            }
        },

        props: {
            addresses: {
                type: Array,
                default() {
                    return [];
                }
            },
            current_address: {
                type: Object,
                default() {
                    return null;
                }
            },
            input_name: {
                type: Array,
                default() {
                    return 'address_id';
                }
            },
        }

    }
</script>
