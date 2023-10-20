<template>
    <v-token-remove
        styles="bg-danger"
        :button_accept_show="false"
        :target="'token__' + token.id"
        button_cancel_name="Cancelar"
    >
        <template v-slot:button> Revoke </template>
        <template v-slot:head> Revocar token </template>
        <template v-slot:body>
            <span class="text-danger h6">
                Estas seguro que deseas revocar el acceso a este token?
            </span>
            <span class="text-info h6">
                Cuando revocas el token todos los usuarios que lo esten usando perderan el acceso a
                los recursos que esten ejecutando.
            </span>
            <br /><br />
            <button
                @click="removeToken(token)"
                class="btn btn-block btn-primary"
                data-bs-dismiss="modal"
            >
                Aceptar
            </button>
        </template>
    </v-token-remove>
</template>
<script>
import VTokenRemove from "../Components/modal.vue";
export default {
    emits: ["tokenWasRevoked"],

    props: {
        token: {
            type: Object,
            required: true,
        },
    },

    components: {
        VTokenRemove,
    },

    methods: {
        removeToken(token) {
            axios
                .delete("/oauth/tokens/" + token.id)
                .then((res) => {
                    this.$emit("tokenWasRevoked", token);
                })
                .catch((e) => {
                    console.log(e);
                });
        },
    },
};
</script>
<style lang=""></style>
