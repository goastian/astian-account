<template>
    <div v-if="captcha.status">
        <div v-if="provider === 'turnstile'" ref="captchaEl" class="cf-turnstile" :data-sitekey="captcha.siteKey"></div>

        <div v-else-if="provider === 'hcaptcha'" ref="captchaEl" class="h-captcha" :data-sitekey="captcha.siteKey">
        </div>
    </div>
</template>

<script>
export default {
    emits: ["verified"],

    data() {
        return {
            captcha: {},
            provider: "",
        };
    },
    mounted() {

        this.captcha = this.$page.props.captcha;
        this.provider = this.captcha.provider;

        this.renderCaptcha();
    },
    computed: {
        inputName() {
            switch (this.provider) {
                case "turnstile":
                    return "cf-turnstile-response";
                case "hcaptcha":
                    return "h-captcha-response";
                default:
                    return "captcha-response";
            }
        },
    },
    methods: {
        /**
         * Callback verification
         */
        onVerified(token) {
            this.$emit("verified", {
                name: this.inputName,
                value: token,
            });
        },

        /**
         * Render captcha
         */
        renderCaptcha() {
            this.$nextTick(() => {
                const el = this.$refs.captchaEl;

                if (
                    this.provider === "turnstile" &&
                    window.turnstile &&
                    el instanceof HTMLElement
                ) {
                    window.turnstile.render(el, {
                        sitekey: this.captcha.siteKey,
                        callback: this.onVerified,
                    });
                } else if (
                    this.provider === "hcaptcha" &&
                    window.hcaptcha &&
                    el instanceof HTMLElement
                ) {
                    window.hcaptcha.render(el, {
                        sitekey: this.captcha.siteKey,
                        callback: this.onVerified,
                    });
                } else {
                    setTimeout(this.renderCaptcha, 500);
                }
            });
        },
    },
};
</script>
