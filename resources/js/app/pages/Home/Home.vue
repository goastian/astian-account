<template>
    <v-guest-layout>
        <template #body>
            <q-page class="landing-page column justify-between">
                <!-- Hero Section -->
                <div class="hero q-pa-xl text-center">
                    <div class="hero-content">
                        <h1
                            class="text-h2 text-white text-bold animate__animated animate__fadeInDown"
                        >
                            Central Authorizer
                        </h1>
                        <p
                            class="text-subtitle1 text-white q-mt-md animate__animated animate__fadeInUp animate__delay-1s"
                        >
                            Centralize authentication and manage your
                            microservices securely with our enterprise-grade
                            solution.
                        </p>
                        <div
                            class="q-mt-xl animate__animated animate__zoomIn animate__delay-2s"
                        >
                            <q-btn
                                label="Get Started"
                                unelevated
                                rounded
                                color="primary"
                                size="lg"
                                class="q-mx-sm q-px-xl"
                                @click="redirectToDocs"
                            />
                            <q-btn
                                label="Live Demo"
                                unelevated
                                rounded
                                outline
                                color="white"
                                size="lg"
                                class="q-mx-sm q-px-xl"
                                @click="redirectToDemo"
                            />
                        </div>
                    </div>

                    <div
                        class="hero-image animate__animated animate__fadeInUp animate__delay-3s"
                    >
                        <img
                            src="https://via.placeholder.com/800x450/2c5364/ffffff?text=Dashboard+Preview"
                            alt="Central Authorizer Dashboard"
                        />
                    </div>
                </div>

                <!-- Trusted By Section -->
                <div class="trusted-by q-pa-xl text-center">
                    <p class="text-caption text-grey-4 q-mb-md">
                        TRUSTED BY INDUSTRY LEADERS
                    </p>
                    <div class="row justify-center q-gutter-xl">
                        <img
                            src="https://via.placeholder.com/120x40/ffffff00/ffffff?text=Company+1"
                            class="grayscale"
                            alt="Company Logo"
                        />
                        <img
                            src="https://via.placeholder.com/120x40/ffffff00/ffffff?text=Company+2"
                            class="grayscale"
                            alt="Company Logo"
                        />
                        <img
                            src="https://via.placeholder.com/120x40/ffffff00/ffffff?text=Company+3"
                            class="grayscale"
                            alt="Company Logo"
                        />
                        <img
                            src="https://via.placeholder.com/120x40/ffffff00/ffffff?text=Company+4"
                            class="grayscale"
                            alt="Company Logo"
                        />
                    </div>
                </div>

                <!-- Features Section -->
                <div class="features-section q-pa-xl">
                    <div class="text-center q-mb-xl">
                        <h2 class="text-h3 text-white text-bold">
                            Powerful Features
                        </h2>
                        <p class="text-subtitle1 text-grey-4">
                            Everything you need for secure authentication
                            management
                        </p>
                    </div>

                    <div class="row wrap justify-center">
                        <q-card
                            v-for="(item, index) in features"
                            :key="index"
                            class="feature-card q-ma-md"
                            flat
                            bordered
                        >
                            <q-card-section horizontal>
                                <q-icon
                                    :name="item.icon"
                                    size="lg"
                                    color="primary"
                                    class="q-mr-md"
                                />
                                <q-card-section>
                                    <div class="text-h6 text-white q-mb-sm">
                                        {{ item.title }}
                                    </div>
                                    <div class="text-grey-4">
                                        {{ item.description }}
                                    </div>
                                </q-card-section>
                            </q-card-section>
                        </q-card>
                    </div>
                </div>

                <!-- CTA Section -->
                <div class="cta-section text-center q-pa-xl">
                    <h2 class="text-h3 text-white text-bold q-mb-md">
                        Ready to Secure Your Microservices?
                    </h2>
                    <p class="text-subtitle1 text-grey-4 q-mb-xl">
                        Start your free 14-day trial today. No credit card
                        required.
                    </p>
                    <q-btn
                        label="Get Started Now"
                        unelevated
                        rounded
                        color="primary"
                        size="lg"
                        class="q-px-xl"
                        @click="redirectToSignup"
                    />
                </div>

                <!-- Footer -->
                <q-footer class="footer text-center q-pa-md">
                    <div class="row justify-center q-gutter-xl q-mb-md">
                        <a href="#" class="text-grey-4">Documentation</a>
                        <a href="#" class="text-grey-4">API Reference</a>
                        <a href="#" class="text-grey-4">Pricing</a>
                        <a href="#" class="text-grey-4">Contact Us</a>
                        <a href="#" class="text-grey-4">Privacy Policy</a>
                    </div>
                    <div class="text-grey-6">
                        &copy; 2025 Central Authorizer. All rights reserved.
                    </div>
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
                    title: "OAuth2 & OpenID Connect",
                    description:
                        "Full support for industry-standard authentication protocols.",
                    icon: "mdi-shield-lock-outline",
                },
                {
                    title: "Central Dashboard",
                    description:
                        "Manage users, permissions and access from a single intuitive interface.",
                    icon: "mdi-monitor-dashboard",
                },
                {
                    title: "Billing & Plans",
                    description:
                        "Integrated payment and subscription management system.",
                    icon: "mdi-credit-card-outline",
                },
                {
                    title: "Enterprise Scalability",
                    description:
                        "Built for modern, distributed architectures at any scale.",
                    icon: "mdi-server-network",
                },
                {
                    title: "Audit Logs",
                    description:
                        "Comprehensive logging for compliance and security monitoring.",
                    icon: "mdi-clipboard-text-outline",
                },
                {
                    title: "Developer Friendly",
                    description:
                        "Easy integration with SDKs for all major platforms.",
                    icon: "mdi-code-braces",
                },
            ],
        };
    },
    methods: {
        redirectToDocs() {
            window.location.href = "/docs";
        },
        redirectToDemo() {
            window.location.href = "/demo";
        },
        redirectToSignup() {
            window.location.href = "/signup";
        },
    },
};
</script>

<style scoped>
@import "https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css";

.landing-page {
    min-height: 100vh;
    background: linear-gradient(135deg, #0f2027 0%, #203a43 50%, #2c5364 100%);
    color: #ffffff;
    font-family: "Inter", -apple-system, BlinkMacSystemFont, sans-serif;
}

.hero {
    padding: 12vh 5vw 8vh;
    position: relative;
    overflow: hidden;
}

.hero::before {
    content: "";
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(
        circle,
        rgba(46, 125, 194, 0.15) 0%,
        transparent 70%
    );
    z-index: 0;
}

.hero-content {
    position: relative;
    z-index: 1;
    max-width: 800px;
    margin: 0 auto;
}

.hero-image {
    margin-top: 5rem;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    max-width: 1000px;
    margin-left: auto;
    margin-right: auto;
}

.hero-image img {
    width: 100%;
    height: auto;
    display: block;
}

.trusted-by {
    background: rgba(255, 255, 255, 0.03);
    backdrop-filter: blur(5px);
}

.grayscale {
    filter: grayscale(100%) brightness(2);
    opacity: 0.7;
    transition: all 0.3s ease;
}

.grayscale:hover {
    filter: grayscale(0%) brightness(1);
    opacity: 1;
}

.features-section {
    background: linear-gradient(
        to bottom,
        rgba(15, 32, 39, 0.8) 0%,
        rgba(44, 83, 100, 0.5) 100%
    );
}

.feature-card {
    width: 100%;
    max-width: 400px;
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    border-radius: 16px;
    transition: all 0.3s ease;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.feature-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.cta-section {
    background: linear-gradient(to right, #4a6fa5, #2c5364);
    position: relative;
    overflow: hidden;
}

.cta-section::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url("https://www.transparenttextures.com/patterns/dark-stripes.png");
    opacity: 0.1;
    z-index: 0;
}

.cta-section > * {
    position: relative;
    z-index: 1;
}

.footer {
    background: rgba(0, 0, 0, 0.3);
    backdrop-filter: blur(10px);
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.footer a {
    text-decoration: none;
    transition: color 0.3s ease;
}

.footer a:hover {
    color: #ffffff !important;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .hero {
        padding: 8vh 5vw;
    }

    .hero h1 {
        font-size: 2.5rem !important;
    }

    .feature-card {
        max-width: 100%;
    }
}
</style>
