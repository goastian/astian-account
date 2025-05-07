<template>
    <q-layout view="lHh Lpr lFf">
        <q-header elevated class="bg-primary text-white">
            <q-toolbar class="shadow-2">
                <q-toolbar-title>Available Plans</q-toolbar-title>
                <v-profile></v-profile>
            </q-toolbar>
        </q-header>

        <q-page-container>
            <q-page padding>
                <div class="row">
                    <div
                        v-for="plan in plans"
                        :key="plan.id"
                        class="col-12 col-md-4 col-md-3 q-gutter-sm q-pa-sm"
                    >
                        <q-card bordered class="q-hoverable shadow-2">
                            <q-card-section class="bg-grey-2">
                                <div class="text-h6">
                                    {{ plan.name }}
                                    <q-badge
                                        v-if="plan.bonus_enabled"
                                        color="green"
                                        class="q-ml-sm"
                                        align="top"
                                    >
                                        +{{ plan.bonus_duration }} days
                                    </q-badge>
                                </div>
                                <div class="text-subtitle2 text-grey-7">
                                    <span v-html="plan.description"></span>
                                </div>
                            </q-card-section>

                            <q-separator />

                            <q-card-section>
                                <div
                                    class="text-subtitle2 text-primary q-mb-sm"
                                >
                                    <q-icon name="vpn_key" class="q-mr-sm" />
                                    Included Services
                                </div>

                                <q-list
                                    dense
                                    bordered
                                    class="rounded-borders bg-grey-1"
                                >
                                    <q-item
                                        v-for="(item, i) in plan.scopes"
                                        :key="i"
                                    >
                                        <q-item-section>
                                            <div class="text-weight-medium">
                                                {{ item.service.name }}
                                            </div>
                                            <div
                                                class="text-caption text-grey-7"
                                            >
                                                Level : {{ item.role.name }}
                                            </div>
                                        </q-item-section>
                                    </q-item>
                                </q-list>
                            </q-card-section>

                            <q-separator />
                            <q-card-section>
                                <div
                                    class="text-subtitle2 text-primary q-mb-sm"
                                >
                                    <q-icon
                                        name="attach_money"
                                        class="q-mr-sm"
                                    />
                                    Available Prices
                                </div>

                                <q-list bordered separator>
                                    <q-item
                                        v-for="(item, index) in plan.prices"
                                        :key="index"
                                        class="q-my-xs"
                                    >
                                        <q-item-section side>
                                            <q-badge color="primary" outline>
                                                {{ item.billing_period }}
                                            </q-badge>
                                        </q-item-section>

                                        <q-item-section>
                                            <div>
                                                <span
                                                    class="text-weight-bold"
                                                    >{{ item.currency }}</span
                                                >
                                                <span class="q-ml-sm">{{
                                                    item.amount_format
                                                }}</span>
                                            </div>
                                            <div class="text-caption text-grey">
                                                Expires:
                                                {{ item.expiration || "—" }}
                                            </div>
                                        </q-item-section>

                                        <q-item-section side>
                                            <q-item tag="label" v-ripple>
                                                <q-item-section avatar>
                                                    <q-radio
                                                        :val="plan"
                                                        v-model="selected_plan"
                                                        color="orange"
                                                        @click="
                                                            selectPlan(item)
                                                        "
                                                    />
                                                </q-item-section>
                                            </q-item>
                                        </q-item-section>
                                    </q-item>
                                </q-list>
                            </q-card-section>
                        </q-card>
                    </div>
                </div>

                <div class="row justify-center q-mt-lg">
                    <q-pagination
                        v-model="search.page"
                        color="primary"
                        :max="pages.total_pages"
                        :max-pages="6"
                        boundary-numbers
                        direction-links
                    />
                </div>

                <v-subscription
                    :period="selected_period"
                    :plan="selected_plan"
                ></v-subscription>
            </q-page>
        </q-page-container>
    </q-layout>
</template>

<script>
export default {
    inject: ["$user"],
    data() {
        return {
            pages: {
                total_pages: 0,
            },
            search: {
                page: 1,
                per_page: 15,
            },
            plans: [],
            selected_period: null,
            selected_plan: null,
        };
    },

    created() {
        this.getPlans();
    },

    watch: {
        "search.page": "getPlans",
    },

    methods: {
        selectPlan(period) {
            this.selected_period = period;
        },

        async getPlans() {
            try {
                const res = await this.$server.get("/api/public/plans", {
                    params: this.search,
                });

                if (res.status == 200) {
                    this.plans = res.data.data;
                    this.pages = res.data.meta.pagination;
                }
            } catch (error) {
                this.$q.notify({
                    message: "Error loading plans.",
                    type: "negative",
                });
            }
        },
    },
};
</script>
