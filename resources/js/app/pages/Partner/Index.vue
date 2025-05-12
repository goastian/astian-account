<template>
    <v-partner-layout>
        <div class="q-pa-md">
            <div class="text-h5 q-mb-md">Dashboard</div>

            <div class="row q-col-gutter-sm">
                <div class="col">
                    <q-input
                        v-model="start"
                        type="date"
                        label="Start date"
                        dense
                        outlined
                    />
                </div>
                <div class="col">
                    <q-input
                        v-model="end"
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
                <div class="col-auto flex items-end">
                    <q-btn
                        label="Get sales"
                        @click="getSales"
                        color="primary"
                    />
                </div>
            </div>

            <apexchart
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
import dayjs from "dayjs";

export default {
    components: {
        apexchart: ApexCharts,
    },
    data() {
        return {
            sales: this.$page.props.sales || {},
            start: null,
            end: null,
            chartType: "bar",
            chartTypes: ["bar", "line", "area"],
            chartOptions: {
                chart: {
                    id: "sales-chart",
                },
                xaxis: {
                    categories: [],
                },
                title: {
                    text: "Sales per Month",
                    align: "center",
                },
                yaxis: [
                    {
                        title: {
                            text: "Transactions",
                        },
                    },
                    {
                        opposite: true,
                        title: {
                            text: "Total Amount ($)",
                        },
                    },
                ],
            },
            chartSeries: [],
        };
    },
    mounted() {
        this.updateChart();

        setInterval(() => {
            this.getSales();
        }, 5000);
    },
    methods: {
        async getSales() {
            try {
                const res = await this.$server.get(this.$page.props.route, {
                    start: this.start,
                    end: this.end,
                });

                this.sales = res.data;
                this.updateChart();
            } catch (error) {
                console.error(error);
            }
        },
        updateChart() {
            const sales = this.sales.values || [];

            const grouped = sales.reduce((acc, item) => {
                const month = dayjs(item.created_at).format("YYYY-MM");
                if (!acc[month]) {
                    acc[month] = { count: 0, total: 0 };
                }
                acc[month].count += 1;
                acc[month].total += parseFloat(
                    item.total * (item.partner_commission_rate / 100) || 0
                );
                return acc;
            }, {});

            const categories = Object.keys(grouped).sort();
            const transactionData = categories.map((m) => grouped[m].count);
            const totalAmountData = categories.map((m) =>
                grouped[m].total.toFixed(2)
            );

            this.chartOptions.xaxis.categories = categories;
            this.chartSeries = [
                {
                    name: "Transactions",
                    type: "column",
                    data: transactionData,
                },
                {
                    name: "Total Amount",
                    type: "line",
                    data: totalAmountData,
                },
            ];
        },
    },
};
</script>
