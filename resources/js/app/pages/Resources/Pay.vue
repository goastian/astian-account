<template>
    <v-guest-layout v-if="!loading">
        <template #title>{{ $page.props.app_name }}</template>

        <template #body>
            <div class="row container-plan justify-center">
                <div class="card left col column">
                    <div class="row items-center top">
                        <span
                            class="icon logo"
                            v-if="plan.scopes[0].service.name == 'vpn'"
                        >🛡️</span>
                        <div class="column">
                            <span>{{ plan.scopes[0].role.name }}</span>
                            <span class="title strong">{{ plan.name }}</span>
                        </div>
                    </div>
                    <div class="container-price row justify-center items-center">
                        <template v-for="item in plan.prices">
                            <div v-if="item.billing_period == params.billing_period">
                                <span
                                    class="price"
                                    v-if="item.currency == 'USD'"
                                >$</span>
                                <span
                                    class="price"
                                    v-else
                                >{{ item.currency }}</span>
                                <span class="price">{{ item.amount_format }}</span>
                                <span>/{{ item.billing_period }}</span>
                            </div>
                        </template>
                    </div>
                    <div class="column description">
                        <h6 class="subTitle strong">Todo lo que incluye:</h6>
                        <div v-html="plan.description"></div>
                    </div>
                    <div class="tip">
                        <span>💡 <strong>Tips:</strong> Puedes cancelar en cualquier momento desde tu panel de control.</span>
                    </div>
                </div>

                <div class="col column right">
                    <div class="card">
                        <div v-if="user " class="column gap-1">
                            <div class="subTitle strong row items-center">
                                <span><q-icon name="mdi-account" /></span>
                                <span>Información del usuario</span>
                            </div>
                            <div class="row items-center gap-05 user-information">
                                <span class="icon user row justify-center items-center"><q-icon name="check" /></span>
                                <div class="column">
                                    <span class="">{{ user.name }}</span>
                                    <span class="sub">{{ user.email }}</span>
                                </div>
                            </div>
                            <div>
                                <button>Cambiar de cuenta</button>
                            </div>
                        </div>

                        <div v-else class="column gap-1">
                            <div>
                                <div class="subTitle strong row items-center">
                                    <span><q-icon name="mdi-alert" /></span>
                                    <span>Acción requerida</span>
                                </div>
                                <div>
                                    <span>Necesitas iniciar sesión para continuar con el pago y activar tu suscripción.</span>
                                </div>
                            </div>
                            <div class="column items-start gap-05 card-warning">
                                <span>¿Por qué necesito una cuenta?</span>
                                <ul>
                                    <li class="">🔹 Gestionar tu suscripción</li>
                                    <li class="">🔹 Acceder a tu dashboard</li>
                                </ul>
                            </div>
                        </div>
                    </div>

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
                            <button class="btn suscribe">Suscribe Now</button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </v-guest-layout>

    <div
        v-else
    >
        <span>loading</span>
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
        }
    },

    mounted() {
        this.getPlan();
    },

    created() {
        if(!Array.isArray(this.$page.props.user)) {
            this.user = this.$page.props.user;
        }
    },

    methods: {
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

                console.log(res.data.data)

                if (res.status === 200) {
                    this.plan = res.data.data[0];
                    this.loading = false;
                }
            } catch (error) {
                this.$q.notify({
                    message: "Error cargando el plan.",
                    type: "negative",
                });
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

.card-method > .icon {
    width: 50px;
    height: 50px;
    background-color: var(--q-background-secondary);
    border-radius: 50%;
    font-size: 1.5rem;
}

.card-method.active > .icon {
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
</style>
