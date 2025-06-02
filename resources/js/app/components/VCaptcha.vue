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
        <div
            v-if="provider === 'turnstile'"
            ref="captchaEl"
            class="cf-turnstile"
            :data-sitekey="captcha.siteKey"
            :data-callback="callback"
        ></div>

        <div
            v-else-if="provider === 'hcaptcha'"
            ref="captchaEl"
            class="h-captcha"
            :data-sitekey="captcha.siteKey"
            :data-callback="callback"
        ></div>
    </div>
</template>

<script>
export default {
    emits: ["verified"],

    data() {
        return {
            callback: "",
            captcha: {},
            provider: "",
        };
    },
    mounted() {
        //Retrieve the captcha data
        this.captcha = this.$page.props.captcha;
        this.provider = this.captcha.provider;

        //Generate callback function
        this.callback = this.generatecallback();
        window[this.callback] = this.onVerified;

        //Call function to render captcha
        this.renderCaptcha();
    },

    beforeUnmount() {
        if (window[this.callback]) {
            delete window[this.callback];
        }
    },
    computed: {
        /**
         * Set the provider input name
         */
        inputName() {
            switch (this.provider) {
                case "turnstile":
                    return "g-recaptcha-response";
                case "hcaptcha":
                    return "h-captcha-response";
                default:
                    return "captcha-response";
            }
        },
    },
    methods: {
        /**
         * Generate a unique function name for callback
         */
        generatecallback() {
            return (
                "onCaptchaVerified_" +
                Math.random().toString(36).substring(2, 15)
            );
        },

        /**
         * Emit event
         * @param token
         */
        onVerified(token) {
            this.$emit("verified", {
                name: this.inputName,
                value: token,
            });
        },

        /**
         * Render captcha provider
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
                        callback: this.callback,
                    });
                } else if (
                    this.provider === "hcaptcha" &&
                    window.hcaptcha &&
                    el instanceof HTMLElement
                ) {
                    window.hcaptcha.render(el, {
                        sitekey: this.captcha.siteKey,
                        callback: this.callback,
                    });
                } else {
                    setTimeout(this.renderCaptcha, 500);
                }
            });
        },
    },
};
</script>
