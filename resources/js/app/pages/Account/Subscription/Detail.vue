<template>
    <div>
        <q-btn
            @click="dialog = true"
            color="positive"
            outline
            icon="mdi-eye-arrow-right"
            size="md"
        />

        <q-dialog v-model="dialog" persistent full-width>
            <q-card class="q-pa-md" style="min-width: 600px; max-width: 90vw">
                <q-card-section class="bg-primary text-white">
                    <div class="text-h6">
                        <q-icon
                            name="mdi-information-outline"
                            class="q-mr-sm"
                        />
                        Product Details
                    </div>
                </q-card-section>

                <q-separator />

                <!-- General data -->
                <q-card-section class="q-gutter-md">
                    <div class="text-subtitle1 text-bold text-primary q-mb-sm">
                        Plan Information
                    </div>

                    <div>
                        <q-icon
                            name="mdi-package-variant"
                            class="q-mr-sm text-primary"
                        />
                        <strong>Plan Name:</strong> {{ item.meta.name }}
                    </div>
                    <div>
                        <q-icon name="mdi-text" class="q-mr-sm text-primary" />
                        <strong>Description:</strong>
                        {{ item.meta.description }}
                    </div>
                    <div>
                        <q-icon
                            name="mdi-cash-sync"
                            class="q-mr-sm text-primary"
                        />
                        <strong>Transaction code:</strong>
                        {{ item.transaction.code }}
                        {{ item.transaction.currency }}
                    </div>
                    <div>
                        <q-icon
                            name="mdi-currency-usd"
                            class="q-mr-sm text-primary"
                        />
                        <strong>Price:</strong> {{ item.transaction.total }}
                        {{ item.transaction.currency }}
                    </div>
                    <div>
                        <q-icon
                            name="mdi-calendar-clock"
                            class="q-mr-sm text-primary"
                        />
                        <strong>Billing Period:</strong>
                        {{ item.transaction.billing_period }}
                    </div>
                    <div>
                        <q-icon
                            name="mdi-credit-card-outline"
                            class="q-mr-sm text-primary"
                        />
                        <strong>Payment Method:</strong>
                        {{ item.transaction.payment_method }}
                    </div>
                    <div>
                        <q-icon
                            name="mdi-calendar-start"
                            class="q-mr-sm text-primary"
                        />
                        <strong>Start Date:</strong> {{ item.start_at }}
                    </div>
                    <div>
                        <q-icon
                            name="mdi-calendar-end"
                            class="q-mr-sm text-primary"
                        />
                        <strong>End Date:</strong> {{ item.end_at }}
                    </div>

                    <div v-if="item.last_renewal_at">
                        <q-icon
                            name="mdi-calendar-end"
                            class="q-mr-sm text-primary"
                        />
                        <strong>Last renewal:</strong>
                        {{ item.last_renewal_at }}
                    </div>
                    <div v-if="item.cancellation_at">
                        <q-icon
                            name="mdi-calendar-end"
                            class="q-mr-sm text-primary"
                        />
                        <strong>Cancellation :</strong>
                        {{ item.cancellation_at }}
                    </div>

                    <div>
                        <q-icon
                            name="mdi-check-circle-outline"
                            class="q-mr-sm text-primary"
                        />
                        <strong>Status:</strong>
                        <q-badge
                            color="green"
                            v-if="item.status === 'successful'"
                            class="q-ml-sm"
                        >
                            {{ item.status }}
                        </q-badge>
                        <q-badge color="red" v-else class="q-ml-sm">
                            {{ item.status }}
                        </q-badge>
                    </div>
                    <div>
                        <q-icon
                            name="mdi-refresh-auto"
                            class="q-mr-sm text-primary"
                        />
                        <strong>Recurring:</strong>
                        <q-icon
                            :name="
                                item.is_recurring ? 'mdi-check' : 'mdi-close'
                            "
                            :color="item.is_recurring ? 'positive' : 'negative'"
                        />
                    </div>
                </q-card-section>

                <q-separator class="q-my-md" />

                <q-card-section>
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
                </q-card-section>

                <q-separator class="q-my-md" />

                <q-card-section>
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
                                <!-- Encabezado de estado y método -->
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

                                <!-- Información de la transacción -->
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

                                <!-- Información técnica -->
                                <div class="q-mb-xs">
                                    <q-icon name="mdi-link" class="q-mr-sm" />
                                    <strong>Session ID:</strong>
                                    {{ tx.session_id }}
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
                                            tx.status === 'pending' &&
                                            tx.payment_url
                                        "
                                        :href="tx.payment_url"
                                        target="_blank"
                                        label="Pay now"
                                        icon="mdi-credit-card"
                                        size="sm"
                                        color="positive"
                                        outline
                                    />
                                    <v-cancel
                                        v-if="tx.status === 'pending'"
                                        :item="tx"
                                        @canceled="emitEvent"
                                    />
                                </div>
                            </q-item-section>
                        </q-item>
                    </q-list>
                </q-card-section>

                <q-card-section>
                    <v-subscription
                        :period="item.meta.price"
                        :plan="item"
                        label="Extend or renew package"
                        :buy="false"
                        :package="item"
                    />
                </q-card-section>

                <q-card-actions align="right">
                    <q-btn flat label="Close" color="primary" v-close-popup />
                </q-card-actions>
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
        };
    },

    methods: {
        emitEvent() {
            this.emit("reload");
        },
    },
};
</script>
