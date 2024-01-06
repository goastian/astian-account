<template lang="">
    <v-modal
        :target="'__X_' + user.id"
        styles="btn-sm btn-danger"
        :button_accept_show="false"
        button_cancel_name="Cancelar operacion"
    >
        <template v-slot:button> Eliminar cuenta </template>
        <template v-slot:head> Eliminar cuenta permanentemente </template>

        <template v-slot:body>
            <div>
                La cuenta será eliminada permanente, una vez eliminada se
                borrará cualquier informacion que hayas guardado, no almacenamos
                tu informacion como otros servicios para nuestros beneficios,
                aparte que si la almacenamos ocuparía espacio en nuestros
                servidores que son limitados. te enviaremos un email.
            </div>
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
    emits: ["status"],

    props: ["user"],

    methods: {
        remove(item) {
            window.axios
                .delete(item.links.disable)
                .then((res) => {
                    this.$emit(
                        "status",
                        "Su cuenta ha sido eliminada, recibira un email con la confirmacion, en 5 segundo se cerrara tu session."
                    );
                })
                .catch((e) => {
                    if (e.response) {
                        this.$emit("status", e.response);
                    }
                });
        },
    },
};
</script>
<style lang=""></style>
