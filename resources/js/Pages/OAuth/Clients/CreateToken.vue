<template>
    <v-modal
        :target="client.name.concat(`_5_${client.id}`)"
        styles="bg-info btn-sm mx-2"
        :button_accept_show="false"
        width="modal-xl"
    >
        <template v-slot:button> Generar Token </template>
        <template v-slot:head>
            <span class="h4"> Generacion de token </span>
        </template>
        <template v-slot:body>
            <div class="row row-cols-2 col-12">
                <div class="col-10">
                    <div class="form-floating">
                        <textarea
                            class="form-control"
                            id="floatingTextarea2"
                            style="height: 5rem"
                            v-model="form_token.code"
                        ></textarea>
                        <label for="floatingTextarea2"> Code </label>
                    </div>
                </div>
                <div class="col-2">
                    <button
                        @click="tokenGenerate(client)"
                        class="btn btn-primary btn-sm my-2"
                    >
                        Obtener token
                    </button>
                </div>
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
                        id="floatingTextarea2"
                        style="height: 8rem"
                        :value="form_token.credentials.access_token"
                    ></textarea>
                    <label for="floatingTextarea2">access token</label>
                </div>
            </div>
            <div class="col">
                <div class="form-floating my-3">
                    <textarea
                        class="form-control"
                        id="floatingTextarea2"
                        style="height: 8rem"
                        :value="form_token.credentials.refresh_token"
                    ></textarea>
                    <label for="floatingTextarea2">refresh token</label>
                </div> 
            </div>
        </template>
    </v-modal>
</template>
<script>
import VModal from "../Components/modal.vue";

export default {
    props: {
        client: {
            type: Object,
            required: true,
        },
    },
    components: {
        VModal,
    },
    data() {
        return {
            form_token: {
                code: "",
                credentials: "",
            },
        };
    },

    methods: {
        copyToken() {
            const token = document.getElementById("token");
            token.select();
            document.execCommand("copy");
        },

        tokenGenerate(client) {
            window.axios
                .post("/oauth/token", {
                    grant_type: "authorization_code",
                    client_id: client.id,
                    client_secret: client.secret,
                    redirect_uri: client.redirect,
                    code: this.form_token.code,
                })
                .then((res) => {
                    this.form_token.credentials = res.data;
                })
                .catch((e) => {
                    console.log(e);
                });
        },
    },
};
</script>
<style lang=""></style>
