<template>
    <div class="card bg-light">
        <div class="card-body">
            <p class="fw-bold">Two Factor Authentication</p>
            <a class="btn btn-link" @click="requestCode()"> Request token </a>
            <input
                type="text"
                class="form-control form-control-sm my-4"
                v-model="token"
                placeholder="Insert Code ..."
            />
            <v-error :error="errors.token"></v-error>
            <a class="btn btn-link" @click="activar()">
                {{ user.m2fa ? "Disabled" : "Enable" }}
            </a>
            <p v-show="errors.message" class="col mt-4 text-danger text-center">
                {{ errors.message }}
            </p>
        </div>
    </div>

    <div class="card bg-light">
        <div class="card-body">
            <p class="fw-bold">Tokens</p>
            <a class="btn btn-link" @click="revocarCredentials">
                Revoke all credentials
            </a>
        </div>
    </div>

    <div class="card bg-light">
        <div class="card-body">
            <p class="fw-bold">Account</p>
            <v-remove
                :user="user"
                @success="showMessage"
                @errors="showMessage"
            ></v-remove>
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
                    this.errors = {}
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
<style lang="scss" scoped>
.col {
    flex: 0 0 auto;
    width: 95%;
    margin: 1% auto;
    padding: 0;
}

.card {
    text-align: center;
    width: 80%;
    margin: 2% auto;
}

.card-body {
    text-align: center !important;
}

a {
    display: block;
    padding: 0%;
    margin: 0%;
}

input {
    display: inline;
    margin: 0.5%;
    @media (min-width: 240px) {
        width: 95%;
    }

    @media (min-width: 800px) {
        width: 50%;
    }

    @media (min-width: 800px) {
        width: 20%;
    }
}
</style>
