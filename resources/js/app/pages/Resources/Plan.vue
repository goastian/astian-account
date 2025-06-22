<template>
    <v-guest-layout>
        <template #title>{{ $page.props.app_name }}</template>

        <template #body>
            <q-page class="q-pa-xl">
                <div class="text-center q-mb-xl">
                    <div class="text-h3 text-weight-bold text-primary">
                        Choose Your Plan
                    </div>
                    <div class="text-subtitle1 text-grey q-mt-sm">
                        Select the perfect plan for your needs
                    </div>
                </div>

                <div class="plans-grid-container">
                    <div class="row q-col-gutter-xl">
                        <div
                            v-for="plan in plans"
                            :key="plan.id"
                            class="col-12 col-sm-6 col-md-4 col-lg-3"
                        >
                            <q-card class="plan-card" flat>
                                <div class="plan-header">
                                    <div
                                        class="text-h5 text-weight-bold text-white text-center q-pt-lg"
                                    >
                                        {{ plan.name }}
                                        <q-badge
                                            v-if="plan.bonus_enabled"
                                            color="warning"
                                            text-color="dark"
                                            class="q-ml-sm"
                                            floating
                                        >
                                            +{{ plan.bonus_duration }} days
                                        </q-badge>
                                    </div>
                                    <div
                                        class="text-subtitle2 text-white text-center q-pb-sm"
                                    >
                                        {{ plan.tagline }}
                                    </div>
                                </div>

                                <q-card-section class="q-pt-lg">
                                    <div
                                        class="text-body1 text-grey-8 q-mb-md"
                                        v-html="plan.description"
                                    ></div>
                                </q-card-section>

                                <q-separator
                                    class="q-mx-auto"
                                    style="width: 80%"
                                />

                                <q-card-section>
                                    <div
                                        class="text-subtitle1 text-center text-primary q-mb-md text-weight-medium"
                                    >
                                        Pricing Options
                                    </div>
                                    <q-list
                                        bordered
                                        separator
                                        class="rounded-borders"
                                    >
                                        <q-item
                                            v-for="(
                                                price, index
                                            ) in plan.prices"
                                            :key="index"
                                            class="q-my-xs price-item rounded-borders"
                                            clickable
                                            v-ripple
                                            @click="
                                                selected_period = price;
                                                selectPlan(plan);
                                            "
                                        >
                                            <q-item-section side>
                                                <q-badge color="primary">
                                                    {{ price.billing_period }}
                                                </q-badge>
                                            </q-item-section>

                                            <q-item-section>
                                                <div
                                                    class="text-weight-bold text-body1"
                                                >
                                                    {{ price.currency }}
                                                    {{ price.amount_format }}
                                                </div>
                                                <div
                                                    class="text-caption text-grey"
                                                >
                                                    <q-icon
                                                        name="event"
                                                        size="14px"
                                                        class="q-mr-xs"
                                                    />
                                                    {{
                                                        formatDate(
                                                            price.expiration
                                                        )
                                                    }}
                                                </div>
                                            </q-item-section>

                                            <q-item-section side>
                                                <q-radio
                                                    :val="price"
                                                    v-model="selected_period"
                                                    color="primary"
                                                    @click="selectPlan(plan)"
                                                />
                                            </q-item-section>
                                        </q-item>
                                    </q-list>
                                </q-card-section>
                            </q-card>
                        </div>
                    </div>
                </div>

                <div class="row justify-center q-my-xl">
                    <q-pagination
                        v-model="search.page"
                        color="primary"
                        :max="pages.total_pages"
                        :max-pages="6"
                        boundary-numbers
                        direction-links
                        unelevated
                        input
                        class="q-mt-xl"
                    />
                </div>

                <!-- Sidebar para suscripción -->
                <q-drawer
                    v-model="showSidebar"
                    side="right"
                    :width="450"
                    bordered
                    overlay
                    class="drawer-container"
                    behavior="mobile"
                >
                    <div class="drawer-header">
                        <div class="text-h5 text-weight-bold">
                            Complete Subscription
                        </div>
                        <q-btn
                            icon="close"
                            color="grey"
                            round
                            flat
                            dense
                            class="absolute-top-right q-ma-sm"
                            @click="showSidebar = false"
                        />
                    </div>

                    <v-subscription
                        :plan="selected_plan"
                        :period="selected_period"
                        @close="showSidebar = false"
                    />
                </q-drawer>
            </q-page>
        </template>
    </v-guest-layout>
</template>

<script>
export default {
    data() {
        return {
            plans: [],
            selected_plan: null,
            selected_period: null,
            showSidebar: false,
            search: {
                page: 1,
                per_page: 100,
            },
            pages: {
                total_pages: 0,
            },
        };
    },

    mounted() {
        this.getPlans();
    },

    methods: {
        formatDate(date) {
            if (!date) return "No expiration";
            return new Date(date).toLocaleDateString("en-US", {
                year: "numeric",
                month: "short",
                day: "numeric",
            });
        },

        selectPlan(plan) {
            if (!this.selected_period) {
                this.$q.notify({
                    message: "Please select a pricing option first",
                    color: "warning",
                    icon: "warning",
                    position: "top",
                });
                return;
            }
            this.selected_plan = plan;
            this.showSidebar = true;
        },

        async getPlans() {
            const query = new URLSearchParams(window.location.search);
            const query_data = {};
            for (const [key, value] of query.entries()) {
                query_data[key] = value;
            }
            Object.assign(this.search, query_data);

            try {
                const res = await this.$server.get(this.$page.props.route, {
                    params: this.search,
                });

                if (res.status === 200) {
                    this.plans = res.data.data;
                    this.pages = res.data.meta.pagination;
                }
            } catch (error) {
                this.$q.notify({
                    message: "Failed to load plans",
                    type: "negative",
                });
            }
        },
    },
};
</script>

<style scoped>
.plans-grid-container {
    max-width: 1400px;
    margin: 0 auto;
}

.plan-card {
    border-radius: 12px;
    background: var(--q-card);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    height: 100%;
    display: flex;
    flex-direction: column;
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.plan-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
    border-color: var(--q-primary);
}

.plan-header {
    background: var(--q-primary);
    color: white;
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
    padding: 1rem;
}

.price-item {
    transition: all 0.2s ease;
    border-radius: 8px;
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.price-item:hover {
    background-color: var(--q-primary-light) !important;
    border-color: var(--q-primary);
    transform: scale(1.01);
}

.drawer-container {
    background: var(--q-page);
}

.drawer-header {
    background: var(--q-card);
    padding: 20px;
    border-bottom: 1px solid var(--q-separator);
    position: sticky;
    top: 0;
    z-index: 1;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

@media (max-width: 600px) {
    .plans-grid-container {
        padding: 0 8px;
    }

    .plan-card {
        border-radius: 8px;
    }
}
</style>
