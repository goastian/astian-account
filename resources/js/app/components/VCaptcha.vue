<!--English description
Captcha Component

Usage:
<v-captcha @verified="handleVerified" />

Description:
This component renders either hCaptcha or Turnstile based on the configuration from the backend.

It emits a `verified` event when the user successfully completes the CAPTCHA challenge.

The emitted payload includes:
{
    name: <inputName>,   // e.g. "h-captcha-response" or "g-recaptcha-response"
    value: <token>       // the CAPTCHA response token
}

You can use this in your form like this:

handleVerified({ name, value }) {
    this.form[name] = value;
}
-->

<!--Descripción en español
Componente Captcha

Uso:
<v-captcha @verified="handleVerified" />

Descripción:
Este componente renderiza hCaptcha o Turnstile según la configuración proporcionada desde el backend.

Emite un evento `verified` cuando el usuario completa correctamente el desafío CAPTCHA.

La carga útil emitida contiene:
{
    name: <nombreDelInput>,   // por ejemplo: "h-captcha-response" o "g-recaptcha-response"
    value: <token>            // el token de respuesta del CAPTCHA
}

Puedes usarlo en tu formulario así:

handleVerified({ name, value }) {
    this.form[name] = value;
}
-->
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

    props: {
        render: {
            required: true,
            type: Number
        }
    },

    data() {
        return {
            captcha: {},
            provider: "",
        };
    },

    watch: {
        render(value) {
            this.reRenderCaptcha();
        }
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

        reRenderCaptcha() {
            if (this.$refs.captchaEl instanceof HTMLElement) {
                this.$refs.captchaEl.innerHTML = "";
            }

            this.renderCaptcha();
        },
    },
};
</script>
