<template>
    <div class="q-pa-md" v-if="period?.id && plan?.id">
        <div class="text-h5 q-mb-md">{{ label }}</div>

        <div class="q-gutter-md row items-start">
            <div v-for="(method, key) in methods" :key="key">
                <q-card
                    v-if="method.enable"
                    @click="selectMethod(key)"
                    flat
                    bordered
                    class="my-card cursor-pointer"
                    :class="{
                        'bg-primary text-white': selected_method === key,
                    }"
                >
                    <q-card-section class="text-center">
                        <q-icon
                            color="positive"
                            :name="method.icon"
                            size="40px"
                            class="q-mb-sm"
                        />
                        <div class="text-subtitle3">{{ method.name }}</div>
                    </q-card-section>
                </q-card>
            </div>
        </div>
        <div class="q-mt-lg">
            <q-banner v-if="selected_method >= 0" class="q-pa-md">
                <div class="text-h6 q-mb-sm">Summary</div>
                <div class="text-body2">
                    <strong>Plan:</strong> {{ plan?.name }} <br />
                    <strong>Billing Period:</strong>
                    {{ period?.billing_period }} <br />
                    <strong>Amount:</strong>
                    {{ period?.currency }} {{ period?.amount_format }} <br />
                    <strong>Expires:</strong>
                    {{ period?.expiration }} <br />
                    <strong>Payment Method:</strong>
                    {{ methods[selected_method]?.name }}
                </div>
            </q-banner>
        </div>

        <div class="q-mt-md">
            <q-btn
                v-if="selected_method >= 0"
                :disabled="disabled"
                color="primary"
                label="Continue to Payment"
                @click="payment"
                class="full-width"
            />
        </div>

        <v-login :guest="guest" @close="guest = false" />
    </div>
</template>

<script>
export default {
    props: {
        period: {
            type: Object,
            required: false,
        },
        plan: {
            type: Object,
            required: false,
        },
        label: {
            type: String,
            required: false,
            default: "Choose your payment method",
        },
        buy: {
            type: Boolean,
            required: false,
            default: true,
        },
        package: {
            type: Object,
            required: false,
        },
    },

    data() {
        return {
            selected_method: -1,
            methods: [],
            disabled: false,
            guest: false,
        };
    },

    created() {
        this.getBillingPeriod();
        this.user = this.$page.props.user;
    },

    methods: {
        selectMethod(key) {
            this.selected_method = key;
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

            if (this.buy) {
                await this.continuePayment();
            } else {
                await this.extendPayment();
            }
        },

        async continuePayment() {
            this.disabled = true;

            try {
                const res = await this.$server.post(
                    this.user.links.subscriptions_buy,
                    {
                        plan: this.plan.id,
                        billing_period: this.period.billing_period,
                        payment_method: this.methods[this.selected_method].key,
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

        async extendPayment() {
            this.disabled = true;

            try {
                const res = await this.$server.post(
                    this.user.links.subscriptions_renew,
                    {
                        package: this.package.id,
                        billing_period: this.period.billing_period,
                        payment_method: this.methods[this.selected_method].key,
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
    },
};
</script>

<style scoped>
.my-card {
    width: 150px;
    transition: 0.3s;
}
.my-card:hover {
    transform: scale(1.05);
}
</style>
