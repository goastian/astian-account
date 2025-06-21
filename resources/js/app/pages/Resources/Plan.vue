<template>
    <v-guest-layout>
        <template #title>{{ $page.props.app_name }}</template>

        <template #body>
            <q-page padding>
                <div class="column padd">
                    <section class="column items-center q-gutter-y-lg">
                        <div class="column items-center">
                            <h2 class="text-title">Choose Your Plan</h2>
                            <span class="text-description">
                                Unlock the full potential of our digital ecosystem with subscription plans tailored to your needs
                            </span>
                        </div>
                        <div class="column items-center q-gutter-y-md">
                            <div class="row nav-period">
                                <template v-for="(item, index) in periods">
                                    <button
                                        :class="{'active' : params.billing_period == item.name }"
                                        @click="select_period(item.name)"
                                    >{{ item.name }}</button>
                                </template>
                            </div>
                        </div>
                    </section>
                    <div class="q-pa-md">
                        <div class="row q-col-gutter-md q-row-gutter-md">
                            <div
                                class="column col-12 col-sm-6 col-md-4 col-lg-3"
                                v-for="plan in plans"
                            >
                                <q-card class="card column">
                                    <section class="column items-center">
                                        <h3 class="text title">{{ plan.name}}</h3>
                                        <template
                                                v-for="(i, ind) in plan.prices"
                                        >
                                            <div
                                                class="row items-end"
                                                v-if="i.billing_period == params.billing_period"
                                            >
                                                <div class="price">
                                                    <span v-if="i.currency == 'USD'">$</span>
                                                    <span v-else>{{ i.currency }}</span>
                                                    <span class="text">{{ i.amount_format }}</span>
                                                </div>
                                                <span class="text-light">/{{ i.billing_period }}</span>
                                            </div>
                                        </template>
                                    </section>
                                    <div class="description">
                                        <div v-html="plan.description" class="column items-start justify-center"></div>
                                    </div>
                                    <div class="card-footer">
                                        <v-suscribe :plan="plan" :period="params.billing_period" />
                                    </div>
                                </q-card>
                            </div>
                            <!--
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
                            -->
                        </div>
                    </div>

                    <div class="row justify-center">
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
                </div>
            </q-page>
        </template>
    </v-guest-layout>
</template>

<script>
import VSuscribe from './Suscribe.vue';
export default {
    components: {
        VSuscribe,
    },
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
            params: {
                billing_period: 'daily'
            },
            app: 'vpn',
            apps: [
                {
                    name: 'all',
                    label: '📦 All Apps'
                },
                {
                    name: 'vpn',
                    label: '🛡️ VPN',
                },
                {
                    name: 'calendar',
                    label: '📅 Calendar'
                },
                {
                    name: 'notes',
                    label: '📝 Notes',
                },
                {
                    name: 'cloud',
                    label: '☁️ Cloud'
                }
            ],
            periods: [
                {
                    name: 'daily',
                    label: 'Daily',
                },
                {
                    name: 'weekly',
                    label: 'Weekly'
                },
                {
                    name: 'biweekly',
                    label: 'Biweekly'
                },
                {
                    name: 'monthly',
                    label: 'Monthly',
                },
                {
                    name: 'yearly',
                    label: 'Yearly'
                }
            ]
        };
    },

    mounted() {
        this.getPlans();
    },

    watch: {
        "search.page": "getPlans",
    },

    methods: {
        select_period(period) {
            this.params.billing_period = period;
            this.plans = [];
            this.getPlans();
        },

        showDescription(plan) {
            this.dialogPlan = plan;
            this.descriptionDialog = true;
        },

        selectPlan(price) {
            this.selected_period = price;
        },

        async getPlans() {
            const query = new URLSearchParams(window.location.search);

            const query_data = {};
            for (const [key, value] of query.entries()) {
                query_data[key] = value;
            }

            Object.assign(this.search, query_data);

            const paramss = {
                ...this.search,
                ...this.params
            }

            try {
                const res = await this.$server.get(this.$page.props.route, {
                    params: paramss
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
    width: 100%;
}

.plans-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
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

.text-title {
    font-size: 3rem;
    font-weight: 700;
    color: var(--q-primary);
    text-align: center;
    font-family: system-ui;
}

.text-description {
    font-size: 1rem;
    font-weight: 500;
    color: var(--q-color-secondary);
    text-align: center;
    max-width: 600px;
}

button {
    padding: .5rem;
    border-radius: .6rem;
    color: white;
    font-size: 1rem;
}

.nav-period {
    background-color: var(--q-background-primary);
    padding: .4rem;
    border-radius: .4rem;
    gap: 1rem;
}

.nav-period > button {
    color: var(--q-color);
}

.nav-period > button.active {
    background-color: var(--q-primary);
    color: white;
    padding: .5rem 2rem;
}

.nav-apps {
    padding: .4rem;
    gap: 1rem;
}

.nav-apps > button {
    background-color: var(--q-background-primary);
    color: var(--q-color);
    padding: .5rem 1.2rem;
}

.nav-apps > button.active {
    background-color: var(--q-primary);
    color: white;
}

.container-card {
    gap: 1rem;
}

.card {
    background-color: var(--q-background-primary);
    border-radius: 1rem;
    padding: 2rem;
    gap: 1rem;
    box-sizing: border-box;
    height: 100%;
}

.text {
    color: var(--q-color);
}

.text-light {
    color: var(--q-color-secondary);
}

.title {
    font-size: 1.5rem;
    font-weight: 700;
    line-height: 2rem;
}

.price > span {
    font-size: 2.5rem;
    line-height: 2.5rem;
    font-weight: 700;
    font-family: system-ui;
    color: var(--q-color);
}

.description > div {
    margin: 0;
}

.card-footer > button {
    width: 100%;
    background-color: var(--q-primary);
    color: white;
}

.padd {
    padding: 1rem 0;
}
</style>
