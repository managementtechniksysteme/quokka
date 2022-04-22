<template>
    <div>
        <div class="row align-items-center">
            <div class="col pr-0">
                <v-select :options="unselected" label="name" placeholder="Person auswählen oder Email Adresse eingeben" :value="selected_input" :selectOnTab="true" @input="setSelected" taggable></v-select>
            </div>

            <div class="col-auto pl-0 ml-1">
                <button type="button" class="btn btn-primary ml-1" @click="addSelected(selected_input, selected_to)">AN</button>
                <button type="button" class="btn btn-outline-secondary ml-1" @click="addSelected(selected_input, selected_cc)">CC</button>
                <button type="button" class="btn btn-outline-secondary ml-1" @click="addSelected(selected_input, selected_bcc)">BCC</button>
            </div>

        </div>

        <div v-if="selected_to.length || selected_cc.length || selected_bcc.length" class="mt-2">
            <div v-if="selected_to.length">
                <div class="text-muted">Empfänger (An):</div>

                <div class="row my-2 align-items-center" v-for="(mailable, index) in selected_to">
                    <input v-if="selected_to.length" type="hidden" :id="'email_to['+index+'][id]'" :name="'email_to['+index+'][id]'" :value="mailable.id" />
                    <input v-if="selected_to.length" type="hidden" :id="'email_to['+index+'][name]'" :name="'email_to['+index+'][name]'" :value="mailable.name" />
                    <input v-if="selected_to.length" type="hidden" :id="'email_to['+index+'][email]'" :name="'email_to['+index+'][email]'" :value="mailable.email" />

                    <div class="col">
                        {{mailable.name}} <span class="text-muted">&lt;{{mailable.email}}&gt;</span>

                    </div>
                    <div class="col-auto ml-auto">
                        <button type="button" class="btn btn-sm btn-outline-danger" @click="removeSelectedTo(mailable, selected_to)">Entfernen</button>
                    </div>
                </div>
            </div>

            <div v-if="selected_cc.length">
                <div class="text-muted">Carbon Copy (CC):</div>

                <div class="row my-2 align-items-center" v-for="(mailable, index) in selected_cc">
                    <input v-if="selected_to.length" type="hidden" :id="'email_cc['+index+'][id]'" :name="'email_cc['+index+'][id]'" :value="mailable.id" />
                    <input v-if="selected_to.length" type="hidden" :id="'email_cc['+index+'][name]'" :name="'email_cc['+index+'][name]'" :value="mailable.name" />
                    <input v-if="selected_to.length" type="hidden" :id="'email_cc['+index+'][email]'" :name="'email_cc['+index+'][email]'" :value="mailable.email" />

                    <div class="col">
                        {{mailable.name}} <span class="text-muted">&lt;{{mailable.email}}&gt;</span>
                    </div>
                    <div class="col-auto ml-auto">
                        <button type="button" class="btn btn-sm btn-outline-danger" @click="removeSelectedCC(mailable, selected_cc)">Entfernen</button>
                    </div>
                </div>
            </div>

            <div v-if="selected_bcc.length">
                <div class="text-muted">Blind Carbon Copy (BCC):</div>

                <div class="row my-2 align-items-center" v-for="(mailable, index) in selected_bcc">
                    <input v-if="selected_to.length" type="hidden" :id="'email_bcc['+index+'][id]'" :name="'email_bcc['+index+'][id]'" :value="mailable.id" />
                    <input v-if="selected_to.length" type="hidden" :id="'email_bcc['+index+'][name]'" :name="'email_bcc['+index+'][name]'" :value="mailable.name" />
                    <input v-if="selected_to.length" type="hidden" :id="'email_bcc['+index+'][email]'" :name="'email_bcc['+index+'][email]'" :value="mailable.email" />

                    <div class="col">
                        {{mailable.name}} <span class="text-muted">&lt;{{mailable.email}}&gt;</span>
                    </div>
                    <div class="col-auto ml-auto">
                        <button type="button" class="btn btn-sm btn-outline-danger" @click="removeSelectedBCC(mailable, selected_bcc)">Entfernen</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        name: "EmailSelector",

        data() {
            return {
                selected_input: null,
                selected_to: this.current_to ? this.current_to : [],
                selected_cc: this.current_cc ? this.current_cc : [],
                selected_bcc: this.current_bcc ? this.current_bcc : [],
                unselected: this.current_to.concat(this.current_cc).concat(this.current_bcc) ? this.people.filter(mailable => {
                    return this.current_to.concat(this.current_cc).concat(this.current_bcc).findIndex(current => current.id === mailable.id) === -1;
                }) : this.people
            }
        },

        methods: {
            setSelected(value) {
                if(value && !value.id) {
                    if (value instanceof Object) {
                        value = {id: null, name: value.name, email: value.name};
                    }
                    else {
                        value = {id: null, name: value, email: value};
                    }

                }

                this.selected_input = value;
            },

            addSelected(value, list) {
                // no input
                if(value === null) {
                    return;
                }

                // get mailable from to list
                var selected = this.selected_to.find(mailable => mailable.email === value.email);

                // move mailable to possibly changed list
                if(selected && list !== this.selected_to) {
                    this.selected_to = this.removeFromArray(this.selected_to, selected);
                    list.push(selected);
                    this.sortArrayByName(list);
                }
                // get mailable from cc list and move to possibly changed list
                if(!selected) {
                    selected = this.selected_cc.find(mailable => mailable.email === value.email);

                    if(selected && list !== this.selected_cc) {
                        this.selected_cc = this.removeFromArray(this.selected_cc, selected);
                        list.push(selected);
                        this.sortArrayByName(list);
                    }
                }
                // get mailable from bcc list and move to possibly changed list
                if(!selected) {
                    selected = this.selected_bcc.find(mailable => mailable.email === value.email);

                    if(selected && list !== this.selected_bcc) {
                        this.selected_bcc = this.removeFromArray(this.selected_bcc, selected);
                        list.push(selected);
                        this.sortArrayByName(list);
                    }
                }

                // the email is already in the correct list
                if(selected) {
                    // substitute exixiting non mailable with possibly new mailable values (email only to mailable)
                    if(!selected.id && value.id) {
                        selected.id = value.id;
                        selected.name = value.name;
                        this.unselected = this.removeFromArray(this.unselected, value);
                    }

                    this.selected_input = null;
                    return;
                }

                // if the selected item is an mailable, remove it from the selectable list
                if(value.id) {
                    this.unselected = this.removeFromArray(this.unselected, value);
                }

                // add the new email to the correct list
                list.push(value);
                this.sortArrayByName(list);

                this.selected_input = null;
            },

            removeSelectedTo(value) {
                this.selected_to = this.removeFromArray(this.selected_to, value);

                // only add the item to the selectable list if it is an mailable
                if(value.id) {
                    this.unselected.push(value);
                    this.sortArrayByName(this.unselected);
                }
            },

            removeSelectedCC(value) {
                this.selected_cc = this.removeFromArray(this.selected_cc, value);

                // only add the item to the selectable list if it is an mailable
                if(value.id) {
                    this.unselected.push(value);
                    this.sortArrayByName(this.unselected);
                }
            },

            removeSelectedBCC(value) {
                this.selected_bcc = this.removeFromArray(this.selected_bcc, value);

                // only add the item to the selectable list if it is an mailable
                if(value.id) {
                    this.unselected.push(value);
                    this.sortArrayByName(this.unselected);
                }
            },

            removeFromArray(entities, value) {
                return entities.filter(mailable => {
                    return !(mailable.id === value.id && mailable.email === value.email);
                });
            },

            sortArrayByName(entities) {
                entities.sort((a, b) => {
                    let a_lower = a.name.toLowerCase();
                    let b_lower = b.name.toLowerCase();
                    return a_lower > b_lower ? 1 : b_lower > a_lower ? -1 : 0;
                });
            }
        },

        props: {
            people: {
                type: Array,
                default() {
                    return [];
                }
            },
            current_to: {
                type: Array,
                default() {
                    return [];
                }
            },
            current_cc: {
                type: Array,
                default() {
                    return [];
                }
            },
            current_bcc: {
                type: Array,
                default() {
                    return [];
                }
            }
        }

    }
</script>
