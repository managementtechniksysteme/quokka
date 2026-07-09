<template>
    <div class="w-100">
        <apexchart :options="chartOptions" :series="series"></apexchart>
    </div>
</template>

<script>
export default {
    name: 'FinanceRevenueExpenseChart',

    props: {
        id:            { type: String },
        revenue:       { type: Number, required: true },
        expense:       { type: Number, required: true },
        currency_unit: { type: String, default: () => '€' },
    },

    data() {
        return {
            isDark: document.documentElement.getAttribute('data-bs-theme') === 'dark',
        };
    },

    computed: {
        chartOptions() {
            void this.isDark; // reactive dependency — recomputes on theme switch
            const s      = getComputedStyle(document.documentElement);
            const green  = s.getPropertyValue('--q-green').trim();
            const red    = s.getPropertyValue('--q-red').trim();
            const faint  = s.getPropertyValue('--q-faint').trim();
            const border = s.getPropertyValue('--q-border').trim();
            const diff   = this.revenue + this.expense;

            return {
                chart: {
                    type: 'bar',
                    width: '100%',
                    background: 'transparent',
                    fontFamily: 'system-ui, -apple-system, "Segoe UI", sans-serif',
                    toolbar: { show: false },
                    defaultLocale: 'de',
                    locales: [{
                        name: 'de',
                        options: {
                            months: ['Januar','Februar','März','April','Mai','Juni','Juli','August','September','Oktober','November','Dezember'],
                            shortMonths: ['Jan','Feb','Mär','Apr','Mai','Jun','Jul','Aug','Sep','Okt','Nov','Dez'],
                            days: ['Sonntag','Montag','Dienstag','Mittwoch','Donnerstag','Freitag','Samstag'],
                            shortDays: ['So','Mo','Di','Mi','Do','Fr','Sa'],
                        },
                    }],
                },
                theme: { mode: this.isDark ? 'dark' : 'light' },
                colors: [green, red, diff >= 0 ? green : red],
                xaxis: {
                    categories: [''],
                    labels: { show: false },
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                },
                yaxis: {
                    labels: {
                        style: { colors: faint },
                        formatter: (v) => v.toFixed(0) + ' ' + this.currency_unit,
                    },
                },
                grid: { borderColor: border },
                dataLabels: {
                    enabled: true,
                    formatter: (v) => v.toFixed(2) + ' ' + this.currency_unit,
                },
                legend: { position: 'top' },
                tooltip: {
                    y: {
                        formatter: (v) => v.toFixed(2) + ' ' + this.currency_unit,
                        title: { formatter: () => '' },
                    },
                },
                plotOptions: {
                    bar: {
                        borderRadius: 6,
                        borderRadiusApplication: 'end',
                    },
                },
            };
        },

        series() {
            return [
                { name: 'Einnahmen', data: [this.revenue] },
                { name: 'Ausgaben',  data: [this.expense] },
                { name: 'Differenz', data: [this.revenue + this.expense] },
            ];
        },
    },

    mounted() {
        this._observer = new MutationObserver(() => {
            this.isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
        });
        this._observer.observe(document.documentElement, {
            attributes: true,
            attributeFilter: ['data-bs-theme'],
        });
    },

    beforeUnmount() {
        this._observer?.disconnect();
    },
};
</script>
