import './bootstrap';

import { createApp } from 'vue';
import AccountingSelector from './components/AccountingSelector.vue';
import AccountingServicesSelector from './components/AccountingServicesSelector.vue';
import AddressDropdown from './components/AddressDropdown.vue';
import AttachmentsSelector from './components/AttachmentsSelector.vue';
import AvatarColourSelector from './components/AvatarColourSelector.vue';
import CompanyDropdown from './components/CompanyDropdown.vue';
import EmailSelector from './components/EmailSelector.vue';
import FinanceGroupDropdown from './components/FinanceGroupDropdown.vue';
import FinanceRevenueExpenseChart from './components/FinanceRevenueExpenseChart.vue';
import FinanceVolumeChart from './components/FinanceVolumeChart.vue';
import JwPagination from './components/JwPagination.vue';
import LogbookSelector from './components/LogbookSelector.vue';
import MarkdownEditor from './components/MarkdownEditor.vue';
import Notification from './components/Notification.vue';
import PeopleSelector from './components/PeopleSelector.vue';
import PersonDropdown from './components/PersonDropdown.vue';
import ProjectDropdown from './components/ProjectDropdown.vue';
import QrScanner from './components/QrScanner.vue';
import RoleDropdown from './components/RoleDropdown.vue';
import ServiceDropdown from './components/ServiceDropdown.vue';
import ServicesSelector from './components/ServicesSelector.vue';
import ServiceUnitDropdown from './components/ServiceUnitDropdown.vue';
import SignaturePad from './components/SignaturePad.vue';
import TopProgress from './components/TopProgress.vue';
import VehicleDropdown from './components/VehicleDropdown.vue';
import WebpushManager from './components/WebpushManager.vue';
import vSelect from 'vue-select';
import VueApexCharts from 'vue3-apexcharts';
import VueEasymde from 'vue3-easymde';
import { VueQrcodeReader } from 'vue-qrcode-reader';

const app = createApp({});

app.component('apexchart', VueApexCharts);
app.component('accounting-selector', AccountingSelector);
app.component('accounting-services-selector', AccountingServicesSelector);
app.component('address-dropdown', AddressDropdown);
app.component('attachments-selector', AttachmentsSelector);
app.component('avatar-colour-selector', AvatarColourSelector);
app.component('company-dropdown', CompanyDropdown);
app.component('email-selector', EmailSelector);
app.component('finance-group-dropdown', FinanceGroupDropdown);
app.component('finance-revenue-expense-chart', FinanceRevenueExpenseChart);
app.component('finance-volume-chart', FinanceVolumeChart);
app.component('jw-pagination', JwPagination);
app.component('logbook-selector', LogbookSelector);
app.component('markdown-editor', MarkdownEditor);
app.component('notification', Notification);
app.component('people-selector', PeopleSelector);
app.component('person-dropdown', PersonDropdown);
app.component('project-dropdown', ProjectDropdown);
app.component('qr-scanner', QrScanner);
app.component('role-dropdown', RoleDropdown);
app.component('service-dropdown', ServiceDropdown);
app.component('services-selector', ServicesSelector);
app.component('service-unit-dropdown', ServiceUnitDropdown);
app.component('signature-pad', SignaturePad);
app.component('top-progress', TopProgress);
app.component('vehicle-dropdown', VehicleDropdown);
app.component('v-select', vSelect);
app.component('webpush-manager', WebpushManager);

app.use(VueApexCharts);
app.use(VueEasymde);
app.use(VueQrcodeReader);

app.mount('#app');

(function() {
    'use strict';
    window.addEventListener('load', function() {
        var forms = document.getElementsByClassName('needs-validation');
        Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();
