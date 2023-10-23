<template>
    <div class="container-fluid">
        <div
            class="card bg-dark card-sm py-0 py-0 w-75"
            style="margin: 1% auto"
        >
            <div class="class card-head h5 py-0 text-light text-center">
                <span>Registrar cliente</span>
            </div>
            <div class="class py-0 card-body">
                <div class="class text-center row row-cols-3 col-sm-12">
                    <div class="col-4">
                        <label for="cliente">Cliente</label>
                        <input
                            type="text"
                            v-model="client.name"
                            class="form-control form-sm"
                            @keyup.enter="storeClients"
                        />
                        <v-error :error="errors.name"></v-error>                       
                    </div>
                    <div class="col-6">
                        <label for="redirect">Redirect</label>
                        <input
                            type="text"
                            v-model="client.redirect"
                            id="redirect"
                            class="form-control"
                            @keyup.enter="storeClients"
                        />
                        <v-error :error="errors.redirect"></v-error>                        
                    </div>
                    <div class="col-2 py-4">
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
            window.axios
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
