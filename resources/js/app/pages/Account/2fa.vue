<template>
    <v-user-layout>
        <q-page padding>
            <div class="row q-col-gutter-md">
                <div class="container-card col column items-center">
                    <q-card bordered class="column card">
                        <div class="column items-center card-top">
                            <div class="container-icon row justify-center items-center">
                                <q-icon name="mdi-shield-outline" />
                            </div>
                            <h2 class="title">
                                Two Factor Authentication
                            </h2>
                            <span class="description">
                                Enter the 6-digit code we sent to your email address to continue.
                            </span>
                        </div>
                        <div class="column q-gutter-y-md">
                            <div class="column text-start q-gutter-y-md">
                                <label class="label">Enter Verification code</label>
                                <div class="code-container">
                                    <input
                                        v-for="(digit, index) in code"
                                        :key="index"
                                        type="text"
                                        class="code-input"
                                        maxlength="1"
                                        v-model="code[index]"
                                        @input="onInput(index)"
                                        @keydown.backspace="onBackspace(index)"
                                        ref="inputs"
                                    />
                                </div>
                                <v-error :error="errors.token" />
                            </div>
                            <div class="column q-gutter-y-md items-center">
                                <q-btn
                                    :label="user.m2fa ? 'Deactivate' : 'Activate'"
                                    :color="user.m2fa ? 'negative' : 'positive'"
                                    @click="activateFactor"
                                    color="negative"
                                    class="full-width"
                                >
                                    <q-tooltip
                                        transition-show="rotate"
                                        transition-hide="rotate"
                                    >
                                        {{
                                            user.m2fa
                                                ? "2FA Activated"
                                                : "2FA inactive"
                                        }}
                                    </q-tooltip>
                                </q-btn>
                                <q-btn
                                    @click="requestCode"
                                    label="Request token"
                                    class="btn-request"
                                    flat
                                />
                            </div>
                        </div>
                    </q-card>
                </div>
            </div>
        </q-page>
    </v-user-layout>
</template>

<script>
export default {
    data() {
        return {
            token: "",
            user: {},
            errors: {},
            code: Array(6).fill(""),
        };
    },
    mounted() {
        this.user = this.$page.props.user;
        this.$refs.inputs[0].focus();
    },
    methods: {
        popup(message, type = "positive") {
            if (message) {
                this.$q.notify({
                    message,
                    type,
                });
            }
        },
        async requestCode() {
            try {
                const res = await this.$server.post(
                    this.user.links.f2a_authorize
                );
                if (res.status === 201) {
                    this.popup(res.data.message);
                    this.errors = {};
                }
            } catch (err) {
                if (err.response) {
                    this.popup(err.response.data.message, "warning");
                }
            }
        },

        async activateFactor() {
            try {
                const res = await this.$server.post(
                    this.user.links.f2a_activate,
                    {
                        token: this.code.join(""),
                    }
                );
                if (res.status === 201) {
                    this.token = "";
                    this.errors = {};
                    this.popup("2FA has been activated successfully");
                    this.getAuthUser();
                }
                if(res.status === 200) {
                    this.errorMessage = '';
                    this.popup(res.data.message, "warning");
                }
                
            } catch (err) {
                if (err.response && err.response.status == 422) {
                    this.errors = err.response.data.errors;
                }
                if (err.response && err.response.status == 403) {
                    this.popup(err.response.data.errors);
                }
            }
        },

        listener() {
            this.$echo
                .private(this.$channels.ch_0())
                .listen("M2FAEvent", () => {
                    this.getAuthUser();
                });
        },

        validated(value) {
            if(value === "") {
                return 'Ingresar Codigo';
            } else if(value.length < 6) {
                return 'El código debe tener 6 dígitos';
            } else {
                return true;
            }
        },

        onInput(index) {
            const value = this.code[index];
            if (!/^\d$/.test(value)) {
                this.code[index] = "";
                return;
            }

            if (index < this.code.length - 1) {
                this.$refs.inputs[index + 1].focus();
            }
        },

        onBackspace(index) {
            if (!this.code[index] && index > 0) {
                this.$refs.inputs[index - 1].focus();
            }
        },
    },
};
</script>

<style scoped>
.container-card {
    padding: 3rem;
}

.card {
    background-color: var(--q-background-primary);
    width: 410px;
    text-align: center;
    padding: 1.6rem;
    gap: 1rem;
}

.card-top {
    gap: 1rem;
}

.container-icon {
    background-color: var(--q-color-blue-light);
    padding: 1rem;
    border-radius: 50%;
    width: 80px;
    height: 80px;
}

.container-icon > .q-icon {
    color: var(--q-color-blue);
    font-size: 30px;
}

.title {
    color: var(--q-color);
    font-size: 1.8rem;
    font-weight: 700;
    line-height: 30px;
}

.description {
    color: var(--q-color-secondary);
    font-size: 1rem;
}

.btn-request {
    width: 100%;
    border-radius: .3rem;
}

.label {
    color: var(--q-color);
}

.code-container {
  display: flex;
  justify-content: center;
  gap: 10px;
}

.code-input {
  width: 40px;
  height: 40px;
  font-size: 24px;
  text-align: center;
  border: 1.7px solid #ccc;
  border-radius: 8px;
  background-color: var(--q-background-primary);
}

.code-input:focus {
  border-color: var(--q-primary);
  outline: none;
}
</style>
