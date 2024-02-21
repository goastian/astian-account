<template>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col">
                    <input
                        type="text"
                        v-model="client.name"
                        class="form-control form-control-sm"
                        @keyup.enter="storeClients"
                        placeholder="Application Name"
                    />
                    <v-error :error="errors.name"></v-error>
                </div>
                <div class="col">
                    <input
                        type="text"
                        v-model="client.redirect"
                        id="redirect"
                        class="form-control form-control-sm"
                        @keyup.enter="storeClients"
                        placeholder="https://app.dominio.dom/callback"
                    />
                    <v-error :error="errors.redirect"></v-error>
                </div>
                <div class="col">
                    <button
                        class="btn btn-sm btn-primary"
                        @click="storeClients"
                    >
                        Add new private client
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
<style lang="scss" scoped>
.col {
    flex: 0 0 auto;
    width: 100%;
    margin: 1%;

    @media (min-width: 850px) {
        width: 30%;
    }
}
</style>
