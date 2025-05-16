<template>
    <v-user-layout>
        <q-page padding>
            <div class="q-pa-md">
                <div class="row items-center q-col-gutter-md">
                    <div class="col-auto">
                        <q-icon
                            name="mdi-account"
                            size="120px"
                            color="primary"
                        />
                    </div>

                    <div class="col">
                        <div class="text-h4 text-weight-bold">
                            Welcome {{ user.name }} {{ user.last_name }}
                        </div>
                        <div class="text-subtitle1 text-grey-7 q-mt-sm">
                            Hello, {{ user.name }}! We're glad you're here. What
                            would you like to do today? Below are several
                            options to manage your account and set it up as you
                            prefer.
                        </div>
                    </div>
                </div>

                <q-card flat bordered class="q-mt-lg q-mb-md">
                    <q-card-section>
                        <div
                            class="text-h6 text-primary text-italic text-weight-medium"
                        >
                            “Great things are done by a series of small things
                            brought together.”
                        </div>
                        <div class="text-caption text-grey-7 q-mt-sm">
                            — Vincent Van Gogh
                        </div>
                        <div class="text-subtitle2 text-grey-7 q-mt-md">
                            Stay productive while we improve your experience.
                            More tools will appear here soon.
                        </div>
                    </q-card-section>
                </q-card>

                <q-card flat bordered class="q-mt-lg q-mb-md">
                    <q-card-section>
                        <div class="text-h6">
                            🚀 Your dashboard is getting an upgrade
                        </div>
                        <div class="text-subtitle2 text-grey-7 q-mt-sm">
                            We’re working on personalized suggestions and
                            insights. Soon, you’ll be able to manage your apps
                            and track your activity from this section.
                        </div>
                    </q-card-section>
                </q-card>

                <q-card flat bordered class="q-mt-lg q-mb-md">
                    <q-card-section>
                        <div class="text-h6">🔧 What's coming soon?</div>
                        <q-list dense class="q-mt-sm q-pl-sm">
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
                        <div class="text-caption text-grey q-mt-sm">
                            We're building this for you. Stay tuned!
                        </div>
                    </q-card-section>
                </q-card>
            </div>
        </q-page>
    </v-user-layout>
</template>

<script>
export default {
    computed: {
        user() {
            return this.$page.props.user;
        },
        userRoutes() {
            return this.$page.props.user_routes;
        },
    },
    mounted() {},
    data() {
        return {};
    },
};
</script>
