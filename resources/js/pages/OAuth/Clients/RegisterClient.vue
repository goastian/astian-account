<template>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col">
                    <input
                        type="text"
                        v-model="client.name"
                        class="form-control form-control-sm"
                        placeholder="Application Name"
                    />
                    <v-error :error="errors.name"></v-error>
                </div>
                <div class="col">
                    <input
                        type="text"
                        v-model="client.redirect"
                        class="form-control form-control-sm"
                        placeholder="https://app.dominio.dom/callback"
                    />
                    <v-error :error="errors.redirect"></v-error>
                </div>
                <div class="col">
                    <div class="form-check">
                        <input
                            class="form-check-input"
                            type="checkbox"
                            id="confidential"
                            v-model="client.confidential"
                        />
                        <label
                            class="form-check-label text-color"
                            for="flexCheckDefault"
                        >
                            Private Client (<strong
                                >By default Public Client</strong
                            >)
                        </label>
                    </div>
                    <v-error :error="errors.confidential"></v-error>
                </div>
                <div class="col">
                    <button
                        class="btn btn-sm btn-primary"
                        @click="storeClients"
                    >
                        Add new client
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
            client: {},
            errors: {},
        };
    },

    methods: {
        storeClients(event) {
            const button = event.target;
            button.disabled = true;

            this.client.confidential =
                document.getElementById("confidential").checked;

            this.$server
                .post("/oauth/clients", this.client)
                .then((res) => {
                    this.client = {};
                    this.errors = {};
                    this.$emit("clientRegistered", res.data);
                    button.disabled = false;
                })
                .catch((e) => {
                    if (e.response && e.response.data.errors) {
                        this.errors = e.response.data.errors;
                    }
                    button.disabled = false;
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
