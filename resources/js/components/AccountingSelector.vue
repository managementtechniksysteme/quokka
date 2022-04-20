<template>
  <div>
      <h3>Anzeigefilter</h3>

      <div v-if="getUnsavedAccounting().length" class="alert alert-warning" role="alert">
          <div class="d-inline-flex align-items-center">
              <svg class="feather feather-24 mr-2">
                  <use xlink:href="/svg/feather-sprite.svg#alert-triangle"></use>
              </svg>
              <p class="m-0">
                  Du hast ungespeicherte Änderungen. Geänderte Zeilen bleiben auch dann sichtbar, wenn der
                  Filterbereich nachträglich geändert wird.
              </p>
          </div>
      </div>

      <form class="needs-validation mt-4" action="" method="post" novalidate>

          <div class="form-row">
              <div class="form-group col-6 col-lg-3">
                  <label for="filter_start">Start</label>
                  <input type="date" class="form-control" v-bind:class="{'is-invalid': filter_start_errors}" id="filter_start" name="filter_start" placeholder="" v-model="filter_start" />
                  <div v-if="filter_start_errors" class="invalid-feedback">
                      {{ filter_start_errors[0] }}
                  </div>
              </div>
              <div class="form-group col-6 col-lg-3">
                  <label for="filter_end">Ende</label>
                  <input type="date" class="form-control" v-bind:class="{'is-invalid': filter_end_errors}" id="filter_end" name="filter_end" placeholder="" v-model="filter_end" />
                  <div v-if="filter_end_errors" class="invalid-feedback">
                      {{ filter_end_errors[0] }}
                  </div>
              </div>
              <div class="form-group col-md-6 col-lg-3">
                  <label>Projekt</label>
                  <v-select :options="projects" label="name" placeholder="Projekt auswählen" :value="filter_project" @input="setFilterProject"></v-select>
                  <div v-if="filter_project_errors" class="invalid-feedback" v-bind:class="{'d-block': filter_project_errors}">
                      {{ filter_project_errors[0] }}
                  </div>
              </div>
              <div class="form-group col-md-6 col-lg-3">
                  <label>Leistung</label>
                  <v-select :options="services" label="name_with_unit" placeholder="Leistung auswählen" :value="filter_service" @input="setFilterService"></v-select>
                  <div v-if="filter_service_errors" class="invalid-feedback" v-bind:class="{'d-block': filter_service_errors}">
                      {{ filter_service_errors[0] }}
                  </div>
              </div>
              <div class="form-group col">
                  <div class="custom-control custom-switch">
                      <input type="checkbox" class="custom-control-input" v-bind:class="{'is-invalid': filter_only_own_errors}" name="filter_only_own" id="filter_only_own" :value="filter_only_own" v-model="filter_only_own" @click="toggleFilterOnlyOwn()">
                      <label class="custom-control-label" for="filter_only_own">Nur eigene Leistungen anzeigen.</label>
                  </div>
                  <div v-if="filter_only_own_errors" class="invalid-feedback" v-bind:class="{'d-block': filter_only_own_errors}">
                      {{ filter_only_own_errors[0] }}
                  </div>
              </div>
          </div>
          <button type="button" class="btn btn-outline-secondary d-inline-flex align-items-center mt-4" @click="filterData()">
              <svg class="feather feather-16 mr-2">
                  <use xlink:href="/svg/feather-sprite.svg#filter"></use>
              </svg>
              Leistungen filtern
          </button>
      </form>

      <h3 class="mt-4">Leistungen abrechnen</h3>

      <form class="needs-validation mt-4" action="" method="post" novalidate>

          <div class="form-row">
              <div class="form-group col-6 col-md-4 col-lg-3">
                  <label for="service_provided_on">Datum</label>
                  <input type="date" class="form-control" v-bind:class="{'is-invalid': service_provided_on_invalid}" id="service_provided_on" name="service_provided_on" placeholder="" required v-model="date" />
                  <div class="invalid-feedback">
                      Datum muss ausgefüllt sein.
                  </div>
              </div>
              <div class="form-group col-3 col-md-4 col-lg-3">
                  <label for="service_provided_started_at">Start</label>
                  <input type="time" class="form-control" v-bind:class="{'is-invalid': service_provided_started_at_invalid}" id="service_provided_started_at" name="service_provided_started_at" placeholder="08:00" v-model="service_provided_started_at" />
                  <div class="invalid-feedback">
                      Start muss eine gültige Uhrzeit sein.
                  </div>
              </div>
              <div class="form-group col-3 col-md-4 col-lg-3">
                  <label for="service_provided_ended_at">Ende</label>
                  <input type="time" class="form-control" v-bind:class="{'is-invalid': service_provided_ended_at_invalid}" id="service_provided_ended_at" name="service_provided_ended_at" placeholder="13:00" v-model="service_provided_ended_at" />
                  <div class="invalid-feedback">
                      Ende muss eine gültige Uhrzeit sein.
                  </div>
              </div>
              <div class="form-group col-md-4 col-lg-3">
                  <label>Projekt</label>
                  <v-select :options="projects" label="name" placeholder="Projekt auswählen" :value="project" @input="setProject"></v-select>
                  <div class="invalid-feedback" v-bind:class="{'d-block': project_invalid}">
                      Projekt muss ausgefüllt sein.
                  </div>
              </div>
              <div class="form-group col-md-4 col-lg-3">
                  <label>Leistung</label>
                  <v-select :options="services" label="name_with_unit" placeholder="Leistung auswählen" :value="service" @input="setService"></v-select>
                  <div class="invalid-feedback" v-bind:class="{'d-block': service_invalid}">
                      Leistung muss ausgefüllt sein.
                  </div>
              </div>
              <div class="form-group col-md-4 col-lg-3">
                  <label for="amount">Menge</label>
                  <input type="number" class="form-control" v-bind:class="{'is-invalid': amount_invalid}" min="0.5" step="0.5" id="amount" name="amount" placeholder="5" v-model="amount" />
                  <div class="invalid-feedback">
                      Menge muss mindestens 0.5 sein.
                  </div>
              </div>
              <div class="form-group col-lg-3">
                  <label for="amount">Bemerkungen</label>
                  <input type="text" class="form-control" id="comment" name="comment" placeholder="Bemerkungen" v-model="comment" />
              </div>
              <div class="form-group d-none d-lg-block col-lg-3">
                  <label for="addservice">&nbsp;</label>
                  <button id="addservice" type="button" class="form-control btn btn-outline-secondary" @click="addAccounting()">
                      Hinzufügen
                  </button>
              </div>
          </div>
          <div class="d-block d-lg-none">
              <button id="addservice" type="button" class="btn btn-outline-secondary" @click="addAccounting()">
                  Hinzufügen
              </button>
          </div>

          <div v-if="accounting.length" class="mt-4">
              <table class="table table-sm">
                  <thead>
                      <tr>
                          <th scope="col" class="col-auto">
                              <button type="button" class="btn btn-sm outline-none" v-bind:class="{'text-gray-500': !getErrorAccounting().length, 'errorstoggle text-red-100': getErrorAccounting().length, 'text-red-500': getErrorAccounting().length && !getShowNoDetailsErrorAccounting().length}" :disabled="!getErrorAccounting().length" @click="toggleShowDetailsError()">
                                  <svg class="feather feather-16">
                                      <use xlink:href="/svg/feather-sprite.svg#alert-triangle"></use>
                                  </svg>
                              </button>
                          </th>
                          <th scope="col" class="col-1-5">Datum</th>
                          <th scope="col" class="col-1">Start</th>
                          <th scope="col" class="col-1">Ende</th>
                          <th scope="col" class="col-2">Projekt</th>
                          <th scope="col" class="col-2">Leistung</th>
                          <th scope="col" class="col-1">Menge</th>
                          <th scope="col" class="col-1-5">Mitarbeiter</th>
                          <th scope="col" class="col-auto text-right">
                              <button type="button" class="btn btn-sm btn-outline-danger d-inline-flex align-items-center" :disabled="!getSelectedAccounting().length" @click="removeSelectedAccounting()">
                                  <svg class="feather feather-16">
                                      <use xlink:href="/svg/feather-sprite.svg#trash-2"></use>
                                  </svg>
                              </button>
                              <button type="button" class="btn btn-sm btn-outline-success d-inline-flex align-items-center" :disabled="!getSelectedAccounting().length" @click="restoreSelectedAccounting()">
                                  <svg class="feather feather-16">
                                      <use xlink:href="/svg/feather-sprite.svg#rotate-ccw"></use>
                                  </svg>
                              </button>
                              <button v-if="!(getSelectedAccounting().length === accounting.length)" type="button" class="btn btn-sm checkboxtoggle text-blue-100" @click="toggleSelectAll()"  @mouseenter="selectAllHover = true"  @mouseleave="selectAllHover = false">
                                  <svg class="feather feather-16">
                                      <use v-if="!selectAllHover" xlink:href="/svg/feather-sprite.svg#circle"></use>
                                      <use v-if="selectAllHover" xlink:href="/svg/feather-sprite.svg#check-circle"></use>
                                  </svg>
                              </button>
                              <button v-if="getSelectedAccounting().length === accounting.length"  type="button" class="btn btn-sm checkboxtoggle text-blue-500" @click="toggleSelectAll()"  @mouseenter="selectAllHover = true"  @mouseleave="selectAllHover = false">
                                  <svg class="feather feather-16">
                                      <use xlink:href="/svg/feather-sprite.svg#check-circle"></use>
                                  </svg>
                              </button>
                          </th>
                      </tr>
                  </thead>
                  <tbody>
                      <template v-for="acc in accounting">
                          <tr v-bind:class="{'border-status border-success': acc.action === 'store' && !acc.selected, 'border-status border-warning': acc.action === 'update' && !acc.selected, 'text-muted ': acc.action === 'destroy', 'border-status border-danger': acc.action === 'destroy' && !acc.selected, 'border-status border-primary': acc.selected}">
                              <td class="col-auto">
                                  <button type="button" class="btn btn-sm outline-none" v-bind:class="{'detailstoggle text-gray-500': !acc.errors && !acc.show_details, 'errorstoggle text-red-100': acc.errors && !acc.show_details, 'text-dark': !acc.errors && acc.show_details, 'text-red-500': acc.errors && acc.show_details}" @click="toggleShowDetails(acc)">
                                      <svg class="feather feather-16">
                                          <use v-if="!acc.errors && !acc.show_details" xlink:href="/svg/feather-sprite.svg#chevron-right"></use>
                                          <use v-if="acc.errors && !acc.show_details" xlink:href="/svg/feather-sprite.svg#alert-triangle"></use>
                                          <use v-if="acc.show_details" xlink:href="/svg/feather-sprite.svg#chevron-down"></use>
                                      </svg>
                                  </button>
                              </td>
                              <td class="col-1-5" @click="setEdit(acc, 'service_provided_on')">
                                  <span v-if="acc.edit !== 'service_provided_on'">{{ acc.service_provided_on.toLocaleDateString("de", { month: '2-digit', day: '2-digit', year: 'numeric' }) }}</span>
                                  <input v-if="acc.edit === 'service_provided_on'" type="date" class="form-control form-control-sm" v-bind:class="{'is-invalid': table_service_provided_on_invalid}" ref="table_input" id="table_service_provided_on" name="table_service_provided_on" :value="getDateStringForInputField(acc.service_provided_on)" placeholder="" required @blur="changeAccountingServiceProvidedOn($event, acc)" />
                              </td>
                              <td class="col-1" @click="setEdit(acc, 'service_provided_started_at')">
                                  <span v-if="acc.edit !== 'service_provided_started_at'">{{ acc.service_provided_started_at }}</span>
                                  <input v-if="acc.edit === 'service_provided_started_at'" type="time" class="form-control form-control-sm" v-bind:class="{'is-invalid': table_service_provided_started_at_invalid}" ref="table_input" id="table_service_provided_started_at" name="table_service_provided_started_at" :value="acc.service_provided_started_at ? acc.service_provided_started_at : ''" placeholder="08:00" @blur="changeAccountingServiceProvidedStartedAt($event, acc)" />
                              </td>
                              <td class="col-1" @click="setEdit(acc, 'service_provided_ended_at')">
                                  <span v-if="acc.edit !== 'service_provided_ended_at'">{{ acc.service_provided_ended_at }}</span>
                                  <input v-if="acc.edit === 'service_provided_ended_at'" type="time" class="form-control form-control-sm" v-bind:class="{'is-invalid': table_service_provided_ended_at_invalid}" ref="table_input" id="table_service_provided_ended_at" name="table_service_provided_ended_at" :value="acc.service_provided_ended_at ? acc.service_provided_ended_at : ''" placeholder="13:00" @blur="changeAccountingServiceProvidedEndedAt($event, acc)" />
                              </td>
                              <td class="col-2" @click="setEdit(acc, 'project')">
                                  <span v-if="acc.edit !== 'project'">{{ getProjectName(acc.project_id) }}</span>
                                  <v-select v-if="acc.edit === 'project'" class="dropdown-sm" :options="projects" ref="table_input"  label="name" placeholder="Projekt auswählen" :value="getProject(acc.project_id)" @input="changeAccountingProject($event, acc)" @close="changeAccountingDropdownValueToSame(acc)"></v-select>
                              </td>
                              <td class="col-1" @click="setEdit(acc, 'service')">
                                  <span v-if="acc.edit !== 'service'">{{ getServiceName(acc.service_id) }}</span>
                                  <v-select v-if="acc.edit === 'service'" class="dropdown-sm" :options="services" ref="table_input"  label="name_with_unit" placeholder="Service auswählen" :value="getService(acc.service_id)" @input="changeAccountingService($event, acc)" @close="changeAccountingDropdownValueToSame(acc)"></v-select>
                              </td>
                              <td class="col-1" @click="setEdit(acc, 'amount')">
                                  <span v-if="acc.edit !== 'amount'">{{ acc.amount }}</span>
                                  <input v-if="acc.edit === 'amount'" type="number" min="0.5" step="0.5" class="form-control form-control-sm" v-bind:class="{'is-invalid': table_amount_invalid}" ref="table_input"  id="table_amount" name="table_amount" :value="acc.amount" placeholder="5" @blur="changeAccountingAmount($event, acc)" />
                              </td>
                              <td class="col-1-5">{{ getEmployeeName(acc.employee_id) }}</td>
                              <td class="col-auto text-right">
                                  <button v-if="!(acc.action === 'destroy')" type="button" class="btn btn-sm btn-outline-danger" @click="removeAccounting(acc)">
                                      <svg class="feather feather-16">
                                          <use xlink:href="/svg/feather-sprite.svg#trash-2"></use>
                                      </svg>
                                  </button>
                                  <button v-if="acc.action === 'destroy'" type="button" class="btn btn-sm btn-outline-success" @click="restoreAccounting(acc)">
                                      <svg class="feather feather-16">
                                          <use xlink:href="/svg/feather-sprite.svg#rotate-ccw"></use>
                                      </svg>
                                  </button>
                                  <button v-if="!acc.selected" type="button" class="btn btn-sm outline-none checkboxtoggle text-blue-100" @click="toggleSelected(acc)" @mouseenter="acc.hover = true"  @mouseleave="acc.hover = false">
                                      <svg class="feather feather-16">
                                          <use v-if="!acc.hover" xlink:href="/svg/feather-sprite.svg#circle"></use>
                                          <use v-if="acc.hover" xlink:href="/svg/feather-sprite.svg#check-circle"></use>
                                      </svg>
                                  </button>
                                  <button v-if="acc.selected" type="button" class="btn btn-sm outline-none checkboxtoggle text-blue-500" @click="toggleSelected(acc)"  @mouseenter="acc.hover = true"  @mouseleave="acc.hover = false">
                                      <svg class="feather feather-16">
                                          <use xlink:href="/svg/feather-sprite.svg#check-circle"></use>
                                      </svg>
                                  </button>
                              </td>
                          </tr>

                          <transition name="collapse">
                              <tr v-if="acc.show_details"  v-bind:class="{'border-status border-success': acc.action === 'store' && !acc.selected, 'border-status border-warning': acc.action === 'update' && !acc.selected, 'text-muted ': acc.action === 'destroy', 'border-status border-danger': acc.action === 'destroy' && !acc.selected, 'border-status border-primary': acc.selected}">
                                  <td class="border-0" ></td>
                                  <td colspan="7" class="border-0"  @click="setEdit(acc, 'comment')">
                                      <div class="form-group">
                                          <label for="table_comment">Bemerkungen</label>
                                          <div v-if="acc.edit !== 'comment'" class="text-s">{{ acc.comment ? acc.comment : 'nicht angegeben' }}</div>
                                          <input v-if="acc.edit === 'comment'" type="text" class="form-control form-control-sm" ref="table_input" id="table_comment" name="table_comment" placeholder="Bemerkungen" :value="acc.comment" @blur="changeAccountingComment($event, acc)" />
                                      </div>
                                      <div v-if="acc.errors" class="alert alert-danger" role="alert">
                                          <p class="mb-0">Probleme in dieser Zeile</p>
                                          <ul class="mb-0">
                                              <li v-for="error in acc.errors">{{ error }}</li>
                                          </ul>
                                      </div>
                                  </td>
                              </tr>
                          </transition>
                      </template>
                  </tbody>
              </table>

              <p v-if="accounting.length" class="mt-3">
                  Der linke farbliche Rand zeigt den Speicherzustand der jeweiligen Zeile:
                  <span class="badge badge-green-100 text-green-800">wird angelegt</span>
                  <span class="badge badge-yellow-100 text-yellow-800">wird bearbeitet</span>
                  <span class="badge badge-red-100 text-red-800">wird entfernt</span>
              </p>
          </div>

          <div v-if="!accounting.length" class="text-center mt-4">
              <img class="empty-state" src="/svg/no-data.svg" alt="no data" />
              <p class="lead text-muted">Es sind keine Abrechnungen passend zum Anzeigefilter vorhanden.</p>
              <p class="lead">Rechne neue Leistungen mithilfe des Formulars ab.</p>
          </div>

          <button v-if="accounting.length" type="button" class="btn btn-primary d-inline-flex align-items-center mt-4" :disabled="!getUnsavedAccounting().length" @click="saveData()">
              <svg class="feather feather-16 mr-2">
                  <use xlink:href="/svg/feather-sprite.svg#save"></use>
              </svg>
              Änderungen speichern
          </button>
      </form>
  </div>
</template>

<script>

    export default {
        name: "AccountingsSelector",

        data() {
            let today = new Date();
            return {
                filter_start: null,
                filter_start_errors: null,
                filter_end: null,
                filter_end_errors:null,
                filter_project: null,
                filter_project_errors: null,
                filter_service: null,
                filter_service_errors: null,
                filter_only_own: true,
                filter_only_own_errors: null,

                date: this.getDateStringForInputField(new Date(today.getTime() - today.getTimezoneOffset() * 60 * 1000)),
                service_provided_on_invalid: false,
                table_service_provided_on_invalid: false,
                service_provided_started_at: null,
                service_provided_started_at_invalid: false,
                table_service_provided_started_at_invalid: false,
                service_provided_ended_at: null,
                service_provided_ended_at_invalid: false,
                table_service_provided_ended_at_invalid: false,
                project: null,
                project_invalid: false,
                table_project_invalid: false,
                service: null,
                service_invalid: false,
                table_service_invalid: false,
                amount: null,
                amount_invalid: false,
                table_amount_invalid: false,
                comment: null,
                accounting: [],

                selectAllHover: false,

            }
        },

        created() {
            if(this.current_accounting) {
                let userTimezoneOffset = new Date().getTimezoneOffset() * 60 * 1000;

                this.current_accounting.forEach(acc => {
                    let date = Date.parse(acc.service_provided_on);

                    this.accounting.push({
                        action: null,
                        action_old: null,
                        errors: null,
                        selected: false,
                        show_details: false,
                        hover: false,
                        edit: null,
                        id: acc.id,
                        service_provided_on: new Date(date - userTimezoneOffset),
                        service_provided_started_at: acc.service_provided_started_at,
                        service_provided_ended_at: acc.service_provided_ended_at,
                        project_id: acc.project_id,
                        service_id: acc.service_id,
                        employee_id: acc.employee_id,
                        amount: acc.amount,
                        comment: acc.comment,
                    });
                });
            }

            else if(this.show_days > 0) {
                let today = new Date();
                let date = new Date(today.getTime() - today.getTimezoneOffset() * 60 * 1000);

                date.setDate(date.getDate() - this.show_days)

                this.filter_start = this.getDateStringForInputField(date);

                this.filterData();
            }
        },

        methods: {
            filterData() {
                let params = {};

                if(this.filter_start) {
                    params.start = this.filter_start;
                }
                if(this.filter_end) {
                    params.end = this.filter_end;
                }
                if(this.filter_project) {
                    params.project_id = this.filter_project.id;
                }
                if(this.filter_service) {
                    params.service_id = this.filter_service.id;
                }
                if(this.filter_only_own) {
                    params.only_own = this.filter_only_own;
                }

                axios.get('/accounting', {params: params})
                .then(response => {
                    this.updateLocalAccounting(response.data)

                    this.filter_start_errors = null;
                    this.filter_end_errors = null;
                    this.filter_project_errors = null;
                    this.filter_service_errors = null;
                    this.filter_only_own_errors = null;
                })
                .catch(error => {
                    console.log(error);
                });
            },

            updateLocalAccounting(fetchedAccounting) {
                let userTimezoneOffset = new Date().getTimezoneOffset() * 60 * 1000;

                let newAccounting = fetchedAccounting.filter(
                    fetchedAccounting => !this.accounting.some(
                        localAccounting => localAccounting.id === fetchedAccounting.id
                    )
                );

                let removedUnchangedAccounting = this.accounting.filter(
                    localAccounting => !fetchedAccounting.some(
                        fetchedAccounting => fetchedAccounting.id === localAccounting.id
                    ) && localAccounting.action === null && localAccounting.action_old === null
                );

                newAccounting.forEach(acc => {
                    let date = Date.parse(acc.service_provided_on);

                    this.accounting.push({
                        action: null,
                        action_old: null,
                        errors: null,
                        selected: false,
                        show_details: false,
                        hover: false,
                        edit: null,
                        id: acc.id,
                        service_provided_on: new Date(date - userTimezoneOffset),
                        service_provided_started_at: acc.service_provided_started_at,
                        service_provided_ended_at: acc.service_provided_ended_at,
                        project_id: acc.project_id,
                        service_id: acc.service_id,
                        employee_id: acc.employee_id,
                        amount: acc.amount,
                        comment: acc.comment,
                    });
                });

                removedUnchangedAccounting.forEach(acc => {
                    this.accounting = this.removeFromArray(this.accounting, acc);
                });

                this.sortArrayByDateTime(this.accounting);
            },

            saveData() {
                this.save_result = null;

                let _accounting = this.getUnsavedAccounting();

                _accounting.forEach(acc => {
                    switch (acc.action) {
                        case 'store':
                            this.storeAccounting(acc);
                            break;
                        case 'update':
                            this.updateAccounting(acc);
                            break;
                        case 'destroy':
                            if(acc.id !== null) {
                                this.destroyAccounting(acc);
                            }
                            else {
                                this.accounting = this.removeFromArray(this.accounting, acc);
                            }
                            break;
                    }
                });
            },

            storeAccounting(accounting) {
                axios.post('/accounting', {
                    service_provided_on: accounting.service_provided_on,
                    service_provided_started_at: accounting.service_provided_started_at,
                    service_provided_ended_at: accounting.service_provided_ended_at,
                    project_id: accounting.project_id,
                    service_id: accounting.service_id,
                    amount: accounting.amount,
                    comment: accounting.comment,
                })
                .then(response => {
                    accounting.id = response.data.id;
                    accounting.employee_id = response.data.employee_id;
                    accounting.action = null;
                    accounting.action_old = null;
                    accounting.errors = null;
                    accounting.show_details = false;
                })
                .catch(error => {
                    accounting.errors = this.extractErrorMessages(error.response);
                    accounting.show_details = this.expand_errors;
                });
            },

            updateAccounting(accounting) {
                axios.post('/accounting/' + accounting.id, {
                    _method: 'PATCH',

                    id: accounting.id,
                    service_provided_on: accounting.service_provided_on,
                    service_provided_started_at: accounting.service_provided_started_at,
                    service_provided_ended_at: accounting.service_provided_ended_at,
                    project_id: accounting.project_id,
                    service_id: accounting.service_id,
                    amount: accounting.amount,
                    comment: accounting.comment,
                })
                .then(response => {
                    accounting.action = null;
                    accounting.action_old = null;
                    accounting.errors = null;
                    accounting.show_details = false;
                })
                .catch(error => {
                    accounting.errors = this.extractErrorMessages(error.response);
                    accounting.show_details = this.expand_errors;
                });
            },

            destroyAccounting(accounting) {
                axios.post('/accounting/' + accounting.id, {
                    _method: 'DELETE'
                })
                .then(response => {
                    this.accounting = this.removeFromArray(this.accounting, accounting);
                    accounting.errors = null;
                    accounting.show_details = false;
                })
                .catch(error => {
                    accounting.errors = this.extractErrorMessages(error.response);
                    accounting.show_details = this.expand_errors;
                });
            },

            extractErrorMessages(response) {
                let messages = [];

                Object.keys(response.data.errors).forEach(item => {
                    response.data.errors[item].forEach(message => {
                        messages.push(message);
                    });
                });

                return messages.length ? messages : null;
            },

            removeFromArray(accounting, value) {
                return accounting.filter(accounting => accounting.id !== value.id);
            },

            sortArrayByDateTime(accounting) {
                accounting.sort((a, b) => {
                    if(a.service_provided_on.getTime() !== b.service_provided_on.getTime()) {
                        return a.service_provided_on - b.service_provided_on;
                    }
                    else if(a.service_provided_started_at !== null && b.service_provided_started_at !== null &&
                        a.service_provided_started_at !== b.service_provided_started_at) {
                        return a.service_provided_started_at < b.service_provided_started_at ? -1 : 1;
                    }
                    else if(a.service_provided_started_at === null && b.service_provided_started_at !== null) {
                        return -1;
                    }
                    else if(a.service_provided_started_at !== null && b.service_provided_started_at === null) {
                        return 1;
                    }
                    else {
                        return 0;
                    }
                });
            },

            getUnsavedAccounting() {
                return this.accounting.filter(acc => acc.action !== null);
            },

            setFilterProject(value) {
                this.filter_project = value;
            },

            setFilterService(value) {
                this.filter_service = value;
            },

            toggleFilterOnlyOwn() {
                this.filter_only_own = !this.filter_only_own;
            },

            setProject(value) {
                this.project = value;
            },

            setService(value) {
                this.service = value;
            },

            addAccounting() {
                let date = new Date(this.date);
                let amount = this.amount === null ? 0 : Number(this.amount);

                this.service_provided_on_invalid = isNaN(date.getTime());
                this.service_provided_started_at_invalid = this.service_provided_started_at !== null &&
                    (this.service_provided_ended_at === null || !this.isTwentyFourHourTimeFormat(this.service_provided_started_at));
                this.service_provided_ended_at_invalid = this.service_provided_ended_at !== null &&
                    (this.service_provided_started_at === null || !this.isTwentyFourHourTimeFormat(this.service_provided_ended_at));
                this.project_invalid = this.project === null;
                this.service_invalid = this.service === null;
                this.amount_invalid = Number.isNaN(amount) || amount % 0.5 !== 0 || amount < 0.5;

                if(this.service_provided_on_invalid || this.service_provided_started_at_invalid ||
                    this.service_provided_ended_at_invalid || this.project_invalid || this.service_invalid ||
                    this.amount_invalid) {
                    return;
                }

                this.accounting.push({
                    action: 'store',
                    action_old: 'store',
                    errors: null,
                    selected: false,
                    show_details: false,
                    hover: false,
                    edit: null,
                    id: null,
                    service_provided_on: date,
                    service_provided_started_at: this.service_provided_started_at,
                    service_provided_ended_at: this.service_provided_ended_at,
                    project_id: this.project.id,
                    service_id: this.service.id,
                    employee_id: null,
                    amount: amount,
                    comment: this.comment,
                });

                let today = new Date();
                this.date = this.getDateStringForInputField(new Date(today.getTime() - today.getTimezoneOffset() * 60 * 1000));
                this.service_provided_on_invalid = false;
                this.service_provided_started_at = null;
                this.service_provided_started_at_invalid = false;
                this.service_provided_ended_at = null;
                this.service_provided_ended_at_invalid = false;
                this.project_invalid = false;
                this.service_invalid = false;
                this.amount = null;
                this.amount_invalid = false;
                this.comment = null;

            },

            removeAccounting(accounting) {
                if(accounting.action !== 'destroy') {
                    accounting.action_old = accounting.action;
                }

                accounting.action = 'destroy';
            },

            restoreAccounting(accounting) {
                accounting.action = accounting.action_old ? accounting.action_old : null;
            },

            removeSelectedAccounting() {
                let selectedAccounting = this.getSelectedAccounting();

                selectedAccounting.forEach(selected => {
                    this.removeAccounting(selected);
                    selected.selected = false;
                });
            },

            restoreSelectedAccounting() {
                let selectedAccounting = this.getSelectedAccounting();

                selectedAccounting.forEach(selected => {
                    this.restoreAccounting(selected);
                    selected.selected = false;
                });
            },

            changeAccountingServiceProvidedOn(event, changedAccounting) {
                let date = new Date(event.target.value);

                if(isNaN(date.getTime())) {
                    this.table_service_provided_on_invalid = true;
                    return;
                }

                changedAccounting.service_provided_on = date;

                this.setChangedAccountingStatus(changedAccounting);

                changedAccounting.edit = null;
            },

            changeAccountingServiceProvidedStartedAt(event, changedAccounting) {
                let time = event.target.value ? event.target.value : null;

                if(time !== null && !this.isTwentyFourHourTimeFormat(time)) {
                    this.table_service_provided_started_at_invalid = true;
                    return;
                }

                changedAccounting.service_provided_started_at = time;

                this.setChangedAccountingStatus(changedAccounting);

                changedAccounting.edit = null;
            },

            changeAccountingServiceProvidedEndedAt(event, changedAccounting) {
                let time = event.target.value ? event.target.value : null;

                if(time !== null && !this.isTwentyFourHourTimeFormat(time)) {
                    this.table_service_provided_ended_at_invalid = true;
                    return;
                }

                changedAccounting.service_provided_ended_at = time;

                this.setChangedAccountingStatus(changedAccounting);

                changedAccounting.edit = null;
            },

            changeAccountingProject(value, changedAccounting) {
                if(!value) {
                    this.table_project_invalid = true;
                    return;
                }

                changedAccounting.project_id = value.id;

                this.setChangedAccountingStatus(changedAccounting);

                changedAccounting.edit = null;
            },

            changeAccountingService(value, changedAccounting) {
                if(!value) {
                    this.table_service_invalid = true;
                    return;
                }

                changedAccounting.servie_id = this.value.id;

                this.setChangedAccountingStatus(changedAccounting);

                changedAccounting.edit = null;
            },

            changeAccountingDropdownValueToSame(changedAccounting) {
                this.setChangedAccountingStatus(changedAccounting);
                this.unsetEdit(changedAccounting);
            },

            changeAccountingAmount(event, changedAccounting) {
                let amount = Number(event.target.value);

                if(Number.isNaN(amount) || amount % 0.5 !== 0 || amount < 0.5) {
                    this.table_amount_invalid = true;
                    return;
                }

                changedAccounting.amount = amount;

                this.setChangedAccountingStatus(changedAccounting);

                changedAccounting.edit = null;
            },

            changeAccountingComment(event, changedAccounting) {
                changedAccounting.comment = event.target.value;

                this.setChangedAccountingStatus(changedAccounting);

                changedAccounting.edit = null;
            },

            setChangedAccountingStatus(changedAccounting) {
                if(changedAccounting.action === 'destroy') {
                    changedAccounting.action = changedAccounting.action_old;
                }

                if(!(changedAccounting.action === 'store')) {
                    changedAccounting.action = 'update';
                }

                if(!changedAccounting.action_old) {
                    changedAccounting.action_old = 'update';
                }
            },

            toggleSelected(accounting) {
                accounting.selected = !accounting.selected;
            },

            toggleSelectAll() {
                let selectedAccounting = this.getSelectedAccounting();

                let selected = selectedAccounting.length !== this.accounting.length

                this.accounting.forEach(acc => {
                    acc.selected = selected;
                });
            },

            getSelectedAccounting() {
                return this.accounting.filter(acc => acc.selected === true);
            },

            toggleShowDetails(accounting) {
                accounting.show_details = !accounting.show_details;
            },

            toggleShowDetailsError() {
                let showNoDetailsErrorAccounting = this.getShowNoDetailsErrorAccounting();

                if(showNoDetailsErrorAccounting.length) {
                    showNoDetailsErrorAccounting.forEach(acc => {
                        acc.show_details = true;
                    });
                }
                else {
                    let showDetailsErrorAccounting = this.getShowDetailsErrorAccounting();

                    showDetailsErrorAccounting.forEach(acc => {
                        acc.show_details = false;
                    });
                }
            },

            getErrorAccounting() {
                return this.accounting.filter(acc => acc.errors !== null);
            },

            getShowDetailsErrorAccounting() {
                return this.accounting.filter(acc => acc.errors !== null && acc.show_details === true);
            },

            getShowNoDetailsErrorAccounting() {
                return this.accounting.filter(acc => acc.errors !== null && acc.show_details === false);
            },

            setEdit(accounting, field) {
                this.getEditAccounting().forEach(editAccounting => {
                    editAccounting.edit = null;
                });

                accounting.edit = field;

                this.$nextTick(() => {
                    if(field === 'project' || field === 'service') {
                        this.$refs.table_input[0].$refs.search.focus();
                    }
                    else if(field !== null) {
                        this.$refs.table_input[0].focus();
                    }
                });


                this.table_service_provided_on_invalid = false;
                this.table_service_provided_started_at_invalid = false;
                this.table_service_provided_ended_at_invalid = false;
                this.table_project_invalid = false;
                this.table_service_invalid = false;
                this.table_amount_invalid = false;
            },

            unsetEdit(accounting) {
                this.setEdit(accounting, null);
            },

            getEditAccounting() {
                return this.accounting.filter(acc => acc.edit !== null);
            },

            getProject(projectId) {
                return this.projects.find(project => project.id === projectId);
            },

            getProjectName(projectId) {
                let project = this.projects.find(project => project.id === projectId);
                return project ? project.name : '';
            },

            getService(serviceId) {
                return this.services.find(service => service.id === serviceId);
            },

            getServiceName(serviceId) {
                let service = this.services.find(service => service.id === serviceId);
                return service ? service.name_with_unit : '';
            },

            getEmployeeName(employeeId) {
                let employee = this.employees.find(employee => employee.id === employeeId);
                return employee ? employee.name : this.current_employee.name;
            },

            isTwentyFourHourTimeFormat(text) {
                return /^([01]\d|2[0123]):[012345]\d$/.test(text);
            },

            getDateStringForInputField(date) {
                return date.toISOString().substr(0, 10);
            },
        },

        props: {
            current_accounting: {
                type: Array,
                default() {
                    return [];
                }
            },

            projects: {
                type: Array,
                default() {
                    return [];
                }
            },

            services: {
                type: Array,
                default() {
                    return [];
                }
            },

            employees: {
                type: Array,
                default() {
                    return [];
                }
            },

            current_employee: {
                type: Object,
                default() {
                    return null;
                }
            },

            expand_errors: {
                type: Boolean,
                default() {
                    return true;
                }
            },

            show_days: {
                type: Number,
                default() {
                    return 3;
                }
            }
        }

    }
</script>
