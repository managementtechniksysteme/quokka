<template>
    <div class="w-100">
        <apexchart :options="chartOptions" :series="series"></apexchart>
    </div>
</template>

<script>
    export default {
        name: "FinanceVolumeChart",

        data() {
            return {
                chartOptions: {
                    chart: {
                        defaultLocale: 'de',
                        locales: [{
                            name: 'de',
                            options: {
                                months: ['Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'],
                                shortMonths: ['Jan', 'Feb', 'Mär', 'Apr', 'Mai', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dea'],
                                days: ['Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag'],
                                shortDays: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
                                toolbar: {
                                    exportToSVG: 'SVG Download',
                                    exportToPNG: 'PNG Download',
                                    exportToCSV: 'CSV Download',
                                    menu: "Menü",
                                    selection: 'Auswahl',
                                    selectionZoom: 'Auswahl vergrößern',
                                    zoomIn: 'Vergrößern',
                                    zoomOut: 'Verkleinern',
                                    pan: 'Verschieben',
                                    reset: 'Zoom zurücksetzten',
                                }
                            }
                        }],
                        fontFamily: 'Roboto',
                        id: this.id,
                        type: 'bar',
                        width: '100%'
                    },
                    colors: ['#5CB85C', '#D9534F', this.total_volume + this.billed_volume >=0 ? '#5CB85C' : '#D9534F'],
                    xaxis: {
                        categories: ['Auftragsvolumen', 'verrechnet', 'offen'],
                        labels: {
                            style: {
                                fontFamily: 'Roboto',
                            }
                        },
                        title: {
                            style: {
                                fontFamily: 'Roboto',
                            }
                        }
                    },
                    yaxis: {
                        labels: {
                            style: {
                                fontFamily: 'Roboto',
                            },
                            formatter: (value) => value + this.currency_unit
                        },
                        title: {
                            style: {
                                fontFamily: 'Roboto',
                            }
                        }
                    },
                    dataLabels: {
                        style: {
                            fontFamily: 'Roboto',
                        },
                        formatter: (value) => value.toFixed(2) + this.currency_unit
                    },
                    legend: {
                        fontFamily: 'Roboto',
                    },
                    tooltip: {
                        enabled: true,
                        y: {
                            formatter: (value, { series, seriesIndex, dataPointIndex, w }) => value.toFixed(2) + this.currency_unit,
                            title: {
                                formatter: () => ''
                            }
                        }
                    },
                    plotOptions: {
                        bar: {
                            distributed: true,
                            borderRadius: 0,
                        }
                    }
                },
                series: [{
                    name: 'series',
                    data: [this.total_volume, this.billed_volume, this.total_volume + this.billed_volume]
                }]
            }
        },

        methods: {
        },

        props: {
            id: {
              type: String,
            },

            total_volume: {
                type: Number,
            },

            billed_volume: {
                type: Number,
            },

            currency_unit: {
                type: String,
                default() {
                    return '€';
                }
            }
        }

    }
</script>
