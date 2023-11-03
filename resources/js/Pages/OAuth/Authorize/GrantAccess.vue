<template>
    <div class="card bg-light mt-3" style="width: 90%; margin: auto">
        <div
            class="card-head text-center text-uppercase my-3 text-danger fw-bold"
        >
            <span
                >Esta aplicacion requiere que le otorgues algunos permisos</span
            >
        </div>
        <div class="card-body px-2 py-2 bg-success text-light">
            <v-scopes
                class="row row-cols-4 text-sm col-sm-12"
                @scopes-selected="setScopes"
            ></v-scopes>

            <div class="col-12 text-center mt-5">
                <button class="btn btn-primary" @click="prepareAuthorize">
                    aceptar
                </button>
                <button class="btn btn-danger mx-4" @click="cancel">
                    cancelar
                </button>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            scopeSelected: [],
            client: {},
        };
    },

    methods: {
        setScopes(scopes) {
            this.scopeSelected = scopes;
        },

        cancel() {
            this.$router.push({ name: "clients" });
        },

        prepareAuthorize() {
            this.searchClient(this.$route.query.redirect_uri);
        },

        createClients(params) {
            window.axios
                .post("/oauth/clients", params)
                .then((res) => {
                    this.sendRequestForAuthorize(res.data);
                })
                .catch((e) => {
                    if (e.response && e.response.data.errors) {
                        console.error(e.response);
                    }
                });
        },

        appName() {
            return this.$route.query.redirect_uri
                .replace("http://", "")
                .split(".")[0];
        },

        searchClient(uri) {
            window.axios
                .get("/oauth/clients/", { params: { redirect: uri } })
                .then((res) => {
                    this.sendRequestForAuthorize(res.data);
                })
                .catch((e) => {
                    if (e.response && e.response.status === 400) {
                        const params = {
                            name: this.appName(),
                            redirect: this.$route.query.redirect_uri,
                        };
                        this.createClients(params);
                    }
                });
        },

        sendRequestForAuthorize(item) {
            const query = new URLSearchParams({
                client_id: item.id,
                redirect_uri: item.redirect,
                response_type: "code",
                scope: this.scopeSelected.join(" "),
                state: this.$route.query.state,
                prompt: "consent",
            });

            window.location.href = `http://auth.spondylus.xyz/oauth/authorize?${query.toString()}`;
        },
    },
};
</script>
