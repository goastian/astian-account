<template>
    <div>
        <div class="row">
            <div class="col-12">
                <div class="card account-security-2fa">
                    <div class="card-head">Two Factor Authentication</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <a class="btn btn-link" @click="requestCode()">
                                    Solicitar Token
                                </a>
                            </div>
                            <div class="col">
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="token"
                                    placeholder=""
                                />
                                <v-error :error="errors.token"></v-error>
                            </div>
                            <div class="col">
                                <a class="btn btn-link" @click="activar()">
                                    {{ user.m2fa ? "Desactivar" : "Activar" }}
                                </a>
                            </div>
                            <div
                                v-show="errors.message"
                                class="col-12 mt-4 text-danger text-center"
                            >
                                {{ errors.message }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 mt-3">
                <div class="card account-security-tokens">
                    <div class="card-head mt-2">Tokens</div>
                    <div class="card-body">
                        <a class="btn btn-link" @click="revocarCredentials">
                            Revocar todos las credenciales generadas
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-12 mt-3">
                <div class="card account-security-remove">
                    <div class="card-head">Account</div>
                    <div class="card-body">
                        <v-remove
                            :user="user"
                            @success="showMessage"
                            @errors="showMessage"
                        ></v-remove>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <v-message :message="message" @close="close"></v-message>
</template>
<script>
import VRemove from "./Remove.vue";

export default {
    components: {
        VRemove,
    },

    data() {
        return {
            token: "",
            user: {},
            message: null,
            errors: {},
        };
    },

    created() {
        this.authenticated();
        this.listener();
    },

    methods: {
        requestCode() {
            this.$server
                .post("/m2fa/authorize")
                .then((res) => {
                    this.message = res.data.message;
                    this.errors.message = null;
                })
                .catch((err) => {
                    if (err.response) {
                        this.errors = err.response.data;
                        this.message = null;
                    }
                });
        },

        showMessage(event) {
            if (!event.status) {
                this.message = event;
                setTimeout(() => {
                    window.location.href = window.location.host;
                }, 5000);
            }
            this.message = event.data.message;
        },

        authenticated() {
            this.$server
                .get("/api/gateway/user")
                .then((res) => {
                    this.user = res.data;
                })
                .catch((e) => {});
        },

        activar() {
            this.errors.message = null;

            this.$server
                .post("/m2fa/activate", {
                    token: this.token,
                })
                .then((res) => {
                    this.token = "";
                    this.message = res.data.message;
                })
                .catch((err) => {
                    if (err.response) {
                        this.errors = err.response.data.errors;
                    }
                });
        },

        close() {
            this.message = null;
        },

        revocarCredentials() {
            this.$server
                .delete("/api/oauth/credentials/revoke")
                .then((res) => {
                    this.message = res.data.message;
                })
                .catch((e) => {
                    console.error(e.response);
                });
        },

        listener() {
            this.$echo
                .private(this.$channels.ch_0())
                .listen("M2FAEvent", (e) => {
                    this.authenticated();
                });
        },
    },
};
</script>
<style lang=""></style>
