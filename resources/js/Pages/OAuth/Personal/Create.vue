<template>
    <div class="row row-cols-1 col-12 border-bottom border-bottom-1">
        <div class="col">
            <div class="card bg-dark text-sm text-light px-4 py-2">
                <div class="card-head h6">Crear Personal Access Token</div>
                <div class="body">
                    <div class="row row-cols-1 col-12">
                        <div class="col-3 mb-2">
                            <input
                                type="text"
                                class="form-control"
                                placeholder="Nombre del token"
                                v-model="name"
                            />
                            <v-error :error="errors.name"></v-error>
                        </div>

                        <div class="col-9">
                            <div class="form-floating py-0 text-black">
                                <textarea
                                    class="form-control py-0"
                                    name="token"
                                    id="token"
                                    v-model="tokens.accessToken"
                                    style="width: 100%"
                                ></textarea>
                                <label for="token">access token</label>
                            </div>
                        </div>

                        <div class="col-10 border-top my-2">
                            <span class="fw-bold">Asignar tareas al token</span>
                            <v-scopes
                                class="row row-cols-3 col-sm-12"
                                @scopes-selected="setScopes"
                            ></v-scopes>
                        </div>

                        <div class="col-2 m-auto" >
                            <button
                                class="btn btn-sm  btn-success"
                                @click="createToken"                                
                            >
                                Generar nuevo Personal Access Token
                            </button>
                        </div>
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
