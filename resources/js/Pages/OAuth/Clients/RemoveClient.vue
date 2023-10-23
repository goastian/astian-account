<template>
    <v-modal
        :target="`_2_${client.id}`"
        @is-accepted="deleteClient(item)"
        styles="bg-danger btn-sm"
        :button_accept_show="false"
    >
        <template v-slot:button> Remove </template>
        <template v-slot:head>
            <span class="h4">Eliminar cliente</span>
        </template>
        <template v-slot:body>
            <div class="text-md">
                <span class="text-danger">
                    Estas seguros que deseas eliminar este Cliente?
                </span>
                <span class="text-info">
                    Esta accion no puede ser revertida una vez realizada
                </span>
                <br />
                <button
                    class="btn mt-5 btn-danger btn-block"
                    @click="deleteClient(client)"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                >
                    Remove
                </button>
            </div>
        </template>
    </v-modal>
</template>
<script> 
export default {
    emits: ["clientWasRemove"],

    props: {
        client: {
            type: Object,
            required: true,
        },
    },

    methods: {
        deleteClient(client) {
            window.axios
                .delete("/oauth/clients/" + client.id)
                .then((res) => {
                    this.$emit("clientWasRemove", res.data);
                })
                .catch((e) => {
                    console.log(e);
                });
        },
    },
};
</script>
<style></style>
