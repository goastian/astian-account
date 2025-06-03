<template>
    <v-user-layout>
        <q-page padding>
            <div class="column q-gutter-y-md q-pa-md container-main">
                <div class="row container-top">
                    <div class="column container-left">
                        <div class="container-welcome rounded pa-md card">
                            <div class="img flex justify-center items-center">
                                <q-icon name="mdi-account" size="40px"/>
                            </div>
                            <div class="column greet w-full no-wrap">
                                <div class="row no-wrap justify-between items-center">
                                    <span class="inline back">Welcome, back</span>
                                    <span class="inline tag">Verified</span>
                                </div>
                                <div class="welcome__name">
                                    <h2>¡Hello, {{ user.name }} {{ user.last_name }}!</h2>
                                    <span class="welcome__description">
                                        We're glad you're here.  What would you like to do today? Below are several options to manage  your account and set it up as you prefer.
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="Container-apps column card ga-y-md">
                            <div class="flex justify-between">
                                <h3 class="subtitle">Apps</h3>
                                <div class="flex q-gutter-x-md">
                                    <button @click="moveLeft">
                                        <q-icon name="mdi-arrow-left" size="20px" />
                                    </button>
                                    <button @click="moveRight">
                                        <q-icon name="mdi-arrow-right" size="20px" />
                                    </button>
                                </div>
                            </div>
                            <div
                                class="apps"
                                ref="carousel"
                            >
                                <a
                                    v-for="(item, index) in apps"
                                    :key="index"
                                    :href="item.route"
                                    target="_blank"
                                    class="cardApp"
                                >
                                    <q-icon :name="item.icon" :class="item.color" size="26px" />
                                    <h4>{{ item.name }}</h4>
                                    <span>{{ item.description }}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col card ga-y-md coming">
                        <h3 class="subtitle">🔧 What's coming soon?</h3>
                        <q-list dense class="q-mt-sm q-pl-sm recent-items">
                            <q-item>
                                <q-item-section avatar>
                                    <q-icon name="mdi-chart-line" />
                                </q-item-section>
                                <q-item-section
                                    >App usage analytics</q-item-section
                                >
                            </q-item>
                            <q-item>
                                <q-item-section avatar>
                                    <q-icon name="mdi-bell-ring-outline" />
                                </q-item-section>
                                <q-item-section
                                    >Custom notifications</q-item-section
                                >
                            </q-item>
                            <q-item>
                                <q-item-section avatar>
                                    <q-icon name="mdi-application-cog" />
                                </q-item-section>
                                <q-item-section
                                    >Application manager</q-item-section
                                >
                            </q-item>
                            <q-item>
                                <q-item-section avatar>
                                    <q-icon name="mdi-code-tags" />
                                </q-item-section>
                                <q-item-section
                                    >Developer options</q-item-section
                                >
                            </q-item>
                            <q-item>
                                <q-item-section avatar>
                                    <q-icon name="mdi-file-outline" />
                                </q-item-section>
                                <q-item-section>File manager</q-item-section>
                            </q-item>
                            <q-item>
                                <q-item-section avatar>
                                    <q-icon name="mdi-lock-check-outline" />
                                </q-item-section>
                                <q-item-section
                                    >Encrypt end-to-end</q-item-section
                                >
                            </q-item>
                            <q-item>
                                <q-item-section avatar>
                                    <q-icon name="mdi-qrcode-scan" />
                                </q-item-section>
                                <q-item-section
                                    >TOP and QR Login</q-item-section
                                >
                            </q-item>
                            <q-item>
                                <q-item-section avatar>
                                    <q-icon name="mdi-currency-btc" />
                                </q-item-section>
                                <q-item-section
                                    >Cryptocurrency payment</q-item-section
                                >
                            </q-item>
                            <q-item>
                                <q-item-section avatar>
                                    <q-icon
                                        name="mdi-dots-horizontal-circle-outline"
                                    />
                                </q-item-section>
                                <q-item-section>And much more</q-item-section>
                            </q-item>
                        </q-list>
                    </div>
                </div>
                <div class="row q-gutter-x-md container-bottom">
                    <div class="col card ga-y-md">
                        <h3 class="subtitle">Account Security</h3>
                        <div class="column q-gutter-y-md">
                            <div
                                class="row card-default justify-between items-center"
                                v-for="(item, index) in security"
                                :key="index"
                            >
                                <div class="row items-center q-gutter-x-md no-wrap">
                                    <q-icon :name="item.icon" class="icon" size="21px" />
                                    <div>
                                        <h4>{{ item.title }}</h4>
                                        <span>{{ item.description }}</span>
                                    </div>
                                </div>
                                <div>
                                    <a @click="open(item.route)" v-if="item.btnTitle">{{ item.btnTitle }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col card ga-y-md">
                        <div class="flex justify-between">
                            <h3 class="subtitle">Subscriptions</h3>
                            <button @click="open(suscriptionRoute.route)">See All</button>
                        </div>
                        <div class="column q-gutter-y-md">
                            <div
                                class="row card-default justify-between items-start"
                                v-for="(item, index) in suscription"
                                :key="index"
                            >
                                <div class="row items-center q-gutter-x-md">
                                    <q-icon name="mdi-playlist-check" class="icon" size="21px" />
                                    <div>
                                        <h4>{{ item.meta.name }}</h4>
                                        <div class="flex description items-center">
                                            <span>{{ item.meta.price.billing_period }} Plan</span>
                                            <span>-</span>
                                            <span>Price: ${{ item.meta.price.amount_format }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <span>Ends in: {{ item.end_at }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </q-page>
    </v-user-layout>
</template>

<script>
export default {
    created() {
        this.app_name = this.$page.props.app_name;
        this.suscription = this.$page.props.transactions;
    },
    computed: {
        user() {
            return this.$page.props.user;
        },
        userRoutes() {
            return this.$page.props.user_routes;
        },
        rowStyle() {
            return {
                transform: `translateX(${this.offset}px)`,
                transition: 'transform 0.3s ease',
            };
        },
    },
    mounted() {
        document.title = `Dashboard - ${this.app_name}`;
    },
    data() {
        return {
            offset: 0,
            step: 100,
            suscription: [],
            suscriptionRoute: {
                route: this.loadRoute(this.$page.props.user_routes, 'Subscriptions'),
            },
            security: [
                {
                    title: 'Password',
                    description: 'Updated 2 months ago',
                    icon: 'mdi-key-outline',
                    btnTitle: 'change',
                    route: this.loadRoute(this.$page.props.user_routes, 'Password'),
                },
                {
                    title: 'Two-factor authentication',
                    description: this.$page.props.user.m2fa ? 'Enabled' : 'Not Enabled',
                    icon: 'mdi-cellphone',
                    btnTitle: this.$page.props.user.m2fa ? 'Change' : 'Enable',
                    route: this.loadRoute(this.$page.props.user_routes, '2FA'),
                },
                {
                    title: 'Account Security: Good',
                    description: this.$page.props.user.m2fa ? 'Your account is secure.' : 'Your account is secure, but you can improve it even more by enabling two-factor authentication.',
                    icon: 'mdi-check',
                }
            ],
            apps: [
                {
                    name: 'Contacts',
                    route: 'https://contacts.astian.org',
                    icon: 'mdi-contacts',
                    description: 'Store and access your contacts.',
                    color: 'teal',
                },
                {
                    name: 'AstianGO',
                    route: 'https://astiango.co',
                    icon: 'mdi-magnify',
                    description: 'Find information quickly',
                    color: 'orange',
                },
                {
                    name: 'Cloud',
                    route: 'https://cloud.astian.org',
                    icon: 'mdi-cloud-check-outline',
                    description: 'Store and share files',
                    color: 'purple'
                },
                {
                    name: 'Calendar',
                    route: 'https://calendar.astian.org',
                    icon: 'mdi-calendar-blank-outline',
                    description: 'Organize your events and reminders',
                    color: 'green'

                },
                {
                    name: 'Midori',
                    route: 'https://astian.org/midori-browser/',
                    icon: 'mdi-earth',
                    description: 'Browse fast and secure',
                    color: 'teal'
                },
                {
                    name: 'Notes',
                    route: 'https://notes.astian.org',
                    icon: 'mdi-note-multiple-outline',
                    description: 'Take notes and organize ideas',
                    color: 'orange'
                },
                {
                    name: 'AstianVPN',
                    route: 'https://vpn.astian.org',
                    icon: 'mdi-shield-outline',
                    description: 'Browse securely and privately',
                    color: 'purple',
                }
            ]
        };
    },
    methods: {
        moveLeft() {
            this.$refs.carousel.scrollLeft -= 200;
        },
        moveRight() {
            this.$refs.carousel.scrollLeft += 200;
        },
        open(item) {
            window.location.href = item;
        },
        loadRoute(routes, name) {
            let res;
            for(const group in routes) {
                if(routes[group].show) {
                    const data = routes[group].menu.filter(data => data.name == name);
                    res = data[0];
                }
            }
            return res.route;
        }
    },
};
</script>

<style scoped>
.ga-y-md {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.container-main {
    max-width: 100%;
}

.container-top {
    max-width: 100%;
    gap: 1rem;
}

.container-left {
    max-width: 70%;
    gap: 1rem;
}

.icon {
    color: var(--q-primary);
    padding: .5rem;
    border-radius: 50%;
}

.card-default > div:last-child > a {
    border-radius: .4rem;
    border: .04rem solid var(--q-primary);
    color: var(--q-primary);
    padding: .5rem 1rem;
    cursor: pointer;
}

.card-default > div:last-child > span {
    color: var(--q-color);
    justify-self: end;
    font-size: 13px;
}

.card-default > div:first-child > div > h4 {
    font-size: 14px;
    font-weight: 600;
    margin: 0;
    line-height: 20px;
    color: var(--q-color);
}

.card-default > div:first-child > div > span {
    font-size: 13px;
    font-weight: 500;
    color: var(--q-color-secondary);
}

.card-default > div:first-child > div > .description {
    font-size: 13px;
    font-weight: 500;
    color: var(--q-color-secondary);
    gap: .5rem;
}

.card-default {
    border-radius: .4rem;
    border: .04rem solid var(--q-border);
    padding: .5rem 1rem;
}

.container-welcome {
    display: flex;
    gap: 1rem;
    height: calc(40% - 1rem);
    align-items: center;
}

.welcome__description {
    width: 100%;
    min-width: 20px;
    max-width: 100%;
    font-size: 1rem;
    font-weight: 500;
    color: var(--q-color-secondary);
}

.welcome__name {
    color: var(--q-color);
    font-size: 1rem;
}

.greet > div:first-child > .back {
    color: var(--q-color-secondary);
    font-size: 12px;
    font-weight: 500;
}

.greet > div:last-child > h2 {
    font-size: 20px;
    margin: 0;
    line-height: 30px;
    font-weight: 600;
}

.greet > div:last-child > span {
    font-size: 13px;
}

.orange {
    background: linear-gradient(to bottom, #FF7478, #A74547);
    color: white;
}

.purple {
    background: linear-gradient(to bottom, #FC30AA, #9C1867);
    color: white;
}

.green {
    background: linear-gradient(to bottom, #7FDD00, #559500);
    color: white;
}

.teal {
    background: linear-gradient(to bottom, #00B9B4, #009793);
    color: white;
}

.img {
    border-radius: 50%;
    background-color: var(--q-primary);
    color: white;
    width: 74px;
    height: 66px;
}

.card {
    border: 1px solid var(--border-color);
    color: var(--text);
    padding: 1rem;
    position: relative;
    border-radius: 1rem;
    background-color: var(--q-background-primary);
}

.tag {
    width: auto;
    padding: .2rem 1rem;
    background-color: var(--q-background-green);
    border-radius: .4rem;
    color: var(--q-color-green);
    border: .04rem solid var(--q-color-green);
    font-size: 12px;
}

.inline {
    display: inline;
}

.subtitle {
    font-size: 16px;
    margin: 0;
    line-height: 22px;
    font-weight: 600;
    color: var(--q-color);
}

.Container-apps {
    width: 100%;
    height: 60%;
}

.apps {
    width: 100%;
    display: flex;
    gap: 1rem;
    overflow: hidden;
}

.apps .q-icon {
    padding: .5rem;
    border-radius: .4rem;
}

.cardApp {
    min-width: 200px;
    width: 100%;
    max-width: 400px;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    padding: .8rem 1.4rem;
    border-radius: .8rem;
    border: .04rem solid var(--q-border);
}

.cardApp > h4 {
    font-size: 14px;
    font-weight: 600;
    color: var(--q-color);
}

.cardApp > span {
    font-size: 13px;
    color: var(--q-color-secondary);
}

.recent-items {
    color: var(--q-color);
}

.coming {
    width: 100%;
    min-width: 200px;
    max-width: 600px;
}

@media (max-width: 980px) {
    .container-bottom {
        flex-direction: column;
        gap: 1rem;
    }

    .container-bottom > div:last-child > div:last-child {
        min-height: 200px;
    }
}

@media (max-width: 900px) {
    .container-top {
        flex-direction: column;
    }

    .container-left {
        max-width: 100%;
    }

    .coming {
        max-width: 100%;
    }
}

</style>
