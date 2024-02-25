<template>
    <div class="card">
        <div class="body">
            <div class="row token">
                <div class="col">
                    <input
                        type="text"
                        class="form-control"
                        placeholder="Token Name"
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
                        <label for="token">Access token</label>
                    </div>
                </div>
            </div>
            <div class="row roles text-color">
                <div class="col-12 text-center px-2">
                    <span class="fw-bold">Add scopes</span>
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
                    <label class="form-check-label text-sm" :for="item.id">
                        <span class="fw-bold">{{ item.id }}</span>
                        : {{ item.description }}
                    </label>
                </div>
                <div class="col-12"></div>
                <div class="col py-2">
                    <button class="btn btn-sm btn-primary" @click="createToken">
                        New Token
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

        createToken(event) {
            const button = event.target;
            button.disabled = true;

            this.$server
                .post("/oauth/personal-access-tokens", {
                    name: this.name,
                    scopes: this.scopeSelected,
                })
                .then((res) => {
                    this.name = "";
                    this.tokens = res.data;
                    this.$emit("tokenWasCreated", res.data);
                    button.disabled = false;
                })
                .catch((e) => {
                    if (e.response && e.response.data.errors) {
                        this.errors = e.response.data.errors;
                    }
                    button.disabled = false;
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
                });

            this.$echo
                .private(this.$channels.ch_0())
                .listen(".UpdateRoleEvent", (e) => {
                    this.getScopes();
                });

            this.$echo
                .private(this.$channels.ch_0())
                .listen(".DestroyRoleEvent", (e) => {
                    this.getScopes();
                });
        },
    },
};
</script>

<style lang="scss" scoped>
.card {
    width: 98%;
    margin: 0 auto;
}
.token .col {
    flex: 0 0 auto !important;
    margin: 1%;

    @media (min-width: 240px) {
        width: 98%;
    }

    @media (min-width: 800px) {
        width: 48%;
    }
}

.roles .col {
    flex: 0 0 auto;

    @media (min-width: 240px) {
        width: 98%;
        margin: 0.5% 0% 0% 4%;
    }

    @media (min-width: 800px) {
        margin: 0.5% 0% 0% 2%;
        width: 48%;
    }

    @media (min-width: 940px) {
        width: 30%;
    }
}
</style>
