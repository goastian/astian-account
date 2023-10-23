<template>
    <div class="row row-cols-2 col-12 border-bottom border-bottom-1">
        <div class="col">
            <div class="card bg-dark text-sm text-light px-4 py-2">
                <div class="card-head h6">Crear Personal Access Token</div>
                <div class="body">
                    <div class="row row-cols-1 col-12">
                        <div class="col-8 mb-2">
                            <input
                                type="text"
                                class="form-control"
                                placeholder="Nombre del token"
                                v-model="name"
                            />
                            <v-error :error="errors.name"></v-error>
                        </div>

                        <div class="col-12">
                            <span class="fw-bold"
                                >Selecciona Permisos para el token</span
                            >
                            <v-scopes @scopes-selected="setScopes"></v-scopes>
                        </div>
                        <div class="col">
                            <button
                                class="btn btn-success"
                                @click="createToken"
                            >
                                Generar nuevo Personal Access Token
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="row row-cols-1 col-12">
                <div class="col my-4">
                    expires: {{ tokens.token ? tokens.token.expires_at : null }}
                </div>
                <div class="col">
                    <div class="form-floating py-0 text-black">
                        <label for="token">access token</label>
                        <textarea
                            class="form-control py-0"
                            name="token"
                            id="token"
                            v-model="tokens.accessToken"
                            style="width: 100%; height: 14rem"
                        ></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    emits: ["tokenWasCreated"],

    data() {
        return {
            scopeSelected: [],
            name: "",
            tokens: {},
            errors: {},
        };
    },

    methods: {
        setScopes(scopes) {
            this.scopeSelected = scopes;
        },

        createToken() {
            window.axios
                .post("/oauth/personal-access-tokens", {
                    name: this.name,
                    scopes: this.scopeSelected,
                })
                .then((res) => {
                    this.name = "";
                    this.tokens = res.data;
                    this.$emit("tokenWasCreated", res.data);
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
