<template>
    <div>
        <button @click="dialog = true" class="btn open">Suscribe Now</button>

        <q-dialog v-model="dialog">
            <div class="row no-wrap card">
                <div class="column left no-wrap">
                    <h2 class="title strong">Orden Summary</h2>

                    <div class="column">
                        <span class="strong">{{ plan.name }}</span>
                        <template v-for="price in plan.prices">
                            <div
                                v-if="price.billing_period == period"
                                class="row items-end"
                            >
                                <span
                                    v-if="price.currency == 'USD'"
                                    class="price strong"
                                    >$</span
                                >
                                <span v-else>{{ price.currency }}</span>
                                <span class="price strong">{{
                                    price.amount_format
                                }}</span>
                                <span> /{{ period }}</span>
                            </div>
                        </template>
                    </div>

                    <q-separator />

                    <div class="column gap-05">
                        <span>Includes:</span>
                        <span v-html="plan.description"></span>
                    </div>

                    <q-separator />

                    <template v-for="price in plan.prices">
                        <div
                            class="column gap-05"
                            v-if="price.billing_period == period"
                        >
                            <div class="row justify-between">
                                <label>Subtotal</label>
                                <span>{{ price.amount_format }}</span>
                            </div>
                            <div class="row justify-between">
                                <label>Tax</label>
                                <span>0.00</span>
                            </div>
                            <div class="row justify-between">
                                <label class="strong">Total</label>
                                <span class="strong">{{
                                    price.amount_format
                                }}</span>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="right column no-wrap">
                    <h2 class="title strong color">Payment Details</h2>
                    <div class="column gap-1 color">
                        <h3 class="subtitle strong">Billing Cycle</h3>
                        <div class="row items-center">
                            <q-icon name="mdi-check" />
                            <span>{{ period }}</span>
                        </div>
                    </div>

                    <div class="column gap-1 color">
                        <div class="row">
                            <q-icon name="mdi-account" />
                            <h3 class="subtitle strong">Your Information</h3>
                        </div>
                        <div class="column">
                            <label>Name:</label>
                            <input :value="user.name" disabled />
                        </div>
                        <div class="column">
                            <label>Email:</label>
                            <input :value="user.email" disabled />
                        </div>
                    </div>

                    <div class="column gap-1 color">
                        <div class="row">
                            <q-icon name="mdi-credit-card" />
                            <h3 class="subtitle strong">Payment Method</h3>
                        </div>
                        <div class="row q-gutter-x-md">
                            <template v-for="method in methods">
                                <button
                                    class="btn column items-center justify-center"
                                    v-if="method.enable"
                                    :class="{ active: active == method.key }"
                                    @click="changeMethod(method.key)"
                                >
                                    <q-icon :name="method.icon" />
                                    <span>{{ method.name }}</span>
                                </button>
                            </template>
                        </div>
                    </div>

                    <div>
                        <button class="btn suscribe" @click="payment">
                            Suscribe Now
                        </button>
                    </div>
                </div>
                <button class="btn-close">
                    <q-icon name="mdi-close" @click="dialog = false" />
                </button>
            </div>
        </q-dialog>

        <v-login
            :guest="guest"
            @close="guest = false"
            :refer="getReferralLink()"
        />
    </div>
</template>

<script>
export default {
    data() {
        return {
            dialog: false,
            user: [],
            methods: [],
            active: null,
            guest: false,
        };
    },
    props: {
        plan: {
            type: String,
            required: true,
        },
        icon: {
            type: Object,
            required: true,
        },
        period: {
            type: String,
            require: true,
        },
    },
    created() {
        this.getBillingPeriod();
        this.user = this.$page.props.user;
    },

    methods: {
        async getBillingPeriod() {
            try {
                const res = await this.$server.get(
                    "/api/public/payments/methods"
                );

                if (res.status == 200) {
                    this.methods = res.data.data;
                }
            } catch (error) {}
        },

        updateUser(user) {
            this.user = user;
        },

        getReferralLink() {
            const params = new URLSearchParams(window.location.search);
            return params.get("referral_code");
        },

        async payment() {
            if (!this.user?.id) {
                this.guest = true;
                this.$q.notify({
                    type: "negative",
                    message: "Please login and try again",
                    timeout: 3000,
                });
                return;
            }

            if (!this.user?.id) {
                this.$q.notify({
                    type: "negative",
                    message: "Please select the plan to continue ...",
                    timeout: 3000,
                });
                return;
            }

            if (this.active == null) {
                this.$q.notify({
                    type: "negative",
                    message:
                        "Please select the payment method to continue. ...",
                    timeout: 3000,
                });
                return;
            }

            await this.continuePayment();
        },

        async continuePayment() {
            this.disabled = true;
            try {
                const res = await this.$server.post(
                    this.user.links.subscriptions_buy,
                    {
                        plan: this.plan.id,
                        billing_period: this.period,
                        payment_method: this.active,
                        refer_link: this.getReferralLink(),
                    }
                );

                if (res.status == 201) {
                    window.location.href = res.data.data.redirect_to;
                }
            } catch (error) {
                this.disabled = false;
            }
        },

        changeMethod(key) {
            this.active = key;
        },
    },
};
</script>

<style scoped>
.card {
    width: 100%;
    min-width: 200px;
    max-width: 900px;
    height: auto;
    background-color: var(--q-background-secondary);
    border: 1px solid var(--q-background-secondary);
    border-radius: 0.5rem;
    position: relative;
}

.title {
    font-size: 1.5rem;
    line-height: 1.5rem;
}

.color {
    color: var(--q-color);
}

.subtitle {
    font-size: 0.9rem;
    line-height: 0.8rem;
}

.strong {
    font-weight: 700;
}

.price {
    font-family: system-ui;
    font-size: 2.5rem;
    line-height: 2.5rem;
}

.gap-05 {
    gap: 0.5rem;
}

.gap-1 {
    gap: 1rem;
}

.gap-15 {
    gap: 1.5rem;
}

.card > .left {
    width: 100%;
    min-width: 100px;
    max-width: 300px;
    padding: 2rem;
    gap: 1rem;
    background: linear-gradient(
        260deg,
        var(--q-background-gradient-one),
        var(--q-background-gradient-two)
    );
    color: white;
}

.card > .right {
    width: 100%;
    height: 100%;
    padding: 2rem;
    gap: 2rem;
}

.btn {
    width: 150px;
    box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
    background-color: var(--q-background-primary);
    padding: 1rem;
    border-radius: 0.5rem;
}

.btn.active {
    background-color: var(--q-color-blue-light);
    outline: 1px solid var(--q-color-blue);
}

.btn.open {
    width: 100%;
    padding: 0.5rem;
    border-radius: 0.2rem;
    background-color: var(--q-primary);
    color: white;
}

.btn-close {
    position: absolute;
    right: 0.5rem;
    top: 0.4rem;
    color: var(--q-color);
}

.btn.suscribe {
    width: 100%;
    background-color: var(--q-primary);
    color: white;
    padding: 0.5rem;
}

input {
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    box-shadow: rgba(149, 157, 165, 0.2) 0px 0px 1px;
}

input:focus {
    outline: none;
}

@media (max-width: 720px) {
    .card {
        flex-direction: column;
        width: auto;
    }

    .card > .left {
        width: 100%;
        max-width: 100%;
    }

    .btn-close {
        color: white;
    }
}
</style>
