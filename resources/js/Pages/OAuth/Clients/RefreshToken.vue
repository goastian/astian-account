<template>
    <v-modal
        :target="`_4_${client.id}`"
        styles="bg-warning btn-sm mx-2"
        :button_accept_show="false"
        width="modal-xl"
        @is-closed="clean"
    >
        <template v-slot:button> Resfresh token </template>
        <template v-slot:head>
            <span class="h4"> Refresh Token </span>
        </template>
        <template v-slot:body>
            <div class="row row-cols-2 col-12">
                <div class="col-12 my-3">
                    <v-scopes @scopes-selected="setScopes"></v-scopes>
                </div>
                <div class="col-10">
                    <div class="form-floating">
                        <textarea
                            class="form-control"
                            id="refresh_token"
                            style="height: 5rem"
                            v-model="form_token.refresh_token"
                        ></textarea>
                        <label for="refresh_token"> Refresh token </label>
                    </div>
                </div>
                <div class="col-2">
                    <button
                        @click="tokenGenerate(client)"
                        class="btn btn-primary btn-sm my-2"
                    >
                        Obtener Nuevo token
                    </button>
                </div>
            </div>
            <div class="col-12">
                <span
                    class="errors d-block"
                    v-for="(item, index) in errors"
                    :key="index"
                    >{{ item }}</span
                >
            </div>
            <div class="col-12 my-4">
                <span>
                    token_type :
                    {{ form_token.credentials.token_type }} <br />
                    expires_in :
                    {{ form_token.credentials.expires_in }}
                </span>

                <div class="form-floating">
                    <textarea
                        class="form-control"
                        id="new_token"
                        style="height: 8rem"
                        :value="form_token.credentials.access_token"
                    ></textarea>
                    <label for="new_token">access token</label>
                </div>
            </div>
            <div class="col">
                <div class="form-floating my-3">
                    <textarea
                        class="form-control"
                        id="new_refresh_token"
                        style="height: 8rem"
                        :value="form_token.credentials.refresh_token"
                    ></textarea>
                    <label for="new_refresh_token">refresh token</label>
                </div>
            </div>
        </template>
    </v-modal>
</template>
<script>
import VModal from "../../Components/modal.vue";
import VScopes from "../../Components/scopes.vue";

export default {
    props: {
        client: {
            type: Object,
            required: true,
        },
    },
    components: {
        VModal,
        VScopes,
    },
    data() {
        return {
            form_token: {
                refresh_token: "",
                credentials: "",
            },
            scopeSelected: [],
            scopes: {},
            errors: {},
        };
    },

    methods: {
        copyToken() {
            const token = document.getElementById("token");
            token.select();
            document.execCommand("copy");
        },

        setScopes(scopes) {
            this.scopeSelected = scopes;
        },

        clean(){
            this.errors = {} 
        },

        tokenGenerate(client) {
            this.errors = {};
            window.axios
                .post("/oauth/token", {
                    grant_type: "refresh_token",
                    refresh_token: this.form_token.refresh_token,
                    client_id: client.id,
                    client_secret: client.secret,
                    redirect_uri: client.redirect,
                    scope: this.scopeSelected.join(" "),
                })
                .then((res) => {
                    this.form_token.credentials = res.data;
                })
                .catch((e) => {
                    if (e.response && e.response.data.error) {
                        this.errors = e.response.data;
                    }
                });
        },

        getScopes() {
            window.axios
                .get("oauth/scopes")
                .then((res) => {
                    this.scopes = res.data;
                })
                .catch((e) => {
                    console.log(e);
                });
        },
    },
};
</script>
<style lang=""></style>
