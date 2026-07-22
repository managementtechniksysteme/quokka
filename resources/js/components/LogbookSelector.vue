<template>
  <div>

      <top-progress ref="top_progress" color="#007BFF" errorColor="#DC3545"></top-progress>

      <notification v-if="dataResult !== null && dataResult.hasOwnProperty('success')" type="success" v-cloak>
          <div class="d-inline-flex align-items-center">
              <svg class="icon-bs icon-24 me-2">
                  <use href="/svg/bootstrap-icons.svg#check"></use>
              </svg>
              {{ this.dataResult.success }}
          </div>
      </notification>
      <notification v-if="dataResult !== null && dataResult.hasOwnProperty('danger')" type="danger" v-cloak>
          <div class="d-inline-flex align-items-center">
              <svg class="icon-bs icon-24 me-2">
                  <use href="/svg/bootstrap-icons.svg#exclamation-octagon"></use>
              </svg>
              {{ this.dataResult.danger }}
          </div>
      </notification>

      <div class="q-page-head">
          <div class="d-flex align-items-center gap-3">
              <span class="q-head-icon">
                  <svg class="icon-bs icon-20"><use href="/svg/bootstrap-icons.svg#journal"></use></svg>
              </span>
              <div>
                  <div class="q-eyebrow">Fahrtenbuch</div>
                  <h1 class="q-title">Fahrtenbuch</h1>
                  <div v-if="logbook.length" class="q-subtitle">
                      {{ logbook.length }} {{ logbook.length === 1 ? 'Eintrag' : 'Einträge' }}
                      <span v-if="getNewLogbook().length" style="color:var(--q-green)">+{{ getNewLogbook().length }}</span>
                      <span v-if="getChangedLogbook().length" style="color:var(--q-amber)">±{{ getChangedLogbook().length }}</span>
                      <span v-if="getDestroyedLogbook().length" style="color:var(--q-red)">-{{ getDestroyedLogbook().length }}</span>
                  </div>
              </div>
          </div>

          <div class="d-flex align-items-center gap-2">
              <button v-if="permissions.includes('logbook.createpdf') && logbook.length" type="button" class="btn q-btn d-inline-flex align-items-center gap-2" @click="createPdf()" @keydown.enter.prevent="createPdf()">
                  <svg class="icon-bs icon-16"><use href="/svg/bootstrap-icons.svg#printer"></use></svg>
                  Auswertung
              </button>

              <button v-if="permissions.includes('service-reports.create') && getSelectedLogbook().length && !selectedLogbookContainsUnsaved() && selectedLogbookIsOwn() && selectedLogbookIsSingleProject()" type="button" class="btn q-btn d-inline-flex align-items-center gap-2" @click="createServiceReportFromSelectedAccounting()" @keydown.enter.prevent="createServiceReportFromSelectedAccounting()">
                  <svg class="icon-bs icon-16"><use href="/svg/bootstrap-icons.svg#gear"></use></svg>
                  Servicebericht erstellen
              </button>
          </div>
      </div>

      <div v-if="getUnsavedLogbook().length" class="q-banner" role="alert">
          <svg class="icon-bs icon-16"><use href="/svg/bootstrap-icons.svg#exclamation-triangle"></use></svg>
          <div>
              Du hast ungespeicherte Änderungen. Geänderte Zeilen bleiben auch dann sichtbar, wenn der
              Filterbereich nachträglich geändert wird.
          </div>
      </div>

      <div class="q-filterbar q-form">
          <div class="q-card">
              <div class="q-card__body">
                  <div class="q-filterbar__fields">
                      <div class="q-filterbar__field">
                          <label for="filter_start">Start</label>
                          <input type="date" :max="filter_end" class="form-control form-control-sm" v-bind:class="{'is-invalid': filter_start_errors}" id="filter_start" name="filter_start" placeholder="" :disabled="filter_only_unsaved" v-model="filter_start" />
                          <div v-if="filter_start_errors" class="invalid-feedback d-block">{{ filter_start_errors[0] }}</div>
                      </div>
                      <div class="q-filterbar__field">
                          <label for="filter_end">Ende</label>
                          <input type="date" :min="filter_start" class="form-control form-control-sm" v-bind:class="{'is-invalid': filter_end_errors}" id="filter_end" name="filter_end" placeholder="" :disabled="filter_only_unsaved" v-model="filter_end" />
                          <div v-if="filter_end_errors" class="invalid-feedback d-block">{{ filter_end_errors[0] }}</div>
                      </div>
                      <div class="q-filterbar__field q-filterbar__field--grow">
                          <label>Fahrzeug</label>
                          <v-select class="dropdown-sm" :options="vehicles" label="registration_identifier" placeholder="Alle Fahrzeuge" :disabled="filter_only_unsaved" :modelValue="filter_vehicle" :selectOnTab="true" @update:modelValue="setFilterVehicle">
                              <template v-slot:no-options>Keine passenden Einträge.</template>
                          </v-select>
                          <div v-if="filter_vehicle_errors" class="invalid-feedback d-block">{{ filter_vehicle_errors[0] }}</div>
                      </div>
                      <div class="q-filterbar__field q-filterbar__field--grow">
                          <label>Projekt</label>
                          <v-select class="dropdown-sm" :options="projects" label="name" placeholder="Alle Projekte" :disabled="filter_only_unsaved" :modelValue="filter_project" :selectOnTab="true" @update:modelValue="setFilterProject">
                              <template v-slot:no-options>Keine passenden Einträge.</template>
                          </v-select>
                          <div v-if="filter_project_errors" class="invalid-feedback d-block">{{ filter_project_errors[0] }}</div>
                      </div>
                  </div>
                  <div class="q-filterbar__actions">
                      <div v-if="permissions.includes('logbook.view.own') && permissions.includes('logbook.view.other')" class="q-filterbar__switch">
                          <div class="form-check form-switch m-0">
                              <input type="checkbox" class="form-check-input" v-bind:class="{'is-invalid': filter_only_own_errors}" name="filter_only_own" id="filter_only_own" :disabled="filter_only_unsaved" :value="filter_only_own" v-model="filter_only_own" @click="toggleFilterOnlyOwn()">
                              <label class="form-check-label" for="filter_only_own">Nur eigene</label>
                          </div>
                          <div v-if="filter_only_own_errors" class="invalid-feedback d-block">{{ filter_only_own_errors[0] }}</div>
                      </div>
                      <div class="q-filterbar__switch">
                          <div class="form-check form-switch m-0">
                              <input type="checkbox" class="form-check-input" name="filter_only_unsaved" id="filter_only_unsaved" :value="filter_only_unsaved" v-model="filter_only_unsaved" @click="toggleFilterOnlyUnsaved()">
                              <label class="form-check-label" for="filter_only_unsaved">Nur ungespeicherte</label>
                          </div>
                      </div>
                      <button type="button" class="btn q-btn q-filterbar__submit d-inline-flex align-items-center gap-2" @click="filterData()">
                          <svg class="icon-bs icon-16"><use href="/svg/bootstrap-icons.svg#funnel"></use></svg>
                          Filtern
                      </button>
                  </div>
              </div>
          </div>
      </div>

      <div class="q-grid">
          <div ref="logbook_overview">
              <div v-if="logbook.length" class="q-card q-dtable">
                  <div class="q-dtable__head q-logbook-grid">
                      <span>
                          <button type="button" class="btn btn-sm q-dtable__icon-btn p-1 d-inline-flex align-items-center" v-bind:class="{'invisible': !getErrorLogbook().length, 'text-danger': getErrorLogbook().length && !getShowNoDetailsErrorLogbook().length, 'text-muted': getErrorLogbook().length && getShowNoDetailsErrorLogbook().length}" :disabled="!getErrorLogbook().length" @click="toggleShowDetailsError()">
                              <svg class="icon-bs icon-16"><use href="/svg/bootstrap-icons.svg#exclamation-triangle"></use></svg>
                          </button>
                      </span>
                      <span>Fahrzeug</span>
                      <span>Datum</span>
                      <span>KM</span>
                      <span class="q-dtable__num">Gef.</span>
                      <span class="q-dtable__num">L</span>
                      <span>Strecke</span>
                      <span class="q-dtable__actions">
                          <button type="button" class="btn btn-sm btn-outline-danger p-1 d-inline-flex align-items-center" :disabled="!getSelectedLogbook().length" @click="removeSelectedLogbook()">
                              <svg class="icon-bs icon-16"><use href="/svg/bootstrap-icons.svg#trash"></use></svg>
                          </button>
                          <button type="button" class="btn btn-sm btn-outline-success p-1 d-inline-flex align-items-center" :disabled="!getSelectedLogbook().length" @click="restoreSelectedLogbook()">
                              <svg class="icon-bs icon-16"><use href="/svg/bootstrap-icons.svg#arrow-counterclockwise"></use></svg>
                          </button>
                          <button type="button" class="btn btn-sm q-dtable__icon-btn p-1 d-inline-flex align-items-center" v-bind:class="getSelectedLogbook().length === pageOfItems.length ? 'text-primary' : 'text-muted'" @click="toggleSelectAll()" @mouseenter="selectAllHover = true" @mouseleave="selectAllHover = false">
                              <svg class="icon-bs icon-16">
                                  <use v-if="getSelectedLogbook().length !== pageOfItems.length && !selectAllHover" href="/svg/bootstrap-icons.svg#circle"></use>
                                  <use v-else href="/svg/bootstrap-icons.svg#check-circle"></use>
                              </svg>
                          </button>
                      </span>
                  </div>

                  <template v-for="(book, index) in pageOfItems" :key="'book-' + (book.id ?? ('new' + index))">
                      <div class="q-dtable__row q-trow q-logbook-grid" v-bind:class="{'is-created': book.action === 'store', 'is-edited': book.action === 'update', 'is-removed': book.action === 'destroy', 'is-selected': book.selected}">
                          <span>
                              <button type="button" class="btn btn-sm q-dtable__icon-btn p-1 d-inline-flex align-items-center" v-bind:class="book.errors ? 'text-danger' : 'text-muted'" @click="toggleShowDetails(book)">
                                  <svg class="icon-bs icon-16">
                                      <use v-if="!book.errors && !book.show_details" href="/svg/bootstrap-icons.svg#chevron-right"></use>
                                      <use v-if="book.errors && !book.show_details" href="/svg/bootstrap-icons.svg#exclamation-triangle"></use>
                                      <use v-if="book.show_details" href="/svg/bootstrap-icons.svg#chevron-down"></use>
                                  </svg>
                              </button>
                          </span>
                          <div class="q-dtable__cell" @click="setEdit(book, 'vehicle')">
                              <span v-if="book.edit !== 'vehicle'" class="q-ab q-mono">{{ getVehicleRegistrationIdentifier(book.vehicle_id) }}</span>
                              <v-select v-if="book.edit === 'vehicle'" class="dropdown-sm" :options="vehicles" ref="table_input" label="registration_identifier" placeholder="Fahrzeug auswählen" :modelValue="getVehicleRegistrationIdentifier(book.vehicle_id)" :selectOnTab="true" @update:modelValue="changeLogbookVehicle($event, book)" @close="changeLogbookDropdownValueToSame(book)" @keydown.enter.prevent="changeLogbookVehicle($event, book)">
                                  <template v-slot:no-options>Keine passenden Einträge.</template>
                              </v-select>
                          </div>
                          <div class="q-dtable__cell" @click="setEdit(book, 'driven_on')">
                              <span v-if="book.edit !== 'driven_on'">{{ book.driven_on.toLocaleDateString("de", { month: '2-digit', day: '2-digit', year: 'numeric' }) }}</span>
                              <input v-if="book.edit === 'driven_on'" type="date" class="form-control form-control-sm" v-bind:class="{'is-invalid': table_driven_on_invalid}" ref="table_input" id="table_driven_on" name="table_driven_on" :value="getDateStringForInputField(book.driven_on)" placeholder="" required @blur="changeLogbookDrivenOn($event, book)" @keydown.enter.prevent="changeLogbookDrivenOn($event, book)" @keydown.tab.prevent="onTableInputTab($event, book, 'driven_on')" />
                          </div>
                          <div class="q-dtable__cell d-flex align-items-center gap-1">
                              <span v-if="book.edit !== 'start_kilometres'" @click="setEdit(book, 'start_kilometres')">{{ book.start_kilometres.toLocaleString() }}</span>
                              <input v-if="book.edit === 'start_kilometres'" type="number" min="0" step="1" class="form-control form-control-sm" v-bind:class="{'is-invalid': table_start_kilometres_invalid}" ref="table_input" id="table_start_kilometres" name="table_start_kilometres" placeholder="131337" :value="book.start_kilometres" @blur="changeLogbookStartKilometres($event, book)" @keydown.enter.prevent="changeLogbookStartKilometres($event, book)" @keydown.tab.prevent="onTableInputTab($event, book, 'start_kilometres')" />
                              <span class="q-dtable__muted" v-if="book.edit !== 'start_kilometres' && book.edit !== 'end_kilometres'">→</span>
                              <span v-if="book.edit !== 'end_kilometres'" @click="setEdit(book, 'end_kilometres')">{{ book.end_kilometres.toLocaleString() }}</span>
                              <input v-if="book.edit === 'end_kilometres'" type="number" min="1" step="1" class="form-control form-control-sm" v-bind:class="{'is-invalid': table_end_kilometres_invalid}" ref="table_input" id="table_end_kilometres" name="table_end_kilometres" placeholder="131415" :value="book.end_kilometres" @blur="changeLogbookEndKilometres($event, book)" @keydown.enter.prevent="changeLogbookEndKilometres($event, book)" @keydown.tab.prevent="onTableInputTab($event, book, 'end_kilometres')" />
                          </div>
                          <div class="q-dtable__cell q-dtable__num" @click="setEdit(book, 'driven_kilometres')">
                              <span v-if="book.edit !== 'driven_kilometres'">{{ book.driven_kilometres.toLocaleString() }}</span>
                              <input v-if="book.edit === 'driven_kilometres'" type="number" min="1" step="1" class="form-control form-control-sm" v-bind:class="{'is-invalid': table_driven_kilometres_invalid}" ref="table_input" id="table_driven_kilometres" name="table_driven_kilometres" placeholder="78" :value="book.driven_kilometres" @blur="changeLogbookDrivenKilometres($event, book)" @keydown.enter.prevent="changeLogbookDrivenKilometres($event, book)" @keydown.tab.prevent="onTableInputTab($event, book, 'driven_kilometres')" />
                          </div>
                          <div class="q-dtable__cell q-dtable__num" @click="setEdit(book, 'litres_refuelled')">
                              <span v-if="book.edit !== 'litres_refuelled'" v-bind:class="{'q-dtable__muted': !book.litres_refuelled}">{{ book.litres_refuelled ? book.litres_refuelled.toLocaleString() : '–' }}</span>
                              <input v-if="book.edit === 'litres_refuelled'" type="number" min="1" step="1" class="form-control form-control-sm" v-bind:class="{'is-invalid': table_litres_refuelled_invalid}" ref="table_input" id="table_litres_refuelled" name="table_litres_refuelled" placeholder="54" :value="book.litres_refuelled" @blur="changeLogbookLitresRefuelled($event, book)" @keydown.enter.prevent="changeLogbookLitresRefuelled($event, book)" @keydown.tab.prevent="onTableInputTab($event, book, 'litres_refuelled')" />
                          </div>
                          <div class="q-dtable__cell d-flex align-items-center gap-1 q-dtable__truncate">
                              <span v-if="book.edit !== 'origin'" class="q-dtable__truncate" @click="setEdit(book, 'origin')">{{ book.origin }}</span>
                              <v-select v-if="book.edit === 'origin'" class="dropdown-sm" :options="places" ref="table_input" placeholder="Start auswählen oder eingeben" :modelValue="origin" :selectOnTab="true" @update:modelValue="changeLogbookOrigin($event, book)" @close="changeLogbookDropdownValueToSame(book)" @keydown.enter.prevent="changeLogbookOrigin($event, book)">
                                  <template v-slot:no-options>Keine passenden Einträge.</template>
                              </v-select>
                              <span class="q-dtable__muted" v-if="book.edit !== 'origin' && book.edit !== 'destination'">→</span>
                              <span v-if="book.edit !== 'destination'" class="q-dtable__truncate" @click="setEdit(book, 'destination')">{{ book.destination }}</span>
                              <v-select v-if="book.edit === 'destination'" class="dropdown-sm" :options="places" ref="table_input" placeholder="Ziel auswählen oder eingeben" :modelValue="destination" :selectOnTab="true" @update:modelValue="changeLogbookDestination($event, book)" @close="changeLogbookDropdownValueToSame(book)" @keydown.enter.prevent="changeLogbookDestination($event, book)">
                                  <template v-slot:no-options>Keine passenden Einträge.</template>
                              </v-select>
                          </div>
                          <div class="q-dtable__actions">
                              <button v-if="book.action !== 'destroy' && canRemoveLogbook(current_employee, book)" type="button" class="btn btn-sm btn-outline-danger p-1 d-inline-flex align-items-center" @click="removeLogbook(book)">
                                  <svg class="icon-bs icon-16"><use href="/svg/bootstrap-icons.svg#trash"></use></svg>
                              </button>
                              <button v-if="book.action === 'destroy' && canRemoveLogbook(current_employee, book)" type="button" class="btn btn-sm btn-outline-success p-1 d-inline-flex align-items-center" @click="restoreLogbook(book)">
                                  <svg class="icon-bs icon-16"><use href="/svg/bootstrap-icons.svg#arrow-counterclockwise"></use></svg>
                              </button>
                              <button type="button" class="btn btn-sm q-dtable__icon-btn p-1 d-inline-flex align-items-center" v-bind:class="book.selected ? 'text-primary' : 'text-muted'" @click="toggleSelected(book)" @mouseenter="book.hover = true" @mouseleave="book.hover = false">
                                  <svg class="icon-bs icon-16">
                                      <use v-if="!book.selected && !book.hover" href="/svg/bootstrap-icons.svg#circle"></use>
                                      <use v-else href="/svg/bootstrap-icons.svg#check-circle"></use>
                                  </svg>
                              </button>
                          </div>
                      </div>

                      <transition name="collapse">
                          <div v-if="book.show_details" class="q-dtable__detail q-trow" v-bind:class="{'is-created': book.action === 'store', 'is-edited': book.action === 'update', 'is-removed': book.action === 'destroy', 'is-selected': book.selected}">
                              <div class="mb-2">
                                  <label class="fw-bold">Projekt</label>
                                  <div v-if="book.edit !== 'project'" @click="setEdit(book, 'project')">{{ book.project_id ? getProjectName(book.project_id) : 'nicht angegeben' }}</div>
                                  <v-select v-if="book.edit === 'project'" class="dropdown-sm" :options="projects" ref="table_input" label="name" placeholder="Projekt auswählen" :modelValue="getProject(book.project_id)" :selectOnTab="true" @update:modelValue="changeLogbookProject($event, book)" @close="changeLogbookDropdownValueToSame(book)" @keydown.enter.prevent="changeLogbookProject($event, book)">
                                      <template v-slot:no-options>Keine passenden Einträge.</template>
                                  </v-select>
                              </div>
                              <div class="mb-2">
                                  <label class="fw-bold">Mitarbeiter</label>
                                  <div>{{ getEmployeeName(book.employee_id) }}</div>
                              </div>
                              <div class="mb-2">
                                  <label for="table_comment" class="fw-bold">Bemerkungen</label>
                                  <p v-if="book.edit !== 'comment'" class="whitespace-preline mb-0" @click="setEdit(book, 'comment')">{{ book.comment ? book.comment : 'nicht angegeben' }}</p>
                                  <textarea v-if="book.edit === 'comment'" class="form-control form-control-sm" ref="table_input" id="table_comment" name="table_comment" placeholder="Bemerkungen" :value="book.comment" @blur="changeLogbookComment($event, book)" />
                              </div>
                              <div v-if="book.errors" class="q-banner" style="background: color-mix(in srgb, var(--q-red) 9%, transparent); border-color: color-mix(in srgb, var(--q-red) 24%, transparent);" role="alert">
                                  <svg class="icon-bs icon-16" style="color: var(--q-red)"><use href="/svg/bootstrap-icons.svg#exclamation-octagon"></use></svg>
                                  <div>
                                      <p class="mb-0 fw-bold">Probleme in dieser Zeile</p>
                                      <ul class="mb-0">
                                          <li v-for="error in book.errors">{{ error }}</li>
                                      </ul>
                                  </div>
                              </div>
                          </div>
                      </transition>
                  </template>
              </div>

              <div v-if="logbook.length" class="mt-3">
                  <jw-pagination :labels="pagination_labels" :items="logbook" :pageSize="page_size" :initialPage="initialPage" :resetTrigger="resetTrigger" @changePage="onChangePage"></jw-pagination>
              </div>

              <p v-if="logbook.length" class="q-legend">
                  Der linke farbliche Rand zeigt den Speicherzustand der jeweiligen Zeile:
                  <b style="color: var(--q-green)">●</b> wird angelegt ·
                  <b style="color: var(--q-amber)">●</b> wird bearbeitet ·
                  <b style="color: var(--q-red)">●</b> wird entfernt
              </p>

              <div v-if="!logbook.length" class="q-empty-state">
                  <svg class="q-empty-icon"><use href="/svg/bootstrap-icons.svg#journal"></use></svg>
                  <p>Es sind keine Fahrtenbuch-Einträge passend zum Anzeigefilter vorhanden.</p>
                  <p>Trage neue Fahrten mithilfe des Formulars ein.</p>
              </div>
          </div>

          <div v-if="permissions.includes('logbook.create')" class="q-grid__form q-form">
              <div class="q-card__head d-flex align-items-center gap-2">
                  <span class="q-section-icon q-section-icon--accent">
                      <svg class="icon-bs icon-16"><use href="/svg/bootstrap-icons.svg#plus"></use></svg>
                  </span>
                  Fahrt eintragen
              </div>
              <div class="q-card__body d-flex flex-column gap-3">
                  <div>
                      <label>Fahrzeug</label>
                      <v-select :options="vehicles" label="registration_identifier" placeholder="Fahrzeug auswählen" :modelValue="vehicle" :selectOnTab="true" @update:modelValue="setVehicle">
                          <template v-slot:no-options>Keine passenden Einträge.</template>
                      </v-select>
                      <div class="invalid-feedback" v-bind:class="{'d-block': vehicle_invalid}">Fahrzeug muss ausgefüllt sein.</div>
                  </div>
                  <div>
                      <label for="driven_on">Datum</label>
                      <input type="date" class="form-control" v-bind:class="{'is-invalid': driven_on_invalid}" id="driven_on" name="driven_on" placeholder="" required v-model="driven_on" />
                      <div class="invalid-feedback">Datum muss ausgefüllt sein.</div>
                  </div>
                  <div class="d-flex gap-2">
                      <div class="flex-grow-1">
                          <label for="start_kilometres">Start km</label>
                          <input type="number" :min="0" step="1" class="form-control" v-bind:class="{'is-invalid': start_kilometres_invalid}" id="start_kilometres" name="start_kilometres" placeholder="131337" required v-model="start_kilometres" @blur="autofill()" />
                          <div class="invalid-feedback">Start Kilometer müssen mindestens 0 sein.</div>
                      </div>
                      <div class="flex-grow-1">
                          <label for="end_kilometres">Ende km</label>
                          <input type="number" min="1" step="1" class="form-control" v-bind:class="{'is-invalid': end_kilometres_invalid}" id="end_kilometres" name="end_kilometres" placeholder="131415" required v-model="end_kilometres" @blur="autofill()" />
                          <div class="invalid-feedback">Ende Kilometer müssen mindestens 1 sein.</div>
                      </div>
                  </div>
                  <div class="d-flex gap-2">
                      <div class="flex-grow-1">
                          <label for="driven_kilometres">gefahrene KM</label>
                          <input type="number" min="1" step="1" class="form-control" v-bind:class="{'is-invalid': driven_kilometres_invalid}" id="driven_kilometres" name="driven_kilometres" placeholder="78" required v-model="driven_kilometres" @blur="autofill()" />
                          <div class="invalid-feedback">gefahrene Kilometer müssen mindestens 1 sein.</div>
                      </div>
                      <div class="flex-grow-1">
                          <label for="litres_refuelled">getankte Liter</label>
                          <input type="number" min="1" step="1" class="form-control" v-bind:class="{'is-invalid': litres_refuelled_invalid}" id="litres_refuelled" name="litres_refuelled" placeholder="54" v-model="litres_refuelled" />
                          <div class="invalid-feedback">getankte Liter müssen mindestens 1 sein.</div>
                      </div>
                  </div>
                  <div>
                      <label>Start</label>
                      <v-select :options="placesList" placeholder="Start auswählen oder eingeben" :modelValue="origin" :selectOnTab="true" :taggable="true" @update:modelValue="setOrigin">
                          <template v-slot:no-options>Keine passenden Einträge.</template>
                      </v-select>
                      <div class="invalid-feedback" v-bind:class="{'d-block': origin_invalid}">Start muss ausgefüllt sein.</div>
                  </div>
                  <div>
                      <label>Ziel</label>
                      <v-select :options="placesList" placeholder="Ziel auswählen oder eingeben" :modelValue="destination" :selectOnTab="true" :taggable="true" @update:modelValue="setDestination">
                          <template v-slot:no-options>Keine passenden Einträge.</template>
                      </v-select>
                      <div class="invalid-feedback" v-bind:class="{'d-block': origin_invalid}">Ziel muss ausgefüllt sein.</div>
                  </div>
                  <div>
                      <label>Projekt</label>
                      <v-select :options="projects" label="name" placeholder="Projekt auswählen" :modelValue="project" :selectOnTab="true" @update:modelValue="setProject">
                          <template v-slot:no-options>Keine passenden Einträge.</template>
                      </v-select>
                      <div class="invalid-feedback" v-bind:class="{'d-block': project_invalid}">Projekt muss ausgefüllt sein.</div>
                  </div>
                  <div>
                      <label for="comment">Bemerkungen</label>
                      <textarea class="form-control" id="comment" name="comment" placeholder="Bemerkungen" v-model="comment" />
                  </div>
                  <div>
                      <div class="d-flex align-items-center gap-2">
                          <div class="form-check form-switch m-0">
                              <input type="checkbox" class="form-check-input" name="return_trip" id="return_trip" :value="return_trip" v-model="return_trip" @click="toggleReturnTrip()">
                              <label class="form-check-label" for="return_trip">Hin- und Rückfahrt</label>
                          </div>
                          <a data-bs-toggle="collapse" href="#returnTripHelpCollapse">
                              <svg class="icon-bs icon-16 text-muted"><use href="/svg/bootstrap-icons.svg#question-circle"></use></svg>
                          </a>
                      </div>
                      <div class="collapse" id="returnTripHelpCollapse">
                          <p class="q-subtitle mt-2 mb-0">
                              Es werden zwei Fahrten mit halbierter Kilometeranzahl und vertauschtem Start sowie
                              Ziel angelegt. Getankte Liter werden beim ersten Eintrag hinzugefügt.
                          </p>
                      </div>
                  </div>
                  <button id="addlogbook" type="button" class="btn q-btn d-inline-flex align-items-center justify-content-center gap-2" @click="addLogbook()">
                      <svg class="icon-bs icon-16"><use href="/svg/bootstrap-icons.svg#plus"></use></svg>
                      Hinzufügen
                  </button>
              </div>
          </div>
      </div>

      <div v-if="logbook.length" class="q-savebar">
          <div class="q-savebar__inner">
              <button ref="save_button" type="button" class="btn btn-primary text-white d-inline-flex align-items-center gap-2" :disabled="!getUnsavedLogbook().length" @click="saveData()">
                  <svg class="icon-bs icon-16"><use href="/svg/bootstrap-icons.svg#floppy"></use></svg>
                  Änderungen speichern
              </button>
          </div>
      </div>

  </div>
</template>

<script>
    const FETCH_ERROR_MESSAGE = "Beim Filtern der Daten traten Probleme auf.";
    const SAVE_SUCCESS_MESSAGE = "Die Änderungen wurden erfolgreich gespeichert.";
    const SAVE_ERROR_MESSAGE = "Beim Speichern der Änderungen traten Probleme auf.";

    const STORE_UNAUTHORIZED_MESSAGE = "Das Anlegen dieser Zeile ist nicht autorisiert";
    const UPDATE_UNAUTHORIZED_MESSAGE = "Das Bearbeiten dieser Zeile ist nicht autorisiert";
    const DESTROY_UNAUTHORIZED_MESSAGE = "Das Entfernen dieser Zeile ist nicht autorisiert";

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
                filter_only_own: this.permissions.includes('logbook.view.own'),
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
                return_trip: false,

                logbook: [],
                pageOfItems: [],

                placesList: this.places,

                initialPage: 1,
                resetTrigger: 0,
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
                this.resetTrigger++;

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

                let axiosInstance = axios.create({
                    validateStatus: function (status) {
                        return status < 300;
                    }
                });

                axiosInstance.get('/logbook', {params: params})
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

                // always reassign to a new array reference (push/sort above
                // mutate in place) so JwPagination's shallow `items` watcher
                // reliably notices this filter/fetch completed.
                this.logbook = [...this.logbook];
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
                let axiosInstance = axios.create({
                    validateStatus: function (status) {
                        return status < 300;
                    }
                });

                return axiosInstance.post('/logbook', {
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
                    }
                    else if(error.response.status === 403) {
                        logbook.errors = [STORE_UNAUTHORIZED_MESSAGE];
                    }

                    logbook.show_details = this.expand_errors;
                });
            },

            updateLogbook(logbook) {
                let axiosInstance = axios.create({
                    validateStatus: function (status) {
                        return status < 300;
                    }
                });

                return axiosInstance.post('/logbook/' + logbook.id, {
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
                    }
                    else if(error.response.status === 403) {
                        logbook.errors = [UPDATE_UNAUTHORIZED_MESSAGE];
                    }

                    logbook.show_details = this.expand_errors;
                });
            },

            destroyLogbook(logbook) {
                let axiosInstance = axios.create({
                    validateStatus: function (status) {
                        return status < 300;
                    }
                });

                return axiosInstance.post('/logbook/' + logbook.id, {
                    _method: 'DELETE'
                })
                .then(() => {
                    this.logbook = this.removeFromArray(this.logbook, logbook);
                })
                .catch(error => {
                    if(error.response.status === 422) {
                        logbook.errors = this.extractErrorMessages(error.response);
                    }
                    else if(error.response.status === 403) {
                        logbook.errors = [DESTROY_UNAUTHORIZED_MESSAGE];
                    }

                    logbook.show_details = this.expand_errors;
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

            createPdf(employeeId) {
                let url = new URL(window.location.origin + '/logbook/download');

                let params = {}

                if(this.filter_start) {
                    params.start = this.filter_start;
                }
                if(this.filter_end) {
                    params.end = this.filter_end;
                }
                if(this.filter_project) {
                    params.project_id = this.filter_project.id;
                }
                if(this.filter_vehicle) {
                    params.vehicle_id = this.filter_vehicle.id;
                }
                if(this.filter_only_own) {
                    params.only_own = this.filter_only_own;
                }

                url.search = new URLSearchParams(params).toString();

                window.open(url).focus();
            },

            createServiceReportFromSelectedAccounting() {
                let url = new URL(window.location.origin + '/service-reports/create');

                url.search = new URLSearchParams(
                    this.getSelectedLogbook().map(logbook => ['logbook[]', logbook.id])
                ).toString();

                window.open(url).focus();
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
                if((this.filter_only_own && this.permissions.includes('logbook.view.other')) ||
                    (!this.filter_only_own && this.permissions.includes('logbook.view.own'))) {
                    this.filter_only_own = !this.filter_only_own;
                }
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

            toggleReturnTrip() {
                this.return_trip = !this.return_trip
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

                let legs = [];

                if(this.return_trip) {
                    let legKilometres = Math.floor(drivenKilometres / 2);
                    let evenDrivenKilometres = drivenKilometres % 2 === 0;

                    let firstLegStartKilometres = startKilometres;
                    let firstLegEndKilometres =
                        evenDrivenKilometres ? startKilometres + legKilometres : startKilometres + legKilometres + 1;
                    let firstLegDrivenKilometres = firstLegEndKilometres - firstLegStartKilometres;
                    let firstLegLitresRefuelled = litresRefuelled;
                    let firstLegOrigin = this.origin;
                    let firstLegDestination = this.destination;

                    let secondLegStartKilometres = firstLegEndKilometres;
                    let secondLegEndKilometres = endKilometres;
                    let secondLegDrivenKilometres = legKilometres;
                    let secondLegLitresRefuelled = null;
                    let secondLegOrigin = this.destination;
                    let secondLegDestination = this.origin;

                    legs.push({
                        start_kilometres: firstLegStartKilometres,
                        end_kilometres: firstLegEndKilometres,
                        driven_kilometres: firstLegDrivenKilometres,
                        litres_refuelled: firstLegLitresRefuelled,
                        origin: firstLegOrigin,
                        destination: firstLegDestination,
                    });

                    legs.push({
                        start_kilometres: secondLegStartKilometres,
                        end_kilometres: secondLegEndKilometres,
                        driven_kilometres: secondLegDrivenKilometres,
                        litres_refuelled: secondLegLitresRefuelled,
                        origin: secondLegOrigin,
                        destination: secondLegDestination,
                    });
                }
                else {
                    legs.push({
                        start_kilometres: startKilometres,
                        end_kilometres: endKilometres,
                        driven_kilometres: drivenKilometres,
                        litres_refuelled: litresRefuelled,
                        origin: this.origin,
                        destination: this.destination,
                    });
                }


                legs.forEach(leg => {
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
                        start_kilometres: leg.start_kilometres,
                        end_kilometres: leg.end_kilometres,
                        driven_kilometres: leg.driven_kilometres,
                        litres_refuelled: leg.litres_refuelled,
                        origin: leg.origin,
                        destination: leg.destination,
                        vehicle_id: this.vehicle.id,
                        project_id: this.project !== null ? this.project.id : null,
                        employee_id: null,
                        comment: this.comment,
                    });
                })

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
                this.origin = legs.length === 1 ? this.destination : this.origin;
                this.origin_invalid = false;
                this.destination = null;
                this.destination_invalid = null;
                this.vehicle_invalid  = false;
                this.project_invalid = false;
                this.comment = null;
                this.return_trip = false;

                this.autofillStartKilometresFromBooked(null, this.vehicle);

                this.initialPage = this.getLastPage();
                this.resetTrigger++;

                this.scrollToNewEntry = true;
            },

            removeLogbook(logbook) {
                if(!this.canRemoveLogbook(this.current_employee, logbook)) {
                    return;
                }

                if(logbook.action !== 'destroy') {
                    logbook.action_old = logbook.action;
                }

                logbook.action = 'destroy';
            },

            restoreLogbook(logbook) {
                if(!this.canRemoveLogbook(this.current_employee, logbook)) {
                    return;
                }

                logbook.action = logbook.action_old ? logbook.action_old : null;
            },

            canRemoveLogbook(employee, logbook) {
                if(logbook.action === 'store' || logbook.action_old === 'store') {
                    return true;
                }

                return (logbook.employee_id === employee.id && this.permissions.includes('logbook.delete.own')) ||
                    (logbook.employee_id !== employee.id && this.permissions.includes('logbook.delete.other'));
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

            selectedLogbookContainsUnsaved() {
                return this.getSelectedLogbook().filter(logbook => logbook.action !== null).length > 0;

            },

            selectedLogbookIsOwn() {
                let selectedLogbook = this.getSelectedLogbook();

                return selectedLogbook.
                filter(logbook =>
                    logbook.employee_id === this.current_employee.id
                ).length === selectedLogbook.length;
            },

            selectedLogbookIsSingleProject() {
                const unique = (value, index, self) => {
                    return self.indexOf(value) === index
                }

                return this.getSelectedLogbook().map(logbook => logbook.project_id).filter(unique).length === 1;
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
                if(!this.canEditLogbook(this.current_employee, logbook)) {
                    return;
                }

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

            canEditLogbook(employee, logbook) {
                if(logbook.action === 'store' || logbook.action_old === 'store') {
                    return true;
                }

                return (logbook.employee_id === employee.id && this.permissions.includes('logbook.update.own')) ||
                    (logbook.employee_id !== employee.id && this.permissions.includes('logbook.update.other'));
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

            permissions: {
                type: Array,
                default() {
                    return [];
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
