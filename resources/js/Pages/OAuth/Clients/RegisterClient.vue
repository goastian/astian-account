<template>
    <div class="card">
        <div class="card-head">
            <span>Registrar cliente</span>
        </div>
        <div class="card-body aouth-clients-register">
            <div class="row">
                <div class="col">
                    <input
                        type="text"
                        v-model="client.name"
                        class="form-control form-sm"
                        @keyup.enter="storeClients"
                        placeholder="Nombre"
                    />
                    <v-error :error="errors.name"></v-error>
                </div>
                <div class="col">
                    <input
                        type="text"
                        v-model="client.redirect"
                        id="redirect"
                        class="form-control"
                        @keyup.enter="storeClients"
                        placeholder="Redirect"
                    />
                    <v-error :error="errors.redirect"></v-error>
                </div>
                <div class="col">
                    <button
                        class="btn btn-block btn-success"
                        @click="storeClients"
                    >
                        registrar
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    emits: ["clientRegistered"],

    data() {
        return {
            client: {
                name: "",
                redirect: "",
            },
            errors: {},
        };
    },

    methods: {
        storeClients() {
            this.$server
                .post("/oauth/clients", this.client)
                .then((res) => {
                    this.client = { name: "", redirect: "" };
                    this.errors = { name: "", redirect: "" };
                    this.$emit("clientRegistered", res.data);
                })
                .catch((e) => {
                    if (e.response && e.response.data.errors) {
                        this.errors = e.response.data.errors;
                    }
                });
        },
    },
};
</script>
<style></style>
