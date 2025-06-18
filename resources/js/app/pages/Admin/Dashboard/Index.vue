<template>
    <v-admin-layout>
        <q-page class="q-pa-md">
            <div class="row">
                <q-card class="col-xs-12 col-sm-6 col-md-3" bordered flat v-for="card in cards" :key="card.label">
                    <q-card-section class="row items-center q-py-sm">
                        <q-icon :name="card.icon" size="32px" color="primary" />
                        <div class="q-ml-md">
                            <div class="text-h6">{{ card.value }}</div>
                            <div class="text-caption text-grey">
                                {{ card.label }}
                            </div>
                        </div>
                    </q-card-section>
                </q-card>
            </div>

            <q-separator class="q-my-lg" />
            <div class="row q-col-gutter-sm">
                <div class="col">
                    <q-input v-model="params.start" type="date" label="Start date" dense outlined />
                </div>
                <div class="col">
                    <q-input v-model="params.end" type="date" label="End date" dense outlined />
                </div>
                <div class="col">
                    <q-select v-model="chartType" :options="chartTypes" label="Chart type" dense outlined />
                </div>
                <div class="col">
                    <q-select v-model="params.type" :options="types" label="Date" dense outlined />
                </div>
                <div class="col-auto flex items-end">
                    <q-btn label="Filter" @click="getData" color="primary" />
                </div>
            </div>

            <div class="row q-col-gutter-md q-mt-lg">
                <div class="col-12 col-md-8">
                    <q-card flat bordered>
                        <q-card-section>
                            <apex-charts width="100%" height="400" type="line" :options="chartOptions"
                                :series="chartSeries" class="q-mt-md" />
                        </q-card-section>
                    </q-card>
                </div>

                <div class="col-12 col-md-4">
                    <q-card flat bordered>
                        <q-card-section>
                            <div class="text-subtitle1 text-weight-medium q-mb-sm">
                                Last {{ last_users.length }} users
                            </div>
                            <q-list dense>
                                <q-item v-for="user in last_users" :key="user.id" clickable>
                                    <q-item-section>
                                        <q-item-label>{{
                                            user.name
                                            }}</q-item-label>
                                        <q-item-label caption>{{
                                            user.email
                                            }}</q-item-label>
                                    </q-item-section>
                                    <q-item-section side>
                                        <q-badge outline color="primary" :label="formatDate(user.created_at)" />
                                    </q-item-section>
                                </q-item>
                            </q-list>
                        </q-card-section>
                    </q-card>
                </div>
            </div>
        </q-page>
    </v-admin-layout>
</template>

<script>
import ApexCharts from "vue3-apexcharts";

export default {
    components: {
        ApexCharts,
    },

    data() {
        return {
            params: {
                start: null,
                end: null,
                type: "month",
            },
            chartType: "line",
            chartTypes: ["bar", "line", "area"],
            types: ["day", "month", "year"],
            users_by_month: [],
            last_users: [],
            groups: 0,
            roles: 0,
            services: 0,
            users: 0,
            channels: 0,
            plans: 0,
            cards: [],
            chartSeries: [],
            chartOptions: {},
        };
    },

    watch: {
        "params.type"(value) {
            this.getData();
        },

        chartType(value) {
            this.renderChart();
        },
    },

    created() {

        if (!this.params.start || !this.params.end) {
            const { start, end } = this.getDefaultDates();
            this.params.start = start;
            this.params.end = end;
        }


        this.getData()
    },

    mounted() {
        setInterval(() => {
            this.getData();
        }, 10000);
    },

    methods: {
        getDefaultDates() {
            const today = new Date();
            const end = today.toISOString().split("T")[0];

            const pastDate = new Date(today);
            pastDate.setMonth(today.getMonth() - 6);
            const start = pastDate.toISOString().split("T")[0];

            return { start, end };
        },

        loadData(data) {
            this.users_by_month = data["users_by_month"];
            this.last_users = data["last_users"];

            this.groups = data["groups"];
            this.roles = data["roles"];
            this.services = data["services"];
            this.users = data["users"];
            this.channels = data["channels"];
            this.plans = data["plans"];

            this.cards = [
                { label: "Groups", value: this.groups, icon: "groups" },
                {
                    label: "Roles",
                    value: this.roles,
                    icon: "admin_panel_settings",
                },
                { label: "Users", value: this.users, icon: "person" },
                { label: "Services", value: this.services, icon: "build" },
                { label: "Channels", value: this.channels, icon: "cast" },
                { label: "Plans", value: this.plans, icon: "paid" },
            ];

            this.renderChart();
        },

        async getData() {
            try {
                const res = await this.$server.get(this.$page.props.route, {
                    params: this.params,
                });

                if (res.status == 200) {
                    this.loadData(res.data.data);
                }
            } catch (error) { }
        },

        renderChart() {
            this.chartSeries = [
                {
                    name: "Users",
                    data: this.users_by_month.map((item) => item.total),
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
                    text: `Users by ${this.params.type}`,
                    align: "left",
                },
                grid: {
                    row: {
                        colors: ["#f3f3f3", "transparent"],
                        opacity: 0.5,
                    },
                },
                xaxis: {
                    categories: this.users_by_month.map((item) => item.month),
                },
            };
        },

        formatDate(dateStr) {
            const options = { year: "numeric", month: "short", day: "numeric" };
            return new Date(dateStr).toLocaleDateString("es-ES", options);
        },
    },
};
</script>

<style scoped>
.text-h6 {
    font-weight: 600;
}
</style>
