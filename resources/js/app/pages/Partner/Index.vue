<template>
    <v-partner-layout>
        <div class="q-pa-md">
            <div class="text-h5 q-mb-md">Dashboard</div>

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

            <div class="row q-col-gutter-md q-mt-md">
                <div class="col">
                    <q-card flat bordered>
                        <q-card-section>
                            <div class="text-subtitle1">Total Sales</div>
                            <div class="text-h6">{{ total_sales }}</div>
                        </q-card-section>
                    </q-card>
                </div>
                <div class="col">
                    <q-card flat bordered>
                        <q-card-section>
                            <div class="text-subtitle1">Total Commission</div>

                            <div
                                v-for="(item, index) in total_commission"
                                :key="index"
                                class="text-h6"
                            >
                                {{ item.total }} {{ item.currency }}
                            </div>
                        </q-card-section>
                    </q-card>
                </div>
            </div>

            <apex-charts
                width="100%"
                height="350"
                :type="chartType"
                :options="chartOptions"
                :series="chartSeries"
                class="q-mt-md"
            />
        </div>
    </v-partner-layout>
</template>

<script>
import ApexCharts from "vue3-apexcharts";

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
                type: "month",
            },
            chartType: "line",
            chartTypes: ["bar", "line", "area"],
            total_sales: 0,
            total_commission: 0,
            chartOptions: {},
            chartSeries: [],
            types: ["day", "month", "year"],
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

        this.getSales();

        this.updateChart(this.sales);

        setInterval(() => {
            this.getSales();
        }, 10000);
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
                const res = await this.$server.get(this.$page.props.route, {
                    params: this.params,
                });

                if (res.status == 200) {
                    this.sales = res.data.data;
                    this.total_sales = res.data.total_sales;
                    this.total_commission = res.data.total_commission;
                    this.updateChart(this.sales);
                }
            } catch (error) {}
        },

        updateChart(data) {
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
    },
};
</script>
