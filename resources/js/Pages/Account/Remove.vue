<template lang="">
    <v-modal
        :target="'__X_' + user.id"
        styles="btn btn-link"
        :button_accept_show="false"
        button_cancel_name="Cancelar operacion"
    >
        <template v-slot:button> Eliminar cuenta </template>
        <template v-slot:head> Eliminar cuenta permanentemente </template>

        <template v-slot:body>
            <p>
                La cuenta será eliminada permanente. Una vez eliminada se
                borrará cualquier informacion que hayas guardado. te enviaremos un email.
            </p>
            <button
                @click="remove(user)"
                data-bs-dismiss="modal"
                class="btn btn-success mt-4"
            >
                Aceptar
            </button>
        </template>
    </v-modal>
</template>
<script>
export default {
    emits: ["success","errors"],

    props: ["user"],

    methods: {
        remove(item) {
            this.$server
                .delete(item.links.disable)
                .then((res) => {
                    this.$emit(
                        "success",
                        "Su cuenta ha sido eliminada, recibira un email con la confirmacion, en 5 segundo se cerrara tu session."
                    );
                })
                .catch((e) => {
                    if (e.response) {
                        this.$emit("errors", e.response);
                    }
                });
        },
    },
};
</script>
<style lang=""></style>
