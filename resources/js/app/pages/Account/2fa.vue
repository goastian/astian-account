<template>
    <v-user-layout>
        <q-page padding>
            <div class="row q-col-gutter-md">
                <div class="col q-ma-sm">
                    <q-card bordered>
                        <q-card-section>
                            <q-toolbar class="q-ma-sm">
                                <q-toolbar-title class="text-grey-7">
                                    Two Factor Authentication
                                </q-toolbar-title>
                                <q-btn icon="mdi-check-decagram-outline" :color="user.m2fa ? 'positive' : 'negative'">
                                    <q-tooltip transition-show="rotate" transition-hide="rotate">
                                        {{
                                            user.m2fa
                                                ? "2FA Activated"
                                                : "2FA inactive"
                                        }}
                                    </q-tooltip>
                                </q-btn>
                            </q-toolbar>
                        </q-card-section>
                        <q-card-section>
                            <q-input v-model="token" label="Insert Code ..." outlined dense />
                            <v-error :error="errors.token" />
                        </q-card-section>

                        <q-card-actions align="between">
                            <q-btn @click="requestCode" label="Request token" color="positive" outline />
                            <q-btn :label="user.m2fa ? 'Deactivate' : 'Activate'"
                                :color="user.m2fa ? 'negative' : 'positive'" @click="activateFactor" outline
                                color="negative">
                                <q-tooltip transition-show="rotate" transition-hide="rotate">
                                    {{
                                        user.m2fa
                                            ? "2FA Activated"
                                            : "2FA inactive"
                                    }}
                                </q-tooltip>
                            </q-btn>
                        </q-card-actions>
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
        };
    },
    mounted() {
        this.user = this.$page.props.user;
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
                        token: this.token,
                    }
                );
                if (res.status === 201) {
                    this.token = "";
                    this.errors = {};
                    this.popup("2FA has been activated successfully");
                    window.location.reload()
                }
            } catch (err) {
                if (err.response) {
                    this.errors = err.response.data.errors;
                }
            }
        },

    },
};
</script>
