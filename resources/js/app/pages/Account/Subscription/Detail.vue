<template>
    <div>
        <button
            @click="dialog = true"
            class="btn"
        >View Details</button>

        <q-dialog v-model="dialog" persistent>
            <q-card class="q-pa-md dialog">
                <!-- Encabezado -->
                <section class="bg-primary text-white row justify-between q-pa-md">
                    <div class="text-h6 flex items-center">
                        <q-icon
                            name="mdi-information-outline"
                            class="q-mr-sm"
                        />
                        Product Details
                    </div>
                    <button v-close-popup>
                        <q-icon name="mdi-close" />
                    </button>
                </section>

                <section class="nav row no-wrap">
                    <button
                        v-for="(i, index) in navs"
                        class="item-nav"
                        @click="selectNav(i.id)"
                        :class="{'active' : i.id == activeNav}"
                    >{{ i.name }}</button>
                </section>

                <!-- Información del plan -->
                <q-card-section class="q-gutter-md" v-if="activeNav == 1">
                    <div class="text-subtitle1 text-bold text-primary">
                        Plan Information
                    </div>

                    <q-list dense>
                        <q-item>
                            <q-item-section avatar>
                                <q-icon
                                    name="mdi-package-variant"
                                    color="primary"
                                />
                            </q-item-section>
                            <q-item-section>
                                <q-item-label
                                    ><strong>Plan Name:</strong>
                                    {{ item.meta.name }}</q-item-label
                                >
                            </q-item-section>
                        </q-item>

                        <q-item>
                            <q-item-section avatar>
                                <q-icon name="mdi-cash-sync" color="primary" />
                            </q-item-section>
                            <q-item-section>
                                <q-item-label>
                                    <strong>Transaction code:</strong>
                                    {{ item.transaction.code }}
                                </q-item-label>
                            </q-item-section>
                        </q-item>

                        <q-item>
                            <q-item-section avatar>
                                <q-icon
                                    name="mdi-currency-usd"
                                    color="primary"
                                />
                            </q-item-section>
                            <q-item-section>
                                <q-item-label>
                                    <strong>Price:</strong>
                                    {{ item.transaction.total }}
                                    {{ item.transaction.currency }}
                                </q-item-label>
                            </q-item-section>
                        </q-item>

                        <q-item>
                            <q-item-section avatar>
                                <q-icon
                                    name="mdi-calendar-clock"
                                    color="primary"
                                />
                            </q-item-section>
                            <q-item-section>
                                <q-item-label>
                                    <strong>Billing Period:</strong>
                                    {{ item.transaction.billing_period }}
                                </q-item-label>
                            </q-item-section>
                        </q-item>

                        <q-item>
                            <q-item-section avatar>
                                <q-icon
                                    name="mdi-credit-card-outline"
                                    color="primary"
                                />
                            </q-item-section>
                            <q-item-section>
                                <q-item-label>
                                    <strong>Payment Method:</strong>
                                    {{ item.transaction.payment_method }}
                                </q-item-label>
                            </q-item-section>
                        </q-item>

                        <q-item>
                            <q-item-section avatar>
                                <q-icon
                                    name="mdi-calendar-start"
                                    color="primary"
                                />
                            </q-item-section>
                            <q-item-section>
                                <q-item-label>
                                    <strong>Start Date:</strong>
                                    {{ item.start_at }}
                                </q-item-label>
                            </q-item-section>
                        </q-item>

                        <q-item>
                            <q-item-section avatar>
                                <q-icon
                                    name="mdi-calendar-end"
                                    color="primary"
                                />
                            </q-item-section>
                            <q-item-section>
                                <q-item-label>
                                    <strong>End Date:</strong> {{ item.end_at }}
                                </q-item-label>
                            </q-item-section>
                        </q-item>

                        <q-item v-if="item.last_renewal_at">
                            <q-item-section avatar>
                                <q-icon
                                    name="mdi-calendar-refresh"
                                    color="primary"
                                />
                            </q-item-section>
                            <q-item-section>
                                <q-item-label>
                                    <strong>Last Renewal:</strong>
                                    {{ item.last_renewal_at }}
                                </q-item-label>
                            </q-item-section>
                        </q-item>

                        <q-item v-if="item.cancellation_at">
                            <q-item-section avatar>
                                <q-icon
                                    name="mdi-calendar-remove"
                                    color="primary"
                                />
                            </q-item-section>
                            <q-item-section>
                                <q-item-label>
                                    <strong>Cancellation:</strong>
                                    {{ item.cancellation_at }}
                                </q-item-label>
                            </q-item-section>
                        </q-item>

                        <q-item>
                            <q-item-section avatar>
                                <q-icon
                                    name="mdi-check-circle-outline"
                                    color="primary"
                                />
                            </q-item-section>
                            <q-item-section>
                                <q-item-label>
                                    <strong>Status:</strong>
                                    <q-badge
                                        :color="
                                            item.status === 'successful'
                                                ? 'green'
                                                : 'red'
                                        "
                                        class="q-ml-sm"
                                    >
                                        {{ item.status }}
                                    </q-badge>
                                </q-item-label>
                            </q-item-section>
                        </q-item>

                        <q-item>
                            <q-item-section avatar>
                                <q-icon
                                    name="mdi-refresh-auto"
                                    color="primary"
                                />
                            </q-item-section>
                            <q-item-section>
                                <q-item-label>
                                    <strong>Recurring:</strong>
                                    <q-icon
                                        :name="
                                            item.is_recurring
                                                ? 'mdi-check'
                                                : 'mdi-close'
                                        "
                                        :color="
                                            item.is_recurring
                                                ? 'positive'
                                                : 'negative'
                                        "
                                    />
                                </q-item-label>
                            </q-item-section>
                        </q-item>
                    </q-list>
                </q-card-section>

                <q-separator class="q-my-md" />

                <!-- Servicios incluidos -->
                <section v-if="activeNav == 3">
                    <div class="text-subtitle1 text-bold text-primary q-mb-sm">
                        Included Services
                    </div>
                    <q-list bordered>
                        <q-item
                            v-for="scope in item.meta.scopes"
                            :key="scope.id"
                            class="q-px-sm"
                        >
                            <q-item-section>
                                <div>
                                    <strong>{{ scope.role.name }}</strong> –
                                    {{ scope.role.description }}
                                </div>
                                <div class="text-caption text-grey">
                                    Service: {{ scope.service.name }}
                                </div>
                            </q-item-section>
                        </q-item>
                    </q-list>
                    <span v-html="item.meta.description"></span>
                </section>

                <!-- Transacciones -->
                <section v-if="activeNav == 2">
                    <div class="text-subtitle1 text-bold text-primary q-mb-sm">
                        Transactions
                    </div>

                    <q-list bordered separator>
                        <q-item
                            v-for="(tx, index) in item.transactions"
                            :key="index"
                            class="q-pa-md"
                        >
                            <q-item-section>
                                <div
                                    class="row items-center justify-between q-mb-sm"
                                >
                                    <div>
                                        <q-badge
                                            :color="
                                                tx.status === 'successful'
                                                    ? 'green'
                                                    : tx.status === 'pending'
                                                    ? 'orange'
                                                    : 'red'
                                            "
                                            class="q-mr-sm"
                                        >
                                            <q-icon
                                                :name="
                                                    tx.status === 'successful'
                                                        ? 'mdi-check-circle'
                                                        : tx.status ===
                                                          'pending'
                                                        ? 'mdi-timer-sand'
                                                        : 'mdi-close-circle-outline'
                                                "
                                                class="q-mr-xs"
                                            />
                                            {{ tx.status }}
                                        </q-badge>
                                        <q-badge
                                            outline
                                            color="primary"
                                            class="q-ml-sm"
                                        >
                                            {{ tx.payment_method }}
                                        </q-badge>
                                    </div>
                                    <div class="text-grey text-caption">
                                        <q-icon
                                            name="mdi-calendar"
                                            class="q-mr-xs"
                                        />
                                        {{ tx.created }}
                                    </div>
                                </div>

                                <q-separator spaced />

                                <div class="q-mb-xs">
                                    <q-icon name="mdi-pound" class="q-mr-sm" />
                                    <strong>Transaction Code:</strong>
                                    {{ tx.code }}
                                </div>
                                <div class="q-mb-xs">
                                    <q-icon name="mdi-cash" class="q-mr-sm" />
                                    <strong>Subtotal:</strong>
                                    {{ tx.subtotal }} {{ tx.currency }}
                                </div>
                                <div class="q-mb-xs" v-if="tx.tax_applied">
                                    <q-icon
                                        name="mdi-percent"
                                        class="q-mr-sm"
                                    />
                                    <strong>Tax:</strong>
                                    {{ tx.tax_percentage }}%
                                    <span v-if="tx.tax_amount"
                                        >({{ tx.tax_amount }})</span
                                    >
                                    <q-badge
                                        color="grey"
                                        v-if="tx.tax_inclusive"
                                        class="q-ml-sm"
                                        >Inclusive</q-badge
                                    >
                                </div>
                                <div class="q-mb-xs">
                                    <q-icon
                                        name="mdi-cash-multiple"
                                        class="q-mr-sm"
                                    />
                                    <strong>Total:</strong> {{ tx.total }}
                                    {{ tx.currency }}
                                </div>

                                <q-separator spaced />

                                <div class="q-mb-xs">
                                    <q-icon name="mdi-link" class="q-mr-sm" />
                                    <strong>Session ID:</strong>
                                    <span class="session-id">{{ tx.session_id }}</span>
                                </div>
                                <div class="q-mb-xs">
                                    <q-icon
                                        name="mdi-fingerprint"
                                        class="q-mr-sm"
                                    />
                                    <strong>Payment Intent:</strong>
                                    {{ tx.payment_intent_id }}
                                </div>
                                <div class="q-mb-xs" v-if="tx.billing_period">
                                    <q-icon
                                        name="mdi-calendar-range"
                                        class="q-mr-sm"
                                    />
                                    <strong>Billing Period:</strong>
                                    {{ tx.billing_period }}
                                </div>
                                <div class="q-mb-xs">
                                    <q-icon
                                        name="mdi-refresh"
                                        class="q-mr-sm"
                                    />
                                    <strong>Renewal:</strong>
                                    {{ tx.renew ? "Yes" : "No" }}
                                </div>
                                <div class="q-mb-xs">
                                    <q-icon
                                        name="mdi-calendar-plus"
                                        class="q-mr-sm"
                                    />
                                    <strong>Created:</strong> {{ tx.created }}
                                </div>
                                <div class="q-mb-xs">
                                    <q-icon
                                        name="mdi-calendar-edit"
                                        class="q-mr-sm"
                                    />
                                    <strong>Updated:</strong> {{ tx.updated }}
                                </div>

                                <div class="q-mt-md row q-gutter-sm">
                                    <q-btn
                                        v-if="
                                            tx.status === 'successful' &&
                                            tx.payment_url
                                        "
                                        :href="tx.payment_url"
                                        target="_blank"
                                        label="Invoice"
                                        icon="mdi-credit-card"
                                        size="sm"
                                        color="positive"
                                        outline
                                    />
                                    <v-cancel
                                        v-if="tx.status === 'pending'"
                                        :item="tx"
                                        @success="emitEvent"
                                    />
                                </div>
                            </q-item-section>
                        </q-item>
                    </q-list>
                </section>

                <!-- Botón para renovar -->
                <section v-if="activeNav == 4">
                    <v-subscription
                        :period="item.meta.price"
                        :plan="item"
                        label="Extend or renew package"
                        :buy="false"
                        :package="item"
                    />
                </section>
            </q-card>
        </q-dialog>
    </div>
</template>

<script>
import VCancel from "./Cancel.vue";

export default {
    emits: ["reload"],

    components: {
        VCancel,
    },

    props: {
        item: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            dialog: false,
            navs: [
                {
                    id: 1,
                    name: 'Details'
                },
                {
                    id: 3,
                    name: 'Features'
                },
                {
                    id: 2,
                    name: 'History'
                },
                {
                    id: 4,
                    name: 'Actions'
                }
            ],
            activeNav: 1,
        };
    },

    methods: {
        emitEvent() {
            this.$emit("reload");
        },
        selectNav(id) {
            this.activeNav = id;
        }
    },
};
</script>

<style scoped>
.dialog {
    width: 500px;
}

.card-main {
    width: 500px;
    gap: 1rem;
    padding: 1.5rem;
    background-color: var(--q-background-primary);
}

.btn {
    padding: .5rem 1.5rem;
    color: var(--q-color);
    border-radius: .4rem;
    border: 1px solid var(--q-border);
}

.btn:hover {
    background-color: var(--q-color-blue-light);
}

.title {
    font-size: 1.5rem;
    color: var(--q-color);
    line-height: 1.5rem;
}

.nav {
    background-color: var(--q-background-secondary);
    padding: .3rem;
    border-radius: .4rem;
    gap: .4rem;
}

.nav > .item-nav {
    width: 100%;
    padding: .4rem 1rem;
    border-radius: .3rem;
    color: var(--q-color-secondary);
}

.nav > .item-nav.active {
    background-color: var(--q-background-primary);
    color: var(--q-color);
}

.card-footer {
    background-color: red;
}

.session-id {
    display: inline-block;
    max-width: 300px;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
}
</style>
