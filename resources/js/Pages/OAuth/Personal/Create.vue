<template>
    <div class="card">
        <div class="card-head">Crear Personal Access Token</div>
        <div class="body">
            <div class="row oauth-client-personal-create">
                <div class="col">
                    <input
                        type="text"
                        class="form-control"
                        placeholder="Nombre del token"
                        v-model="name"
                    />
                    <v-error :error="errors.name"></v-error>
                </div>

                <div class="col">
                    <div class="form-floating">
                        <textarea
                            class="form-control py-0"
                            name="token"
                            id="token"
                            v-model="tokens.accessToken"
                        ></textarea>
                        <label for="token">access token</label>
                    </div>
                </div>
            </div>
            <div class="row oauth-client-personal-roles">
                <div class="col-12 text-center px-2">
                    <span class="fw-bold">Asignar Roles</span>
                </div>
                <div
                    class="col form-check"
                    v-for="(item, index) in scopes"
                    :key="index"
                >
                    <input
                        class="form-check-input"
                        type="checkbox"
                        :value="item.id"
                        :id="item.id"
                        @click="isSelected(item.id)"
                    />
                    <label class="form-check-label" :for="item.id">
                        <span class="fw-bold">{{ item.id }}</span>
                        : {{ item.description }}
                    </label>
                </div>
                <div class="col-12"></div>
                <div class="col py-2">
                    <button class="btn btn-sm btn-success" @click="createToken">
                        Generar Token
                    </button>
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
            name: "",
            tokens: {},
            errors: {},
            scopes: {},
            scopesSelected: [],
        };
    },

    mounted() {
        this.getScopes();
        this.scopesSelected = [];
        this.listenEvent();
    },

    methods: {
        setScopes(scopes) {
            this.scopeSelected = scopes;
        },

        createToken() {
            this.$server
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

        isSelected(id) {
            const position = this.scopesSelected.indexOf(id);
            if (position != -1) {
                this.scopesSelected.splice(position, 1);
            } else {
                this.scopesSelected.push(id);
            }
            this.$emit("scopesSelected", this.scopesSelected);
        },

        getScopes() {
            this.$server
                .get("/api/oauth/scopes")
                .then((res) => {
                    this.scopes = res.data;
                })
                .catch((e) => {});
        },

        listenEvent() {
            this.$echo
                .private(this.$channels.ch_0())
                .listen(".StoreRoleEvent", (e) => {
                    this.getScopes();
                })
                .listen(".UpdateRoleEvent", (e) => {
                    this.getScopes();
                })
                .listen(".DestroyRoleEvent", (e) => {
                    this.getScopes();
                });
        },
    },
};
</script>
