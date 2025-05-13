<template>
    <v-guest-layout>
        <template #title>{{ $page.props.app_name }}</template>

        <template #body>
            <q-page padding>
                <div class="plans-grid-container q-pt-xl q-px-md">
                    <div class="plans-grid">
                        <q-card
                            v-for="plan in plans"
                            :key="plan.id"
                            class="plan-card"
                            bordered
                            flat
                        >
                            <q-card-section class="plan-header">
                                <div class="text-h5 text-bold text-center">
                                    {{ plan.name }}
                                    <q-badge
                                        v-if="plan.bonus_enabled"
                                        color="amber"
                                        class="q-ml-sm"
                                        floating
                                    >
                                        +{{ plan.bonus_duration }} days
                                    </q-badge>
                                </div>
                            </q-card-section>

                            <q-separator />

                            <q-card-section>
                                <div
                                    class="text-subtitle2 text-center text-deep-purple q-mb-sm"
                                >
                                    Included Services
                                </div>
                                <div class="q-mb-md text-center">
                                    <q-chip
                                        v-for="(item, i) in plan.scopes"
                                        :key="i"
                                        icon="check"
                                        color="green-3"
                                        text-color="green-10"
                                        class="q-ma-xs"
                                        outline
                                        dense
                                    >
                                        {{ item.service.name }} ({{
                                            item.role.name
                                        }})
                                    </q-chip>
                                </div>
                            </q-card-section>

                            <q-separator />

                            <q-card-section>
                                <div
                                    class="text-subtitle2 text-center text-primary q-mb-sm"
                                >
                                    Available Prices
                                </div>
                                <q-list bordered separator>
                                    <q-item
                                        v-for="(price, index) in plan.prices"
                                        :key="index"
                                        class="q-mt-xs"
                                    >
                                        <q-item-section side>
                                            <q-badge color="blue-8" outline>
                                                {{ price.billing_period }}
                                            </q-badge>
                                        </q-item-section>

                                        <q-item-section>
                                            <div class="text-weight-bold">
                                                {{ price.currency }}
                                                {{ price.amount_format }}
                                            </div>
                                            <div class="text-caption text-grey">
                                                Expires:
                                                {{ price.expiration || "—" }}
                                            </div>
                                        </q-item-section>

                                        <q-item-section side>
                                            <q-radio
                                                :val="plan"
                                                v-model="selected_plan"
                                                color="deep-orange"
                                                @click="selectPlan(price)"
                                            />
                                        </q-item-section>
                                    </q-item>
                                </q-list>
                            </q-card-section>
                        </q-card>
                    </div>
                </div>

                <div class="row justify-center q-mt-xl">
                    <q-pagination
                        v-model="search.page"
                        color="primary"
                        :max="pages.total_pages"
                        :max-pages="6"
                        boundary-numbers
                        direction-links
                        unelevated
                        input
                    />
                </div>

                <v-subscription
                    :period="selected_period"
                    :plan="selected_plan"
                />
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
            dialogPlan: null,
            search: {
                page: 1,
                per_page: 12,
            },
            pages: {
                total_pages: 0,
            },
        };
    },

    mounted() {
        const values = this.$page.props.plans;
        this.plans = values.data;
        this.pages = values.meta.pagination;
    },

    watch: {
        "search.page": "getPlans",
    },

    methods: {
        showDescription(plan) {
            this.dialogPlan = plan;
            this.descriptionDialog = true;
        },

        selectPlan(price) {
            this.selected_period = price;
        },

        async getPlans() {
            try {
                const res = await this.$server.get("/api/public/plans", {
                    params: this.search,
                });

                if (res.status === 200) {
                    this.plans = res.data.data;
                    this.pages = res.data.meta.pagination;
                }
            } catch (error) {
                this.$q.notify({
                    message: "Error cargando los planes.",
                    type: "negative",
                });
            }
        },
    },
};
</script>
<style scoped>
.plans-grid-container {
    width: 100%;
    margin: 0 auto;
}

.plans-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 2rem;
}

.plan-card {
    border-radius: 20px;
    background: white;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
}

.plan-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 25px rgba(0, 0, 0, 0.08);
}

.plan-header {
    background: linear-gradient(145deg, #5c6bc0, #3949ab);
    color: white;
    border-top-left-radius: 20px;
    border-top-right-radius: 20px;
    padding: 1.2rem;
}
</style>
