<template>
    <div class="container-fluid">
        <v-form
            :target="`_6_${client.id}`" 
            @is-accepted="sendRequestForAuthorize(client)"
        >
            <template v-slot:button> Authorize </template>
            <template v-slot:head>
                <span class="h4">Agregar permisos</span>
            </template>
            <template v-slot:body>
                <v-scopes @scopes-selected="getScopes"></v-scopes>
            </template>
        </v-form>
    </div>
</template>
<script>
import VForm from "../../Components/modal.vue";
import VScopes from "../../Components/scopes.vue";

export default {
    props: {
        client: {
            type: Object,
            required: true,
        },
    },

    components: {
        VForm,
        VScopes,
    },

    data() {
        return {
            form: {
                name: "",
                redirect: "",
            }, 
            scopeSelected:[],
        };
    },

    methods: {
        
        getScopes(scopes) {
            this.scopeSelected = scopes;
        },

        sendRequestForAuthorize(item) { 
            const query = new URLSearchParams({
                client_id: item.id,
                redirect_uri: item.redirect,
                response_type: "code",
                scope: this.scopeSelected.join(" "),
            });
            window.open(
                `http://auth.spondylus.xyz/oauth/authorize?${query.toString()}`
            );
        },
    },
};
</script>
<style></style>
