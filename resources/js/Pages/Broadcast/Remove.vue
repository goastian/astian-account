<template>
    <v-modal
        :target="item.canal"
        styles="btn btn-sm btn-danger"
        :button_accept_show="false"
    >
        <template v-slot:button> remover </template>
        <template v-slot:head>
            <span>Eliminar canal</span>
        </template>
        <template v-slot:body>
            <div class="text-center">
                <p>
                    estas seguro que deseas eliminar este canal? ten en cuenta
                    que si este canal esta en uso deconectará a todos los
                    usuarios o aplicaciones que esten emitiendo por esa señal.
                </p>

                <button class="mt-4 btn-primary btn" @click="remove(item)" data-bs-dismiss="modal">
                    Aceptar
                </button>
            </div>
        </template>
    </v-modal>
</template>
<script>
export default {

    props: ["item"],

    emits: ["broadcastWasRemove"],

    methods: {
        remove(item) {
            window.axios
                .delete(item.links.destroy)
                .then((res) => {
                    this.$emit("broadcastWasRemove", res.data.data);
                })
                .catch((e) => {
                    console.error(e.response);
                });
        },
    },
};
</script>
