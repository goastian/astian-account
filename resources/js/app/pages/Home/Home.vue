<template>
    <v-guest-layout>
        <template #body>
            <q-page class="landing-page column justify-between">
                <div class="hero q-pa-xl text-center">
                    <h1
                        class="text-h2 text-white text-bold animate__animated animate__fadeInDown"
                    >
                        Central Authorizer
                    </h1>
                    <p
                        class="text-subtitle1 text-white q-mt-md animate__animated animate__fadeInUp"
                    >
                        Centralize authentication and manage your microservices
                        securely.
                    </p>
                    <q-btn
                        label="View Documentation"
                        unelevated
                        rounded
                        class="q-mt-lg animate__animated animate__zoomIn"
                        @click="redirectToDocs"
                    />
                </div>

                <!-- FEATURES -->
                <div class="features row wrap justify-center q-pa-xl">
                    <q-card
                        v-for="(item, index) in features"
                        :key="index"
                        class="feature-card q-ma-md"
                        flat
                        bordered
                    >
                        <q-card-section>
                            <div class="text-h6 text-white q-mb-sm">
                                {{ item.title }}
                            </div>
                            <div class="text-white">{{ item.description }}</div>
                        </q-card-section>
                    </q-card>
                </div>

                <q-footer class="footer text-center q-pa-md text-grey-4">
                    &copy; 2025 Central Authorizer. All rights reserved.
                </q-footer>
            </q-page>
        </template>
    </v-guest-layout>
</template>

<script>
export default {
    name: "LandingPage",
    data() {
        return {
            features: [
                {
                    title: "OAuth2 Integration",
                    description:
                        "Standard and secure authentication protocol for microservices.",
                },
                {
                    title: "Central Dashboard",
                    description:
                        "Manage users, subscriptions and access from a single place.",
                },
                {
                    title: "Billing & Plans",
                    description:
                        "Integrated payment and subscription system out of the box.",
                },
                {
                    title: "Secure & Scalable",
                    description:
                        "Built for modern, distributed, scalable architectures.",
                },
            ],
        };
    },
    methods: {
        redirectToDocs() {
            window.location.href = "/docs";
        },
    },
};
</script>

<style scoped>
@import "https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css";

.landing-page {
    min-height: 100vh;
    background: linear-gradient(to bottom, #0f2027, #203a43, #2c5364);
    color: #ffffff;
}

.hero {
    padding-top: 10vh;
}

.features {
    padding-bottom: 5vh;
}

.feature-card {
    width: 280px;
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(6px);
    border-radius: 12px;
    transition: transform 0.3s ease;
}

.feature-card:hover {
    transform: translateY(-6px);
}

.footer {
    background: rgba(0, 0, 0, 0.2);
}
</style>
