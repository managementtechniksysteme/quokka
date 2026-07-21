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

// Mobile tab bar: auto-hide on scroll-down, reveal on scroll-up (2026-07-20
// decision — only this bar, not the app bar or the Mehr sheet). Listens on
// the app's own scroll container (<main>), not window, since layout.app
// scrolls main rather than the document. rAF-throttled; a small threshold
// near the top keeps it always visible there. Lives here (not an inline
// <script> in partials/navbar.blade.php) because that partial renders inside
// #app, Vue's mount root — its client template compiler silently strips
// <script> tags found in that subtree, so an inline script there never runs.
(function () {
    'use strict';
    window.addEventListener('load', function () {
        var bar = document.getElementById('mobileTabbar');
        var scroller = document.querySelector('#app.is-authed main');
        if (!bar || !scroller) return;
        var lastY = scroller.scrollTop, ticking = false;
        scroller.addEventListener('scroll', function () {
            if (ticking) return;
            ticking = true;
            requestAnimationFrame(function () {
                var y = scroller.scrollTop;
                if (y < 40 || y < lastY) {
                    bar.classList.remove('q-tabbar--hidden');
                } else if (y > lastY) {
                    bar.classList.add('q-tabbar--hidden');
                }
                lastY = y;
                ticking = false;
            });
        }, { passive: true });
    }, false);
})();

// Sheets (.q-sheet, i.e. every Bootstrap offcanvas-bottom we use — Mehr, the
// mobile sort pickers): swipe the handle down to dismiss (2026-07-21, user
// request after trying the sheets on a real phone). Bootstrap's offcanvas has
// no gesture support at all, so this is custom pointer-event JS layered on
// top of it. Deliberately scoped to the handle only, not the whole sheet body
// — Mehr's content scrolls internally, and a whole-sheet drag would fight
// that scroll (swipe down to close vs. swipe down to scroll to the top).
// While dragging, the offcanvas's own CSS transition is switched off via its
// inline style so the sheet tracks the finger 1:1; released past the
// threshold, it finishes its own slide-out via that same inline transform
// (still transition-enabled at that point) and only calls Bootstrap's real
// hide() once that animation ends — so Bootstrap does its normal cleanup
// (backdrop, focus-restore, hidden.bs.offcanvas) without ever fighting our
// inline transform for control of the element mid-transition.
(function () {
    'use strict';
    window.addEventListener('load', function () {
        document.querySelectorAll('.q-sheet__handle').forEach(function (handle) {
            var sheet = handle.closest('.offcanvas');
            if (!sheet) return;
            var dragging = false;
            var startY = 0;

            sheet.addEventListener('shown.bs.offcanvas', function () {
                sheet.style.transform = '';
            });

            handle.addEventListener('pointerdown', function (e) {
                dragging = true;
                startY = e.clientY;
                sheet.style.transition = 'none';
                handle.setPointerCapture(e.pointerId);
            });

            handle.addEventListener('pointermove', function (e) {
                if (!dragging) return;
                var delta = Math.max(0, e.clientY - startY);
                sheet.style.transform = 'translateY(' + delta + 'px)';
            });

            var endDrag = function (e) {
                if (!dragging) return;
                dragging = false;
                sheet.style.transition = '';
                var delta = Math.max(0, e.clientY - startY);
                var threshold = sheet.getBoundingClientRect().height * 0.22;
                if (delta > threshold) {
                    sheet.style.transform = 'translateY(100%)';
                    window.setTimeout(function () {
                        window.bootstrap.Offcanvas.getOrCreateInstance(sheet).hide();
                    }, 250);
                } else {
                    sheet.style.transform = '';
                }
            };

            handle.addEventListener('pointerup', endDrag);
            handle.addEventListener('pointercancel', endDrag);
        });
    }, false);
})();

// Mehr sheet: blur its trigger once the sheet finishes closing. Bootstrap's
// offcanvas restores keyboard focus to the element that opened it when it
// hides (a real accessibility behaviour, not a bug) — but .q-tabbar__item's
// :focus style brightens its text the same way .active does, so the "Mehr"
// tab visually looked stuck in a selected/clicked state after dismiss
// (2026-07-20, user-reported). Blurring after close removes that residual
// state; a genuinely keyboard-focused Mehr tab (e.g. mid-Tab-navigation)
// still gets its :focus style exactly while it's actually focused.
(function () {
    'use strict';
    window.addEventListener('load', function () {
        var sheet = document.getElementById('mehrSheet');
        if (!sheet) return;
        sheet.addEventListener('hidden.bs.offcanvas', function () {
            // Bootstrap restores focus to the trigger as part of its own
            // hide cleanup, which runs AFTER this event fires — blurring
            // synchronously here loses that race. Deferring to a macrotask
            // runs after Bootstrap's own focus() call completes.
            setTimeout(function () {
                if (document.activeElement && document.activeElement.blur) {
                    document.activeElement.blur();
                }
            }, 0);
        });
    }, false);
})();
