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
                  <svg class="icon-bs icon-20"><use href="/svg/bootstrap-icons.svg#clock"></use></svg>
              </span>
              <div>
                  <div class="q-eyebrow">Abrechnung</div>
                  <h1 class="q-title">Leistungsabrechnung</h1>
                  <div v-if="accounting.length" class="q-subtitle">
                      {{ accounting.length }} {{ accounting.length === 1 ? 'Eintrag' : 'Einträge' }}
                      <span v-if="getNewAccounting().length" style="color:var(--q-green)">+{{ getNewAccounting().length }}</span>
                      <span v-if="getChangedAccounting().length" style="color:var(--q-amber)">±{{ getChangedAccounting().length }}</span>
                      <span v-if="getDestroyedAccounting().length" style="color:var(--q-red)">-{{ getDestroyedAccounting().length }}</span>
                  </div>
              </div>
          </div>

          <div class="d-flex align-items-center gap-2">
              <button v-if="permissions.includes('accounting.createpdf') && getShownEmployeeIds().length === 1" type="button" class="btn q-btn d-inline-flex align-items-center gap-2" @click="createPdf(current_employee.id)" @keydown.enter.prevent="createPdf(current_employee.id)">
                  <svg class="icon-bs icon-16"><use href="/svg/bootstrap-icons.svg#printer"></use></svg>
                  Auswertung
              </button>

              <div v-if="permissions.includes('accounting.createpdf') && getShownEmployeeIds().length > 1" class="dropdown">
                  <button type="button" class="btn q-btn dropdown-toggle d-inline-flex align-items-center gap-2" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                      <svg class="icon-bs icon-16"><use href="/svg/bootstrap-icons.svg#printer"></use></svg>
                      Auswertung
                  </button>

                  <div class="dropdown-menu dropdown-menu-end" style="min-width: 280px">
                      <form @submit.prevent="onReportSubmit">
                          <div v-for="employeeId in getShownEmployeeIds()" :key="'emp-'+employeeId" class="form-check">
                              <input type="checkbox" class="form-check-input" :id="'employee-'+employeeId" name="employee_ids[]" :value="employeeId">
                              <label class="form-check-label d-inline-flex align-items-center gap-1" v-bind:class="{'text-primary': employeeId === current_employee.id}" :for="'employee-'+employeeId">
                                  <svg class="icon-bs icon-14"><use href="/svg/bootstrap-icons.svg#person"></use></svg>
                                  {{ getEmployeeName(employeeId) }}
                              </label>
                          </div>
                          <button class="btn btn-sm btn-primary text-white mx-2 mt-1 d-inline-flex align-items-center gap-2" type="submit">
                              <svg class="icon-bs icon-16"><use href="/svg/bootstrap-icons.svg#printer"></use></svg>
                              Erstellen
                          </button>
                      </form>
                  </div>
              </div>

              <button v-if="permissions.includes('service-reports.create') && getSelectedAccounting().length && !selectedAccountingContainsUnsaved() && selectedAccountingIsHourBased() && selectedAccountingIsOwn() && selectedAccountingIsSingleProject()" type="button" class="btn q-btn d-inline-flex align-items-center gap-2" @click="createServiceReportFromSelectedAccounting()" @keydown.enter.prevent="createServiceReportFromSelectedAccounting()">
                  <svg class="icon-bs icon-16"><use href="/svg/bootstrap-icons.svg#gear"></use></svg>
                  Servicebericht erstellen
              </button>
          </div>
      </div>

      <div v-if="getUnsavedAccounting().length" class="q-banner" role="alert">
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
                          <label>Projekt</label>
                          <v-select class="dropdown-sm" :options="projects" label="name" placeholder="Alle Projekte" :disabled="filter_only_unsaved" :modelValue="filter_project" :selectOnTab="true" @update:modelValue="setFilterProject">
                              <template v-slot:no-options>Keine passenden Einträge.</template>
                          </v-select>
                          <div v-if="filter_project_errors" class="invalid-feedback d-block">{{ filter_project_errors[0] }}</div>
                      </div>
                      <div class="q-filterbar__field q-filterbar__field--grow">
                          <label>Leistung</label>
                          <v-select class="dropdown-sm" :options="services" label="name_with_unit" placeholder="Alle Leistungen" :disabled="filter_only_unsaved" :modelValue="filter_service" :selectOnTab="true" @update:modelValue="setFilterService">
                              <template v-slot:no-options>Keine passenden Einträge.</template>
                          </v-select>
                          <div v-if="filter_service_errors" class="invalid-feedback d-block">{{ filter_service_errors[0] }}</div>
                      </div>
                  </div>
                  <div class="q-filterbar__actions">
                      <div v-if="permissions.includes('accounting.view.own') && permissions.includes('accounting.view.other')" class="q-filterbar__switch">
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
          <div ref="accounting_overview">
              <div v-if="accounting.length" class="q-card q-dtable">
                  <div class="q-dtable__head q-accounting-grid">
                      <span>
                          <button type="button" class="btn btn-sm q-dtable__icon-btn p-1 d-inline-flex align-items-center" v-bind:class="{'invisible': !getErrorAccounting().length, 'text-danger': getErrorAccounting().length && !getShowNoDetailsErrorAccounting().length, 'text-muted': getErrorAccounting().length && getShowNoDetailsErrorAccounting().length}" :disabled="!getErrorAccounting().length" @click="toggleShowDetailsError()">
                              <svg class="icon-bs icon-16"><use href="/svg/bootstrap-icons.svg#exclamation-triangle"></use></svg>
                          </button>
                      </span>
                      <span>Datum</span>
                      <span>Zeit</span>
                      <span>Projekt</span>
                      <span>Leistung</span>
                      <span class="q-dtable__num">Menge</span>
                      <span>MA</span>
                      <span class="q-dtable__actions">
                          <button type="button" class="btn btn-sm btn-outline-danger p-1 d-inline-flex align-items-center" :disabled="!getSelectedAccounting().length" @click="removeSelectedAccounting()">
                              <svg class="icon-bs icon-16"><use href="/svg/bootstrap-icons.svg#trash"></use></svg>
                          </button>
                          <button type="button" class="btn btn-sm btn-outline-success p-1 d-inline-flex align-items-center" :disabled="!getSelectedAccounting().length" @click="restoreSelectedAccounting()">
                              <svg class="icon-bs icon-16"><use href="/svg/bootstrap-icons.svg#arrow-counterclockwise"></use></svg>
                          </button>
                          <button type="button" class="btn btn-sm q-dtable__icon-btn p-1 d-inline-flex align-items-center" v-bind:class="getSelectedAccounting().length === pageOfItems.length ? 'text-primary' : 'text-muted'" @click="toggleSelectAll()" @mouseenter="selectAllHover = true" @mouseleave="selectAllHover = false">
                              <svg class="icon-bs icon-16">
                                  <use v-if="getSelectedAccounting().length !== pageOfItems.length && !selectAllHover" href="/svg/bootstrap-icons.svg#circle"></use>
                                  <use v-else href="/svg/bootstrap-icons.svg#check-circle"></use>
                              </svg>
                          </button>
                      </span>
                  </div>

                  <template v-for="(acc, index) in pageOfItems" :key="'acc-' + (acc.id ?? ('new' + index))">
                      <div class="q-dtable__row q-trow q-accounting-grid" v-bind:class="{'is-created': acc.action === 'store', 'is-edited': acc.action === 'update', 'is-removed': acc.action === 'destroy', 'is-selected': acc.selected}">
                          <span>
                              <button type="button" class="btn btn-sm q-dtable__icon-btn p-1 d-inline-flex align-items-center" v-bind:class="acc.errors ? 'text-danger' : 'text-muted'" @click="toggleShowDetails(acc)">
                                  <svg class="icon-bs icon-16">
                                      <use v-if="!acc.errors && !acc.show_details" href="/svg/bootstrap-icons.svg#chevron-right"></use>
                                      <use v-if="acc.errors && !acc.show_details" href="/svg/bootstrap-icons.svg#exclamation-triangle"></use>
                                      <use v-if="acc.show_details" href="/svg/bootstrap-icons.svg#chevron-down"></use>
                                  </svg>
                              </button>
                          </span>
                          <div class="q-dtable__cell" @click="setEdit(acc, 'service_provided_on')">
                              <span v-if="acc.edit !== 'service_provided_on'">{{ acc.service_provided_on.toLocaleDateString("de", { month: '2-digit', day: '2-digit', year: 'numeric' }) }}</span>
                              <input v-if="acc.edit === 'service_provided_on'" type="date" class="form-control form-control-sm" v-bind:class="{'is-invalid': table_service_provided_on_invalid}" ref="table_input" id="table_service_provided_on" name="table_service_provided_on" :value="getDateStringForInputField(acc.service_provided_on)" placeholder="" required @blur="changeAccountingServiceProvidedOn($event, acc)" @keydown.enter.prevent="changeAccountingServiceProvidedOn($event, acc)" @keydown.tab.prevent="onTableInputTab($event, acc, 'service_provided_on')" />
                          </div>
                          <div class="q-dtable__cell d-flex align-items-center gap-1" v-if="acc.edit !== 'service_provided_started_at' && acc.edit !== 'service_provided_ended_at' && !acc.service_provided_started_at && !acc.service_provided_ended_at">
                              <span class="q-dtable__muted" @click="setEdit(acc, 'service_provided_started_at')">–</span>
                          </div>
                          <div class="q-dtable__cell d-flex align-items-center gap-1" v-else>
                              <span v-if="acc.edit !== 'service_provided_started_at'" @click="setEdit(acc, 'service_provided_started_at')">{{ acc.service_provided_started_at ?? '–' }}</span>
                              <input v-if="acc.edit === 'service_provided_started_at'" type="time" :max="acc.service_provided_ended_at" class="form-control form-control-sm" v-bind:class="{'is-invalid': table_service_provided_started_at_invalid}" ref="table_input" id="table_service_provided_started_at" name="table_service_provided_started_at" :value="acc.service_provided_started_at ? acc.service_provided_started_at : ''" placeholder="08:00" required @blur="changeAccountingServiceProvidedStartedAt($event, acc)" @keydown.enter.prevent="changeAccountingServiceProvidedStartedAt($event, acc)" @keydown.tab.prevent="onTableInputTab($event, acc, 'service_provided_started_at')" />
                              <span class="q-dtable__muted" v-if="acc.edit !== 'service_provided_started_at' && acc.edit !== 'service_provided_ended_at'">–</span>
                              <span v-if="acc.edit !== 'service_provided_ended_at'" @click="setEdit(acc, 'service_provided_ended_at')">{{ acc.service_provided_ended_at ?? '–' }}</span>
                              <input v-if="acc.edit === 'service_provided_ended_at'" :min="acc.service_provided_started_at" type="time" class="form-control form-control-sm" v-bind:class="{'is-invalid': table_service_provided_ended_at_invalid}" ref="table_input" id="table_service_provided_ended_at" name="table_service_provided_ended_at" :value="acc.service_provided_ended_at ? acc.service_provided_ended_at : ''" placeholder="13:00" required @blur="changeAccountingServiceProvidedEndedAt($event, acc)" @keydown.enter.prevent="changeAccountingServiceProvidedEndedAt($event, acc)" @keydown.tab.prevent="onTableInputTab($event, acc, 'service_provided_ended_at')" />
                          </div>
                          <div class="q-dtable__cell q-dtable__truncate" @click="setEdit(acc, 'project')" :title="getProjectName(acc.project_id)">
                              <span v-if="acc.edit !== 'project'">{{ getProjectName(acc.project_id) }}</span>
                              <v-select v-if="acc.edit === 'project'" class="dropdown-sm" :options="projects" ref="table_input" label="name" placeholder="Projekt auswählen" :modelValue="getProject(acc.project_id)" :selectOnTab="true" @update:modelValue="changeAccountingProject($event, acc)" @close="changeAccountingDropdownValueToSame(acc)" @keydown.enter.prevent="changeAccountingProject($event, acc)">
                                  <template v-slot:no-options>Keine passenden Einträge.</template>
                              </v-select>
                          </div>
                          <div class="q-dtable__cell q-dtable__truncate" @click="setEdit(acc, 'service')" :title="getServiceName(acc.service_id)">
                              <span v-if="acc.edit !== 'service'">{{ getServiceName(acc.service_id) }}</span>
                              <v-select v-if="acc.edit === 'service'" class="dropdown-sm" :options="services" ref="table_input" label="name_with_unit" placeholder="Service auswählen" :modelValue="getService(acc.service_id)" :selectOnTab="true" @update:modelValue="changeAccountingService($event, acc)" @close="changeAccountingDropdownValueToSame(acc)" @keydown.enter.prevent="changeAccountingService($event, acc)">
                                  <template v-slot:no-options>Keine passenden Einträge.</template>
                              </v-select>
                          </div>
                          <div class="q-dtable__cell q-dtable__num" @click="setEdit(acc, 'amount')">
                              <span v-if="acc.edit !== 'amount'">{{ acc.amount.toLocaleString() }}</span>
                              <input v-if="acc.edit === 'amount'" type="number" :min="service !== null && service.type === 'wage' ? min_amount : 0.01" :step="service !== null && service.type === 'wage' ? min_amount : 0.01" class="form-control form-control-sm" v-bind:class="{'is-invalid': table_amount_invalid}" ref="table_input" id="table_amount" name="table_amount" :value="acc.amount" placeholder="5" @blur="changeAccountingAmount($event, acc)" @keydown.enter.prevent="changeAccountingAmount($event, acc)" @keydown.tab.prevent="onTableInputTab($event, acc, 'amount')" />
                          </div>
                          <div class="q-dtable__truncate" :title="getEmployeeName(acc.employee_id)">{{ getEmployeeShortName(acc.employee_id) }}</div>
                          <div class="q-dtable__actions">
                              <button v-if="acc.action !== 'destroy' && canRemoveAccounting(current_employee, acc)" type="button" class="btn btn-sm btn-outline-danger p-1 d-inline-flex align-items-center" @click="removeAccounting(acc)">
                                  <svg class="icon-bs icon-16"><use href="/svg/bootstrap-icons.svg#trash"></use></svg>
                              </button>
                              <button v-if="acc.action === 'destroy' && canRemoveAccounting(current_employee, acc)" type="button" class="btn btn-sm btn-outline-success p-1 d-inline-flex align-items-center" @click="restoreAccounting(acc)">
                                  <svg class="icon-bs icon-16"><use href="/svg/bootstrap-icons.svg#arrow-counterclockwise"></use></svg>
                              </button>
                              <button type="button" class="btn btn-sm q-dtable__icon-btn p-1 d-inline-flex align-items-center" v-bind:class="acc.selected ? 'text-primary' : 'text-muted'" @click="toggleSelected(acc)" @mouseenter="acc.hover = true" @mouseleave="acc.hover = false">
                                  <svg class="icon-bs icon-16">
                                      <use v-if="!acc.selected && !acc.hover" href="/svg/bootstrap-icons.svg#circle"></use>
                                      <use v-else href="/svg/bootstrap-icons.svg#check-circle"></use>
                                  </svg>
                              </button>
                          </div>
                      </div>

                      <transition name="collapse">
                          <div v-if="acc.show_details" class="q-dtable__detail q-trow" v-bind:class="{'is-created': acc.action === 'store', 'is-edited': acc.action === 'update', 'is-removed': acc.action === 'destroy', 'is-selected': acc.selected}">
                              <div class="mb-2">
                                  <label for="table_comment" class="fw-bold">Bemerkungen</label>
                                  <p v-if="acc.edit !== 'comment'" class="whitespace-preline mb-0" @click="setEdit(acc, 'comment')">{{ acc.comment ? acc.comment : 'nicht angegeben' }}</p>
                                  <textarea v-if="acc.edit === 'comment'" class="form-control form-control-sm" ref="table_input" id="table_comment" name="table_comment" placeholder="Bemerkungen" :value="acc.comment" @blur="changeAccountingComment($event, acc)" />
                              </div>
                              <div v-if="acc.errors" class="q-banner" style="background: color-mix(in srgb, var(--q-red) 9%, transparent); border-color: color-mix(in srgb, var(--q-red) 24%, transparent);" role="alert">
                                  <svg class="icon-bs icon-16" style="color: var(--q-red)"><use href="/svg/bootstrap-icons.svg#exclamation-octagon"></use></svg>
                                  <div>
                                      <p class="mb-0 fw-bold">Probleme in dieser Zeile</p>
                                      <ul class="mb-0">
                                          <li v-for="error in acc.errors">{{ error }}</li>
                                      </ul>
                                  </div>
                              </div>
                          </div>
                      </transition>
                  </template>
              </div>

              <div v-if="accounting.length" class="mt-3">
                  <jw-pagination :labels="pagination_labels" :items="accounting" :pageSize="page_size" :initialPage="initialPage" :resetTrigger="resetTrigger" @changePage="onChangePage"></jw-pagination>
              </div>

              <p v-if="accounting.length" class="q-legend">
                  Der linke farbliche Rand zeigt den Speicherzustand der jeweiligen Zeile:
                  <b style="color: var(--q-green)">●</b> wird angelegt ·
                  <b style="color: var(--q-amber)">●</b> wird bearbeitet ·
                  <b style="color: var(--q-red)">●</b> wird entfernt
              </p>

              <div v-if="!accounting.length" class="q-empty-state">
                  <svg class="q-empty-icon"><use href="/svg/bootstrap-icons.svg#clock"></use></svg>
                  <p>Es sind keine Abrechnungen passend zum Anzeigefilter vorhanden.</p>
                  <p>Rechne neue Leistungen mithilfe des Formulars ab.</p>
              </div>
          </div>

          <div v-if="permissions.includes('accounting.create')" class="q-grid__form q-form">
              <div class="q-card__head d-flex align-items-center gap-2">
                  <span class="q-section-icon q-section-icon--accent">
                      <svg class="icon-bs icon-16"><use href="/svg/bootstrap-icons.svg#plus"></use></svg>
                  </span>
                  Leistung erfassen
              </div>
              <div class="q-card__body d-flex flex-column gap-3">
                  <div>
                      <label for="service_provided_on">Datum</label>
                      <input type="date" class="form-control" v-bind:class="{'is-invalid': service_provided_on_invalid}" id="service_provided_on" name="service_provided_on" placeholder="" required v-model="date" />
                      <div class="invalid-feedback">Datum muss ausgefüllt sein.</div>
                  </div>
                  <div class="d-flex gap-2">
                      <div class="flex-grow-1">
                          <label for="service_provided_started_at">Start</label>
                          <input type="time" :max="service_provided_ended_at" class="form-control" v-bind:class="{'is-invalid': service_provided_started_at_invalid}" id="service_provided_started_at" name="service_provided_started_at" placeholder="08:00" :disabled="this.service !== null && this.service.unit !== services_hour_unit" required v-model="service_provided_started_at" @blur="autofill()" />
                          <div class="invalid-feedback">Start muss eine gültige Uhrzeit sein.</div>
                      </div>
                      <div class="flex-grow-1">
                          <label for="service_provided_ended_at">Ende</label>
                          <input type="time" :min="service_provided_started_at" class="form-control" v-bind:class="{'is-invalid': service_provided_ended_at_invalid}" id="service_provided_ended_at" name="service_provided_ended_at" placeholder="13:00" required :disabled="this.service !== null && this.service.unit !== services_hour_unit" v-model="service_provided_ended_at" @blur="autofill()" />
                          <div class="invalid-feedback">Ende muss eine gültige Uhrzeit sein.</div>
                      </div>
                  </div>
                  <div>
                      <label>Projekt</label>
                      <v-select :options="projects" label="name" placeholder="Projekt auswählen" :modelValue="project" :selectOnTab="true" @update:modelValue="setProject">
                          <template v-slot:no-options>Keine passenden Einträge.</template>
                      </v-select>
                      <div class="invalid-feedback" v-bind:class="{'d-block': project_invalid}">Projekt muss ausgefüllt sein.</div>
                  </div>
                  <div>
                      <label>Leistung</label>
                      <v-select :options="services" label="name_with_unit" placeholder="Leistung auswählen" :modelValue="service" :selectOnTab="true" @update:modelValue="setService">
                          <template v-slot:no-options>Keine passenden Einträge.</template>
                      </v-select>
                      <div class="invalid-feedback" v-bind:class="{'d-block': service_invalid}">Leistung muss ausgefüllt sein.</div>
                  </div>
                  <div>
                      <label for="amount">Menge</label>
                      <input type="number" class="form-control" v-bind:class="{'is-invalid': amount_invalid}" :min="service !== null && service.type === 'wage' ? min_amount : 0.01" :step="service !== null && service.type === 'wage' ? min_amount : 0.01" id="amount" name="amount" placeholder="5" v-model="amount" @blur="autofill()" />
                      <div class="invalid-feedback">
                          <span v-if="service !== null && this.service.type === 'wage'">Menge muss ein Vielfaches von {{min_amount}} sein.</span>
                          <span v-else>Menge muss mindestens 0.01 sein.</span>
                      </div>
                  </div>
                  <div>
                      <label for="comment">Bemerkungen</label>
                      <textarea class="form-control" id="comment" name="comment" placeholder="Bemerkungen" v-model="comment" />
                  </div>
                  <button id="addaccounting" type="button" class="btn q-btn d-inline-flex align-items-center justify-content-center gap-2" @click="addAccounting()">
                      <svg class="icon-bs icon-16"><use href="/svg/bootstrap-icons.svg#plus"></use></svg>
                      Hinzufügen
                  </button>
              </div>
          </div>
      </div>

      <div v-if="accounting.length" class="q-savebar">
          <div class="q-savebar__inner">
              <button ref="save_button" type="button" class="btn btn-primary text-white d-inline-flex align-items-center gap-2" :disabled="!getUnsavedAccounting().length" @click="saveData()">
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
        name: "AccountingSelector",

        data() {
            let today = new Date();
            return {
                pagination_labels: PAGINATION_LABELS,

                filter_start: null,
                filter_start_errors: null,
                filter_end: null,
                filter_end_errors:null,
                filter_project: null,
                filter_project_errors: null,
                filter_service: null,
                filter_service_errors: null,
                filter_only_own: this.permissions.includes('accounting.view.own'),
                filter_only_own_errors: null,
                filter_only_unsaved: false,

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
                pageOfItems: [],

                initialPage: 1,
                resetTrigger: 0,
                scrollToNewEntry: false,

                selectAllHover: false,

                dataResult: null,
            }
        },

        mounted() {
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
            onChangePage(pageOfItems) {
                this.deselectAll();
                this.pageOfItems = pageOfItems;

                this.$nextTick(() => {
                    if(this.scrollToNewEntry) {
                        this.$refs.save_button.scrollIntoView({behavior: 'smooth'});
                    }
                    else {
                        this.$refs.accounting_overview.scrollIntoView({behavior: 'smooth'});
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
                    this.accounting = this.getUnsavedAccounting();

                    // Add a bit of timeout to progress bar because otherwise it runs forever if state is changed
                    // too quickly.
                    let topProgress = this.$refs.top_progress

                    setTimeout(() => {
                        topProgress.done()
                    }, 10);

                    this.filter_start_errors = null;
                    this.filter_end_errors = null;
                    this.filter_project_errors = null;
                    this.filter_service_errors = null;
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
                if(this.filter_project) {
                    params.project_id = this.filter_project.id;
                }
                if(this.filter_service) {
                    params.service_id = this.filter_service.id;
                }
                if(this.filter_only_own) {
                    params.only_own = this.filter_only_own;
                }

                let axiosInstance = axios.create({
                    validateStatus: function (status) {
                        return status < 300;
                    }
                });

                axiosInstance.get('/accounting', {params: params})
                .then(response => {
                    this.updateLocalAccounting(response.data);

                    this.$refs.top_progress.done();

                    this.filter_start_errors = null;
                    this.filter_end_errors = null;
                    this.filter_project_errors = null;
                    this.filter_service_errors = null;
                    this.filter_only_own_errors = null;
                })
                .catch(error => {
                    this.$refs.top_progress.fail();

                    if(error.response.status === 422) {
                        this.filter_start_errors = this.extractErrorMessages(error.response, 'start');
                        this.filter_end_errors = this.extractErrorMessages(error.response, 'end');
                        this.filter_project_errors = this.extractErrorMessages(error.response, 'project_id');
                        this.filter_service_errors = this.extractErrorMessages(error.response, 'service_id');
                        this.filter_only_own_errors = this.extractErrorMessages(error.response, 'only_own');
                    }
                    else {
                        this.dataResult = {'danger': FETCH_ERROR_MESSAGE};
                    }
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

                // always reassign to a new array reference (push/sort above
                // mutate in place) so JwPagination's shallow `items` watcher
                // reliably notices this filter/fetch completed, without
                // needing a deep watch that would also fire — wrongly — on
                // per-row property toggles like `selected` during normal use.
                this.accounting = [...this.accounting];
            },

            saveData() {
                this.dataResult = null;

                let unsavedAccounting = this.getUnsavedAccounting();
                let promises = [];

                unsavedAccounting.forEach(acc => {
                    switch (acc.action) {
                        case 'store':
                            promises.push(this.storeAccounting(acc));
                            break;
                        case 'update':
                            promises.push(this.updateAccounting(acc));
                            break;
                        case 'destroy':
                            if(acc.id !== null) {
                                promises.push(this.destroyAccounting(acc));
                            }
                            else {
                                this.accounting = this.removeFromArray(this.accounting, acc);
                            }
                            break;
                    }
                });

                Promise.all(promises).then(() => {
                    if(this.getErrorAccounting().length) {
                        this.dataResult = {'danger': SAVE_ERROR_MESSAGE};
                    }
                    else {
                        this.filter_only_unsaved = false;
                        this.filterData();

                        this.dataResult = {'success': SAVE_SUCCESS_MESSAGE};
                    }
                });
            },

            storeAccounting(accounting) {
                let axiosInstance = axios.create({
                    validateStatus: function (status) {
                        return status < 300;
                    }
                });

                return axiosInstance.post('/accounting', {
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
                    if(error.response.status === 422) {
                        accounting.errors = this.extractErrorMessages(error.response);
                    }
                    else if(error.response.status === 403) {
                        accounting.errors = [STORE_UNAUTHORIZED_MESSAGE];
                    }

                    accounting.show_details = this.expand_errors;
                });
            },

            updateAccounting(accounting) {
                let axiosInstance = axios.create({
                    validateStatus: function (status) {
                        return status < 300;
                    }
                });

                return axiosInstance.post('/accounting/' + accounting.id, {
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
                .then(() => {
                    accounting.action = null;
                    accounting.action_old = null;
                    accounting.errors = null;
                    accounting.show_details = false;
                })
                .catch(error => {
                    if(error.response.status === 422) {
                        accounting.errors = this.extractErrorMessages(error.response);
                    }
                    else if(error.response.status === 403) {
                        accounting.errors = [UPDATE_UNAUTHORIZED_MESSAGE];
                    }

                    accounting.show_details = this.expand_errors;
                });
            },

            destroyAccounting(accounting) {
                let axiosInstance = axios.create({
                    validateStatus: function (status) {
                        return status < 300;
                    }
                });

                return axiosInstance.post('/accounting/' + accounting.id, {
                    _method: 'DELETE'
                })
                .then(() => {
                    this.accounting = this.removeFromArray(this.accounting, accounting);
                })
                .catch(error => {
                    if(error.response.status === 422) {
                        accounting.errors = this.extractErrorMessages(error.response);
                    }
                    else if(error.response.status === 403) {
                        accounting.errors = [DESTROY_UNAUTHORIZED_MESSAGE];
                    }

                    accounting.show_details = this.expand_errors;
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

            removeFromArray(accounting, value) {
                return accounting.filter(accounting => accounting.id !== value.id);
            },

            sortArrayByDateTime(accounting) {
                accounting.sort((a, b) => {
                    if(a.service_provided_on.getTime() !== b.service_provided_on.getTime()) {
                        return a.service_provided_on - b.service_provided_on;
                    }
                    else if(a.service_provided_started_at !== b.service_provided_started_at) {
                        return a.service_provided_started_at < b.service_provided_started_at ? -1 : 1;
                    }
                    else {
                        return 0;
                    }
                });
            },

            onReportSubmit(event) {
                const data = new FormData(event.target);
                this.createPdf(data.getAll('employee_ids[]'))
            },

            createPdf(employeeIds) {
                let url = new URL(window.location.origin + '/accounting/download');

                const params = new URLSearchParams(params);

                employeeIds = Array.isArray(employeeIds) ? employeeIds : [employeeIds];

                employeeIds.forEach(function (id) {
                    params.append('employee_ids[]', id);
                });

                if(this.filter_start) {
                    params.append('start', this.filter_start);
                }
                if(this.filter_end) {
                    params.append('end', this.filter_end);
                }
                if(this.filter_project) {
                    params.append('project_id', this.filter_project.id);
                }
                if(this.filter_service) {
                    params.append('service_id', this.filter_service.id);
                }

                url.search = params.toString();

                window.open(url).focus();
            },

            createServiceReportFromSelectedAccounting() {
                let url = new URL(window.location.origin + '/service-reports/create');

                url.search = new URLSearchParams(
                    this.getSelectedAccounting().map(accounting => ['accounting[]', accounting.id])
                ).toString();

                window.open(url).focus();
            },

            getUnsavedAccounting() {
                return this.accounting.filter(acc => acc.action !== null);
            },

            getNewAccounting() {
                return this.accounting.filter(acc => acc.action === 'store');
            },

            getChangedAccounting() {
                return this.accounting.filter(acc => acc.action === 'update');
            },

            getDestroyedAccounting() {
                return this.accounting.filter(acc => acc.action === 'destroy');
            },

            setFilterProject(value) {
                this.filter_project = value;
            },

            setFilterService(value) {
                this.filter_service = value;
            },

            toggleFilterOnlyOwn() {
                if((this.filter_only_own && this.permissions.includes('accounting.view.other')) ||
                    (!this.filter_only_own && this.permissions.includes('accounting.view.own'))) {
                    this.filter_only_own = !this.filter_only_own;
                }
            },

            toggleFilterOnlyUnsaved() {
                this.filter_only_unsaved = !this.filter_only_unsaved;
            },

            setProject(value) {
                this.project = value;
            },

            setService(value) {
                this.service = value;
                this.autofill();
            },

            addAccounting() {
                let date = new Date(this.date);
                let amount = this.amount === null ? 0 : Number(this.amount);

                this.service_provided_on_invalid = isNaN(date.getTime());
                this.service_provided_started_at_invalid = this.service.type === 'wage' && this.service.unit === 'h' &&
                    !this.isTwentyFourHourTimeFormat(this.service_provided_started_at);
                this.service_provided_ended_at_invalid = this.service.type === 'wage' && this.service.unit === 'h' &&
                    !this.isTwentyFourHourTimeFormat(this.service_provided_ended_at);
                this.project_invalid = this.project === null;
                this.service_invalid = this.service === null;
                this.amount_invalid = this.isAmountInvalid(amount, this.service);

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

                this.service_provided_on_invalid = false;
                this.service_provided_started_at = null;
                this.service_provided_started_at_invalid = false;
                this.service_provided_ended_at = null;
                this.service_provided_ended_at_invalid = false;
                this.project_invalid = false;
                this.service_invalid = false;
                this.amount = null;
                this.amount_invalid = false;

                this.initialPage = this.getLastPage();
                this.resetTrigger++;

                this.scrollToNewEntry = true;
            },

            removeAccounting(accounting) {
                if(!this.canRemoveAccounting(this.current_employee, accounting)) {
                    return;
                }

                if(accounting.action !== 'destroy') {
                    accounting.action_old = accounting.action;
                }

                accounting.action = 'destroy';
            },

            restoreAccounting(accounting) {
                if(!this.canRemoveAccounting(this.current_employee, accounting)) {
                    return;
                }

                accounting.action = accounting.action_old ? accounting.action_old : null;
            },

            canRemoveAccounting(employee, accounting) {
                if(accounting.action === 'store' || accounting.action_old === 'store') {
                    return true;
                }

                return (accounting.employee_id === employee.id && this.permissions.includes('accounting.delete.own')) ||
                    (accounting.employee_id !== employee.id && this.permissions.includes('accounting.delete.other'));
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
                let service = this.getService(changedAccounting.service_id);

                if(service.type !== 'wage' || service.unit !== this.services_hour_unit) {
                    return;
                }

                let time = event.target.value ? event.target.value : null;

                // try to autofill if no value given and set new value
                // for checking - seems like a bit of a hack
                if(!time) {
                    changedAccounting.service_provided_started_at = null;
                    this.autofill(changedAccounting);
                    time = changedAccounting.service_provided_started_at;
                }

                if(!this.isTwentyFourHourTimeFormat(time)) {
                    this.table_service_provided_started_at_invalid = true;
                    return;
                }

                changedAccounting.service_provided_started_at = time;

                this.autofill(changedAccounting);

                this.setChangedAccountingStatus(changedAccounting);

                changedAccounting.edit = null;
            },

            changeAccountingServiceProvidedEndedAt(event, changedAccounting) {
                let service = this.getService(changedAccounting.service_id);

                if(service.type !== 'wage' || service.unit !== this.services_hour_unit) {
                    return;
                }

                let time = event.target.value ? event.target.value : null;

                // try to autofill if no value given and set new value
                // for checking - seems like a bit of a hack
                if(!time) {
                    changedAccounting.service_provided_ended_at = null;
                    this.autofill(changedAccounting);
                    time = changedAccounting.service_provided_ended_at;
                }

                if(!this.isTwentyFourHourTimeFormat(time)) {
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

                changedAccounting.service_id = value.id;

                this.autofill(changedAccounting);

                this.setChangedAccountingStatus(changedAccounting);

                changedAccounting.edit = null;
            },

            changeAccountingDropdownValueToSame(changedAccounting) {
                this.setChangedAccountingStatus(changedAccounting);
                this.unsetEdit(changedAccounting);
            },

            changeAccountingAmount(event, changedAccounting) {
                let amount = Number(event.target.value);

                // try to autofill if no value given and set new value
                // for checking - seems like a bit of a hack
                if(!amount) {
                    changedAccounting.amount  = null;
                    this.autofill(changedAccounting);
                    amount = changedAccounting.amount;
                }

                if(this.isAmountInvalid(amount, this.getService(changedAccounting.service_id))) {
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

                if(changedAccounting.action !== 'store') {
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

                let selected = selectedAccounting.length !== this.pageOfItems.length

                this.pageOfItems.forEach(acc => {
                    acc.selected = selected;
                });
            },

            deselectAll() {
                this.getSelectedAccounting().forEach(acc => {
                    acc.selected = false;
                });
            },

            getSelectedAccounting() {
                return this.accounting.filter(acc => acc.selected === true);
            },

            selectedAccountingContainsUnsaved() {
                return this.getSelectedAccounting().filter(accounting => accounting.action !== null).length > 0;

            },

            selectedAccountingIsHourBased() {
                let selectedAccounting = this.getSelectedAccounting();
                let hourBased = this.getSelectedAccounting().filter(accounting => {
                    let service = this.getService(accounting.service_id);
                    return service.unit && service.unit === this.services_hour_unit;
                });

                return hourBased.length === selectedAccounting.length;
            },

            selectedAccountingIsOwn() {
                let selectedAccounting = this.getSelectedAccounting();

                return selectedAccounting.
                    filter(accounting =>
                        accounting.employee_id === this.current_employee.id
                    ).length === selectedAccounting.length;
            },

            selectedAccountingIsSingleProject() {
                const unique = (value, index, self) => {
                    return self.indexOf(value) === index
                }

                return this.getSelectedAccounting().map(accounting => accounting.project_id).filter(unique).length === 1;
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
                if(!this.canEditAccounting(this.current_employee, accounting)) {
                    return;
                }

                let service = this.getService(accounting.service_id);

                if((field === 'service_provided_started_at' || field === 'service_provided_ended_at') &&
                    (service.type !== 'wage' || service.unit !== this.services_hour_unit)) {
                    return;
                }

                this.getEditAccounting().forEach(editAccounting => {
                    this.blurTableInput(editAccounting.edit);
                    editAccounting.edit = null;
                });

                accounting.edit = field;

                this.$nextTick(() => {
                    this.focusTableInput(field);
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

            canEditAccounting(employee, accounting) {
                if(accounting.action === 'store' || accounting.action_old === 'store') {
                    return true;
                }

                return (accounting.employee_id === employee.id && this.permissions.includes('accounting.update.own')) ||
                    (accounting.employee_id !== employee.id && this.permissions.includes('accounting.update.other'));
            },

            focusTableInput(field) {
                if(field === 'project' || field === 'service') {
                    this.$refs.table_input[0].$refs.search.focus();
                }
                else if(field !== null) {
                    this.$refs.table_input[0].focus();
                }
            },

            blurTableInput(field) {
                if(field === 'project' || field === 'service') {
                    this.$refs.table_input[0].$refs.search.blur();
                }
                else if(field !== null) {
                    this.$refs.table_input[0].blur();
                }
            },

            getEditAccounting() {
                return this.accounting.filter(acc => acc.edit !== null);
            },

            onTableInputTab(event, accounting, field) {
                switch (field) {
                    case 'service_provided_on':
                        this.setEdit(accounting, 'service_provided_started_at');
                        break;
                    case 'service_provided_started_at':
                        this.setEdit(accounting, 'service_provided_ended_at');
                        break;
                    case 'service_provided_ended_at':
                        this.setEdit(accounting, 'project');
                        break;
                    case 'project':
                        this.setEdit(accounting, 'service');
                        break;
                    case 'service':
                        this.setEdit(accounting, 'amount');
                        break;
                    case 'amount':
                        accounting.show_details = true;
                        this.setEdit(accounting, 'comment');
                        break;
                    case 'comment':
                        this.unsetEdit(accounting);
                        break;
                }
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

            getEmployeeShortName(employeeId) {
                let employee = this.employees.find(employee => employee.id === employeeId);
                return employee ? employee.short_name : this.current_employee.short_name;
            },

            getShownEmployeeIds() {
                // unsaved (not yet persisted) rows always belong to the current
                // employee but carry employee_id: null until the server assigns
                // one on save — map those to the real id so they don't show up
                // as a separate ("nameless") entry in the report dropdown.
                return this.accounting.length ?
                    Array.from(new Set(this.accounting.map(acc => acc.employee_id ?? this.current_employee.id))) : [];
            },

            isTwentyFourHourTimeFormat(text) {
                return /^([01]\d|2[0123]):[012345]\d$/.test(text);
            },

            isMultipleOf(value, multiplier) {
                return value.toFixed(2).toString().replace('.', '') %
                    multiplier.toFixed(2).toString().replace('.', '') === 0
            },

            hasAtMostDecimals(value, maxDecimals) {
                let valueString = value.toString();

                let decimals = valueString.includes('.') ? valueString.split('.')[1].length : 0

                return decimals <= maxDecimals;
            },

            getDateStringForInputField(date) {
                return date !== null ? date.toISOString().slice(0, 10) : null;
            },

            getTwentyFourHourTimeString(date) {
                return date !== null ? ("0" + date.getHours()).slice(-2) + ":" + ("0" + date.getMinutes()).slice(-2) : null;
            },

            autofill(accounting = null) {
                let start = accounting === null ? this.service_provided_started_at : accounting.service_provided_started_at;
                let end = accounting === null ? this.service_provided_ended_at : accounting.service_provided_ended_at;
                let amount = accounting === null ? this.amount : accounting.amount;
                let service = accounting === null ? this.service : this.getService(accounting.service_id);

                if(service === null) {
                    return;
                }

                if(service.type !== 'wage' || service.unit !== this.services_hour_unit) {
                    this.removeTimes(accounting);
                }
                else if(start && end && !amount) {
                    this.autofillAmount(accounting, start, end);
                }
                else if(start && amount && !end) {
                    this.autofillEnd(accounting, start, amount);
                }
                else if(end && amount && !start) {
                    this.autofillStart(accounting, end, amount);
                }
            },

            removeTimes(accounting = null) {
                if(accounting) {
                    accounting.service_provided_started_at = null;
                    accounting.service_provided_ended_at = null;
                }
                else {
                    this.service_provided_started_at = null;
                    this.service_provided_ended_at = null;
                }
            },

            autofillAmount(accounting = null, start, end) {
                let minAmountMinutes = this.min_amount * 60;
                let today = new Date();
                let date = this.getDateStringForInputField(new Date(today.getTime() - today.getTimezoneOffset() * 60 * 1000));

                let startDate = new Date(date + ' ' + start);
                let endDate = new Date(date + ' ' + end);

                let timeStartMinutes = startDate.getHours() * 60 + startDate.getMinutes();
                let timeEndMinutes = endDate.getHours() * 60 + endDate.getMinutes();

                let differenceMinutes = timeEndMinutes - timeStartMinutes;

                if(differenceMinutes < minAmountMinutes) {
                    return;
                }

                // round down to nearest amount of minimum units that fit into the duration
                differenceMinutes = differenceMinutes - differenceMinutes % minAmountMinutes;

                let differenceHours = differenceMinutes / 60;

                if(accounting === null) {
                    this.amount = differenceHours;
                }
                else {
                    accounting.amount = differenceHours;
                }
            },

            autofillEnd(accounting = null, start, amount) {
                let today = new Date();
                let date = this.getDateStringForInputField(new Date(today.getTime() - today.getTimezoneOffset() * 60 * 1000));

                let amountMilliseconds = amount * 60 * 60 * 1000;

                let timeStart = new Date(date + ' ' + start);
                let timeEnd = new Date(timeStart.getTime() + amountMilliseconds);

                let timeEndString = this.getTwentyFourHourTimeString(timeEnd);

                if(accounting === null) {
                    this.service_provided_ended_at = timeEndString;
                }
                else {
                    accounting.service_provided_ended_at = timeEndString;
                }
            },

            autofillStart(accounting = null, end, amount) {
                let today = new Date();
                let date = this.getDateStringForInputField(new Date(today.getTime() - today.getTimezoneOffset() * 60 * 1000));

                let amountMilliseconds = amount * 60 * 60 * 1000;

                let timeEnd = new Date(date + ' ' + end);
                let timeStart = new Date(timeEnd.getTime() - amountMilliseconds);

                let timeStartString = this.getTwentyFourHourTimeString(timeStart);

                if(accounting === null) {
                    this.service_provided_started_at = timeStartString;
                }
                else {
                    accounting.service_provided_started_at = timeStartString;
                }
            },

            isAmountInvalid(amount, service) {
                if(!service) {
                    return true;
                }

                if(service.type === 'material') {
                    return Number.isNaN(amount) || amount < 0.01 || !this.hasAtMostDecimals(amount, 2) || !this.isMultipleOf(amount, 0.01);
                }
                else if(service.type === 'wage') {
                    return Number.isNaN(amount) || amount < this.min_amount || !this.hasAtMostDecimals(amount, 2) || !this.isMultipleOf(amount, this.min_amount);
                }
            },

            getLastPage() {
                return this.accounting.length ? Math.ceil(this.accounting.length / this.page_size) : 0;
            }

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

            permissions: {
                type: Array,
                default() {
                    return [];
                }
            },

            services_hour_unit: {
                type: String,
                default() {
                    return null;
                }
            },

            min_amount: {
                type: Number,
                default() {
                    return 0.5;
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
