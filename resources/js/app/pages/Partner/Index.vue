<template>
    <v-partner-layout>
        <q-page padding>
            <div class="container-main q-gutter-y-md q-pa-md">
                <div class="column main">
                    <div class="">
                        <h2 class="text-h4">Dashboard</h2>
                        <span class="textSecondary">Welcome to your affiliate management center</span>
                    </div>

                    <div class="column">
                        <div class="row q-col-gutter-md">
                            <div class="col col-nmb">
                                <q-card flat bordered class="card q-pa-sm">
                                    <q-card-section>
                                        <div class="text-subtitle1 row justify-between">
                                            <span>Total Sales</span>
                                            <div class="tag row justify-center items-center">
                                                <span><q-icon name="mdi-cart-outline" /></span>
                                            </div>
                                        </div>
                                        <div class="text-h4 textPrimary">
                                            <strong>{{ total_sales }}</strong>
                                        </div>
                                        <div>
                                            <span class="textSecondary">Completed sales</span>
                                        </div>
                                    </q-card-section>
                                </q-card>
                            </div>

                            <div class="col col-nmb">
                                <q-card flat bordered class="card q-pa-sm">
                                    <q-card-section>
                                        <div class="text-subtitle1 row justify-between">
                                            <span>Conversions</span>
                                            <div class="tag row justify-center items-center">
                                                <span><q-icon name="mdi-cash" /></span>
                                            </div>
                                        </div>
                                        <div
                                            v-if="total_commission.length > 0"
                                            v-for="(item, index) in total_commission"
                                            :key="index"
                                            class="text-h4 text-secondary textPrimary"
                                        >
                                            <strong>
                                                <span v-if="item.currency == 'USD'" class="textPrimary">$</span>
                                                <span v-else>{{ item.currency }}</span>
                                                <span v-if="item.total">{{ item.total }}</span>
                                            </strong>
                                        </div>
                                        <div
                                            v-else
                                            class="text-h4 textPrimary"
                                        >
                                            <strong>
                                                <span>$0</span>
                                            </strong>
                                        </div>
                                        <div>
                                            <span class="textSecondary">Earnings generated</span>
                                        </div>
                                    </q-card-section>
                                </q-card>
                            </div>

                            <div class="col col-nmb">
                                <q-card flat bordered class="card q-pa-sm">
                                    <q-card-section>
                                        <div class="text-subtitle1 row justify-between">
                                            <span>Conversion Rate</span>
                                            <div class="tag row justify-center items-center">
                                                <span><q-icon name="percent" /></span>
                                            </div>
                                        </div>
                                        <div
                                            class="text-h4 strong textPrimary"
                                        >
                                            {{ percentage }}%
                                        </div>
                                        <div>
                                            <span class="textSecondary">Monthly average</span>
                                        </div>
                                    </q-card-section>
                                </q-card>
                            </div>

                        </div>

                        <q-card flat bordered class="card q-pa-md">
                            <div class="row q-col-gutter-sm">
                                <div class="col">
                                    <q-input
                                        v-model="params.start"
                                        type="date"
                                        label="Start date"
                                        dense
                                        outlined
                                    />
                                </div>
                                <div class="col">
                                    <q-input
                                        v-model="params.end"
                                        type="date"
                                        label="End date"
                                        dense
                                        outlined
                                    />
                                </div>
                                <div class="col">
                                    <q-select
                                        v-model="chartType"
                                        :options="chartTypes"
                                        label="Chart type"
                                        dense
                                        outlined
                                    />
                                </div>
                                <div class="col">
                                    <q-select
                                        v-model="params.type"
                                        :options="types"
                                        label="Date"
                                        dense
                                        outlined
                                    />
                                </div>
                                <div class="col-auto flex items-end">
                                    <q-btn
                                        label="Get sales"
                                        @click="getSales"
                                        color="primary"
                                    />
                                </div>
                            </div>

                            <apex-charts
                                width="100%"
                                height="350"
                                :type="chartType"
                                :options="chartOptions"
                                :series="chartSeries"
                                class="q-mt-md grapic"
                            />
                        </q-card>
                    </div>
                </div>
            </div>
        </q-page>
    </v-partner-layout>
</template>

<script>
import ApexCharts from "vue3-apexcharts";
import useThemeStore from '../../stores/useThemeStore.js';

export default {
    components: {
        ApexCharts,
    },
    data() {
        return {
            sales: [],
            params: {
                start: null,
                end: null,
                type: "day",
            },
            chartType: "line",
            chartTypes: ["bar", "line", "area"],
            total_sales: 0,
            total_commission: 0,
            chartOptions: {},
            chartSeries: [],
            types: ["day", "month", "year"],
            theme: useThemeStore(),
            percentage: '',
        };
    },

    watch: {
        chartType(value) {
            this.updateChart(this.sales);
        },

        "params.type"(value) {
            this.getSales();
        },
    },

    created() {
        if (!this.params.start || !this.params.end) {
            const { start, end } = this.getDefaultDates();
            this.params.start = start;
            this.params.end = end;
        }

        this.sales = this.$page.props.sales.data;
        this.total_sales = this.$page.props.sales.total_sales;
        this.total_commission = this.$page.props.sales.total_commission;

        this.percentage = this.$page.props.sales.percentage

        setInterval(() => {
            this.getSales();
        }, 10000);
    },

    mounted() {
        this.updateChart(this.sales);
        console.log(this.total_commission);
    },

    watch: {
        'theme.selectedTheme'() {
            this.updateChart(this.sales);
        }
    },

    methods: {
        getDefaultDates() {
            const today = new Date();
            const end = today.toISOString().split("T")[0];

            const pastDate = new Date(today);
            pastDate.setMonth(today.getMonth() - 3);
            const start = pastDate.toISOString().split("T")[0];

            return { start, end };
        },

        async getSales() {
            try {
                const newParams = { ...this.params };
                newParams.end = this.handlerDate(newParams.end);
                const res = await this.$server.get(this.$page.props.route, {
                    params: newParams,
                });

                if (res.status == 200) {
                    this.sales = res.data.data;
                    this.total_sales = res.data.total_sales;
                    this.total_commission = res.data.total_commission;
                    this.updateChart(this.sales);
                }
            } catch (error) {}
        },

        handlerDate(date) {
            const fechaLocal = new Date(`${date}T00:00:00`);
            fechaLocal.setDate(fechaLocal.getDate() + 1);
            return fechaLocal.toISOString().split('T')[0];
        },

        updateChart(data) {
            const textColor = this.getCssVariable('--q-color');

            this.chartSeries = [
                {
                    name: "Sales",
                    data: data.map((item) => item.total),
                },
                {
                    name: "Commission",
                    data: data.map((item) => item.commission),
                },
            ];

            this.chartOptions = {
                chart: {
                    height: 350,
                    type: this.chartType,
                    foreColor: textColor,
                    zoom: {
                        enabled: false,
                    },
                },
                dataLabels: {
                    enabled: true,
                },
                stroke: {
                    curve: "smooth",
                },
                title: {
                    text: `Sales by ${this.params.type}`,
                    align: "left",
                },
                grid: {
                    row: {
                        colors: ["#f3f3f3", "transparent"],
                        opacity: 0.5,
                    },
                },
                xaxis: {
                    categories: data.map((item) => item.date),
                },
            };
        },

        getCssVariable(name) {
            const themeElement = document.querySelector('[data-theme]');
            return getComputedStyle(themeElement).getPropertyValue(name).trim();
        },
    },
};
</script>

<style scoped>
.container-main {
    padding: 1.5rem 2rem;
}

.main {
    gap: 1rem;
}

.col-nmb {
    padding-bottom: 1px;
}

.card {
    border-radius: 1rem;
}

.tag {
    width: 35px;
    height: 35px;
    background-color: var(--q-background-secondary);
    border-radius: .6rem;
}

.textSecondary {
    color: var(--q-color-secondary);
}

.textPrimary {
    font-family: system-ui;
}

.strong {
    font-weight: 700;
}

.grapic {
    color: var(--q-color) !important;
}
</style>
