<template>
    <div>
        <div class="row row-cols-1 col-12">
            <div class="col">
                <p class="font-monospace text-uppercase fw-bold">
                    Two Factor Authentication
                </p>
                <span :class="[user.m2fa ? 'text-success' : 'text-danger']"
                    >status : {{ user.m2fa ? "Activo" : "Inactivo" }}</span
                >
            </div>
            <div class="col py-3">
                <a
                    :class="['btn', user.m2fa ? 'btn-danger' : 'btn-success']"
                    @click="requestCode()"
                    >{{
                        user.m2fa
                            ? "Deshabilitar opcion"
                            : "Activar Two Factor Authentication"
                    }}</a
                >
            </div>
            <div class="col py-3">
                <label for="token">TOKEN</label>
                <input
                    type="text"
                    class="form-control mb-3 form-control-sm w-25"
                    v-model="token"
                />
                <v-error :error="errors.token"></v-error>
            </div>
            <div class="col">
                <a
                    :class="[
                        'btn btn-block',
                        user.m2fa ? 'btn-danger' : 'btn-success',
                    ]"
                    @click="activar()"
                >
                    {{ user.m2fa ? "Desactivar" : "Activar" }}
                </a>
            </div>

            <div v-show="message" class="col mt-4 text-success">
                {{ message }}
            </div>
        </div>
    </div>
</template>
<script>
export default {
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
            window.axios
                .post("/m2fa/authorize")
                .then((res) => {
                    this.message = res.data.message;
                })
                .catch((err) => {
                    console.log(err.response);
                });
        },

        authenticated() {
            window.axios
                .get("/api/gateway/user")
                .then((res) => {
                    this.user = res.data;
                })
                .catch((e) => {
                    console.log(e);
                });
        },

        activar() {
            window.axios
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
