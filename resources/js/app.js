/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import AddressDropdown from './components/AddressDropdown';
import AttachmentsSelector from './components/AttachmentsSelector';
import AvatarColourSelector from './components/AvatarColourSelector';
import CompanyDropdown from './components/CompanyDropdown';
import EmailSelector from "./components/EmailSelector";
import GestureLinks from './components/GestureLinks';
import Notification from './components/Notification';
import PeopleSelector from './components/PeopleSelector';
import PersonDropdown from './components/PersonDropdown';
import ProjectDropdown from "./components/ProjectDropdown";
import ServiceUnitDropdown from "./components/ServiceUnitDropdown";
import ServicesSelector from "./components/ServicesSelector";
import SignaturePad from "./components/SignaturePad";
import QrScanner from "./components/QrScanner";
import WebpushManager from "./components/WebpushManager";
import vSelect from 'vue-select';
import VueEasymde from "vue-easymde";
import { VueHammer } from 'vue2-hammer';
import VueSignaturePad from 'vue-signature-pad';
import "easymde/dist/easymde.min.css";
import VueQrcodeReader from "vue-qrcode-reader";

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component('address-dropdown', AddressDropdown);
Vue.component('attachments-selector', AttachmentsSelector);
Vue.component('avatar-colour-selector', AvatarColourSelector);
Vue.component('company-dropdown', CompanyDropdown);
Vue.component('email-selector', EmailSelector);
Vue.component('gesture-links', GestureLinks);
Vue.component('notification', Notification);
Vue.component('people-selector', PeopleSelector);
Vue.component('person-dropdown', PersonDropdown);
Vue.component('project-dropdown', ProjectDropdown);
Vue.component("services-selector", ServicesSelector);
Vue.component("service-unit-dropdown", ServiceUnitDropdown);
Vue.component('signature-pad', SignaturePad);
Vue.component('vue-easymde', VueEasymde);
Vue.component('v-select', vSelect);
Vue.component('qr-scanner', QrScanner);
Vue.component('webpush-manager', WebpushManager);

Vue.use(VueHammer);
Vue.use(VueSignaturePad);
Vue.use(VueQrcodeReader);

VueHammer.config.pan = {
    direction: 'horizontal'
};

VueHammer.config.swipe = {
    direction: 'horizontal'
};

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});

(function() {
    'use strict';
    window.addEventListener('load', function() {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function(form) {
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
