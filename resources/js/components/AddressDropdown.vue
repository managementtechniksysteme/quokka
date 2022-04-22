<template>
    <div>
        <input v-if="selected" type="hidden" :id="inputname" :name="inputname" :value="selected.id" />
        <v-select :options="addresses" label="address_line" placeholder="Addresse auswählen" :value="selected" :selectOnTab="true" @input="setSelected">
            <template v-slot:option="option">
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
            inputname: {
                type: String,
                default() {
                    return 'address_id';
                }
            },

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
        }

    }
</script>
