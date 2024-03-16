<template>
    <v-modal
        :target="'__a__'+ token.id"
        styles="btn-sm btn-danger"
        button_cancel_name="Cancelar"
        :button_accept_show="false"
    >
        <template v-slot:button> remove </template>
        <template v-slot:head> Revocar token </template>
        <template v-slot:body>
            <div class="text-center text-md">
                <span class="text-danger"
                    >Are you sure to destroy this token?</span
                >
                <br />
                <br />
                <button
                    @click="removeToken(token)"
                    class="mx-4 btn btn-block btn-primary"
                    data-bs-dismiss="modal"
                >
                    Aceptar
                </button>
            </div>
        </template>
    </v-modal>
</template>
<script> 

export default {
    emits: ["TokenWasRemove"],

    props: {
        token: {
            type: Object,
            required: true,
        },
    },

    methods: {
        removeToken(token) {
            this.$server
                .delete("/oauth/personal-access-tokens/" + token.id)
                .then((res) => {
                    this.$emit("TokenWasRemove", res.data);
                })
                .catch((e) => {});
        },
    },
};
</script>
<style lang=""></style>
