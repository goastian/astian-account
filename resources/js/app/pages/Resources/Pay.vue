<template>
    <v-guest-layout v-if="!loading">
        <template #title>{{ $page.props.app_name }}</template>

        <template #body>
            <div class="row container-plan justify-center">
                <div class="card left col column">
                    <div class="row items-center top">
                        <span class="icon logo" v-if="plan.scopes[0].service.name == 'vpn'">🛡️</span>
                        <div class="column">
                            <span>{{ plan.scopes[0].role.name }}</span>
                            <span class="title strong">{{ plan.name }}</span>
                        </div>
                    </div>
                    <div class="container-price row justify-center items-center">
                        <template v-for="item in plan.prices">
                            <div v-if="item.billing_period == params.billing_period">
                                <span class="price" v-if="item.currency == 'USD'">$</span>
                                <span class="price" v-else>{{ item.currency }}</span>
                                <span class="price">{{ item.amount_format }}</span>
                                <span>/{{ item.billing_period }}</span>
                            </div>
                        </template>
                    </div>
                    <div class="column description">
                        <h6 class="subTitle strong">What's included:</h6>
                        <div v-html="plan.description"></div>
                    </div>
                    <div class="tip">
                        <span>💡 <strong>Tips:</strong> You can cancel at any time from your dashboard.</span>
                    </div>
                </div>

                <div class="col column right">
                    <div class="card column gap-1">
                        <div v-if="user" class="column gap-1">
                            <div class="subTitle strong row items-center">
                                <span><q-icon name="mdi-account" /></span>
                                <span>User Information</span>
                            </div>
                            <div class="row items-center gap-05 user-information">
                                <span class="icon user row justify-center items-center"><q-icon name="check" /></span>
                                <div class="column">
                                    <span class="">{{ user.name }}</span>
                                    <span class="sub">{{ user.email }}</span>
                                </div>
                            </div>
                            <!--
                            <div>
                                <button>Cambiar de cuenta</button>
                            </div>
                            -->
                        </div>

                        <div v-else class="column gap-1">
                            <div>
                                <div class="subTitle strong row items-center">
                                    <span><q-icon name="mdi-alert" /></span>
                                    <span>Action required</span>
                                </div>
                                <div>
                                    <span>You need to log in to continue with the payment and activate your
                                        subscription.</span>
                                </div>
                            </div>
                            <div class="column items-start gap-05 card-warning">
                                <span>Why do I need an account?</span>
                                <ul>
                                    <li class="">🔹 Manage your subscription</li>
                                    <li class="">🔹 Access your dashboard</li>
                                </ul>
                            </div>
                        </div>

                        <div>
                            <button class="btn suscribe" @click="payment" :disabled="!user">Suscribe Now</button>
                        </div>
                    </div>
                    <!--
                    <div class="card column gap-1">
                        <div class="subTitle strong row items-center">
                            <span><q-icon name="mdi-credit-card-outline" /></span>
                            <span>Método de pago</span>
                        </div>
                        <div class="column gap-05">
                            <div class="row card-method gap-05 active">
                                <span class="icon row justify-center items-center"><q-icon name="mdi-account-multiple" /></span>
                                <div class="column">
                                    <span>p2p</span>
                                    <span class="sub">Activación después de la revisión.</span>
                                </div>
                            </div>
                            <div class="row card-method gap-05">
                                <span class="icon row justify-center items-center"><q-icon name="mdi-credit-card-outline" /></span>
                                <div class="column">
                                    <span>Stripe</span>
                                    <span class="sub">Serás redirigido automáticamente a Stripe.</span>
                                </div>
                            </div>
                        </div>
                        <div>
                            <button class="btn suscribe" @click="payment">Suscribe Now</button>
                        </div>
                    </div>
                    -->
                </div>
            </div>
        </template>
    </v-guest-layout>

    <div v-else>
        <div class="loading-container" role="status" aria-live="polite">
            <div class="spinner" aria-hidden="true"></div>
            <div class="loading-text">Loading, please wait...</div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            user: null,
            plan: null,
            loading: true,
            params: {},
            methods: [],
        }
    },

    mounted() {
        this.getPlan();
        this.getBillingPeriod();
    },

    created() {
        if (!Array.isArray(this.$page.props.user)) {
            this.user = this.$page.props.user;
        }
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
            } catch (error) { }
        },

        async getPlan() {
            const urlParams = new URLSearchParams(window.location.search);

            /*urlParams.forEach((value, key) => {
                params[key] = value;
            });*/
            this.params.name = urlParams.get('name');
            this.params.service = urlParams.get('service');
            this.params.billing_period = urlParams.get('billing_period');

            try {
                const res = await this.$server.get("/plans", {
                    params: this.params,
                });

                if (res.status === 200) {
                    if (res.data.data.length == 0) {
                        const url = this.$page.props.guest_routes['plans'];
                        window.location.href = url;
                    }
                    this.plan = res.data.data[0];
                    this.loading = false;
                }
            } catch (error) {
                this.$q.notify({
                    message: "Error cargando el plan.",
                    type: "negative",
                });
                const url = this.$page.props.guest_routes['plans'];
                window.location.href = url;
            }
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

            await this.continuePayment();
        },

        async continuePayment() {
            this.disabled = true;
            try {
                const res = await this.$server.post(
                    this.user.links.subscriptions_buy,
                    {
                        plan: this.plan.id,
                        billing_period: this.params.billing_period,
                        payment_method: 'stripe',
                    }
                );

                if (res.status == 201) {
                    window.location.href = res.data.data.redirect_to;
                }
            } catch (error) {
                this.disabled = false;
            }
        },
    }
}
</script>

<style scoped>
.container-plan {
    width: 100%;
    min-width: 200px;
    max-width: 1200px;
    padding: 1rem;
    padding-top: 3rem;
    gap: 1rem;
}

.gap-1 {
    gap: 1rem;
}

.gap-05 {
    gap: .5rem;
}

.card {
    background-color: var(--q-background-primary);
    border-radius: 1rem;
    padding: 2rem;
}

.left {
    gap: 1rem;
}

.top {
    gap: .5rem;
}

.icon.logo {
    background-color: var(--q-primary);
    color: white;
    font-size: 2rem;
    padding: .2rem;
    border-radius: 1rem;
}

.title {
    font-size: 1.5rem;
}

.subTitle {
    font-size: 1rem;
}

.strong {
    font-weight: 700;
    color: var(--q-color);
}

.sub {
    color: var(--q-color-secondary);
}

.container-price {
    background-color: var(--q-background-secondary);
    height: 120px;
    border-radius: .6rem;
}

.price {
    font-weight: 700;
    font-size: 3rem;
    line-height: 2rem;
    font-family: system-ui;
    color: var(--q-primary);
}

.description {
    gap: .4rem;
}

.tip {
    background-color: var(--q-color-blue-light);
    color: var(--q-color-blue);
    padding: 1rem;
    border-radius: .6rem;
}

.right {
    gap: 1rem;
}

.user-information {
    background-color: var(--q-color-green-light);
    border-radius: .5rem;
    padding: .8rem;
    border: 1px solid var(--q-color-green-secondary);
}

.icon.user {
    width: 50px;
    height: 50px;
    background-color: var(--q-color-green);
    color: white;
    font-size: 1.5rem;
    padding: .4rem;
    border-radius: 50%;
}

.card-warning {
    background-color: var(--q-color-yellow-light);
    border-radius: .5rem;
    padding: .8rem;
    border: 1px solid var(--q-color-yellow-secondary);
    color: var(--q-color-yellow);
}

.card-method {
    padding: 1rem;
    border-radius: .5rem;
    border: 1px solid var(--q-border);
}

.card-method.active {
    background-color: var(--q-color-blue-light);
    border: 1px solid var(--q-color-blue);
}

.card-method>.icon {
    width: 50px;
    height: 50px;
    background-color: var(--q-background-secondary);
    border-radius: 50%;
    font-size: 1.5rem;
}

.card-method.active>.icon {
    background-color: var(--q-primary);
    color: white;
}

.btn.suscribe {
    width: 100%;
    padding: .5rem;
    border-radius: .5rem;
    background-color: var(--q-primary);
    color: white;
}

.loading-container {
    height: 100vh;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    background-color: var(--q-background-primary);
}

/* El círculo animado */
.spinner {
    width: 70px;
    height: 70px;
    border: 8px solid #ddd;
    border-top-color: #4a90e2;
    /* color principal */
    border-radius: 50%;
    animation: spin 1.3s linear infinite;
    box-shadow: 0 0 10px rgba(74, 144, 226, 0.5);
}

/* Animación de giro */
@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

/* Mensaje amigable */
.loading-text {
    margin-top: 20px;
    font-size: 1.2rem;
    color: #555;
    font-weight: 600;
    user-select: none;
}

@media (max-width: 700px) {
    .container-plan {
        flex-direction: column;
    }
}
</style>
