<template>
  <div v-bind:class="{'h-100': $screen.xl}">

      <vue-topprogress ref="top_progress" color="#007BFF" errorColor="#DC3545"></vue-topprogress>

      <notification v-if="dataResult !== null && dataResult.hasOwnProperty('success')" type="success" v-cloak>
          <div class="d-inline-flex align-items-center">
              <svg class="feather feather-24 mr-2">
                  <use xlink:href="/svg/feather-sprite.svg#check"></use>
              </svg>
              {{ this.dataResult.success }}
          </div>
      </notification>
      <notification v-if="dataResult !== null && dataResult.hasOwnProperty('danger')" type="danger" v-cloak>
          <div class="d-inline-flex align-items-center">
              <svg class="feather feather-24 mr-2">
                  <use xlink:href="/svg/feather-sprite.svg#alert-octagon"></use>
              </svg>
              {{ this.dataResult.danger }}
          </div>
      </notification>



      <div v-bind:class="{'container': !$screen.xl, 'container-fluid h-100': $screen.xl}">
          <div class="row" v-bind:class="{'h-100': $screen.xl}">

              <div class="order-1" v-bind:class="{'col-12': !$screen.xl, 'col-xl-2 bg-gray-100': $screen.xl}">
                  <div v-bind:class="{'sticky-top pt-xl-4': $screen.xl}">
                      <h3>Anzeigefilter</h3>

                      <form class="needs-validation mt-4" action="" method="post" novalidate>

                          <div class="form-row">
                              <div class="form-group col-6 col-lg-3 col-xl-12">
                                  <label for="filter_start">Start</label>
                                  <input type="date" :max="filter_end" class="form-control" v-bind:class="{'is-invalid': filter_start_errors}" id="filter_start" name="filter_start" placeholder="" :disabled="filter_only_unsaved" v-model="filter_start" />
                                  <div v-if="filter_start_errors" class="invalid-feedback">
                                      {{ filter_start_errors[0] }}
                                  </div>
                              </div>
                              <div class="form-group col-6 col-lg-3 col-xl-12">
                                  <label for="filter_end">Ende</label>
                                  <input type="date" :min="filter_start" class="form-control" v-bind:class="{'is-invalid': filter_end_errors}" id="filter_end" name="filter_end" placeholder="" :disabled="filter_only_unsaved" v-model="filter_end" />
                                  <div v-if="filter_end_errors" class="invalid-feedback">
                                      {{ filter_end_errors[0] }}
                                  </div>
                              </div>
                              <div class="form-group col-md-6 col-lg-3 col-xl-12">
                                  <label>Fahrzeug</label>
                                  <v-select :options="vehicles" label="registration_identifier" placeholder="Fahrzeug auswählen" :disabled="filter_only_unsaved" :value="filter_vehicle" :selectOnTab="true" @input="setFilterVehicle">
                                      <template v-slot:no-options>Keine passenden Einträge.</template>
                                  </v-select>
                                  <div v-if="filter_vehicle_errors" class="invalid-feedback" v-bind:class="{'d-block': filter_vehicle_errors}">
                                      {{ filter_vehicle_errors[0] }}
                                  </div>
                              </div>
                              <div class="form-group col-md-6 col-lg-3 col-xl-12">
                                  <label>Projekt</label>
                                  <v-select :options="projects" label="name" placeholder="Projekt auswählen" :disabled="filter_only_unsaved" :value="filter_project" :selectOnTab="true"  @input="setFilterProject">
                                      <template v-slot:no-options>Keine passenden Einträge.</template>
                                  </v-select>
                                  <div v-if="filter_project_errors" class="invalid-feedback" v-bind:class="{'d-block': filter_project_errors}">
                                      {{ filter_project_errors[0] }}
                                  </div>
                              </div>
                              <div class="form-group col-12">
                                  <div class="custom-control custom-switch">
                                      <input type="checkbox" class="custom-control-input" v-bind:class="{'is-invalid': filter_only_own_errors}" name="filter_only_own" id="filter_only_own" :disabled="filter_only_unsaved" :value="filter_only_own" v-model="filter_only_own" @click="toggleFilterOnlyOwn()">
                                      <label class="custom-control-label" for="filter_only_own">Nur eigene Einträge anzeigen</label>
                                  </div>
                                  <div v-if="filter_only_own_errors" class="invalid-feedback" v-bind:class="{'d-block': filter_only_own_errors}">
                                      {{ filter_only_own_errors[0] }}
                                  </div>
                              </div>
                              <div class="form-group col-12">
                                  <div class="custom-control custom-switch">
                                      <input type="checkbox" class="custom-control-input" name="filter_only_unsaved" id="filter_only_unsaved" :value="filter_only_unsaved" v-model="filter_only_unsaved" @click="toggleFilterOnlyUnsaved()">
                                      <label class="custom-control-label" for="filter_only_unsaved">Nur geänderte Einträge anzeigen</label>
                                  </div>
                              </div>
                          </div>
                          <button type="button" class="btn btn-outline-secondary d-inline-flex align-items-center mt-4" @click="filterData()">
                              <svg class="feather feather-16 mr-2">
                                  <use xlink:href="/svg/feather-sprite.svg#filter"></use>
                              </svg>
                              Einträge filtern
                          </button>
                      </form>
                  </div>
              </div>

              <div v-bind:class="{'col-12 order-2 mt-4': !$screen.xl, 'col-xl-2 order-3 bg-gray-100': $screen.xl}">
                  <div v-bind:class="{'sticky-top pt-xl-4': $screen.xl}">
                      <h3>Fahrt eintragen</h3>

                      <form class="needs-validation mt-4" action="" method="post" novalidate>
                          <div class="form-row">
                              <div class="form-group col-md-4 col-lg-2 col-xl-12">
                                  <label>Fahrzeug</label>
                                  <v-select :options="vehicles" label="registration_identifier" placeholder="Fahrzeug auswählen" :value="vehicle" :selectOnTab="true" @input="setVehicle">
                                      <template v-slot:no-options>Keine passenden Einträge.</template>
                                  </v-select>
                                  <div class="invalid-feedback" v-bind:class="{'d-block': vehicle_invalid}">
                                      Fahrzeug muss ausgefüllt sein.
                                  </div>
                              </div>
                              <div class="form-group col-4 col-md-4 col-lg-2 col-xl-12">
                                  <label for="driven_on">Datum</label>
                                  <input type="date" class="form-control" v-bind:class="{'is-invalid': driven_on_invalid}" id="driven_on" name="driven_on" placeholder="" required v-model="driven_on" />
                                  <div class="invalid-feedback">
                                      Datum muss ausgefüllt sein.
                                  </div>
                              </div>
                              <div class="form-group col-4 col-md-4 col-lg-2 col-xl-6">
                                  <label for="start_kilometres">Start Kilometer</label>
                                  <input type="number" :min="0" step="1" class="form-control" v-bind:class="{'is-invalid': start_kilometres_invalid}" id="start_kilometres" name="start_kilometres" placeholder="131337" required v-model="start_kilometres" @blur="autofill()" />
                                  <div class="invalid-feedback">
                                      Start Kilometer müssen mindestens 0 sein.
                                  </div>
                              </div>
                              <div class="form-group col-4 col-md-4 col-lg-2 col-xl-6">
                                  <label for="end_kilometres">Ende Kilometer</label>
                                  <input type="number" min="1" step="1" class="form-control" v-bind:class="{'is-invalid': end_kilometres_invalid}" id="end_kilometres" name="end_kilometres" placeholder="131415" required v-model="end_kilometres" @blur="autofill()" />
                                  <div class="invalid-feedback">
                                      Ende Kilometer müssen mindestens 1 sein.
                                  </div>
                              </div>
                              <div class="form-group col-6 col-md-4 col-lg-2 col-xl-6">
                                  <label for="driven_kilometres">gefahrene KM</label>
                                  <input type="number" min="1" step="1" class="form-control" v-bind:class="{'is-invalid': driven_kilometres_invalid}" id="driven_kilometres" name="driven_kilometres" placeholder="78" required v-model="driven_kilometres" @blur="autofill()" />
                                  <div class="invalid-feedback">
                                      gefahrene Kilometer müssen mindestens 1 sein.
                                  </div>
                              </div>
                              <div class="form-group col-6 col-md-4 col-lg-2 col-xl-6">
                                  <label for="litres_refuelled">getankte Liter</label>
                                  <input type="number" min="1" step="1" class="form-control" v-bind:class="{'is-invalid': litres_refuelled_invalid}" id="litres_refuelled" name="litres_refuelled" placeholder="54" required v-model="litres_refuelled" />
                                  <div class="invalid-feedback">
                                      getankte Liter müssen mindestens 1 sein.
                                  </div>
                              </div>
                              <div class="form-group col-md-4 col-lg-2 col-xl-12">
                                  <label>Start</label>
                                  <v-select :options="placesList" placeholder="Start auswählen oder eingeben" :value="origin" :selectOnTab="true" :taggable="true" @input="setOrigin">
                                      <template v-slot:no-options>Keine passenden Einträge.</template>
                                  </v-select>
                                  <div class="invalid-feedback" v-bind:class="{'d-block': origin_invalid}">
                                      Start muss ausgefüllt sein.
                                  </div>
                              </div>
                              <div class="form-group col-md-4 col-lg-2 col-xl-12">
                                  <label>Ziel</label>
                                  <v-select :options="placesList" placeholder="Ziel auswählen oder eingeben" :value="destination" :selectOnTab="true" :taggable="true" @input="setDestination">
                                      <template v-slot:no-options>Keine passenden Einträge.</template>
                                  </v-select>
                                  <div class="invalid-feedback" v-bind:class="{'d-block': origin_invalid}">
                                      Ziel muss ausgefüllt sein.
                                  </div>
                              </div>
                              <div class="form-group col-md-4 col-lg-3 col-xl-12">
                                  <label>Projekt</label>
                                  <v-select :options="projects" label="name" placeholder="Projekt auswählen" :value="project" :selectOnTab="true" @input="setProject">
                                      <template v-slot:no-options>Keine passenden Einträge.</template>
                                  </v-select>
                                  <div class="invalid-feedback" v-bind:class="{'d-block': project_invalid}">
                                      Projekt muss ausgefüllt sein.
                                  </div>
                              </div>
                              <div class="form-group col-lg-3 col-xl-12">
                                  <label for="comment">Bemerkungen</label>
                                  <textarea class="form-control" v-bind:class="{'textarea-h1': $screen.lg && !$screen.xl}" id="comment" name="comment" placeholder="Bemerkungen" v-model="comment" />
                              </div>
                              <div class="form-group d-none d-lg-block d-xl-none col-lg-2">
                                  <label for="addlogbook">&nbsp;</label>
                                  <button id="addlogbook" type="button" class="form-control btn btn-outline-secondary d-inline-flex align-items-center justify-content-center" @click="addLogbook()">
                                      <svg class="feather feather-16 mr-2">
                                          <use xlink:href="/svg/feather-sprite.svg#plus"></use>
                                      </svg>
                                      Hinzufügen
                                  </button>
                              </div>
                          </div>
                          <div class="d-block d-lg-none d-xl-block mt-4">
                              <button id="addlogbook" type="button" class="btn btn-outline-secondary d-inline-flex align-items-center" @click="addLogbook()">
                                  <svg class="feather feather-16 mr-2">
                                      <use xlink:href="/svg/feather-sprite.svg#plus"></use>
                                  </svg>
                                  Hinzufügen
                              </button>
                          </div>
                      </form>
                  </div>
              </div>

              <div v-bind:class="{'col-12 order-3': !$screen.xl, 'col-xl-8 order-2 pb-xl-4': $screen.xl}"  ref="logbook_overview">
                  <div class="sticky-top bg-general">
                      <h3 class="sticky-top d-none d-xl-block pt-xl-4 pb-2">
                          Fahrtenbuch
                          <small v-if="logbook.length" class="text-muted">
                              {{ logbook.length }} Einträge
                              <span v-if="getNewLogbook().length" class="text-success">+{{ getNewLogbook().length }}</span>
                              <span v-if="getChangedLogbook().length" class="text-warning">±{{ getChangedLogbook().length }}</span>
                              <span v-if="getDestroyedLogbook().length" class="text-danger">-{{ getDestroyedLogbook().length }}</span>
                          </small>
                      </h3>

                      <div v-if="getUnsavedLogbook().length" class="alert alert-warning" role="alert">
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
                  </div>

                  <div v-if="logbook.length" class="mt-4 p-1">
                      <table class="table table-sm">
                          <thead>
                              <tr>
                                  <th scope="col" class="col-auto">
                                      <button type="button" class="btn btn-sm outline-none p-1 d-inline-flex align-items-center" v-bind:class="{'text-gray-500': !getErrorLogbook().length, 'errorstoggle text-red-100': getErrorLogbook().length, 'text-red-500': getErrorLogbook().length && !getShowNoDetailsErrorLogbook().length}" :disabled="!getErrorLogbook().length" @click="toggleShowDetailsError()">
                                          <svg class="feather feather-16">
                                              <use xlink:href="/svg/feather-sprite.svg#alert-triangle"></use>
                                          </svg>
                                      </button>
                                  </th>
                                  <th scope="col" class="col-1-5">Fahrzeug</th>
                                  <th scope="col" class="col-1-5">Datum</th>
                                  <th scope="col" class="col-1">Start KM</th>
                                  <th scope="col" class="col-1">Ende KM</th>
                                  <th scope="col" class="col-1">gef. KM</th>
                                  <th scope="col" class="col-1">get. L</th>
                                  <th scope="col" class="col-1-5">Start</th>
                                  <th scope="col" class="col-1-5">Ziel</th>
                                  <th scope="col" class="col-auto text-right">
                                      <button type="button" class="btn btn-sm btn-outline-danger p-1 d-inline-flex align-items-center" :disabled="!getSelectedLogbook().length" @click="removeSelectedLogbook()">
                                          <svg class="feather feather-16">
                                              <use xlink:href="/svg/feather-sprite.svg#trash-2"></use>
                                          </svg>
                                      </button>
                                      <button type="button" class="btn btn-sm btn-outline-success p-1 d-inline-flex align-items-center" :disabled="!getSelectedLogbook().length" @click="restoreSelectedLogbook()">
                                          <svg class="feather feather-16">
                                              <use xlink:href="/svg/feather-sprite.svg#rotate-ccw"></use>
                                          </svg>
                                      </button>
                                      <button v-if="(getSelectedLogbook().length !== pageOfItems.length)" type="button" class="btn btn-sm outline-none checkboxtoggle text-blue-100 p-1 d-inline-flex align-items-center" @click="toggleSelectAll()"  @mouseenter="selectAllHover = true"  @mouseleave="selectAllHover = false">
                                          <svg class="feather feather-16">
                                              <use v-if="!selectAllHover" xlink:href="/svg/feather-sprite.svg#circle"></use>
                                              <use v-if="selectAllHover" xlink:href="/svg/feather-sprite.svg#check-circle"></use>
                                          </svg>
                                      </button>
                                      <button v-if="getSelectedLogbook().length === pageOfItems.length"  type="button" class="btn btn-sm outline-none checkboxtoggle text-blue-500 p-1 d-inline-flex align-items-center" @click="toggleSelectAll()"  @mouseenter="selectAllHover = true"  @mouseleave="selectAllHover = false">
                                          <svg class="feather feather-16">
                                              <use xlink:href="/svg/feather-sprite.svg#check-circle"></use>
                                          </svg>
                                      </button>
                                  </th>
                              </tr>
                          </thead>
                          <tbody>
                              <template v-for="book in pageOfItems">
                                  <tr class="hover-highlight" v-bind:class="{'border-status border-success': book.action === 'store' && !book.selected, 'border-status border-warning': book.action === 'update' && !book.selected, 'text-muted ': book.action === 'destroy', 'border-status border-danger': book.action === 'destroy' && !book.selected, 'border-status border-primary': book.selected}">
                                      <td class="col-auto">
                                          <button type="button" class="btn btn-sm outline-none p-1 d-inline-flex align-items-center" v-bind:class="{'detailstoggle text-gray-500': !book.errors && !book.show_details, 'errorstoggle text-red-100': book.errors && !book.show_details, 'text-dark': !book.errors && book.show_details, 'text-red-500': book.errors && book.show_details}" @click="toggleShowDetails(book)">
                                              <svg class="feather feather-16">
                                                  <use v-if="!book.errors && !book.show_details" xlink:href="/svg/feather-sprite.svg#chevron-right"></use>
                                                  <use v-if="book.errors && !book.show_details" xlink:href="/svg/feather-sprite.svg#alert-triangle"></use>
                                                  <use v-if="book.show_details" xlink:href="/svg/feather-sprite.svg#chevron-down"></use>
                                              </svg>
                                          </button>
                                      </td>

                                      <td class="col-1-5" @click="setEdit(book, 'vehicle')">
                                          <span v-if="book.edit !== 'vehicle'">{{ getVehicleRegistrationIdentifier(book.vehicle_id) }}</span>
                                          <v-select v-if="book.edit === 'vehicle'" class="dropdown-sm" :options="vehicles" ref="table_input"  label="registration_identifier" placeholder="Fahrzeug auswählen" :value="getVehicleRegistrationIdentifier(book.vehicle_id)" :selectOnTab="true" @input="changeLogbookVehicle($event, book)"  @close="changeLogbookDropdownValueToSame(book)" @keydown.enter.prevent="changeLogbookVehicle($event, book)">
                                              <template v-slot:no-options>Keine passenden Einträge.</template>
                                          </v-select>
                                      </td>
                                      <td class="col-1-5" @click="setEdit(book, 'driven_on')">
                                          <span v-if="book.edit !== 'driven_on'">{{ book.driven_on.toLocaleDateString("de", { month: '2-digit', day: '2-digit', year: 'numeric' }) }}</span>
                                          <input v-if="book.edit === 'driven_on'" type="date" class="form-control form-control-sm" v-bind:class="{'is-invalid': table_driven_on_invalid}" ref="table_input" id="table_driven_on" name="table_driven_on" :value="getDateStringForInputField(book.driven_on)" placeholder="" required @blur="changeLogbookDrivenOn($event, book)" @keydown.enter.prevent="changeLogbookDrivenOn($event, book)" @keydown.tab.prevent="onTableInputTab($event, book, 'driven_on')" />
                                      </td>
                                      <td class="col-1" @click="setEdit(book, 'start_kilometres')">
                                          <span v-if="book.edit !== 'start_kilometres'">{{ book.start_kilometres }}</span>
                                          <input v-if="book.edit === 'start_kilometres'" type="number" min="0" step="1" class="form-control form-control-sm" v-bind:class="{'is-invalid': table_start_kilometres_invalid}" ref="table_input" id="table_start_kilometres" name="table_start_kilometres" placeholder="131337" :value="book.start_kilometres" @blur="changeLogbookStartKilometres($event, book)" @keydown.enter.prevent="changeLogbookStartKilometres($event, book)" @keydown.tab.prevent="onTableInputTab($event, book, 'start_kilometres')" />
                                      </td>
                                      <td class="col-1" @click="setEdit(book, 'end_kilometres')">
                                          <span v-if="book.edit !== 'end_kilometres'">{{ book.end_kilometres }}</span>
                                          <input v-if="book.edit === 'end_kilometres'" type="number" min="1" step="1" class="form-control form-control-sm" v-bind:class="{'is-invalid': table_end_kilometres_invalid}" ref="table_input" id="table_end_kilometres" name="table_end_kilometres" placeholder="131415" :value="book.end_kilometres" @blur="changeLogbookEndKilometres($event, book)" @keydown.enter.prevent="changeLogbookEndKilometres($event, book)" @keydown.tab.prevent="onTableInputTab($event, book, 'end_kilometres')" />
                                      </td>
                                      <td class="col-1" @click="setEdit(book, 'driven_kilometres')">
                                          <span v-if="book.edit !== 'driven_kilometres'">{{ book.driven_kilometres }}</span>
                                          <input v-if="book.edit === 'driven_kilometres'" type="number" min="1" step="1" class="form-control form-control-sm" v-bind:class="{'is-invalid': table_driven_kilometres_invalid}" ref="table_input" id="table_driven_kilometres" name="table_driven_kilometres" placeholder="78" :value="book.driven_kilometres" @blur="changeLogbookDrivenKilometres($event, book)" @keydown.enter.prevent="changeLogbookDrivenKilometres($event, book)" @keydown.tab.prevent="onTableInputTab($event, book, 'driven_kilometres')" />
                                      </td>
                                      <td class="col-1" @click="setEdit(book, 'litres_refuelled')">
                                          <span v-if="book.edit !== 'litres_refuelled'">{{ book.litres_refuelled }}</span>
                                          <input v-if="book.edit === 'litres_refuelled'" type="number" min="1" step="1" class="form-control form-control-sm" v-bind:class="{'is-invalid': table_litres_refuelled_invalid}" ref="table_input" id="table_litres_refuelled" name="table_litres_refuelled" placeholder="54" :value="book.litres_refuelled" @blur="changeLogbookLitresRefuelled($event, book)" @keydown.enter.prevent="changeLogbookLitresRefuelled($event, book)" @keydown.tab.prevent="onTableInputTab($event, book, 'litres_refuelled')" />
                                      </td>
                                      <td class="col-1-5" @click="setEdit(book, 'origin')">
                                          <span v-if="book.edit !== 'origin'">{{ book.origin }}</span>
                                          <v-select v-if="book.edit === 'origin'" class="dropdown-sm" :options="places" ref="table_input" placeholder="Start auswählen oder eingeben" :value="origin" :selectOnTab="true" @input="changeLogbookOrigin($event, book)"  @close="changeLogbookDropdownValueToSame(book)" @keydown.enter.prevent="changeLogbookOrigin($event, book)">
                                              <template v-slot:no-options>Keine passenden Einträge.</template>
                                          </v-select>
                                      </td>
                                      <td class="col-1-5" @click="setEdit(book, 'destination')">
                                          <span v-if="book.edit !== 'destination'">{{ book.destination }}</span>
                                          <v-select v-if="book.edit === 'destination'" class="dropdown-sm" :options="places" ref="table_input" placeholder="Ziel auswählen oder eingeben" :value="destination" :selectOnTab="true" @input="changeLogbookDestination($event, book)"  @close="changeLogbookDropdownValueToSame(book)" @keydown.enter.prevent="changeLogbookDestination($event, book)">
                                              <template v-slot:no-options>Keine passenden Einträge.</template>
                                          </v-select>
                                      </td>

                                      <td class="col-auto text-right">
                                          <button v-if="!(book.action === 'destroy')" type="button" class="btn btn-sm btn-outline-danger p-1 d-inline-flex align-items-center" @click="removeLogbook(book)">
                                              <svg class="feather feather-16">
                                                  <use xlink:href="/svg/feather-sprite.svg#trash-2"></use>
                                              </svg>
                                          </button>
                                          <button v-if="book.action === 'destroy'" type="button" class="btn btn-sm btn-outline-success p-1 d-inline-flex align-items-center" @click="restoreLogbook(book)">
                                              <svg class="feather feather-16">
                                                  <use xlink:href="/svg/feather-sprite.svg#rotate-ccw"></use>
                                              </svg>
                                          </button>
                                          <button v-if="!book.selected" type="button" class="btn btn-sm outline-none checkboxtoggle text-blue-100 p-1 d-inline-flex align-items-center" @click="toggleSelected(book)" @mouseenter="book.hover = true"  @mouseleave="book.hover = false">
                                              <svg class="feather feather-16">
                                                  <use v-if="!book.hover" xlink:href="/svg/feather-sprite.svg#circle"></use>
                                                  <use v-if="book.hover" xlink:href="/svg/feather-sprite.svg#check-circle"></use>
                                              </svg>
                                          </button>
                                          <button v-if="book.selected" type="button" class="btn btn-sm outline-none checkboxtoggle text-blue-500 p-1 d-inline-flex align-items-center" @click="toggleSelected(book)"  @mouseenter="book.hover = true"  @mouseleave="book.hover = false">
                                              <svg class="feather feather-16">
                                                  <use xlink:href="/svg/feather-sprite.svg#check-circle"></use>
                                              </svg>
                                          </button>
                                      </td>
                                  </tr>

                                  <transition name="collapse">
                                      <tr v-if="book.show_details"  v-bind:class="{'border-status border-success': book.action === 'store' && !book.selected, 'border-status border-warning': book.action === 'update' && !book.selected, 'text-muted ': book.action === 'destroy', 'border-status border-danger': book.action === 'destroy' && !book.selected, 'border-status border-primary': book.selected}">
                                          <td class="border-0" ></td>
                                          <td colspan="7" class="border-0">
                                              <div class="row">
                                                  <div class="col-2 font-weight-bold">Projekt:</div>
                                                  <div class="col-4">
                                                      <div v-if="book.edit !== 'project'" @click="setEdit(book, 'project')">{{ book.project_id ? getProjectName(book.project_id) : 'nicht angegeben' }}</div>
                                                      <v-select v-if="book.edit === 'project'" class="dropdown-sm" :options="projects" ref="table_input"  label="name" placeholder="Projekt auswählen" :value="getProject(book.project_id)" :selectOnTab="true" @input="changeLogbookProject($event, book)"  @close="changeLogbookDropdownValueToSame(book)" @keydown.enter.prevent="changeLogbookProject($event, book)">
                                                          <template v-slot:no-options>Keine passenden Einträge.</template>
                                                      </v-select>
                                                  </div>
                                              </div>
                                              <div class="row mt-2">
                                                  <div class="col-2 font-weight-bold">Mitarbeiter:</div>
                                                  <div class="col-4">{{ getEmployeeName(book.employee_id) }}</div>
                                              </div>
                                              <div class="form-group mt-2">
                                                  <label for="table_comment"><span class="font-weight-bold">Bemerkungen:</span></label>
                                                  <p v-if="book.edit !== 'comment'" class="whitespace-preline" @click="setEdit(book, 'comment')">{{ book.comment ? book.comment : 'nicht angegeben' }}</p>
                                                  <textarea v-if="book.edit === 'comment'" class="form-control form-control-sm" ref="table_input"  id="table_comment" name="table_comment" placeholder="Bemerkungen" :value="book.comment" @blur="changeLogbookComment($event, book)" />
                                              </div>

                                              <div v-if="book.errors" class="alert alert-danger" role="alert">
                                                  <p class="mb-0">Probleme in dieser Zeile</p>
                                                  <ul class="mb-0">
                                                      <li v-for="error in book.errors">{{ error }}</li>
                                                  </ul>
                                              </div>
                                          </td>
                                      </tr>
                                  </transition>

                              </template>
                          </tbody>
                      </table>

                      <jw-pagination :labels="pagination_labels" :items="logbook" :pageSize="page_size" :initialPage="initialPage" @changePage="onChangePage"></jw-pagination>

                      <p v-if="logbook.length" class="mt-3">
                          Der linke farbliche Rand zeigt den Speicherzustand der jeweiligen Zeile:
                          <span class="badge badge-green-100 text-green-800">wird angelegt</span>
                          <span class="badge badge-yellow-100 text-yellow-800">wird bearbeitet</span>
                          <span class="badge badge-red-100 text-red-800">wird entfernt</span>
                      </p>
                  </div>

                  <div v-if="!logbook.length" class="text-center mt-4">
                      <img class="empty-state" src="/svg/no-data.svg" alt="no data" />
                      <p class="lead text-muted">Es sind keine Fahrtenbuch Einträge passend zum Anzeigefilter vorhanden.</p>
                      <p class="lead">Trage neue Fahrten mithilfe des Formulars ein.</p>
                  </div>

                  <button v-if="logbook.length" ref="save_button" type="button" class="btn btn-primary d-inline-flex align-items-center mt-4" :disabled="!getUnsavedLogbook().length" @click="saveData()">
                      <svg class="feather feather-16 mr-2">
                          <use xlink:href="/svg/feather-sprite.svg#save"></use>
                      </svg>
                      Änderungen speichern
                  </button>

              </div>

          </div>
      </div>

  </div>
</template>

<script>
    const FETCH_ERROR_MESSAGE = "Beim Filtern der Daten traten Probleme auf.";
    const SAVE_SUCCESS_MESSAGE = "Die Änderungen wurden erfolgreich gespeichert.";
    const SAVE_ERROR_MESSAGE = "Beim Speichern der Änderungen traten Probleme auf.";

    const PAGINATION_LABELS = {
        first: '<<',
        last: '>>',
        previous: '<',
        next: '>'
    };

    export default {
        name: "LogbookSelector",

        data() {
            let today = new Date();
            return {
                pagination_labels: PAGINATION_LABELS,

                filter_start: null,
                filter_start_errors: null,
                filter_end: null,
                filter_end_errors:null,
                filter_vehicle: null,
                filter_vehicle_errors: null,
                filter_project: null,
                filter_project_errors: null,
                filter_only_own: false,
                filter_only_own_errors: null,
                filter_only_unsaved: false,

                driven_on: this.getDateStringForInputField(new Date(today.getTime() - today.getTimezoneOffset() * 60 * 1000)),
                driven_on_invalid: false,
                table_driven_on_invalid: false,
                start_kilometres: null,
                start_kilometres_invalid: false,
                table_start_kilometres_invalid: false,
                end_kilometres: null,
                end_kilometres_invalid: false,
                table_end_kilometres_invalid: false,
                driven_kilometres: null,
                driven_kilometres_invalid: false,
                table_driven_kilometres_invalid: false,
                litres_refuelled: null,
                litres_refuelled_invalid: false,
                table_litres_refuelled_invalid: false,
                origin: null,
                origin_invalid: false,
                table_origin_invalid: false,
                destination: null,
                destination_invalid: false,
                table_destination_invalid: false,
                vehicle: null,
                vehicle_invalid: false,
                table_vehicle_invalid: false,
                project: null,
                project_invalid: false,
                table_project_invalid: false,
                comment: null,

                logbook: [],
                pageOfItems: [],

                placesList: this.places,

                initialPage: 1,
                scrollToNewEntry: false,

                selectAllHover: false,

                dataResult: null,
            }
        },

        mounted() {
            if(this.current_logbook) {
                let userTimezoneOffset = new Date().getTimezoneOffset() * 60 * 1000;

                this.current_logbook.forEach(book => {
                    let date = Date.parse(book.driven_on);

                    this.accounting.push({
                        action: null,
                        action_old: null,
                        errors: null,
                        selected: false,
                        show_details: false,
                        hover: false,
                        edit: null,
                        id: book.id,
                        driven_on: new Date(date - userTimezoneOffset),
                        start_kilometres: book.start_kilometres,
                        end_kilometres: book.end_kilometres,
                        driven_kilometres: book.driven_kilometres,
                        litres_refuelled: book.litres_refuelled,
                        origin: book.origin,
                        destination: book.destination,
                        vehicle_id: book.vehicle_id,
                        project_id: book.project_id,
                        employee_id: book.employee_id,
                        comment: book.comment,
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
            onChangePage(pageOfItems) {
                this.deselectAll();
                this.pageOfItems = pageOfItems;

                this.$nextTick(() => {
                    if(this.scrollToNewEntry) {
                        this.$refs.save_button.scrollIntoView({behavior: 'smooth'});
                    }
                    else {
                        this.$refs.logbook_overview.scrollIntoView({behavior: 'smooth'});
                    }

                    this.scrollToNewEntry = false;
                });
            },

            filterData() {
                this.dataResult = null;
                this.$refs.top_progress.start();

                this.initialPage = 1;

                if(this.filter_only_unsaved) {
                    this.logbook = this.getUnsavedLogbook();

                    // Add a bit of timeout to progress bar because otherwise it runs forever if state is changed
                    // too quickly.
                    let topProgress = this.$refs.top_progress

                    setTimeout(() => {
                        topProgress.done()
                    }, 10);

                    this.filter_start_errors = null;
                    this.filter_end_errors = null;
                    this.filter_vehicle_errors = null;
                    this.filter_project_errors = null;
                    this.filter_only_own_errors = null;

                    return;
                }

                let params = {};

                if(this.filter_start) {
                    params.start = this.filter_start;
                }
                if(this.filter_end) {
                    params.end = this.filter_end;
                }
                if(this.filter_vehicle) {
                    params.vehicle_id = this.filter_vehicle.id;
                }
                if(this.filter_project) {
                    params.project_id = this.filter_project.id;
                }
                if(this.filter_only_own) {
                    params.only_own = this.filter_only_own;
                }

                axios.get('/logbook', {params: params})
                .then(response => {
                    this.updateLocalLogbook(response.data);

                    this.$refs.top_progress.done();

                    this.filter_start_errors = null;
                    this.filter_end_errors = null;
                    this.filter_vehicle_errors = null;
                    this.filter_project_errors = null;
                    this.filter_only_own_errors = null;
                })
                .catch(error => {
                    this.$refs.top_progress.fail();

                    if(error.response.status === 422) {
                        this.filter_start_errors = this.extractErrorMessages(error.response, 'start');
                        this.filter_end_errors = this.extractErrorMessages(error.response, 'end');
                        this.filter_vehicle_errors = this.extractErrorMessages(error.response, 'vehicle_id');
                        this.filter_project_errors = this.extractErrorMessages(error.response, 'project_id');
                        this.filter_only_own_errors = this.extractErrorMessages(error.response, 'only_own');
                    }
                    else {
                        this.dataResult = {'danger': FETCH_ERROR_MESSAGE};
                    }
                });
            },

            updateLocalLogbook(fetchedLogbook) {
                let userTimezoneOffset = new Date().getTimezoneOffset() * 60 * 1000;

                let newLogbook = fetchedLogbook.filter(
                    fetchedLogbook => !this.logbook.some(
                        localLogbook => localLogbook.id === fetchedLogbook.id
                    )
                );

                let removedUnchangedLogbook = this.logbook.filter(
                    localLogbook => !fetchedLogbook.some(
                        fetchedLogbook => fetchedLogbook.id === localLogbook.id
                    ) && localLogbook.action === null && localLogbook.action_old === null
                );

                newLogbook.forEach(book => {
                    let date = Date.parse(book.driven_on);

                    this.logbook.push({
                        action: null,
                        action_old: null,
                        errors: null,
                        selected: false,
                        show_details: false,
                        hover: false,
                        edit: null,
                        id: book.id,
                        driven_on: new Date(date - userTimezoneOffset),
                        start_kilometres: book.start_kilometres,
                        end_kilometres: book.end_kilometres,
                        driven_kilometres: book.driven_kilometres,
                        litres_refuelled: book.litres_refuelled,
                        origin: book.origin,
                        destination: book.destination,
                        vehicle_id: book.vehicle_id,
                        project_id: book.project_id,
                        employee_id: book.employee_id,
                        comment: book.comment,
                    });
                });

                removedUnchangedLogbook.forEach(book => {
                    this.logbook = this.removeFromArray(this.logbook, book);
                });

                this.sortArrayByDateVehicleStartKilometres(this.logbook);
            },

            saveData() {
                this.dataResult = null;

                let unsavedLogbook = this.getUnsavedLogbook();
                let promises = [];

                unsavedLogbook.forEach(book => {
                    switch (book.action) {
                        case 'store':
                            promises.push(this.storeLogbook(book));
                            break;
                        case 'update':
                            promises.push(this.updateLogbook(book));
                            break;
                        case 'destroy':
                            if(book.id !== null) {
                                promises.push(this.destroyLogbook(book));
                            }
                            else {
                                this.logbook = this.removeFromArray(this.logbook, book);
                            }
                            break;
                    }
                });

                Promise.all(promises).then(() => {
                    if(this.getErrorLogbook().length) {
                        this.dataResult = {'danger': SAVE_ERROR_MESSAGE};
                    }
                    else {
                        this.filter_only_unsaved = false;
                        this.filterData();

                        this.dataResult = {'success': SAVE_SUCCESS_MESSAGE};
                    }
                });
            },

            storeLogbook(logbook) {
                return axios.post('/logbook', {
                    driven_on: logbook.driven_on,
                    start_kilometres: logbook.start_kilometres,
                    end_kilometres: logbook.end_kilometres,
                    driven_kilometres: logbook.driven_kilometres,
                    litres_refuelled: logbook.litres_refuelled,
                    origin: logbook.origin,
                    destination: logbook.destination,
                    vehicle_id: logbook.vehicle_id,
                    project_id: logbook.project_id,
                    comment: logbook.comment,
                })
                .then(response => {
                    logbook.id = response.data.id;
                    logbook.employee_id = response.data.employee_id;
                    logbook.action = null;
                    logbook.action_old = null;
                    logbook.errors = null;
                    logbook.show_details = false;
                })
                .catch(error => {
                    if(error.response.status === 422) {
                        logbook.errors = this.extractErrorMessages(error.response);
                        logbook.show_details = this.expand_errors;
                    }
                });
            },

            updateLogbook(logbook) {
                return axios.post('/logbook/' + logbook.id, {
                    _method: 'PATCH',

                    id: logbook.id,
                    driven_on: logbook.driven_on,
                    start_kilometres: logbook.start_kilometres,
                    end_kilometres: logbook.end_kilometres,
                    driven_kilometres: logbook.driven_kilometres,
                    litres_refuelled: logbook.litres_refuelled,
                    origin: logbook.origin,
                    destination: logbook.destination,
                    vehicle_id: logbook.vehicle_id,
                    project_id: logbook.project_id,
                    employee_id: logbook.employee_id,
                    comment: logbook.comment,
                })
                .then(() => {
                    logbook.action = null;
                    logbook.action_old = null;
                    logbook.errors = null;
                    logbook.show_details = false;
                })
                .catch(error => {
                    if(error.response.status === 422) {
                        logbook.errors = this.extractErrorMessages(error.response);
                        logbook.show_details = this.expand_errors;
                    }
                });
            },

            destroyLogbook(logbook) {
                return axios.post('/logbook/' + logbook.id, {
                    _method: 'DELETE'
                })
                .then(() => {
                    this.logbook = this.removeFromArray(this.logbook, logbook);
                })
                .catch(error => {
                    if(error.response.status === 422) {
                        logbook.errors = this.extractErrorMessages(error.response);
                        logbook.show_details = this.expand_errors;
                    }
                });
            },

            extractErrorMessages(response, field = null) {
                let messages = [];

                Object.keys(response.data.errors).forEach(item => {
                    response.data.errors[item].forEach(message => {
                        if(field === null || item === field) {
                            messages.push(message);
                        }
                    });
                });

                return messages.length ? messages : null;
            },

            removeFromArray(logbook, value) {
                return logbook.filter(book => book.id !== value.id);
            },

            sortArrayByDateVehicleStartKilometres(logbook) {
                logbook.sort((a, b) => {
                    if(a.driven_on.getTime() !== b.driven_on.getTime()) {
                        return a.driven_on - b.driven_on;
                    }
                    else if(a.vehicle_id !== b.vehicle_id) {
                        return this.getVehicleRegistrationIdentifier(a.vehicle_id).localeCompare(
                            this.getVehicleRegistrationIdentifier(b.vehicle_id)
                        );
                    }
                    else if(a.start_kilometres !== b.start_kilometres) {
                        return a.start_kilometres - b.start_kilometres;
                    }
                    else {
                        return 0;
                    }
                });
            },

            getUnsavedLogbook() {
                return this.logbook.filter(book => book.action !== null);
            },

            getNewLogbook() {
                return this.logbook.filter(book => book.action === 'store');
            },

            getChangedLogbook() {
                return this.logbook.filter(book => book.action === 'update');
            },

            getDestroyedLogbook() {
                return this.logbook.filter(book => book.action === 'destroy');
            },

            setFilterVehicle(value) {
                this.filter_vehicle = value;
            },

            setFilterProject(value) {
                this.filter_project = value;
            },

            toggleFilterOnlyOwn() {
                this.filter_only_own = !this.filter_only_own;
            },

            toggleFilterOnlyUnsaved() {
                this.filter_only_unsaved = !this.filter_only_unsaved;
            },

            setOrigin(value) {
                this.origin = value;
            },

            setDestination(value) {
                this.destination = value;
            },

            getPlace(place) {
                return this.placesList.find(listPlace => listPlace === place);
            },

            addPlaces(places) {
                [].concat(places).forEach(place => {
                    if(!this.getPlace(place)) {
                        this.placesList.push(place);
                    }
                });

                this.placesList.sort();
            },

            setVehicle(value) {
                this.vehicle = value;
                this.autofill();
            },

            setProject(value) {
                this.project = value;
            },

            addLogbook() {
                let date = new Date(this.driven_on);
                let startKilometres = this.start_kilometres === null ? null : Number(this.start_kilometres);
                let endKilometres = this.end_kilometres === null ? null : Number(this.end_kilometres);
                let drivenKilometres = this.driven_kilometres === null ? null : Number(this.driven_kilometres);
                let litresRefuelled = this.litres_refuelled === null ? null : Number(this.litres_refuelled);

                this.driven_on_invalid = isNaN(date.getTime());
                this.start_kilometres_invalid = !Number.isInteger(startKilometres) || startKilometres < 0;
                this.end_kilometres_invalid = !Number.isInteger(endKilometres) || endKilometres < 1;
                this.driven_kilometres_invalid = !Number.isInteger(drivenKilometres) || drivenKilometres < 1;
                this.litres_refuelled_invalid = litresRefuelled !== null && (!Number.isInteger(litresRefuelled) || litresRefuelled < 1);
                this.origin_invalid = !this.origin;
                this.destination_invalid = !this.destination;
                this.vehicle_invalid = this.vehicle === null;


                if(this.driven_on_invalid || this.start_kilometres_invalid || this.end_kilometres_invalid ||
                    this.driven_kilometres_invalid || this.litres_refuelled_invalid || this.origin_invalid ||
                    this.destination_invalid || this.vehicle_invalid) {
                    return;
                }

                this.logbook.push({
                    action: 'store',
                    action_old: 'store',
                    errors: null,
                    selected: false,
                    show_details: false,
                    hover: false,
                    edit: null,
                    id: null,
                    driven_on: date,
                    start_kilometres: startKilometres,
                    end_kilometres: endKilometres,
                    driven_kilometres: drivenKilometres,
                    litres_refuelled: litresRefuelled,
                    origin: this.origin,
                    destination: this.destination,
                    vehicle_id: this.vehicle.id,
                    project_id: this.project !== null ? this.project.id : null,
                    employee_id: null,
                    comment: this.comment,
                });

                this.addPlaces([this.origin, this.destination]);

                this.driven_on_invalid = false;
                this.start_kilometres = null;
                this.start_kilometres_invalid = false;
                this.end_kilometres = null;
                this.end_kilometres_invalid = false;
                this.driven_kilometres = null;
                this.driven_kilometres_invalid = false;
                this.litres_refuelled = null;
                this.litres_refuelled_invalid = false;
                this.origin = this.destination;
                this.origin_invalid = false;
                this.destination = null;
                this.destination_invalid = null;
                this.vehicle_invalid  = false;
                this.project_invalid = false;
                this.comment = null;

                this.autofillStartKilometresFromBooked(null, this.vehicle);

                this.initialPage = this.getLastPage();

                this.scrollToNewEntry = true;
            },

            removeLogbook(logbook) {
                if(logbook.action !== 'destroy') {
                    logbook.action_old = logbook.action;
                }

                logbook.action = 'destroy';
            },

            restoreLogbook(logbook) {
                logbook.action = logbook.action_old ? logbook.action_old : null;
            },

            removeSelectedLogbook() {
                let selectedLogbook = this.getSelectedLogbook();

                selectedLogbook.forEach(selected => {
                    this.removeLogbook(selected);
                    selected.selected = false;
                });
            },

            restoreSelectedLogbook() {
                let selectedLogbook = this.getSelectedLogbook();

                selectedLogbook.forEach(selected => {
                    this.restoreLogbook(selected);
                    selected.selected = false;
                });
            },


            changeLogbookDrivenOn(event, changedLogbook) {
                let date = new Date(event.target.value);

                if(isNaN(date.getTime())) {
                    this.table_driven_on_invalid = true;
                    return;
                }

                changedLogbook.driven_on = date;

                this.setChangedLogbookStatus(changedLogbook);

                changedLogbook.edit = null;
            },

            changeLogbookStartKilometres(event, changedLogbook) {
                let startKilometres = Number(event.target.value);

                // try to autofill if no value given and set new value
                // for checking - seems like a bit of a hack
                if(!startKilometres) {
                    changedLogbook.start_kilometres = null;
                    this.autofill(changedLogbook);
                    startKilometres = changedLogbook.start_kilometres;
                }

                if(!Number.isInteger(startKilometres) || startKilometres < 0) {
                    this.table_start_kilometres_invalid = true;
                    return;
                }

                changedLogbook.start_kilometres = startKilometres;

                this.autofill(changedLogbook);

                this.setChangedLogbookStatus(changedLogbook);

                changedLogbook.edit = null;
            },

            changeLogbookEndKilometres(event, changedLogbook) {
                let endKilometres = Number(event.target.value);

                // try to autofill if no value given and set new value
                // for checking - seems like a bit of a hack
                if(!endKilometres) {
                    changedLogbook.end_kilometres = null;
                    this.autofill(changedLogbook);
                    endKilometres = changedLogbook.end_kilometres;
                }

                if(!Number.isInteger(endKilometres) || endKilometres < 1) {
                    this.table_end_kilometres_invalid = true;
                    return;
                }

                changedLogbook.end_kilometres = endKilometres;

                this.autofill(changedLogbook);

                this.setChangedLogbookStatus(changedLogbook);

                changedLogbook.edit = null;
            },

            changeLogbookDrivenKilometres(event, changedLogbook) {
                let drivenKilometres = Number(event.target.value);

                // try to autofill if no value given and set new value
                // for checking - seems like a bit of a hack
                if(!drivenKilometres) {
                    changedLogbook.driven_kilometres = null;
                    this.autofill(changedLogbook);
                    drivenKilometres = changedLogbook.driven_kilometres;
                }

                if(!Number.isInteger(drivenKilometres) || drivenKilometres < 1) {
                    this.table_driven_kilometres_invalid = true;
                    return;
                }

                changedLogbook.driven_kilometres = drivenKilometres;

                this.autofill(changedLogbook);

                this.setChangedLogbookStatus(changedLogbook);

                changedLogbook.edit = null;
            },

            changeLogbookLitresRefuelled(event, changedLogbook) {
                let litresRefuelled = Number(event.target.value);

                // try to autofill if no value given and set new value
                // for checking - seems like a bit of a hack
                if(!litresRefuelled) {
                    changedLogbook.litres_refuelled = null;
                    this.autofill(changedLogbook);
                    litresRefuelled = changedLogbook.litres_refuelled;
                }

                if(litresRefuelled !== null && (!Number.isInteger(litresRefuelled) || litresRefuelled < 1)) {
                    this.table_litres_refuelled_invalid = true;
                    return;
                }

                changedLogbook.litres_refuelled = litresRefuelled;

                this.setChangedLogbookStatus(changedLogbook);

                changedLogbook.edit = null;
            },

            changeLogbookOrigin(value, changedLogbook) {
                if(!value) {
                    this.table_origin_invalid = true;
                    return;
                }

                changedLogbook.origin = value;

                this.addPlaces(value);

                this.setChangedLogbookStatus(changedLogbook);

                changedLogbook.edit = null;
            },

            changeLogbookDestination(value, changedLogbook) {
                if(!value) {
                    this.table_destination_invalid = true;
                    return;
                }

                changedLogbook.destination = value;

                this.addPlaces(value);

                this.setChangedLogbookStatus(changedLogbook);

                changedLogbook.edit = null;
            },

            changeLogbookVehicle(value, changedLogbook) {
                if(!value) {
                    this.table_vehicle_invalid = true;
                    return;
                }

                changedLogbook.vehicle_id = value.id;

                this.autofill(changedLogbook);

                this.setChangedLogbookStatus(changedLogbook);

                changedLogbook.edit = null;
            },

            changeLogbookProject(value, changedLogbook) {
                changedLogbook.project_id = value ? value.id : null;

                this.setChangedLogbookStatus(changedLogbook);

                // leave edit active so value can be cleared
                changedLogbook.edit = null;
            },

            changeLogbookDropdownValueToSame(changedLogbook) {
                this.setChangedLogbookStatus(changedLogbook);
                this.unsetEdit(changedLogbook);
            },

            changeLogbookComment(event, changedLogbook) {
                changedLogbook.comment = event.target.value;

                this.setChangedLogbookStatus(changedLogbook);

                changedLogbook.edit = null;
            },

            setChangedLogbookStatus(changedLogbook) {
                if(changedLogbook.action === 'destroy') {
                    changedLogbook.action = changedLogbook.action_old;
                }

                if(changedLogbook.action !== 'store') {
                    changedLogbook.action = 'update';
                }

                if(!changedLogbook.action_old) {
                    changedLogbook.action_old = 'update';
                }
            },

            toggleSelected(logbook) {
                logbook.selected = !logbook.selected;
            },

            toggleSelectAll() {
                let selectedLogbook = this.getSelectedLogbook();

                let selected = selectedLogbook.length !== this.pageOfItems.length

                this.pageOfItems.forEach(book => {
                    book.selected = selected;
                });
            },

            deselectAll() {
                this.getSelectedLogbook().forEach(book => {
                    book.selected = false;
                });
            },

            getSelectedLogbook() {
                return this.logbook.filter(book => book.selected === true);
            },

            toggleShowDetails(logbook) {
                logbook.show_details = !logbook.show_details;
            },

            toggleShowDetailsError() {
                let showNoDetailsErrorLogbook = this.getShowNoDetailsErrorLogbook();

                if(showNoDetailsErrorLogbook.length) {
                    showNoDetailsErrorLogbook.forEach(book => {
                        book.show_details = true;
                    });
                }
                else {
                    let showDetailsErrorLogbook = this.getShowDetailsErrorLogbook();

                    showDetailsErrorLogbook.forEach(book => {
                        book.show_details = false;
                    });
                }
            },

            getErrorLogbook() {
                return this.logbook.filter(book => book.errors !== null);
            },

            getShowDetailsErrorLogbook() {
                return this.logbook.filter(book => book.errors !== null && book.show_details === true);
            },

            getShowNoDetailsErrorLogbook() {
                return this.logbook.filter(book => book.errors !== null && book.show_details === false);
            },

            setEdit(logbook, field) {
                this.getEditLogbook().forEach(editLogbook => {
                    this.blurTableInput(editLogbook.edit);
                    editLogbook.edit = null;
                });

                logbook.edit = field;

                this.$nextTick(() => {
                    this.focusTableInput(field);
                });


                this.table_driven_on_invalid = false;
                this.table_start_kilometres_invalid = false;
                this.table_end_kilometres_invalid = false;
                this.table_driven_kilometres_invalid = false;
                this.table_service_invalid = false;
                this.table_amount_invalid = false;
            },

            unsetEdit(logbook) {
                this.setEdit(logbook, null);
            },

            focusTableInput(field) {
                if(field === 'origin' || field === 'destination' || field === 'project' || field === 'vehicle') {
                    this.$refs.table_input[0].$refs.search.focus();
                }
                else if(field !== null) {
                    this.$refs.table_input[0].focus();
                }
            },

            blurTableInput(field) {
                if(field === 'origin' || field === 'destination' || field === 'project' || field === 'vehicle') {
                    this.$refs.table_input[0].$refs.search.blur();
                }
                else if(field !== null) {
                    this.$refs.table_input[0].blur();
                }
            },

            getEditLogbook() {
                return this.logbook.filter(book => book.edit !== null);
            },

            onTableInputTab(event, logbook, field) {
                switch (field) {
                    case 'vehicle':
                        this.setEdit(logbook, 'driven_on');
                        break;
                    case 'driven_on':
                        this.setEdit(logbook, 'start_kilometres');
                        break;
                    case 'start_kilometres':
                        this.setEdit(logbook, 'end_kilometres');
                        break;
                    case 'end_kilometres':
                        this.setEdit(logbook, 'driven_kilometres');
                        break;
                    case 'driven_kilometres':
                        this.setEdit(logbook, 'litres_refuelled');
                        break;
                    case 'litres_refuelled':
                        this.setEdit(logbook, 'origin');
                        break;
                    case 'origin':
                        this.setEdit(logbook, 'destination');
                        break;
                    case 'destination':
                        logbook.show_details = true;
                        this.setEdit(logbook, 'project');
                        break;
                    case 'project':
                        logbook.show_details = true;
                        this.setEdit(logbook, 'comment');
                        break;
                    case 'comment':
                        this.unsetEdit(logbook);
                        break;
                }
            },

            getProject(projectId) {
                return this.projects.find(project => project.id === projectId);
            },

            getProjectName(projectId) {
                let project = this.getProject(projectId);
                return project ? project.name : '';
            },

            getVehicle(vehicleId) {
                return this.vehicles.find(vehicle => vehicle.id === vehicleId);
            },

            getVehicleRegistrationIdentifier(vehicleId) {
                let vehicle = this.getVehicle(vehicleId);

                return vehicle ? vehicle.registration_identifier : '';
            },

            getHighestVehicleEndKilometres(vehicle) {
                let vehicleLogbook = this.getVehicleLogbook(vehicle);

                return vehicleLogbook.length ?
                    vehicleLogbook.reduce((prev, current) =>
                        (prev.end_kilometres > current.end_kilometres) ? prev : current).end_kilometres :
                    null;
            },

            getVehicleLogbook(vehicle) {
                return this.logbook.filter(book => book.vehicle_id === vehicle.id);
            },

            getEmployeeName(employeeId) {
                let employee = this.employees.find(employee => employee.id === employeeId);
                return employee ? employee.name : this.current_employee.name;
            },

            getDateStringForInputField(date) {
                return date !== null ? date.toISOString().slice(0, 10) : null;
            },

            autofill(logbook = null) {
                let startKilometres =
                    logbook === null ? Number(this.start_kilometres) : Number(logbook.start_kilometres);
                let endKilometres =
                    logbook === null ? Number(this.end_kilometres) : Number(logbook.end_kilometres);
                let drivenKilometres =
                    logbook === null ? Number(this.driven_kilometres) : Number(logbook.driven_kilometres);
                let vehicle = logbook === null ? this.vehicle : this.getVehicle(logbook.service_id);

                if(vehicle !== null && !startKilometres && !endKilometres && !drivenKilometres) {
                    this.autofillStartKilometresFromBooked(logbook, vehicle);
                }
                else if(startKilometres && endKilometres && !drivenKilometres) {
                    this.autofillDrivenKilometres(logbook, startKilometres, endKilometres);
                }
                else if(startKilometres && drivenKilometres && !endKilometres) {
                    this.autofillEndKilometres(logbook, startKilometres, drivenKilometres);
                }
                else if(endKilometres && drivenKilometres && !startKilometres) {
                    this.autofillStartKilometres(logbook, endKilometres, drivenKilometres);
                }
            },

            autofillDrivenKilometres(logbook = null, startKilometres, endKilometres) {
                let drivenKilometres = endKilometres - startKilometres;

                if(logbook === null) {
                    this.driven_kilometres = drivenKilometres;
                }
                else {
                    logbook.driven_kilometres = drivenKilometres;
                }
            },

            autofillEndKilometres(logbook = null, startKilometres, drivenKilometres) {
                let endKilometres = startKilometres + drivenKilometres;

                if(logbook === null) {
                    this.end_kilometres = endKilometres;
                }
                else {
                    logbook.end_kilometres = endKilometres;
                }
            },

            autofillStartKilometres(logbook = null, endKilometres, drivenKilometres) {
                let startKilometres = endKilometres - drivenKilometres;

                if(logbook === null) {
                    this.start_kilometres = startKilometres;
                }
                else {
                    logbook.start_kilometres = startKilometres;
                }
            },

            autofillStartKilometresFromBooked(logbook = null, vehicle) {
                let startKilometres = vehicle.current_kilometres ? vehicle.current_kilometres : null;
                let highestVehicleEndKilometres = this.getHighestVehicleEndKilometres(vehicle);

                if(highestVehicleEndKilometres && highestVehicleEndKilometres > startKilometres) {
                    startKilometres = highestVehicleEndKilometres
                }

                if(logbook === null) {
                    this.start_kilometres = startKilometres;
                }
                else {
                    logbook.start_kilometres = startKilometres;
                }
            },

            getLastPage() {
                return this.logbook.length ? Math.ceil(this.logbook.length / this.page_size) : 0;
            }

        },

        props: {
            current_logbook: {
                type: Array,
                default() {
                    return [];
                }
            },

            places: {
                type: Array,
                default() {
                    return [];
                }
            },

            vehicles: {
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
            },

            page_size: {
                type: Number,
                default() {
                    return 20;
                }
            }
        }

    }
</script>
